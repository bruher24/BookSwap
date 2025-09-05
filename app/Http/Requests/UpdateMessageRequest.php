<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMessageRequest extends FormRequest
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
            'message_id' => [
                'required',
                'integer',
                Rule::exists('messages', 'id')
                    ->whereNull('deleted_at'),
            ],
            'chat_id' => [
                'required',
                'integer',
                Rule::exists('chats', 'id')
                    ->whereNull('deleted_at'),
            ],
            'from_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at'),
            ],
            'to_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at'),
            ],
            'subject' => [
                'nullable',
                'string',
            ],
            'body' => [
                'required',
                'string',
            ],
            'seen' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
