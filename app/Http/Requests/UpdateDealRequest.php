<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateDealRequest extends FormRequest
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
        $this->merge(['deal_id' => $this->route('deal')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'deal_id' => [
                'required',
                'integer',
                Rule::exists('deals', 'id')
                    ->withoutTrashed(),
            ],
            'seller_id' => [
                'required',
                'integer',
                'different:buyer_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
            ],
            'buyer_id' => [
                'required',
                'integer',
                'different:seller_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
            ],
            'date' => [
                'required',
                'string',
                Rule::date()->beforeOrEqual(now()),
            ],
        ];
    }
}
