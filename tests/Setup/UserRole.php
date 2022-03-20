<?php

namespace Tests\Setup;

use App\Models\User;
use App\Models\Role;


class UserRole 
{
	protected $user;
	protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }	

    public static function setAs($role)
    {
        return new UserRole($role);
    }

    public function create()
    {        
        $user = User::factory()->create();
        
        $role = Role::factory()->create(['name' => $this->role ?? 'super_admin']);

        $user->roles()->attach($role);
        
        return $user;
    }
}