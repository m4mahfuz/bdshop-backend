<?php

namespace App\Http\Requests;

use App\Rules\QuantityCheck;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCartRequest extends FormRequest
{           
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }    

    // public function getSessionId(): string
    // {
    //     return $this->session_id;
    // }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }
    
    public function getAction(): ?string
    {
        return $this->action;
    }
    
    public function rules()
    {
        return [            
            // 'session_id' => 'required|exists:sessions,id',
            //'session_id' => 'null|sometimes|required|string',
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'action' => 'sometimes|required|string',
            'price' => 'required|exists:products,price',
            'quantity' => ['required', 'numeric', new QuantityCheck($this->product_id)],
        ];
    }
}
