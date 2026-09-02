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
        $mode = strtolower(env('PASSWORD_RESET_URL_MODE', 'web'));
        $email = urlencode($this->email);

        if ($mode === 'mobile') {
            $url = 'ticketpro://reset-password?token=' . urlencode($token) . '&email=' . $email;
        } else {
            $url = route('password.reset', [
                'token' => $token,
                'email' => $this->email,
            ], false);
        }

        $this->notify(new ResetPasswordNotification($token, $url));
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

    public function numero_empleado()
    {
        return $this->hasOne(
            NumeroEmpleado::class,
            'login',
            'login'
        );
    }
}