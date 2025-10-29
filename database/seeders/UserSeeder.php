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
        User::factory()->create([
            'name' => 'Cliente Exemplo',
            'email' => 'cliente@example.com',
            'password' => Hash::make('password'),
            'role' => 'cliente',
        ]);

        User::factory()->create([
            'name' => 'Suporte Exemplo',
            'email' => 'suporte@example.com',
            'password' => Hash::make('password'),
            'role' => 'suporte',
        ]);

        User::factory()->create([
            'name' => 'Admin Exemplo',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
