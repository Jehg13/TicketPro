<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TicketComentario;
use App\Models\Solucion;
use App\Models\Oficina;
use App\Models\Departamento;

class TicketU extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio',
        'login',
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
        'fecha_tomado',
        'solucion_id',
        'equipo',
        'informacion_adicional',
    ];

    protected $casts = [
        'afecta_otros' => 'boolean',
        'es_recurrente' => 'boolean',
        'evidencia' => 'array',
        'fecha_tomado' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'login',
            'login'
        );
    }

    public function departamento()
    {
        return $this->belongsTo(
            Departamento::class,
            'login',
            'usuario_departamento'
        );
    }

    public function oficina()
    {
        return $this->hasOneThrough(
            Oficina::class,
            Departamento::class,
            'usuario_departamento',
            'id',
            'login',
            'oficina_id'
        );
    }

    public function historialComentarios()
    {
        return $this->hasMany(
            TicketComentario::class,
            'ticket_id',
            'id'
        )->orderBy(
            'created_at',
            'asc'
        );
    }

    public function tomadoPor()
    {
        return $this->belongsTo(
            User::class,
            'tomado_por',
            'login'
        );
    }

    public function solucion()
    {
        return $this->hasOne(
            Solucion::class,
            'ticket_id',
            'id'
        );
    }
}