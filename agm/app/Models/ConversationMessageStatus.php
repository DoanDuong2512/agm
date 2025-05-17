<?php

namespace App\Models;

use App\Events\MessageSeenEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConversationMessageStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'conversation_message_statuses';

    protected $fillable = [
        'message_id',
        'participant_id',
        'participant_type',
        'is_read',
        'is_delivered',
        'read_at',
        'delivered_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_delivered' => 'boolean'
    ];

    /**
     * Get the message this status belongs to.
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(ConversationMessage::class, 'message_id');
    }

    /**
     * Get the participant this status belongs to.
     */
    public function participant(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            $this->save();
        }

        return $this;
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered()
    {
        $this->is_delivered = true;
        $this->delivered_at = now();
        $this->save();

        return $this;
    }
}
