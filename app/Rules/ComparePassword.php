<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ComparePassword implements Rule
{    
    protected $currentPassword;

    public function __construct($currentPassword)
    {
        $this->currentPassword = $currentPassword;
    }
 
    public function passes($attribute, $value)
    {
        return strcmp($this->currentPassword, $value) === 0 ? false : true;
    }
    
    public function message()
    {
        return 'New password cann\'t be same as current password. Please choose a different password.';
    }
}
