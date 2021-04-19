<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TipoCondicionIndicador extends Enum implements LocalizedEnum
{
    const CONDICION_MENOR   = 'CME';
    const CONDICION_MAYOR   = 'CMA';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::CONDICION_MENOR:
                return "Condición Menor";

            case self::CONDICION_MAYOR:
                return "Condición Mayor";

            default:
                break;
        }

        return parent::getDescription($value);
    }
}
