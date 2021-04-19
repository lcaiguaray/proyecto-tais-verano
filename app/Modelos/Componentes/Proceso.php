<?php

namespace App\Modelos\Componentes;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $table = 'procesos';

    protected $fillable = [
        'mapa_proceso_id', 'nombre', 'tipo', 'descripcion', 'activo', 'deleted_at', 'activated_at',
        'created_by', 'updated_by', 'deleted_by', 'activated_by'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function mapa_proceso(){
        return $this->belongsTo(MapaProceso::class);
    }

    public function subproceso(){
        return $this->hasMany(Subproceso::class);
    }

    public function estrategia(){
        return $this->hasMany(Estrategia::class, 'objeto_id');
    }

    public function indicador(){
        return $this->hasMany(Indicador::class, 'objeto_id');
    }
}
