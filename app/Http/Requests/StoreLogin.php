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

    /**
     * Handle the failed validation.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     * @throws ThrottleRequestsException
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Throttle logic: checking if attempts exceeded
        $key = 'login:' . $this->ip(); // Or use another unique key for each user
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $remainingSeconds = RateLimiter::availableAt($key) - now()->timestamp;

            // Convert seconds to minutes and seconds
            $minutes = floor($remainingSeconds / 60);
            $seconds = $remainingSeconds % 60;

            throw new ThrottleRequestsException("تعداد تلاش‌هایتان بیش از حد مجاز است. لطفا $minutes دقیقه و $seconds ثانیه دیگر دوباره امتحان کنید.");
        }

        parent::failedValidation($validator);
    }
}
