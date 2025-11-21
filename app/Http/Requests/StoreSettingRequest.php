<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSettingRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                Rule::unique('settings', 'name')
                    ->withoutTrashed(),
            ],
            'label' => [
                'required',
                'string',
                Rule::unique('settings', 'label')
                    ->withoutTrashed(),
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'available_values' => [
                'nullable',
                'array'
            ]
        ];
    }
}
