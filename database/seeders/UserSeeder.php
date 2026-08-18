<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Jesus Hinojosa',
                'email' => 'jehg13072002@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 1,
                'numeroempleado' => '10001',
                'rol' => 'tecnologias',
            ],
            [
                'name' => 'Juan Perez',
                'email' => 'juan.perez@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 2,
                'numeroempleado' => '10002',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Carlos Martinez',
                'email' => 'carlos.martinez@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 3,
                'numeroempleado' => '10003',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Ana Garcia',
                'email' => 'ana.garcia@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 4,
                'numeroempleado' => '10004',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Luis Rodriguez',
                'email' => 'luis.rodriguez@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 5,
                'numeroempleado' => '10005',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Maria Gonzalez',
                'email' => 'maria.gonzalez@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 6,
                'numeroempleado' => '10006',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Pedro Sanchez',
                'email' => 'pedro.sanchez@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 7,
                'numeroempleado' => '10007',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Sofia Torres',
                'email' => 'sofia.torres@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 8,
                'numeroempleado' => '10008',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Miguel Ramirez',
                'email' => 'miguel.ramirez@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 9,
                'numeroempleado' => '10009',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Laura Hernandez',
                'email' => 'laura.hernandez@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 10,
                'numeroempleado' => '10010',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Diego Flores',
                'email' => 'diego.flores@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 11,
                'numeroempleado' => '10011',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Valeria Cruz',
                'email' => 'valeria.cruz@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 12,
                'numeroempleado' => '10012',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Roberto Morales',
                'email' => 'roberto.morales@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 1,
                'numeroempleado' => '10013',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Fernanda Castillo',
                'email' => 'fernanda.castillo@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 2,
                'numeroempleado' => '10014',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Alejandro Vargas',
                'email' => 'alejandro.vargas@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 3,
                'numeroempleado' => '10015',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Gabriela Mendoza',
                'email' => 'gabriela.mendoza@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 4,
                'numeroempleado' => '10016',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Ricardo Ortega',
                'email' => 'ricardo.ortega@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 5,
                'numeroempleado' => '10017',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Daniela Silva',
                'email' => 'daniela.silva@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 6,
                'numeroempleado' => '10018',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Fernando Reyes',
                'email' => 'fernando.reyes@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 7,
                'numeroempleado' => '10019',
                'rol' => 'usuario',
            ],
            [
                'name' => 'Andrea Navarro',
                'email' => 'andrea.navarro@gmail.com',
                'password' => Hash::make('12345678'),
                'departamento_id' => 8,
                'numeroempleado' => '10020',
                'rol' => 'usuario',
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}