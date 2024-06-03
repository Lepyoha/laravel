<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserCity extends Enum
{
    const Donetsk = 0;
    const Mandrykino = 1;
    const Andreevka = 2;
    const Maryanovka = 3;

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::Donetsk:
                return 'г. Донецк';
            case self::Mandrykino:
                return 'с. Мандрыкино';
            case self::Andreevka:
                return 'с. Андреевка';
            case self::Maryanovka:
                return 'с. Марьяновка';
            default:
                return parent::getDescription($value);
        }
    }
}
