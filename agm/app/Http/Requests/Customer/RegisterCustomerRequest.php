<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiBaseRequest;

class RegisterCustomerRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'email' => 'required|email|unique:customers',
            'password' => 'required|string|min:6',
        ];
    }
}
