<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Oficina;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = [
        'nombre',
        'oficina_id',
        'usuario_departamento',
    ];

    public function users()
    {
        return $this->hasMany(
            User::class,
            'usuario_departamento',
            'id'
        );
    }

    public function oficina()
    {
        return $this->belongsTo(
            Oficina::class,
            'oficina_id',
            'id'
        );
    }
}