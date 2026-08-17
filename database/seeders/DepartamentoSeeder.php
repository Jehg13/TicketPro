<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departamentos')->insert([
            [
                'nombre' => 'Recursos Humanos',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Contabilidad',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Ventas',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Marketing',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Compras',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Logistica',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Produccion',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Atencion al Cliente',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Calidad',
                'oficina_id' => 1,
            ],
            [
                'nombre' => 'Mantenimiento',
                'oficina_id' => 1,
            ],
        ]);
    }
}