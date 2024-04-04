<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'login' => 'required|min:5|max:255|unique:users',
            'password' => 'required|min:6|max:255|confirmed',
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
            'confirmed' => ":attributelar mos kelmayabdi",
            'unique' => ":attribute band",
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
            'password' => 'Parol'
        ];
    }
}
