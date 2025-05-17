<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Customer $customer;
    public string $token;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, $token)
    {
        $this->customer = $customer;
        $this->token = $token;
    }
}
