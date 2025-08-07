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

            'mortality' => 'required|numeric|min:0',
            'actions'   => 'nullable|string',
            'desc'      => 'nullable|string',


            'feeds'         => 'nullable|array',
            'feeds.*.type'  => 'required_with:feeds|string',
            'feeds.*.bags'  => 'required_with:feeds|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'mortality.required' => 'تعداد تلفات را وارد نمایید.',
            'mortality.numeric'  => 'تعداد تلفات باید به صورت عددی وارد شود.',

            'feeds.*.type.required_with' => 'نوع دان برای یکی از ردیف‌ها انتخاب نشده است.',
            'feeds.*.bags.required_with' => 'تعداد کیسه برای یکی از ردیف‌ها وارد نشده است.',
            'feeds.*.bags.numeric'     => 'تعداد کیسه باید به صورت عددی وارد شود.',
            'feeds.*.bags.min'         => 'تعداد کیسه باید حداقل ۱ باشد.',
        ];
    }

    protected function prepareForValidation(): void
    {

        if ($this->has('feeds') && is_string($this->feeds)) {
            $this->merge([
                'feeds' => json_decode($this->feeds, true) ?? []
            ]);
        }
    }
}
