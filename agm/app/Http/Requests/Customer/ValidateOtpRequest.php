<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiBaseRequest;

class ValidateOtpRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'vn_id' => 'required|string|size:12',
            'digit_code' => 'required|string|size:6',
            'temp_token' => 'required|string|size:32'
        ];
    }
}
