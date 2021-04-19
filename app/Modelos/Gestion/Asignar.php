<?php

namespace App\Modelos\Gestion;

use Illuminate\Database\Eloquent\Model;
use App\Modelos\Auth\Usuario;

class Asignar extends Model
{
    protected $table = 'asignars';

    protected $fillable = [
        'usuario_id', 'empresa_id', 'descripcion', 'deleted_at', 'activated_at',
        'created_by', 'updated_by', 'deleted_by', 'activated_by', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
}
