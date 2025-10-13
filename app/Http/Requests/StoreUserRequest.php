<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'name')
                    ->withoutTrashed(),
            ],
            'email' => [
                'required',
                'email',
                'max:70',
                Rule::unique('users', 'email')
                    ->withoutTrashed(),
            ],
            'email_verified_at' => [
                'nullable',
                Rule::date()->beforeOrEqual(now()),
            ],
            'password' => [
                'required',
                'string',
            ],
            'photo_id' => [
                'nullable',
                'integer',
                Rule::exists('photos', 'id')
                    ->withoutTrashed(),
            ],
            'remember_token' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }
}
