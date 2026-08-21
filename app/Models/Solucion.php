<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Solucion extends Model
{
    protected $table = 'soluciones';

    protected $fillable = [
        'ticket_id',
        'login',
        'solucionado_por',
        'problema_solucionado',
        'solucion',
        'firma',
        'fecha_solucion',
        'nombre_firmante',
        'fecha_firma',
        'evidencia',
    ];

    protected $casts = [
        'problema_solucionado' => 'boolean',
        'fecha_solucion' => 'datetime',
        'fecha_firma' => 'datetime',
        'evidencia' => 'array',
    ];

    public function solucionadoPor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'solucionado_por',
            'login'
        );
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            TicketU::class,
            'ticket_id'
        );
    }
}