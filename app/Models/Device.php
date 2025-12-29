<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'device_type',
        'device_push_token',
        'access_token',
        'access_token_expires_at',
        'latest_ip_address',
        'apn_key_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
