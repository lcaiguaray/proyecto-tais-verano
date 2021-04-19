<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Enums\Rol;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (roles() as $role) {
            Role::create(['name' => $role]);
        }

        foreach (permisos() as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roleAdmin = Role::findByName(Rol::ADMINISTRADOR);
        $roleAdmin->syncPermissions(Permission::all());

        $roleUser = Role::findByName(Rol::USUARIO);
        $roleUser->syncPermissions(Permission::all());
    }
}
