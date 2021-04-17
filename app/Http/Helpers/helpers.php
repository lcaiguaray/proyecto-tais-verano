<?php
use App\Enums\Sexo;
use App\Enums\TipoProceso;

// ENUMS
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

// END ENUMS