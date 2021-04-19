<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Permiso extends Enum implements LocalizedEnum
{
    const EMPRESA_INDEX               = 'empresas';
    const EMPRESA_CREATE              = 'empresas.create';
    const EMPRESA_EDIT                = 'empresas.edit';
    const EMPRESA_COMPONENTE          = 'empresas.componentes';
    const MAPA_PROCESO_INDEX          = 'mapa_procesos';
    const MAPA_PROCESO_CREATE         = 'mapa_procesos.create';
    const MAPA_PROCESO_EDIT           = 'mapa_procesos.edit';
    const MAPA_PROCESO_DELETE         = 'mapa_procesos.delete';
    const MAPA_PROCESO_ACTIVE         = 'mapa_procesos.active';
    const PROCESO_INDEX               = 'procesos';
    const PROCESO_CREATE              = 'procesos.create';
    const PROCESO_EDIT                = 'procesos.edit';
    const PROCESO_DELETE              = 'procesos.delete';
    const PROCESO_ACTIVE              = 'procesos.active';
    const SUBPROCESO_INDEX            = 'subprocesos';
    const SUBPROCESO_CREATE           = 'subprocesos.create';
    const SUBPROCESO_EDIT             = 'subprocesos.edit';
    const SUBPROCESO_DELETE           = 'subprocesos.delete';
    const SUBPROCESO_ACTIVE           = 'subprocesos.active';
    const ESTRATEGIA_INDEX            = 'estrategias';
    const ESTRATEGIA_CREATE           = 'estrategias.create';
    const ESTRATEGIA_EDIT             = 'estrategias.edit';
    const ESTRATEGIA_DELETE           = 'estrategias.delete';
    const ESTRATEGIA_ACTIVE           = 'estrategias.active';
    const ESTRATEGIA_SHOW             = 'estrategias.show';
    const INDICADOR_INDEX             = 'indicadores';
    const INDICADOR_CREATE            = 'indicadores.create';
    const INDICADOR_EDIT              = 'indicadores.edit';
    const INDICADOR_DELETE            = 'indicadores.delete';
    const INDICADOR_ACTIVE            = 'indicadores.active';
    const INDICADOR_SHOW              = 'indicadores.show';
    const DATA_FUENTE_INDEX           = 'datafuente';
    const DATA_FUENTE_CREATE          = 'datafuente.create';
    const DATA_FUENTE_EDIT            = 'datafuente.edit';
    const DATA_FUENTE_DELETE          = 'datafuente.delete';
    const DATA_FUENTE_ACTIVE          = 'datafuente.active';
    const DATA_FUENTE_SHOW            = 'datafuente.show';
}
