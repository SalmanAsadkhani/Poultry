<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogin extends FormRequest
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
            'username' => ['required','min:4','max:20'],
            'password' => ['required','min:6','max:20'],
            'captcha' => ['required',  function ($attribute, $value, $fail) {
                if (strtolower($value) !== strtolower(session('capLogin'))) {
                    $fail('کد امنیتی اشتباه است.');
                }
            }]
        ];
    }

}
