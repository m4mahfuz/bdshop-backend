<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscountRequest extends FormRequest
{           
    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }    

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
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
            'description' => 'required|string',
            'amount' => 'nullable|sometimes|integer',
            'limit' => 'nullable|sometimes|integer',
            'starting' => 'required',
            'ending' => 'required|after:starting',            
            'active' => 'sometimes|boolean'
        ];
    }
}
