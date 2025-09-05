<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingValueRequest extends FormRequest
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
        // TODO: продумать
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('setting_user', 'user_id')
                    ->where('setting_id', request('setting_id'))
                    ->whereNull('deleted_at'),
            ],
            'setting_id' => [
                'required',
                'integer',
                Rule::exists('setting_user', 'setting_id')
                    ->where('user_id', request('user_id'))
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
