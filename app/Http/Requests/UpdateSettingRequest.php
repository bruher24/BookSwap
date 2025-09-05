<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingRequest extends FormRequest
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
            'setting_id' => [
                'required',
                'integer',
                Rule::exists('settings', 'id')
                    ->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('settings', 'name')
                    ->whereNull('deleted_at')
                    ->ignore(request('setting_id')),
            ],
            'label' => [
                'required',
                'string',
                Rule::unique('settings', 'label')
                    ->whereNull('deleted_at')
                    ->ignore(request('setting_id')),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
