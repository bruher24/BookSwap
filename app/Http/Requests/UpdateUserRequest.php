<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Query\Builder;
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:50|unique:users,name,' . $this->user()->id,
            'email' => 'nullable|string|min:5|max:100|email|unique:users,email,' . $this->user()->id,
            'old_password' => 'required_with:password|nullable|string',
            'password' => 'required_with:old_password|nullable|string|confirmed',
            'password_confirmation' => 'required_with:old_password|nullable|string|same:password',
            'phone_number' => [
                'nullable',
                'string',
                Rule::unique('phones', 'number')->where(
                    fn(Builder $query) => $query->whereNot('user_id', $this->user()->id)
                ),
            ],
        ];
    }
}
