<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'type_id',
        'active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];    

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean'
    ];


    public function type()
    {
        return $this->belongsTo(Type::class);
    }

}
