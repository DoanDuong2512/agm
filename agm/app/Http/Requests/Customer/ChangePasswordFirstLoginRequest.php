<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiBaseRequest;

class ChangePasswordFirstLoginRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'vn_id' => 'required|string|size:12|exists:customers,vn_id',
            'temp_token' => 'required|string|size:32',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
