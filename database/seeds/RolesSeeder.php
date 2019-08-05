<?php

use App\Repository\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'user'
        ]);

        Role::create([
            'name' => 'admin'
        ]);
    }
}
