<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678'),
            'phone_number' => '012-3456789',
            'address' => '123 Admin Street, Kuala Lumpur, Malaysia',
            'role' => User::ROLE_ADMIN,
        ]);

        User::create([
            'name' => 'Recruiter User',
            'email' => 'recruiter@example.com',
            'password' => Hash::make('12345678'),
            'phone_number' => '011-9876543',
            'address' => '456 Recruiter Lane, Shah Alam, Malaysia',
            'role' => User::ROLE_RECRUITER,
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('12345678'),
            'phone_number' => '019-8765432',
            'address' => '789 User Drive, Penang, Malaysia',
            'role' => User::ROLE_USER,
        ]);
    }
}
