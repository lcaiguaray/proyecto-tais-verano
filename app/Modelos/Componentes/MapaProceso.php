<?php

namespace App\Modelos\Componentes;

use Illuminate\Database\Eloquent\Model;
use App\Modelos\Gestion\Empresa;

class MapaProceso extends Model
{
    protected $table = 'mapa_procesos';

    protected $fillable = [
        'empresa_id', 'nombre', 'descripcion', 'deleted_at', 'activated_at',
        'created_by', 'updated_by', 'deleted_by', 'activated_by', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function proceso(){
        return $this->hasMany(Proceso::class);
    }

    public function estrategia(){
        return $this->hasMany(Estrategia::class);
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
}
