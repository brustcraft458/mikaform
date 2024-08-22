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
            'phone' => '0812345678'
        ], [
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'verified_at'=> now()
        ]);
    }
}
