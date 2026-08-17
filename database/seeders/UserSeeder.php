<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Jesus Hinojosa',
            'email' => 'jehg13072002@gmail.com',
            'password' => Hash::make('12345678'),
            'departamento_id' => 1,
            'numeroempleado' => '10001',
            'rol' => 'tecnologias',
        ]);
    }
}