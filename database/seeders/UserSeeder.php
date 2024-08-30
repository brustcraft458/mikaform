<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'username' => 'adminuser',
            'phone' => '6281234567890'
        ], [
            'password' => bcrypt('admin12345678'),
            'role' => 'admin',
            'verified_at'=> now()
        ]);
    }
}
