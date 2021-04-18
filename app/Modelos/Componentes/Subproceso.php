<?php

namespace App\Modelos\Componentes;

use Illuminate\Database\Eloquent\Model;

class Subproceso extends Model
{
    protected $table = 'subprocesos';

    protected $fillable = [
        'nombre', 'descripcion', 'proceso_id', 'activo', 'deleted_at', 'activated_at',
        'created_by', 'updated_by', 'deleted_by', 'activated_by'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function proceso(){
        return $this->belongsTo(Proceso::class);
    }
}
