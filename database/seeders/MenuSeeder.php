<?php

namespace Database\Seeders;

use App\Models\Admin\MenuUrl;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administracion = MenuUrl::create(['name' => 'Administración', 'link' => '#', 'icon' => 'fas fa-shield-alt ', 'father' => 0, 'order' => 1]);
        MenuUrl::create(['name' => 'Roles', 'link' => 'administracion/roles', 'icon' => 'fas fa-user-shield', 'father' => $administracion->id, 'order' => 1]);
        MenuUrl::create(['name' => 'Usuarios', 'link' => 'administracion/usuarios', 'icon' => 'fas fa-users', 'father' => $administracion->id, 'order' => 2]);
    }
}
