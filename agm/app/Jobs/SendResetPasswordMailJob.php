<?php

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $resetLink;
    protected string $email;
    /**
     * Create a new job instance.
     */
    public function __construct($resetLink, $email)
    {
        $this->resetLink = $resetLink;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       try {
           Mail::to($this->email)->send(new ResetPasswordMail($this->resetLink, $this->email));
       } catch (\Exception $exception) {
           Log::channel('worker')->error("Send mail reset password to $this->email error: " . $exception->getMessage());
       }
    }
}
