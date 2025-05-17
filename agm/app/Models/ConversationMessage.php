<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConversationMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'conversation_messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'sender_type',
        'body',
        'type',
        'is_system_message'
    ];

    protected $casts = [ 'is_system_message' => 'boolean'];


    const TYPE_TEXT = 'text';
    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';
    const TYPE_SYSTEM = 'system';

    /**
     * Get the conversation this message belongs to.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the sender of this message.
     */
    public function sender(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the status records for this message.
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(ConversationMessageStatus::class, 'message_id');
    }

    /**
     * Create read statuses for all participants except the sender.
     */
    public function createReadStatuses()
    {
        $participants = $this->conversation->participants()
            ->where('participant_id', '!=', $this->sender_id)
            ->where('participant_type', '!=', $this->sender_type)
            ->get();

        foreach ($participants as $participant) {
            ConversationMessageStatus::create([
                'message_id' => $this->id,
                'participant_id' => $participant->participant_id,
                'participant_type' => $participant->participant_type,
                'is_delivered' => true,
                'delivered_at' => now()
            ]);
        }
    }

    /**
     * Create message statuses for all participants except the sender.
     * This should be called whenever a new message is created.
     */
    public function createMessageStatuses()
    {
        // Lấy tất cả người tham gia trừ người gửi
        $participants = $this->conversation->participants()
            ->where(function ($query) {
                $query->where('participant_id', '!=', $this->sender_id)
                      ->orWhere('participant_type', '!=', $this->sender_type);
            })
            ->get();

        // Create statuses for all recipients in a single query
        if ($participants->count() > 0) {
            $now = now();
            $statusRecords = $participants->map(function ($participant) use ($now) {
            return [
                'message_id' => $this->id,
                'participant_id' => $participant->participant_id,
                'participant_type' => $participant->participant_type,
                'is_delivered' => true,
                'delivered_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            })->toArray();

            ConversationMessageStatus::insert($statusRecords);
        }
    }
}
