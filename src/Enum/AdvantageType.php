<?php

namespace App\Enum;

enum AdvantageType: string
{
    case HIGH_QUALITY = 'high_quality';
    case LOW_PRICE = 'low_price';
    case FAST_SHIPPING = 'fast_shipping';

    public function getLabel(): string
    {
        return match ($this) {
            self::HIGH_QUALITY => 'Высокое качество',
            self::LOW_PRICE => 'Низкая цена',
            self::FAST_SHIPPING => 'Быстрая доставка',
        };
    }

    public static function choices(): array
    {
        return array_combine(
            array_map(fn($case) => $case->getLabel(), self::cases()),
            array_map(fn($case) => $case->value, self::cases())
        );
    }
}
