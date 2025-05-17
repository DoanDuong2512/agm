<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Customer\GetMessageWithAdminRequest;
use App\Http\Requests\Customer\SendMessageConversationRequest;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\ConversationParticipant;
use App\Models\User;
use App\Events\NewMessageEvent;
use App\Transformers\ConversationMessageTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessSendMessageJob;
use App\Jobs\ProcessGetMessageJob;

class ConversationController extends BaseApiController
{
    /**
     * Get all conversations for the authenticated customer
     */
    public function getConversations(Request $request): JsonResponse
    {
        try {
            $customer = Auth::guard('customer')->user();
            $conversations = ConversationParticipant::where('participant_id', $customer->id)
                ->where('participant_type', get_class($customer))
                ->with(['conversation' => function($query) {
                    $query->with('lastMessage');
                }])
                ->get()
                ->map(function($participant) use ($customer) {
                    $conversation = $participant->conversation;

                    // Get other participant for private chats
                    $otherParticipant = null;
                    if ($conversation->type === Conversation::TYPE_PRIVATE) {
                        $otherParticipant = $conversation->participants()
                            ->whereNot(function($query) use ($customer) {
                                $query->where('participant_id', $customer->id)
                                      ->where('participant_type', get_class($customer));
                            })
                            ->first();

                        if ($otherParticipant) {
                            $otherParticipant->load('participant');
                        }
                    }

                    // Count unread messages
                    $unreadCount = ConversationMessage::where('conversation_id', $conversation->id)
                        ->where(function($query) use ($customer) {
                            $query->where('sender_id', '!=', $customer->id)
                                ->orWhere('sender_type', '!=', get_class($customer));
                        })
                        ->where('created_at', '>', $participant->last_read_at ?? '1970-01-01')
                        ->count();

                    return [
                        'id' => $conversation->id,
                        'type' => $conversation->type,
                        'title' => $conversation->title ?? ($otherParticipant ? $otherParticipant->participant->name : null),
                        'last_message' => $conversation->lastMessage ? [
                            'body' => $conversation->lastMessage->body,
                            'created_at' => $conversation->lastMessage->created_at ? $conversation->lastMessage->created_at->timestamp : null,
                            'sender_id' => $conversation->lastMessage->sender_id,
                            'sender_type' => $conversation->lastMessage->sender_type,
                            'sender_name' => $conversation->lastMessage->sender ? $conversation->lastMessage->sender->name : null,
                            'is_mine' => ($conversation->lastMessage->sender_id == $customer->id && $conversation->lastMessage->sender_type == get_class($customer)),
                        ] : null,
                        'unread_count' => $unreadCount,
                        'other_participant' => $otherParticipant ? [
                            'id' => $otherParticipant->participant->id,
                            'name' => $otherParticipant->participant->name,
                            'type' => $otherParticipant->participant_type,
                        ] : null,
                        'updated_at' => $conversation->updated_at ? $conversation->updated_at->timestamp : null,
                        'created_at' => $conversation->created_at ? $conversation->created_at->timestamp : null,
                    ];
                })
                ->sortByDesc(function($conversation) {
                    return $conversation['last_message'] ? $conversation['last_message']['created_at'] : $conversation['created_at'];
                })
                ->values();

            return $this->responseSuccess($conversations);
        } catch (\Exception $e) {
            Log::error('Get conversations error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Get messages for a conversation
     */
    public function getMessages(Request $request, $conversationId): JsonResponse
    {
        try {
            $customer = Auth::guard('customer')->user();
            // Verify customer is in this conversation
            $exists = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $customer->id)
                ->where('participant_type', get_class($customer))
                ->exists();
            if (!$exists) {
                return $this->responseNotFound('Conversation not found');
            }
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 15);

            $messages = ConversationMessage::where('conversation_id', $conversationId)
                ->with([
                    'sender' => function($query) {
                        $query->select('id', 'name', 'email');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            // Đẩy việc đánh dấu đã đọc vào queue để xử lý sau
            ProcessGetMessageJob::dispatch($conversationId, $customer->id, get_class($customer));

            $messagesWithStatus = $messages->map(function($message) use ($customer) {
                $message->is_mine = ($message->sender_id == $customer->id && $message->sender_type == get_class($customer));
                return $message;
            });
            $pagination = [
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
            ];

            $messagesWithStatus = (new ConversationMessageTransformer())->transforms($messagesWithStatus);
            return $this->responseSuccess($messagesWithStatus, 200, 'Success', $pagination);
        } catch (\Exception $e) {
            Log::error('Get messages error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Create a new conversation with admin
     */
    public function createConversation(Request $request): JsonResponse
    {
        try {
            $customer = Auth::guard('customer')->user();
            $adminId = $request->input('admin_id');

            $admin = User::find($adminId);
            if (!$admin) {
                return $this->responseError('Admin not found', 404);
            }

            // Check if a conversation already exists between admin and customer
            $existingConversation = Conversation::findBetween(
                $customer->id, get_class($customer),
                $admin->id, get_class($admin)
            );

            if ($existingConversation) {
                return $this->responseSuccess([
                    'conversation_id' => $existingConversation->id,
                ], 200, 'Conversation already exists');
            }

            // Create a new conversation
            DB::beginTransaction();

            $conversation = Conversation::create([
                'type' => Conversation::TYPE_PRIVATE,
                'created_by_id' => $customer->id,
                'created_by_type' => get_class($customer),
            ]);

            // Add participants
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'participant_id' => $customer->id,
                'participant_type' => get_class($customer),
            ]);

            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'participant_id' => $admin->id,
                'participant_type' => get_class($admin),
            ]);

            DB::commit();

            return $this->responseSuccess([
                'conversation_id' => $conversation->id,
            ], 200, 'Conversation created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create conversation error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Send a new message
     */
    public function sendMessage(SendMessageConversationRequest $request): JsonResponse
    {
        try {
            $customer = Auth::guard('customer')->user();
            $body = $request->input('body');
            $conversationId = $request->input('conversation_id');

            // Kiểm tra nhanh xem người dùng có trong cuộc trò chuyện không
            $exists = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $customer->id)
                ->where('participant_type', get_class($customer))
                ->exists();

            if (!$exists) {
                return $this->responseNotFound('Conversation not found');
            }

            // Tạo message ngay lập tức
            $message = ConversationMessage::create([
                'conversation_id' => $conversationId,
                'sender_id' => $customer->id,
                'sender_type' => get_class($customer),
                'body' => $body,
                'type' => ConversationMessage::TYPE_TEXT,
            ]);
            event(new NewMessageEvent($message));
            ProcessSendMessageJob::dispatch($message, $conversationId);
            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error('Send message error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Get list of admins available for chat
     */
    public function getAdmins(Request $request): JsonResponse
    {
        try {
            $admins = User::select('id', 'name', 'email')
                ->orderBy('name')
                ->get();

            return $this->responseSuccess($admins);
        } catch (\Exception $e) {
            Log::error('Get admins for chat error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Get or create a conversation with a specific admin by email
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getConversationWithAdmin(Request $request): JsonResponse
    {
        try {
            $customer = Auth::guard('customer')->user();
            $adminEmail = $request->input('admin_email');

            if (!$adminEmail) {
                return $this->responseError('Admin email is required', 400);
            }

            // Find admin by email
            $admin = User::where('email', $adminEmail)->first();

            if (!$admin) {
                return $this->responseNotFound('Admin not found');
            }

            // Find conversation between customer and admin
            $conversation = Conversation::findBetween(
                $customer->id, get_class($customer),
                $admin->id, get_class($admin)
            );

            // If conversation doesn't exist, create a new one
            if (!$conversation) {
                DB::beginTransaction();
                try {
                    // Create new conversation
                    $conversation = Conversation::create([
                        'type' => Conversation::TYPE_PRIVATE,
                        'created_by_id' => $customer->id,
                        'created_by_type' => get_class($customer),
                    ]);

                    // Add customer to conversation
                    ConversationParticipant::create([
                        'conversation_id' => $conversation->id,
                        'participant_id' => $customer->id,
                        'participant_type' => get_class($customer),
                    ]);

                    // Add admin to conversation
                    ConversationParticipant::create([
                        'conversation_id' => $conversation->id,
                        'participant_id' => $admin->id,
                        'participant_type' => get_class($admin),
                    ]);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            // Get other participant (admin) details
            $otherParticipant = $conversation->participants()
                ->whereNot(function($query) use ($customer) {
                    $query->where('participant_id', $customer->id)
                          ->where('participant_type', get_class($customer));
                })
                ->first();

            if ($otherParticipant) {
                $otherParticipant->load('participant');
            }

            // Load last message
            $conversation->load('lastMessage');

            return $this->responseSuccess([
                'id' => $conversation->id,
                'type' => $conversation->type,
                'title' => $conversation->title ?? ($otherParticipant ? $otherParticipant->participant->name : null),
                'last_message' => $conversation->lastMessage ? [
                    'body' => $conversation->lastMessage->body,
                    'created_at' => $conversation->lastMessage->created_at ? $conversation->lastMessage->created_at->timestamp : null,
                    'sender_id' => $conversation->lastMessage->sender_id,
                    'sender_type' => $conversation->lastMessage->sender_type,
                    'sender_name' => $conversation->lastMessage->sender ? $conversation->lastMessage->sender->name : null,
                    'is_mine' => ($conversation->lastMessage->sender_id == $customer->id && $conversation->lastMessage->sender_type == get_class($customer)),
                ] : null,
                'other_participant' => $otherParticipant ? [
                    'id' => $otherParticipant->participant->id,
                    'name' => $otherParticipant->participant->name,
                    'email' => $otherParticipant->participant->email,
                    'type' => $otherParticipant->participant_type,
                ] : null,
                'updated_at' => $conversation->updated_at ? $conversation->updated_at->timestamp : null,
                'created_at' => $conversation->created_at ? $conversation->created_at->timestamp : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Get conversation with admin error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
