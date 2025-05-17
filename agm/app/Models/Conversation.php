<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'conversations';

    protected $fillable = [
        'type',
        'title',
        'created_by_id',
        'created_by_type',
        'last_message_id',
    ];

    protected $casts = [];

    // Types of conversations
    const TYPE_PRIVATE = 'private';
    const TYPE_GROUP = 'group';

    /**
     * Get the creator of the conversation.
     */
    public function createdBy(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get all participants in the conversation.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(ConversationParticipant::class);
    }

    /**
     * Get all messages in the conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class);
    }

    /**
     * Get the last message of the conversation.
     */
    public function lastMessage(): BelongsTo
    {
        return $this->belongsTo(ConversationMessage::class, 'last_message_id');
    }

    /**
     * Check if the conversation is between the given users.
     */
    public static function findBetween($user1Id, $user1Type, $user2Id, $user2Type)
    {
        // First, find conversation IDs where user1 is a participant
        $user1Conversations = ConversationParticipant::where('participant_id', $user1Id)
            ->where('participant_type', $user1Type)
            ->pluck('conversation_id')
            ->toArray();

        // Then find conversation IDs where user2 is a participant in those conversations
        $commonConversations = ConversationParticipant::whereIn('conversation_id', $user1Conversations)
            ->where('participant_id', $user2Id)
            ->where('participant_type', $user2Type)
            ->pluck('conversation_id')
            ->toArray();

        // Finally, return the private conversation from the common conversations
        return Conversation::whereIn('id', $commonConversations)
            ->where('type', self::TYPE_PRIVATE)
            ->first();
    }

    /**
     * Update the last message of the conversation.
     * 
     * @param ConversationMessage $message
     * @return void
    */
    public function updateLastMessage(ConversationMessage $message): void
    {
        $this->last_message_id = $message->id;
        $this->touch();
        $this->save();
        
        // Create message statuses for all other participants
        $message->createMessageStatuses();
    }
}
