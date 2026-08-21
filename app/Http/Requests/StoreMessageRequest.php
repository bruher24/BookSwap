<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreMessageRequest extends FormRequest
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
            'chat_id' => [
                'required',
                'integer',
                Rule::exists('chats', 'id')
                    ->withoutTrashed(),
            ],
            'sender_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
            ],
            'subject' => [
                'required',
                'string',
            ],
            'body' => [
                'required',
                'string',
            ],
        ];
    }
}
