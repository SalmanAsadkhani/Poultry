<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Add_daily extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'mortality' =>  'required|numeric|min:0',
            'actions' =>   ['nullable', 'string'],
            'desc' =>   ['nullable', 'string'],
        ];

    }

    public function messages()
    {
        return [
            'mortality.required' => 'تعداد تلفات رو وارد نمایید',
        ];
    }
}
