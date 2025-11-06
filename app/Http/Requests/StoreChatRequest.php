<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChatRequest extends FormRequest
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
            'first_user_id' => [
                'required',
                'integer',
                'different:second_user_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
                Rule::unique('chats', 'first_user_id')
                    ->where('second_user_id', request('second_user_id'))
                    ->withoutTrashed(),
                Rule::unique('chats', 'second_user_id')
                    ->where('first_user_id', request('second_user_id'))
                    ->withoutTrashed(),
            ],
            'second_user_id' => [
                'required',
                'integer',
                'different:first_user_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
                Rule::unique('chats', 'second_user_id')
                    ->where('first_user_id', request('first_user_id'))
                    ->withoutTrashed(),
                Rule::unique('chats', 'first_user_id')
                    ->where('second_user_id', request('first_user_id'))
                    ->withoutTrashed(),
            ],
            'blocked_by' => [
                'nullable',
                'string',
                'in:first,second',
            ],
        ];
    }
}
