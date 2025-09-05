<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at'),
            ],
            'name' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'name')
                    ->whereNull('deleted_at')
                    ->ignore(request('user_id')),
            ],
            'email' => [
                'nullable',
                'string',
                'min:5',
                'max:100',
                'email',
                Rule::unique('users', 'email')
                    ->whereNull('deleted_at')
                    ->ignore(request('user_id')),
            ],
            'old_password' => [
                'required_with:password',
                'nullable',
                'string'
            ],
            'password' => [
                'required_with:old_password',
                'nullable',
                'string',
                'confirmed'
            ],
            'password_confirmation' => [
                'required_with:old_password',
                'nullable',
                'string',
                'same:password'
            ],
            'phone_number' => [
                'nullable',
                'string',
                Rule::unique('phones', 'number')
                    ->whereNot('user_id', request('user_id')),
            ],
        ];
    }
}
