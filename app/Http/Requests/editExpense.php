<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editExpense extends FormRequest
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
            'breeding_cycle_id' => ['required', 'integer', 'exists:breeding_cycles,id'],
            'expense_category_id' => ['required', 'integer', 'exists:expense_categories,id'],
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['nullable', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'min:3', 'max:255'],
        ];
    }
}
