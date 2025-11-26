<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Advisor
        User::create([
            'name' => 'Dr. Sarah Johnson',
            'email' => 'advisor@example.com',
            'role' => 'advisor',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create Team Leaders
        User::create([
            'name' => 'Michael Chen',
            'email' => 'leader1@example.com',
            'role' => 'leader',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Emily Rodriguez',
            'email' => 'leader2@example.com',
            'role' => 'leader',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create Team Members
        User::create([
            'name' => 'David Kim',
            'email' => 'member1@example.com',
            'role' => 'member',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jessica Williams',
            'email' => 'member2@example.com',
            'role' => 'member',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Alex Thompson',
            'email' => 'member3@example.com',
            'role' => 'member',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
