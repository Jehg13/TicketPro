<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backups extends Model
{
    protected $table = 'backups';

    protected $fillable = [
        'login',
        'nombre',
        'archivo',
        'tipo',
        'frecuencia',
        'activo',
        'hora',
        'dia_semana',
        'dia_mes',
        'estado',
        'tamaño',
        'fecha_inicio',
        'fecha_finalizacion',
        'ultima_ejecucion',
        'proxima_ejecucion',
        'mensaje',
        'es_configuracion'
    ];

protected $casts = [
    'activo' => 'boolean',
    'fecha_inicio' => 'datetime',
    'fecha_finalizacion' => 'datetime',
    'ultima_ejecucion' => 'datetime',
    'proxima_ejecucion' => 'datetime',
];

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'login',
            'login'
        );
    }
}