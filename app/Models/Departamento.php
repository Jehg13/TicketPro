<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = [
        'nombre',
        'oficina_id',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'departamento_id');
    }

    public function oficina()
    {
        return $this->belongsTo(Oficina::class, 'oficina_id');
    }
}