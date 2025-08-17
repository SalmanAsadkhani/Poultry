<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceIncomeRequest extends FormRequest
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
            'breeding_cycle_id' => 'required|exists:breeding_cycles,id',
            'income_category'  => 'required|string|in:chicken,misc',
            'NameInvoice'       => ['required', 'string', 'max:255' , 'min:3'],
        ];
    }
}
