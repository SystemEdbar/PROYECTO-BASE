<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MenuSeeder::class);
        $this->call(PermisosSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UsuarioSeeder::class);
        if (config('APP.ENV') === 'local') {
            $this->call(DataDesarrolloSeeder::class);
        }
    }
}
