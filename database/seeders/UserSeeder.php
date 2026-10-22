<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin Utama
        User::create([
            'name' => 'Admin Asli',
            'email' => 'admin@datamining.com',
            'password' => Hash::make('admin1234'), // Jangan lupa di-hash!
            'role' => 'admin', // Atur role sebagai admin
        ]);
    }
}