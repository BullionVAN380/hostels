<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@hostel.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@hostel.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@hostel.com',
            'password' => Hash::make('password123'),
            'role' => 'student',
        ]);
    }
}
