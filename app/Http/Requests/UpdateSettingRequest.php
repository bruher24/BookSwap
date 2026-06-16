<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateSettingRequest extends FormRequest
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
        $setting = $this->route('setting');
        $this->merge([
            'setting_id' => $setting instanceof Setting ? $setting->id : $setting,
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
