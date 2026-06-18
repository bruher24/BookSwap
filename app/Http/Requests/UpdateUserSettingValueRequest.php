<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateUserSettingValueRequest extends FormRequest
{
    private array $values;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function prepareForValidation(): void
    {
        $setting = $this->route('setting');
        $this->values = $setting instanceof Setting ? $setting->available_values : [];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'value' => [
                'required',
                'string',
                Rule::in(array_map('strval', $this->values)),
            ]
        ];
    }
}
