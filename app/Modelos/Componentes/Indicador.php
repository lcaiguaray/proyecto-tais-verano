<?php

namespace App\Modelos\Componentes;

use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    protected $table = 'indicadors';

    protected $fillable = [
        'mapa_proceso_id', 'objeto_tipo', 'objeto_id', 'nombre', 'responsable', 'objetivo', 'descripcion',
        'realizar', 'mecanismo', 'tolerancia', 'resultados', 'meta', 'linea_base', 'tipo_condicion', 'iniciativa', 'frecuencia', 'formula', 'primer_parametro', 
        'segundo_parametro', 'deleted_at', 'activated_at', 'created_by', 'updated_by', 'deleted_by', 'activated_by', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function mapa_proceso(){
        return $this->belongsTo(MapaProceso::class);
    }

    public function proceso(){
        return $this->belongsTo(Proceso::class, 'objeto_id');
    }

    public function subproceso(){
        return $this->belongsTo(Subproceso::class, 'objeto_id');
    }

    public function data_fuente(){
        return $this->hasMany(DataFuente::class);
    }
}
