<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\ApiBaseRequest;

class GetDataForPrintRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'customer_id' => 'required|exists:customers,id',
        ];
    }
}
