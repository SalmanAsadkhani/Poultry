<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDaily extends FormRequest
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
            'feed' =>  'required|numeric|min:1',
            'actions' =>   ['nullable', 'string'],
            'desc' =>   ['nullable', 'string'],
        ];

    }

    public function messages()
    {
        return [
            'mortality.required' => 'تعداد تلفات رو وارد نمایید',
            'mortality.numeric' => 'تعداد تلفات باید به صورت عددی وارد شود',
            'feed.required' => 'تعداد دان مصرفی رو وارد نمایید',
            'feed.numeric' => 'تعداد دان مصرفی باید به صورت عددی وارد شود',
        ];
    }
}
