<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class watterJugRequest extends FormRequest
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
            'x_capacity' => 'required|numeric|gt:0',
            'y_capacity' => 'required|numeric|gt:0',
            'z_amount_wanted' => 'required|numeric|gt:0'
        ];
    }


    public function messages(): array
    {
        // custom messages for 'required' rule to provide more meaningful error validation messages
        // default behaviour does not provide the exact variable name

        return [
            'x_capacity.required' => 'The x_capacity_field is required',
            'y_capacity.required' => 'The y_capacity is required',
            'z_amount_wanted.required' => 'The z_amount_wanted is required'
        ];
    }
}
