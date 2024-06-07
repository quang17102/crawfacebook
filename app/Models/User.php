<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'delay',
        'limit',
        'limit_follow',
        'expire',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'date:d-m-Y',
    ];

    public function link()
    {
        return $this->hasOne(Link::class, 'user_id', 'id');
    }

    public function userRoles()
    {
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }

    public function links()
    {
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }

    protected function getTimeToExpireAttribute()
    {
        return round((strtotime($this->expire) - strtotime(now())) / (3600 * 24), 0);
    }
}
