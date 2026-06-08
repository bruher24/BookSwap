<?php

namespace App\Enums;

enum BookCondition: string
{
    case Perfect = 'perfect';
    case Good = 'good';
    case Normal = 'normal';
    case Bad = 'bad';
    case Terrible = 'terrible';

    public function label(): string
    {
        return match ($this) {
            self::Perfect => 'Отличное',
            self::Good => 'Хорошее',
            self::Normal => 'Нормальное',
            self::Bad => 'Плохое',
            self::Terrible => 'Ужасное',
        };
    }
}
