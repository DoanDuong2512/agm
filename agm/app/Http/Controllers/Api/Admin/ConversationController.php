<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Customer\SendMessageConversationRequest;
use App\Jobs\ProcessGetMessageJob;
use App\Jobs\ProcessSendMessageJob;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\ConversationMessageStatus;
use App\Models\ConversationParticipant;
use App\Models\Customer;
use App\Events\NewMessageEvent;
use App\Transformers\ConversationMessageTransformer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ConversationController extends BaseApiController
{
    /**
     * Lấy danh sách các cuộc trò chuyện của admin
     */
    public function getConversations(Request $request): JsonResponse
    {
        try {
            $admin = Auth::guard('admin')->user();

            $conversations = ConversationParticipant::where('participant_id', $admin->id)
                ->where('participant_type', get_class($admin))
                ->with(['conversation' => function($query) {
                    $query->with('lastMessage');
                }])
                ->get()
                ->map(function($participant) use ($admin) {
                    $conversation = $participant->conversation;

                    // Lấy người tham gia khác trong cuộc trò chuyện 1-1
                    $otherParticipant = null;
                    if ($conversation->type === Conversation::TYPE_PRIVATE) {
                        $otherParticipant = $conversation->participants()
                            ->whereNot(function($query) use ($admin) {
                                $query->where('participant_id', $admin->id)
                                    ->where('participant_type', get_class($admin));
                            })
                            ->first();

                        if ($otherParticipant) {
                            $otherParticipant->load('participant');
                        }
                    }

                    // Đếm số tin chưa đọc
                    $unreadCount = ConversationMessage::where('conversation_id', $conversation->id)
                        ->where(function($query) use ($admin) {
                            $query->where('sender_id', '!=', $admin->id)
                                ->orWhere('sender_type', '!=', get_class($admin));
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
                            'is_mine' => ($conversation->lastMessage->sender_id == $admin->id && $conversation->lastMessage->sender_type == get_class($admin)),
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
            Log::error('Admin get conversations error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Lấy tin nhắn cho một cuộc trò chuyện
     */
    public function getMessages(Request $request, $conversationId): JsonResponse
    {
        try {
            $admin = Auth::guard('admin')->user();

            // Xác thực admin có trong cuộc trò chuyện không
            $exists = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $admin->id)
                ->where('participant_type', get_class($admin))
                ->first();

            if (!$exists) {
                return $this->responseError('Conversation not found', 404);
            }

            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', 10);

            $messages = ConversationMessage::where('conversation_id', $conversationId)
                ->with([
                    'sender' => function ($query) {
                        $query->select('id', 'name', 'email');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);
            ProcessGetMessageJob::dispatch($conversationId, $admin->id, get_class($admin));

            $messagesWithStatus = $messages->map(function($message) use ($admin) {
                $message->is_mine = ($message->sender_id == $admin->id && $message->sender_type == get_class($admin));
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
            Log::error('Admin get messages error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Tạo cuộc trò chuyện mới với khách hàng
     */
    public function createConversation(Request $request): JsonResponse
    {
        try {
            $admin = Auth::guard('admin')->user();
            $customerId = $request->input('customer_id');

            $customer = Customer::find($customerId);
            if (!$customer) {
                return $this->responseError('Customer not found', 404);
            }

            // Kiểm tra xem đã có cuộc trò chuyện giữa admin và customer chưa
            $existingConversation = Conversation::findBetween(
                $admin->id, get_class($admin),
                $customer->id, get_class($customer)
            );

            if ($existingConversation) {
                return $this->responseSuccess([
                    'conversation_id' => $existingConversation->id,
                ], 200 , 'Conversation already exists');
            }

            // Tạo cuộc trò chuyện mới
            DB::beginTransaction();

            $conversation = Conversation::create([
                'type' => Conversation::TYPE_PRIVATE,
                'created_by_id' => $admin->id,
                'created_by_type' => get_class($admin),
            ]);

            // Thêm người tham gia
            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'participant_id' => $admin->id,
                'participant_type' => get_class($admin),
            ]);

            ConversationParticipant::create([
                'conversation_id' => $conversation->id,
                'participant_id' => $customer->id,
                'participant_type' => get_class($customer),
            ]);

            DB::commit();

            return $this->responseSuccess([
                'conversation_id' => $conversation->id,
            ], 200, 'Conversation created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin create conversation error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Gửi tin nhắn mới
     */
    public function sendMessage(SendMessageConversationRequest $request): JsonResponse
    {
        try {
            $admin = Auth::guard('admin')->user();
            $body = $request->input('body');
            $conversationId = $request->input('conversation_id');
            // Xác thực admin có trong cuộc trò chuyện không
            $exists = ConversationParticipant::where('conversation_id', $conversationId)
                ->where('participant_id', $admin->id)
                ->where('participant_type', get_class($admin))
                ->exists();

            if (!$exists) {
                return $this->responseNotFound('Conversation not found');
            }

            // Tạo tin nhắn mới
            $message = ConversationMessage::create([
                'conversation_id' => $conversationId,
                'sender_id' => $admin->id,
                'sender_type' => get_class($admin),
                'body' => $body,
                'type' => ConversationMessage::TYPE_TEXT,
            ]);

            event(new NewMessageEvent($message));
            ProcessSendMessageJob::dispatch($message, $conversationId);
            return $this->responseSuccess();
        } catch (\Exception $e) {
            Log::error('Admin send message error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }

    /**
     * Lấy danh sách khách hàng có thể chat
     */
    public function getCustomers(Request $request): JsonResponse
    {
        try {
            $search = $request->get('search', '');

            $query = Customer::query();

            // Search by name or email
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $customers = $query->select('id', 'name', 'email')
                ->orderBy('name')
                ->limit(20)
                ->get();

            return $this->responseSuccess($customers);
        } catch (\Exception $e) {
            Log::error('Admin get customers for chat error: ' . $e->getMessage());
            return $this->responseInternalServerError();
        }
    }
}
