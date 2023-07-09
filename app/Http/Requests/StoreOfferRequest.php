<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfferRequest extends FormRequest
{           
    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): int
    {
        return $this->type;
    }    

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getStarting(): string
    {
        return $this->starting;
    }

    public function getEnding(): string
    {
        return $this->ending;
    }
    
    public function getActive(): ?bool
    {
        return $this->active;
    }
    
    public function rules()
    {
        return [            
            'name' => 'required|string',
            'type' => 'required|numeric',
            'amount' => 'nullable|sometimes|integer',
            'starting' => 'required',
            'ending' => 'required|after:starting',            
            'active' => 'sometimes|boolean'
        ];
    }
}
