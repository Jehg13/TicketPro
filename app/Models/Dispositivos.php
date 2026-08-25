<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispositivos extends Model
{
    protected $table = 'dispositivos';

    protected $fillable = [
        'login',
        'nombre_equipo',
        'id_equipo',
        'estado'
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'login', 'login');
    }
}