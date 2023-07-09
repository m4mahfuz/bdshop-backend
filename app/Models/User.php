<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
 
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class); 
    }

    public function addresses()
    {
        return $this->hasMany(Address::class); 
    }

    public function defaultShippingAddress()
    {
        return $this->hasOne(DefaultShippingAddress::class); 
    }

    // public function sessions()
    // {
    //     return $this->hasMany(Session::class);
    // }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }    

}
