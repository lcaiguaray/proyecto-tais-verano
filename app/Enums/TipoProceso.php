<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TipoProceso extends Enum implements LocalizedEnum
{
    const ESTRATEGICO    = 'E';
    const PRIMARIO       = 'P';
    const APOYO          = 'A';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::ESTRATEGICO:
                return "Proceso Estratégico";

            case self::PRIMARIO:
                return "Proceso Primario";
            
            case self::APOYO:
                return "Proceso de Apoyo";

            default:
                break;
        }

        return parent::getDescription($value);
    }
}
