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
    const QUINCENAL       = 'Q';
    const MENSUAL         = 'M';
    const ANUAL           = 'A';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::DIARIO:
                return "Diario";

            case self::QUINCENAL:
                return "Quincenal";
    
            case self::MENSUAL:
                return "Mensual";
            
            case self::ANUAL:
                return "Anual";

            default:
                break;
        }

        return parent::getDescription($value);
    }
}
