<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminService 
{
	protected $request;
	protected $role;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

  public function isAuthorized($roles)
    {
      if (is_array($roles)) {
        return $this->hasAnyRole($roles) ;
      }

      return $this->hasRole($roles) ;
    }

	/**
    * @param string|array $roles
    */
    public function authorizedAdmin($roles)
    {
      if (is_array($roles)) {
        return $this->hasAnyRole($roles) || abort(401, 'This action is unauthorized.');
      }

      return $this->hasRole($roles) || abort(401, 'This action is unauthorized.');
    }

    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
      return null !== $this->request->user('admin')?->type()->whereIn('name', $roles)->first();      
    }

    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
      return null !== $this->request->user('admin')?->type()->where('name', $role)->first();
    }

    public function assign($role)
    {
        $this->role = $role;
        // $this->roles()->syncWithoutDetaching($role);
        // return $this->roles()->attach($role);
        return $this;
    }

    public function to(Authenticatable $user)
    {
    	return $user->roles()->syncWithoutDetaching($this->role);
    }
}