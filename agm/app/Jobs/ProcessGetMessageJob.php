<?php

namespace App\Jobs;

use App\Models\ConversationMessageStatus;
use App\Models\ConversationMessage;
use App\Models\ConversationParticipant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessGetMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $conversationId;
    protected $participantId;
    protected $participantType;

    /**
     * Create a new job instance.
     */
    public function __construct(int $conversationId, int $participantId, string $participantType)
    {
        $this->conversationId = $conversationId;
        $this->participantId = $participantId;
        $this->participantType = $participantType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $participant = ConversationParticipant::where('conversation_id', $this->conversationId)
                ->where('participant_id', $this->participantId)
                ->where('participant_type', $this->participantType)
                ->first();

            if ($participant) {
                $participant->markAsRead();
            }
        } catch (\Exception $e) {
            // Log the error
            Log::channel('worker')->error('Error in ProcessGetMessageJob: ' . $e->getMessage());
            throw $e;
        }
    }
}
