<?php

namespace App\Jobs;

use App\Models\Conversation;
use App\Models\ConversationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessSendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;
    protected $conversationId;

    /**
     * Create a new job instance.
     */
    public function __construct(ConversationMessage $message, int $conversationId)
    {
        $this->message = $message;
        $this->conversationId = $conversationId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::transaction(function () {
                $conversation = Conversation::find($this->conversationId);
                if ($conversation) {
                    $conversation->messages()->save($this->message);
                    $conversation->updateLastMessage($this->message);
                }
            });
        } catch (\Exception $exception) {
            // Log the error
            Log::channel('worker')->error('Error in ProcessSendMessageJob: ' . $exception->getMessage());
        }
    }
}
