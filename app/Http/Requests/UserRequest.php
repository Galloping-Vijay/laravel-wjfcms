<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserRequest extends RequestPost
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:255', Rule::unique('users')->ignore(Auth::user()->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore(Auth::user()->id)],
            'tel' => ['required', Rule::unique('users')->ignore(Auth::user()->id), function ($attribute, $value, $fail) {
                if (!preg_match("/^1[345678]{1}\d{9}$/", $value)) {
                    $fail('请输入正确的手机号码');
                }
            }],
        ];
    }
}
