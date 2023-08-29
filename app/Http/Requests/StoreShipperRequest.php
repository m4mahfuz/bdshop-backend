<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreShipperRequest extends FormRequest
{    
    public function getActive(): bool
    {
        return $this->active;
    }
    
    public function getName(): string
    {        
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getUrl(): string 
    {
        return $this->url;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    
    public function rules()
    {
        return [
           'active' => 'required|boolean',
           'name' =>'required|string',
           'address' =>'required|string',
           'url' =>'nullable|string',
           'phone' =>'required|numeric|digits_between:11,13',               
        ];
    }
}
