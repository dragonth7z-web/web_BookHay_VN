<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    protected $table = 'login_histories';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'ip_address', 'device', 'status', 'failure_reason',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
