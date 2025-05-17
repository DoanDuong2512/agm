<?php

namespace App\Http\Requests\Customer;

use App\Http\Requests\ApiBaseRequest;

class SendMessageConversationRequest extends ApiBaseRequest
{
    public function authorize() {
        return true;
    }
    public function rules() {
        return [
            'body' => 'required|string',
            'conversation_id' => 'required|numeric'
        ];
    }
}
