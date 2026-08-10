<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Carlos Mtz',
            'email' => 'tecnologias@cymez.com',
            'password' => 'tecnologias1',
            'departamento_id' => 2,
            'rol' => 'tecnologias',
        ]);
    }
}