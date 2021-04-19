<?php

namespace App\Modelos\Componentes;

use Illuminate\Database\Eloquent\Model;

class DataFuente extends Model
{
    protected $table = 'data_fuentes';

    protected $fillable = [
        'indicador_id', 'valor', 'fecha',  'deleted_at', 'activated_at', 'created_by',
        'updated_by', 'deleted_by', 'activated_by', 'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    // RELATIONS
    public function indicador(){
        return $this->belongsTo(Indicador::class);
    }
}
