<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Rol extends Enum implements LocalizedEnum
{
    const ADMINISTRADOR = 'Administrador';
    const USUARIO       = 'Usuario';
}
