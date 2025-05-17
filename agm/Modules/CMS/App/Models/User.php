<?php

namespace Modules\CMS\App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'integer',
    ];

    const ACTIVE = 1;
    const INACTIVE = 0;

    public function getStatusTextAttribute()
    {
        return (int)$this->is_active === self::ACTIVE ? 'Hoạt động' : 'Không hoạt động';
    }

    public function getStatusColorAttribute()
    {
        return (int)$this->is_active === self::ACTIVE ? 'green' : 'red';
    }
}