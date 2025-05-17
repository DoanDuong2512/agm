<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\TestEvent;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pusherConfig = config('broadcasting.connections.pusher');
        $this->info('Pusher Config: '. json_encode($pusherConfig['options']));
        
        try {
            $message = 'Hello World! ' . time();
            $this->info('Dispatching event with message: ' . $message);
            event(new TestEvent($message));
            $this->info('Event dispatched successfully!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
