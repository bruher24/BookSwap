<?php

namespace App\Http\Requests;

use App\Models\Chat;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class SendMessageRequest extends FormRequest
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
        $chat = $this->route('chat');
        $this->merge([
            'chat_id' => $chat instanceof Chat ? $chat->id : $chat,
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
            'body' => [
                'required',
                'string',
                'min:1',
                'max:1000',
            ],
        ];
    }
}
