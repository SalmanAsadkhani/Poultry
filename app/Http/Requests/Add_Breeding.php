<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Add_Breeding extends FormRequest
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
            'Name' => ['required', 'string', 'max:255' , 'min:3'],
            'Date' => ['required', 'regex:/^\d{4}\/\d{2}\/\d{2}$/'],
            'Count' => ['required', 'numeric', 'min:5000'],
        ];
    }


    public function messages()
    {
        return [
            'Name.required' => ' نام پرورش الزامی می باشد',
            'Date.required' => 'تاریخ جوجه‌ریزی الزامی می باشد',
            'Count.required' => 'تعداد جوجه الزامی می باشد',
            'Count.min' => 'تعداد جوجه وارد شده اشتباه است',
            'Date.regex' => 'تاریخ وارد شده نامعتبر است '
        ];
    }
}
