<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{      
    public function getReadStatus(): bool
    {
        return $this->read;
    }
    
    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function getMessage(): string
    {
        return $this->message;
    }    

    public function rules()
    {
        return [            
            'name' => [
                'required',
                'string',                
            ],
            'email' => [
                'required',
                'string',                
            ],
            'message' => [
                'required',
                'string',
            ],
            'read' => 'sometimes|required|boolean'
        ];
    }
}
