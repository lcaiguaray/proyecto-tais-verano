<?php

namespace App\Modelos\Gestion;

use Illuminate\Database\Eloquent\Model;
use App\Modelos\Componentes\MapaProceso;

class Empresa extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'razon_social', 'ruc', 'telefono', 'email', 'direccion', 'deleted_at', 'activated_at',
        'created_by', 'updated_by', 'deleted_by', 'activated_by', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function mapa_proceso(){
        return $this->hasMany(MapaProceso::class);
    }
}
