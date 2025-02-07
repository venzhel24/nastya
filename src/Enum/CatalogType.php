<?php

namespace App\Enum;

enum CatalogType: string
{
    case ELECTRONICS = 'electronics';
    case CLOTHING = 'clothing';
    case FURNITURE = 'furniture';

    public function getLabel(): string
    {
        return match ($this) {
            self::ELECTRONICS => 'Электроника',
            self::CLOTHING => 'Одежда',
            self::FURNITURE => 'Мебель',
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
