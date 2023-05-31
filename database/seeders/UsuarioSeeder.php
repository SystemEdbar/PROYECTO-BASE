<?php

namespace Database\Seeders;

use App\Models\Catalogos\Customer;
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
        $rol = Role::findByName('Super Administrador');
        $permisos = Permission::all();
        foreach ($permisos as $permiso){
            $permiso->assignRole($rol);
        }

        //Creacion de Super Usuario
       $superAdmin1= User::create([
           'name' => 'Super',
           'lastname'=>'Usuario',
           'email' => 'systemedbar@gmail.com',
           'dpi'=>'3310381611801',
           'password' => Hash::make('SystemEdbar+2023'),
       ]); $superAdmin1->assignRole(['Super Administrador']);

        $superAdmin2 = User::create([
            'name' => 'Juan Avidio',
            'lastname' => 'Salazar García',
            'email' => 'juan.salazar@upcv.gob.gt',
            'dpi' => '2298309431801',
            'password' => Hash::make('Upcv2023'),
        ]);$superAdmin2->assignRole(['Super Administrador']);
    }
}
