<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TipoFormula extends Enum implements LocalizedEnum
{
    const COMPLEMENTO   = 'F1';
    const PORCENTUAL    = 'F2';
    const SUMA          = 'F3';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::COMPLEMENTO:
                return "Formula 01";

            case self::PORCENTUAL:
                return "Formula 02";

            case self::SUMA:
                return "Formula 03";

            default:
                break;
        }

        return parent::getDescription($value);
    }
}
