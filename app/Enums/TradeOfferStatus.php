<?php

namespace App\Enums;

enum TradeOfferStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Finished = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Ожидает ответа',
            self::Accepted => 'Принята',
            self::Rejected => 'Отклонена',
            self::Finished => 'Завершена',
        };
    }
}
