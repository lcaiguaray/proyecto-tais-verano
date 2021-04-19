<?php

use Illuminate\Database\Seeder;
use App\Modelos\Auth\Usuario;
use App\Enums\Rol;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usuario = new Usuario();
        $usuario->nombre = 'Admin';
        $usuario->apellido_paterno = 'Administrador';
        $usuario->apellido_materno = 'Tais';
        $usuario->dni = '44445555';
        $usuario->sexo = 'M';
        $usuario->email = 'administrador@hormail.com';
        $usuario->name = 'admin';
        $usuario->password = Hash::make('admin');
        $usuario->created_by = 1;
        $usuario->save();

        $usuario->assignRole(Rol::ADMINISTRADOR);
    }
}
