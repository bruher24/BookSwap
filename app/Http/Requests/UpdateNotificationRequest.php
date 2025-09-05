<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNotificationRequest extends FormRequest
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
            'notification_id' => [
                'required',
                'integer',
                Rule::exists('notifications', 'id')
                    ->whereNull('deleted_at'),
            ],
            'subject' => [
                'required',
                'string',
            ],
            'body' => [
                'required',
                'string',
            ],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at'),
            ],
            'seen' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
