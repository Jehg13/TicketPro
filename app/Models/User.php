<?php
namespace App\Models;
use App\Models\Departamento;
use App\Models\Notificacion;
use App\Models\SolicitudCambio;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens,HasFactory,Notifiable;
    protected $table='users';
    protected $primaryKey='login';
    protected $keyType='string';
    public $incrementing=false;
    public $timestamps=false;
    protected $fillable=[
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
    protected $hidden=[
        'pswd',
        'activation_code',
        'mfa',
    ];
    protected $casts=[
        'pswd_last_updated'=>'datetime',
        'mfa_last_updated'=>'datetime',
    ];
    public function getAuthPassword()
    {
        return $this->pswd;
    }
    public function sendPasswordResetNotification($token)
    {
        $url=url('/reset-password/'.$token.'?email='.urlencode($this->email));
        $this->notify(new ResetPasswordNotification($url,$this));
    }
    public function departamento()
    {
        return $this->hasOne(Departamento::class,'usuario_departamento','login');
    }
    public function solicitudesCambio()
    {
        return $this->hasMany(SolicitudCambio::class,'user_login','login');
    }
    public function solicitudesRevisadas()
    {
        return $this->hasMany(SolicitudCambio::class,'revisado_por','login');
    }
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class,'login','login');
    }
    public function getNameAttribute($value)
    {
        return $value;
    }
}