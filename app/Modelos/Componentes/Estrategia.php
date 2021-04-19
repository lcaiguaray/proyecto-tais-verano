<?php

namespace App\Modelos\Componentes;

use Illuminate\Database\Eloquent\Model;

class Estrategia extends Model
{
    protected $table = 'estrategias';

    protected $fillable = [
        'mapa_proceso_id', 'objeto_tipo', 'objeto_id', 'nombre', 'tipo_estrategia',  'deleted_at', 'activated_at',
        'created_by', 'updated_by', 'deleted_by', 'activated_by', 'activo'
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
}
