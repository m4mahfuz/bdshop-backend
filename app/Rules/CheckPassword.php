<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CheckPassword implements Rule
{
    private $guard;

    public function __construct($guard)
    {
        $this->guard = $guard;
    }

    
    public function passes($attribute, $value)
    {
         // return Hash::check($value, Auth::guard('admin')->user()->password);
         return Hash::check($value, Auth::guard($this->guard)->user()->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your current password does not match the password you provided.';
    }
}
