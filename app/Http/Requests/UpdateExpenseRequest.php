<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
    // app/Http/Requests/UpdateExpenseRequest.php
    public function rules(): array
    {
         return[
            'name'        => 'required|string|min:2|max:100',
            'type'        => 'required|string|in:feed,drug,misc',
            'quantity'    => 'required|integer|min:1',
            'price'       => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ];

    }
}
