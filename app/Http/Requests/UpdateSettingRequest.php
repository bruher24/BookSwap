<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        $this->merge(['setting_id' => $this->route('setting')]);
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
                    ->withoutTrashed(),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('settings', 'name')
                    ->withoutTrashed()
                    ->ignore(request('setting_id')),
            ],
            'label' => [
                'required',
                'string',
                Rule::unique('settings', 'label')
                    ->withoutTrashed()
                    ->ignore(request('setting_id')),
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
