<?php

namespace App\Enums;

enum ServicePrice: int
{
    case FREEZE_NEXT_MONTH = 20;
    case URGENT_FREEZE = 70;
    case CHANGE_TARIFF_NEXT_MONTH = 50;
    case URGENT_TARIFF_CHANGE = 100;

    public function description(): string
    {
        return match ($this) {
            self::FREEZE_NEXT_MONTH => 'Заморозка с 1 числа следующего месяца на 3 месяца - 20 рублей',
            self::URGENT_FREEZE => 'Срочная заморозка с завтрашнего дня - 70 рублей',
            self::CHANGE_TARIFF_NEXT_MONTH => 'Смена тарифного плана с 1 числа следующего месяца - 50 рублей',
            self::URGENT_TARIFF_CHANGE => 'Срочная смена тарифного плана - 100 рублей',
        };
    }
}
