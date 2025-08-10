<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class StoreLogin extends FormRequest
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
    public function rules(): array
    {

        return [
            'username' => ['required', 'min:4', 'max:20'],
            'password' => ['required', 'min:6', 'max:20'],
            'captcha' => ['required', function ($attribute, $value, $fail) {
                if (strtolower($value) !== strtolower(session('capLogin'))) {
                    $fail('کد امنیتی اشتباه است.');
                }
            }],


        ];
    }

}
