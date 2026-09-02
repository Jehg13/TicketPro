<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceToken extends Model
{
    protected $table = 'device_tokens';

    protected $fillable = [
        'login',
        'token',
        'platform',
        'device_id',
        'app_version',
        'last_seen_at',
        'revoked_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'login', 'login');
    }
}
