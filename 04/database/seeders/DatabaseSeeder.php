<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $pasword = Hash::make('parola');
        $time = time();

        User::query()->create([
            'id' => 1,
            'name' => 'Superadmin',
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'email_verified_at' => $time,
            'password' => Hash::make('parola'),
            'role_id' => 1,
        ]);

        User::query()->create([
            'id' => 2,
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => $time,
            'password' => Hash::make('parola'),
            'role_id' => 2,
        ]);

        User::query()->create([
            'id' => 3,
            'name' => 'User',
            'username' => 'user',
            'email' => 'user@example.com',
            'email_verified_at' => $time,
            'password' => Hash::make('parola'),
            'role_id' => 3,
        ]);
    }
}
