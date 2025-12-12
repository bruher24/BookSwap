<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateTradeOfferRequest extends FormRequest
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
        $this->merge(['trade_offer_id' => $this->route('trade_offer')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'trade_offer_id' => [
                'required',
                'integer',
                Rule::exists('trade_offers', 'id')
                    ->withoutTrashed(),
            ],
            'sender_id' => [
                'required',
                'integer',
                'different:receiver_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
            ],
            'receiver_id' => [
                'required',
                'integer',
                'different:sender_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
            ],
            'trade_offer_items' => [
                'required',
                'array',
                Rule::exists('books', 'id'),
            ],
            'date' => [
                'required',
                'string',
                Rule::date()->beforeOrEqual(now()),
            ],
        ];
    }
}
