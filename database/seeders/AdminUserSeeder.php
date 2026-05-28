<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@etu.uae.ac.ma'],
            [
                'name' => 'System Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'plain_password' => 'admin',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');
    }
}
