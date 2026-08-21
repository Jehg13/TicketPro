<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCambio extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_cambio';

    protected $fillable = [
        'folio',
        'user_id',
        'campo',
        'valor_actual',
        'nuevo_valor',
        'motivo',
        'estado',
        'comentario_admin',
        'revisado_por',
        'revisado_at',
    ];

    protected $casts = [
        'revisado_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'login');
    }

    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }
}