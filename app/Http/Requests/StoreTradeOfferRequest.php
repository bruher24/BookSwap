<?php

namespace App\Http\Requests;

use App\Enums\TradeOfferStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreTradeOfferRequest extends FormRequest
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
            'sender_id' => [
                'required',
                'integer',
                'different:receiver_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
                Rule::unique('trade_offers', 'sender_id')
                    ->where('receiver_id', request('receiver_id'))
                    ->where('status', TradeOfferStatus::Pending->value)
                    ->withoutTrashed(),
            ],
            'receiver_id' => [
                'required',
                'integer',
                'different:sender_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
                Rule::unique('trade_offers', 'receiver_id')
                    ->where('sender_id', request('sender_id'))
                    ->where('status', TradeOfferStatus::Pending->value)
                    ->withoutTrashed(),
            ],
            'sender_items' => [
                'required',
                'array',
            ],
            'sender_items.*' => [
                'required',
                'integer',
                Rule::exists('books', 'id')
                    ->withoutTrashed(),
            ],
            'receiver_items' => [
                'required',
                'array',
            ],
            'receiver_items.*' => [
                'required',
                'integer',
                Rule::exists('books', 'id')
                    ->withoutTrashed(),
            ],
        ];
    }
}
