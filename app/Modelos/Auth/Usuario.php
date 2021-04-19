<?php

namespace App\Modelos\Auth;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Modelos\Gestion\Asignar;

class Usuario extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nombre', 'apellido_paterno', 'apellido_materno', 'dni',
        'sexo', 'fecha_nacimiento', 'telefono', 'direccion', 'activo', 'deleted_at', 'activated_at', 'last_login_at',
        'last_login_ip', 'created_by', 'updated_by', 'deleted_by', 'activated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // RELATIONS
    public function asignar(){
        return $this->hasMany(Asignar::class);
    }

    // Other methods
    public function getNombreCompleto()
    {
        return $this->nombre.' '.$this->apellido_paterno.' '.$this->apellido_materno;
    }
}
