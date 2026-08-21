<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComentario extends Model
{
    use HasFactory;

    protected $table = 'ticket_comentarios';

    protected $fillable = [
        'ticket_id',
        'login',
        'mensaje',
        'archivo',
    ];

    public function ticket()
    {
        return $this->belongsTo(
            TicketU::class,
            'ticket_id',
            'id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'login',
            'login'
        );
    }
}