<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use updateOrCreate to make the seeder idempotent (no duplicate key errors)
        $defaults = [
            ['name' => 'Admin', 'email' => 'admin@admin.com', 'role' => 'admin'],
            ['name' => 'User', 'email' => 'user@user.com', 'role' => 'user'],
        ];

        foreach ($defaults as $d) {
            User::updateOrCreate(
                ['email' => $d['email']],
                [
                    'name' => $d['name'],
                    'password' => Hash::make('password'),
                    'role' => $d['role'],
                ]
            );
        }

        // Create or update 8 additional users with known credentials (user1..user8)
        for ($i = 1; $i <= 8; $i++) {
            $email = "user{$i}@example.com";
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => "User {$i}",
                    'password' => Hash::make('password'),
                    'role' => 'user',
                ]
            );
        }
    }
}
