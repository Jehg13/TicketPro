<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TicketComentario;
use App\Models\Solucion;

class TicketU extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'user_id',
        'titulo',
        'tipo_falla',
        'prioridad',
        'descripcion',
        'afecta_otros',
        'es_recurrente',
        'comentarios',
        'evidencia',
        'estado',
         'tomado_por',
        'solucion_id',
    ];

    protected $casts = [
        'afecta_otros' => 'boolean',
        'es_recurrente' => 'boolean',
        'evidencia' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    public function historialComentarios()
    {
        return $this->hasMany(
            TicketComentario::class,
            'ticket_id',
            'id'
        )->orderBy('created_at', 'asc');
    }

    public function tomadoPor()
{
    return $this->belongsTo(User::class, 'tomado_por');
}

public function solucion()
{
    return $this->hasOne(Solucion::class, 'ticket_id', 'id');
}
}