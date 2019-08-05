<?php

use App\Repository\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'role_id' => Role::USER_ROLE_ID,
            'email' => false,
            'name' => 'anonymous',
            'password' => false
        ]);

        User::create([
            'role_id' => Role::ADMIN_ROLE_ID,
            'email' => env('ADMIN_EMAIL'),
            'name' => 'admin',
            'password' => Hash::make(env('ADMIN_PASSWORD')),
        ]);
    }
}
