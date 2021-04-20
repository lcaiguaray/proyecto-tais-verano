<?php
use Illuminate\Support\Facades\Auth;
use App\Modelos\Auth\Usuario;
use App\Enums\Sexo;
use App\Enums\TipoProceso;
use App\Enums\TipoObjeto;
use App\Enums\TipoFrecuencia;
use App\Enums\TipoFormula;
use App\Enums\TipoEstrategia;
use App\Enums\Rol;
use App\Enums\Permiso;
use App\Enums\TipoCondicionIndicador;
use Carbon\Carbon;

// ENUMS
if (!function_exists('roles')) {
    function roles()
    {
        return Rol::getInstances();
    }
}

if (!function_exists('permisos')) {
    function permisos()
    {
        return Permiso::getInstances();
    }
}

if (!function_exists('sexos')) {
    function sexos()
    {
        return Sexo::getInstances();
    }
}

if (!function_exists('tipo_procesos')) {
    function tipo_procesos()
    {
        return TipoProceso::getInstances();
    }
}

if (!function_exists('tipo_objetos')) {
    function tipo_objetos($tipo = null)
    {
        if($tipo) return TipoObjeto::getDescription($tipo);
        else return TipoObjeto::getInstances();
    }
}

if (!function_exists('tipo_frecuencias')) {
    function tipo_frecuencias($tipo = null)
    {
        if($tipo) return TipoFrecuencia::getDescription($tipo);
        else return TipoFrecuencia::getInstances();
    }
}

if (!function_exists('tipo_formulas')) {
    function tipo_formulas($tipo = null)
    {
        if($tipo) return TipoFormula::getDescription($tipo);
        else return TipoFormula::getInstances();
    }
}

if (!function_exists('tipo_estrategias')) {
    function tipo_estrategias($tipo = null)
    {
        if($tipo) return TipoEstrategia::getDescription($tipo);
        else return TipoEstrategia::getInstances();
    }
}

if (!function_exists('tipo_condiciones_indicador')) {
    function tipo_condiciones_indicador()
    {
        return TipoCondicionIndicador::getInstances();
    }
}
// END ENUMS

// FECHAS
if (!function_exists('getFecha')) {
    function getFecha($fecha, $formato = null)
    {
        if (is_null($formato))
            $formato = 'd/m/Y';

        return Carbon::parse($fecha)->format($formato);
    }
}

if(!function_exists('convertirA_fecha')) {
    function convertirA_fecha($fecha, $time, $formato = null)
    {
        if(is_null($formato)) $formato = 'Y-m-d'; //FORMATO DB

        if($time) return Carbon::createFromFormat($formato, $fecha)->setTimeFromTimeString('00:00:00');
        else return Carbon::createFromFormat($formato, $fecha);
    }
}
// END FECHAS

// OTHERS
if(!function_exists('getUsuario')) {
    function getUsuario(){
        return Usuario::FindOrFail(Auth::user()->id);
    }
}
// END OTHERS