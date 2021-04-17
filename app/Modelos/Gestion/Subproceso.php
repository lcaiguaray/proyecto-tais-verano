<?php

namespace App\Modelos\Gestion;

use Illuminate\Database\Eloquent\Model;

class Subproceso extends Model
{
    protected $table = 'subprocesos';

    protected $fillable = [
        'nombre', 'descripcion', 'proceso_id', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function proceso(){
        return $this->belongsTo(Proceso::class);
    }
}
