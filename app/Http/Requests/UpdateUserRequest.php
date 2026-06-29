<?php

namespace App\Http\Requests;

use App\Models\User;
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
        $user = $this->route('user');
        $this->merge([
            'user_id' => $user instanceof User ? $user->id : $user,
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
            'phone' => [
                'nullable',
                'string',
                Rule::unique('users', 'phone')
                    ->ignore($this->input('user_id'))
                    ->withoutTrashed(),
            ],
            'telegram_id' => [
                'nullable',
                'string',
                Rule::unique('users', 'telegram_id')
                    ->ignore($this->input('user_id'))
                    ->withoutTrashed(),
            ],
            'vk_id' => [
                'nullable',
                'string',
                Rule::unique('users', 'vk_id')
                    ->ignore($this->input('user_id'))
                    ->withoutTrashed(),
            ],
            'city' => [
                'string',
                'nullable',
                'max:50'
            ]
        ];
    }
}
