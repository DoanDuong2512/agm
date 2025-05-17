<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiBaseRequest;

class GetConfigRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'key' => 'required|string',
        ];
    }
}
