<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ConversationParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'conversation_participants';

    protected $fillable = [
        'conversation_id',
        'participant_id',
        'participant_type',
        'last_read_at',
        'is_admin'
    ];

    protected $casts = ['is_admin' => 'boolean'];

    /**
     * Get the conversation this participant belongs to.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the participant model (User or Customer).
     */
    public function participant(): MorphTo
    {
        return $this->morphTo();
    }

    public function markAsRead()
    {
        // Cập nhật thời gian đọc cuối cùng
        $this->update(['last_read_at' => now()]);

        // Lấy danh sách các message chưa đọc
        $unreadMessages = ConversationMessage::where('conversation_id', $this->conversation_id)
            ->where(function($query) {
                $query->where('sender_id', '!=', $this->participant_id)
                    ->orWhere('sender_type', '!=', $this->participant_type);
            })
            ->whereDoesntHave('statuses', function($query) {
                $query->where('participant_id', $this->participant_id)
                    ->where('participant_type', $this->participant_type)
                    ->where('is_read', true);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Cập nhật trạng thái đọc cho mỗi tin nhắn
        foreach ($unreadMessages as $message) {
            // Tìm hoặc tạo mới trạng thái
            $status = ConversationMessageStatus::firstOrCreate(
                [
                    'message_id' => $message->id,
                    'participant_id' => $this->participant_id,
                    'participant_type' => $this->participant_type,
                ],
                [
                    'is_delivered' => true,
                    'delivered_at' => now(),
                ]
            );

            // Đánh dấu là đã đọc
            $status->markAsRead();
        }

        return $this;
    }

}
