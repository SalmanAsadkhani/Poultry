<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncomeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $rules =  [
            'type'              => 'required|string|in:chicken,misc',
            'name'              => 'required|string|min:3|max:255',
            'category_id'       => 'required|integer',
            'breeding_cycle_id' => 'required|integer|exists:breeding_cycles,id',
            'quantity'          => 'required|integer|min:1',
            'price'        => 'nullable|integer|min:0',
            'description'       => 'nullable|string',
        ];

        if ($this->type == 'chicken') {
            $rules['weight'] = 'required|numeric|min:0';
        }

        return $rules;
    }
}
