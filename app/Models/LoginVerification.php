<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginVerification extends Model
{
    protected $fillable = ['user_id', 'token', 'ip_address', 'user_agent', 'is_confirmed', 'expires_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
}
