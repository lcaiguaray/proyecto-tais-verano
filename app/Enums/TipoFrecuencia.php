<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TipoFrecuencia extends Enum implements LocalizedEnum
{
    const DIARIO          = 'D';
    const SEMANAL         = 'S';
    const QUINCENAL       = 'Q';
    const MENSUAL         = 'M';
    const BIMESTRAL       = 'B';
    const TRIMESTRAL      = 'T';
    const ANUAL           = 'A';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::DIARIO:
                return "Diario";

            case self::SEMANAL:
                return "Semanal";

            case self::QUINCENAL:
                return "Quincenal";
    
            case self::MENSUAL:
                return "Mensual";

            case self::BIMESTRAL:
                return "Bimestral";

            case self::TRIMESTRAL:
                return "Trimestral";
            
            case self::ANUAL:
                return "Anual";

            default:
                break;
        }

        return parent::getDescription($value);
    }
}
