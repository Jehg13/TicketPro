<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Tabla asociada.
     */
    protected $table = 'users';

    /**
     * Laravel normalmente espera una PK llamada "id".
     * En este caso la PK es "login".
     */
    protected $primaryKey = 'login';

    /**
     * La PK es VARCHAR, no INT.
     */
    protected $keyType = 'string';

    /**
     * La PK no es autoincremental.
     */
    public $incrementing = false;
public $timestamps = false;
    /**
     * Campos que pueden ser asignados masivamente.
     */
    protected $fillable = [
        'login',
        'pswd',
        'name',
        'email',
        'active',
        'activation_code',
        'priv_admin',
        'mfa',
        'picture',
        'role',
        'phone',
        'pswd_last_updated',
        'mfa_last_updated',
    ];

    /**
     * Campos ocultos.
     */
    protected $hidden = [
        'pswd',
        'activation_code',
        'mfa',
    ];

    /**
     * Conversión de tipos.
     */
    protected $casts = [
        'pswd_last_updated' => 'datetime',
        'mfa_last_updated' => 'datetime',
    ];

    /**
     * Nombre de la columna que Laravel utilizará
     * para autenticar al usuario.
     */
    public function getAuthPassword()
    {
        return $this->pswd;
    }

    /**
     * Relación con Departamento.
     *
     * departamentos.usuario_departamento
     *          ↓
     * users.login
     */
    public function departamento()
    {
        return $this->hasOne(
            Departamento::class,
            'usuario_departamento',
            'login'
        );
    }

    /**
     * Solicitudes de cambio realizadas por el usuario.
     */
    public function solicitudesCambio()
    {
        return $this->hasMany(
            SolicitudCambio::class,
            'user_login',
            'login'
        );
    }

    /**
     * Solicitudes de cambio revisadas por el usuario.
     */
    public function solicitudesRevisadas()
    {
        return $this->hasMany(
            SolicitudCambio::class,
            'revisado_por',
            'login'
        );
    }

    /**
     * Notificaciones del usuario.
     */
    public function notificaciones()
    {
        return $this->hasMany(
            Notificacion::class,
            'login',
            'login'
        );
    }

    /**
     * Ruta para el nombre del usuario.
     */
    public function getNameAttribute($value)
    {
        return $value;
    }
}