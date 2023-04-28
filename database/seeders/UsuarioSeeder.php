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
       $pass = 'Project+1801';
       $admin= User::create([
           'name' => 'Usuario',
           'lastname'=>'Administrador',
           'email' => 'admin@system.com',
           'dpi'=>'0909150003216',
           'password' => Hash::make($pass),
       ]);
        $rol = Role::findByName('Super Administrador');
        $permisos = Permission::all();
        foreach ($permisos as $permiso){
            $permiso->assignRole($rol);
        }
        $admin->assignRole(['Super Administrador']);
    }
}
