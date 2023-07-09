<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferProductRequest extends FormRequest
{           
    public function getOfferId(): int
    {
        return $this->offer;
    }    
    
    public function getProducts(): array
    {
        return $this->products;
    }
    
    public function rules()
    {
        return [            
            'offer' => 'required|integer',
            'products' => 'required|array'
        ];
    }
}
