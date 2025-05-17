<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;

class ChangePasswordRequest extends ApiBaseRequest
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
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:6',             // must be at least 6 characters in length
                'max:32',
            ]
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới có độ dài ít nhất 6 ký tự',
            'new_password.max' => 'Mật khẩu mới có độ dài tối đa 32 ký tự',
            're_new_password.required' => 'Vui lòng nhập lại mật khẩu mới',
            're_new_password.min' => 'Mật khẩu mới có độ dài ít nhất 6 ký tự',
            're_new_password.max' => 'Mật khẩu mới có độ dài tối đa 32 ký tự',
        ];
    }

}
