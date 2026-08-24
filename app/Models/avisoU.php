<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvisoU extends Model
{
    protected $table = 'avisos';

    protected $fillable = [
        'titulo',
        'tipo',
        'importancia',
        'fecha_inicio',
        'fecha_fin',
        'aplica_a',
        'descripcion',
        'afecta_a',
        'mostrar_notificaciones',
        'fijado',
        'archivo',
        'publicado_por',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'afecta_a' => 'array',
        'mostrar_notificaciones' => 'boolean',
        'fijado' => 'boolean',
    ];
}