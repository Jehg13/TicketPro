<?php

namespace App\Models;

use App\Services\PushNotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'login',
        'tipo',
        'titulo',
        'mensaje',
        'icono',
        'color',
        'url',
        'leida',
        'referencia_id',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    protected static function booted(): void
    {
        /*
         * All ticket, comment, solution, change and aviso controllers persist
         * through this model. Keeping dispatch here prevents duplicate sends
         * when a notification is created by more than one client.
         */
        static::created(function (self $notificacion): void {
            try {
                app(PushNotificationService::class)->send($notificacion);
            } catch (\Throwable $exception) {
                report($exception);
            }
        });
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'login',
            'login'
        );
    }
}