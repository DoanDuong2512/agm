<?php

namespace App\Events;

use App\Models\ConversationMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ConversationMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Sửa đoạn này để broadcast cho cả kênh admin
        $channels = [
            new PresenceChannel('conversation.'.$this->message->conversation_id),
        ];
        
        // Nếu tin nhắn từ customer, thêm kênh admin để thông báo
        if ($this->message->sender_type === 'App\\Models\\Customer') {
            $channels[] = new PresenceChannel('admin-notifications');
        }
        
        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'new.message';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'conversation_id' => $this->message->conversation_id,
                'sender_id' => $this->message->sender_id,
                'sender_type' => $this->message->sender_type,
                'body' => $this->message->body,
                'type' => $this->message->type,
                'created_at' => $this->message->created_at ? $this->message->created_at->timestamp : null,
            ],
            'sender' => [
                'id' => $this->message->sender_id,
                'type' => $this->message->sender_type,
                'name' => $this->message->sender->name,
            ]
        ];
    }
}
