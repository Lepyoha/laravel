<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserStatus extends Enum
{
    const Active = 0;
    const Frozen = 1;
    const Disabled = 2;
    const PhysicallyDisabled = 3;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::Active:
                return 'Активен';
            case self::Frozen:
                return 'Заморожен';
            case self::Disabled:
                return 'Отключён';
            case self::PhysicallyDisabled:
                return 'Отключён физически';
            default:
                return self::getKey($value);
        }
    }
}
