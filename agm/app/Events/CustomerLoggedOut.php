<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerLoggedOut
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Customer $customer;
    public string $token;
    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, string $token)
    {
        $this->customer = $customer;
        $this->token = $token;
    }
}
