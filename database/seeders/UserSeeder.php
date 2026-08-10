<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Juan Perez',
            'email' => 'administracion@cymez.com',
            'password' => 'administracion1',
            'departamento_id' => 2,
            'rol' => 'usuario',
        ]);
    }
}