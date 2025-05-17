<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiBaseRequest;

class ForgotPasswordRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'vn_id' => 'required|string|size:12',
            'email' => 'required|email'
        ];
    }
}
