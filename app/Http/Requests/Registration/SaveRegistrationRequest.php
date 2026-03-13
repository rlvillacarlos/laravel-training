<?php

namespace App\Http\Requests\Registration;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveRegistrationRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'unique:users',
                'alpha_dash:ascii',
            ],
            'email' => [
                'required',
                Rule::email()->strict()
                    ->validateMxRecord()
                    ->preventSpoofing(),
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:'.config('registration.password.length'),
                'confirmed',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'alpha_dash' => 'The :attribute is not valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'player name',
        ];
    }
}
