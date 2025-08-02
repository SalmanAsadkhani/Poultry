<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules =  [
            'type'              => 'required|string|in:feed,drug,misc',
            'name'              => 'required|string|min:3|max:255',
            'category_id'       => 'required|integer',
            'breeding_cycle_id' => 'required|integer|exists:breeding_cycles,id',
            'quantity'          => 'required|integer|min:1',
            'unit_price'        => 'nullable|integer|min:0',
            'description'       => 'nullable|string',
        ];

        if ($this->type == 'feed') {
            $rules['bag_count'] = 'required|integer|min:1|max:450';
        }

        return $rules;
    }




}
