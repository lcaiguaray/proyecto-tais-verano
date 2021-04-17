<?php

namespace App\Modelos\Gestion;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $table = 'procesos';

    protected $fillable = [
        'empresa_id', 'nombre', 'tipo', 'descripcion', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function subprocesos(){
        return $this->hasMany(Subproceso::class);
    }
}
