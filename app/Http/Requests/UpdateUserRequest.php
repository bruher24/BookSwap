<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    #[Override]
    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->route('user')->id,
        ]);
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
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'name')
                    ->ignore($this->input('user_id'))
                    ->withoutTrashed(),
            ],
            'email' => [
                'nullable',
                'string',
                'min:5',
                'max:100',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($this->input('user_id'))
                    ->withoutTrashed(),
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
                    ->whereNot('user_id', $this->input('user_id')),
            ],
        ];
    }
}
