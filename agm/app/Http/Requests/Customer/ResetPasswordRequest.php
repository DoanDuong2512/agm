<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiBaseRequest;

class ResetPasswordRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'token' => 'required',
            'email' => 'required|email|exists:customers,email',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
