<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeywordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (!$this->has('id')) {
            return [
                'key_name' => ['required', 'unique:wx_keywords', function ($attribute, $value, $fail) {
                    $this->merge([
                        'key_name' => strtolower(trim($value))
                    ]);
                }],
                'key_value' => ['required'],
            ];
        }
        return [];
    }
}
