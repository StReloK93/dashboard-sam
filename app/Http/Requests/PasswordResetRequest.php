<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old' => 'required',
            'new' => 'required|min:6|max:255|confirmed',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => ":attribute bo'sh bo'lmasligi kerak.",
            'min' => ":attribute :min simboldan kam bo'lmasligi kerak.",
            'max' => ":attribute :max simboldan ko'p bo'lmasligi kerak.",
            'confirmed' => ":attributelar mos kelmayabdi"
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'new' => 'Parol'
        ];
    }
}
