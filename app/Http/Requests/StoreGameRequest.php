<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGameRequest extends FormRequest
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
            'name' => [
                'required',
                'unique:games'
            ]
        ];
    }

    public function messages(): array 
    {
        return [
            'required' => 'The :attribute is required.',
            'unique' => 'The :attribute is already taken'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'game name'
        ];
    }
}
