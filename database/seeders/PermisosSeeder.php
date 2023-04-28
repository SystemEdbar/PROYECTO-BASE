<?php

namespace Database\Seeders;

use App\Models\Admin\MenuUrl;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = MenuUrl::where('link', '!=', '#')->get();
        foreach ($menus as $menu) {
            Permission::create(['name' => 'show ' . $menu['link']]);
            Permission::create(['name' => 'create ' . $menu['link']]);
            Permission::create(['name' => 'update ' . $menu['link']]);
            Permission::create(['name' => 'delete ' . $menu['link']]);
        }
    }
}
