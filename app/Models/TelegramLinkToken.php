<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramLinkToken extends Model
{
    protected $table = 'telegram_link_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}