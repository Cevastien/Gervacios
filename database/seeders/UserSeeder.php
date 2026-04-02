<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder
 *
 * Creates default admin and staff users for testing.
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@kiosk.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Staff user
        User::create([
            'name' => 'Staff Member',
            'email' => 'staff@kiosk.test',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
    }
}
