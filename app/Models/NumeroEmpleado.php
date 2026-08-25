<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumeroEmpleado extends Model
{
    protected $table = 'numeros_empleado';

    protected $fillable = [
        'login',
        'numero_empleado',
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