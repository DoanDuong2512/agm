<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public static function validateCredentials(array $credentials): bool
    {
        try {
            $customer = Customer::where('vn_id', $credentials['vn_id'])
                ->orderBy('vn_id_issue_date', 'desc')
                ->first();

            if (!$customer) {
                return false;
            }

            $provider = auth('customer')->getProvider();
            return $provider->validateCredentials($customer, $credentials);
        } catch (\Exception $e) {
            Log::error('Auth service validateCredentials error: ' . $e->getMessage());
            return false;
        }
    }
}
