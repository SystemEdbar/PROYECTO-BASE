<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Asignacion de Roles
        $rol1 = Role::findByName('Super Administrador');
        $rol2 = Role::findByName('Administrador');
        $permisos = Permission::all();
        foreach ($permisos as $permiso){
            $permiso->assignRole($rol1);
            $permiso->assignRole($rol2);
        }
        //Creacion de Super Usuario
       $superAdmin1= User::create([
           'name' => 'Super',
           'lastname'=>'Usuario',
           'email' => 'systemedbar@gmail.com',
           'password' => Hash::make('SystemEdbar+2023'),
       ]); $superAdmin1->assignRole(['Super Administrador']);
    }
}
