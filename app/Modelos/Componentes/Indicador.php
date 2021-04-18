<?php

namespace App\Modelos\Componentes;

use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    protected $table = 'indicadors';

    protected $fillable = [
        'objeto_tipo', 'objeto_id', 'responsable', 'meta', 'descripcion', 'deleted_at', 'activated_at',
        'created_by', 'updated_by', 'deleted_by', 'activated_by', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
}
