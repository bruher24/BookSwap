<?php

namespace App\Enums;

enum BookTypeEnum: int
{
    case HARD = 1;
    case SOFT = 2;
    case DIGITAL = 3;
    case OTHER = 4;

    public function label(): string
    {
        return match ($this) {
            self::HARD => 'Твердая обложка',
            self::SOFT => 'Мягкая обложка',
            self::DIGITAL => 'Электронная',
            self::OTHER => 'Другое',
            default => 'Неизвестно'
        };
    }

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
