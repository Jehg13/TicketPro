<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    use HasFactory;

    public function departamento(){
        return $this->hasMany(Departamento::class);
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
