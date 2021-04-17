<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Sexo extends Enum
{
    const HOMBRE    = 'H';
    const MUJER     = 'M';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::HOMBRE:
                return "Masculino";

            case self::MUJER:
                return "Femenino";

            default:
                break;
        }

        return parent::getDescription($value);
    }
}
