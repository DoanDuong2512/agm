<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiBaseRequest;

class GetMessageWithAdminRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'admin_email' => 'required|email',
        ];
    }
}
