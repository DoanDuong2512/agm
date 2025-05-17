<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;

class LoginRequest extends ApiBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vn_id' => 'required',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'vn_id.required'    => 'Vui lòng nhập số căn cước.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.string'   => 'Mật khẩu phải là chuỗi ký tự.',
        ];
    }
}
