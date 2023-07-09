<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{    
    public function getActive(): bool
    {
        return $this->active;
    }
    
    public function getName(): string
    {        
        return $this->name;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAddressLine(): string 
    {
        return $this->address_line;
    }

    public function getPhone(): int
    {
        return $this->phone;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getPostalCode(): int
    {
        return $this->postal_code;
    }

    public function getDefaultShippingAddress(): bool
    {
        return $this->default_shipping_address;
    }
    
    public function rules()
    {
        return [
           'active' => 'required|boolean',
           'name' =>'required|string',
           'title' =>'required|string',
           'address_line' =>'required|string',
           'phone' =>'required|numeric|digits_between:11,13
',
           'city' =>'required|string',
           'postal_code' =>'required|numeric',
           'default_shipping_address' => 'required|boolean'           
        ];
    }
}
