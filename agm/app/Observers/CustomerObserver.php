<?php

namespace App\Observers;

use App\Models\Customer;
use App\Services\SessionService;

class CustomerObserver
{
    public function deleted(Customer $customer)
    {
        SessionService::invalidateAll($customer);
    }
}
