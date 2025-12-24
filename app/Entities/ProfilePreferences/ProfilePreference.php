<?php

namespace App\Entities\ProfilePreferences;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProfilePreference extends Model
{
    const PREFERENCE_LIKE = 1;
    const PREFERENCE_UNLIKE = 0;

    protected $fillable = [
        'user_id',
        'preferred_user_id',
        'preference'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id')->whereNull('deleted_at');
    }

    public function preferredUser()
    {
        return $this->belongsTo(User::class, 'preferred_user_id')->whereNull('deleted_at');
    }

}
