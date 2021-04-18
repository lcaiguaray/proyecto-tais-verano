<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TipoObjeto extends Enum
{
    const PROCESO       =   'P';
    const SUBPROCESO    =   'S';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::PROCESO:
                return "Proceso";

            case self::SUBPROCESO:
                return "SubProceso";

            default:
                break;
        }

        return parent::getDescription($value);
    }
}
