<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'username' => 'akun',
            'email' => 'akun@gmail.com',
            'full_name' => 'akun',
            'password' => bcrypt('akun')
        ]);
        User::create([
            'username' => 'akun2',
            'email' => 'akun2@gmail.com',
            'full_name' => 'akun2',
            'password' => bcrypt('akun')
        ]);
    }
}
