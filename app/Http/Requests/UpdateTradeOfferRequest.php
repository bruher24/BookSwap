<?php

namespace App\Http\Requests;

use App\Enums\TradeOfferStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'trade_offer_items' => [
                'required',
                'array',
                Rule::exists('books', 'id'),
            ],
            'status' => [new Enum(TradeOfferStatus::class)],
        ];
    }
}
