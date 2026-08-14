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
            'name' => 'Jesus Guerra',
            'email' => 'jefehi13@gmail.com',
            'password' => Hash::make('tecnologias1'),
            'departamento_id' => 2,
            'rol' => 'tecnologias',
        ]);
    }
}