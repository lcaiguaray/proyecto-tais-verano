<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TipoEstrategia extends Enum implements LocalizedEnum
{
    const FINANCIERA                 = 'F';
    const CLIENTES                   = 'C';
    const PROCESOS_INTERNOS          = 'PI';
    const APRENDIZAJE_CONOCIMIENTO   = 'AC';

    public static function getDescription($value): string
    {
        switch ($value) {
            case self::FINANCIERA:
                return "Financiera";

            case self::CLIENTES:
                return "Clientes";

            case self::PROCESOS_INTERNOS:
                return "Procesos Internos";

            case self::APRENDIZAJE_CONOCIMIENTO:
                return "Aprendizaje y Conocimiento";

            default:
                break;
        }

        return parent::getDescription($value);
    }
}
