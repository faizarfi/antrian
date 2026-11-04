<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator PST',
                'email' => 'admin@bps.go.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Petugas 1
        User::updateOrCreate(
            ['username' => 'petugas1'],
            [
                'name' => 'Petugas Loket 1',
                'email' => 'petugas1@bps.go.id',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'is_active' => true,
            ]
        );

        // Petugas 2
        User::updateOrCreate(
            ['username' => 'petugas2'],
            [
                'name' => 'Petugas Loket 2',
                'email' => 'petugas2@bps.go.id',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'is_active' => true,
            ]
        );
    }
}
