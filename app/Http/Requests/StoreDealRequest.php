<?php

namespace App\Http\Requests;

use App\Rules\UniqueDealCheck;
// use Carbon\Carbon;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDealRequest extends FormRequest
{   
    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function getProductPrice(): ?int
    {
        return $this->price;
    }   

    public function getActive(): bool
    {
        return $this->active;
    }   

    public function getAmountType(): int
    {
        return $this->amount_type;
    }


    public function getAmount(): int
    {
        return $this->amount;
    }   

    public function getDealType(): string
    {
        // session(['dealType' => $this->deal_type]);
        return $this->deal_type;
    }   

    public function getStarting()
    {
        return $this->starting;
    }   

    public function getEnding()
    {
        return $this->ending;
    }   

    
    public function rules()
    {        
        return [            
            // 'product_id' => 'required|unique:daily_deals,product_id|exists:products,id',
            'product_id' => [
                'sometimes',
                'required', 
                'exists:products,id',
                new UniqueDealCheck([
                    'deal_type' => $this->deal_type,
                    'product_id' => $this->product_id,
                    'starting' => $this->starting,
                    'ending' => $this->ending,
                ])
            ],
            'price' => 'sometimes|required|numeric',
            'active' => 'required|boolean',
            'amount_type' => 'required|numeric',
            'amount' => 'required|integer',
            'deal_type' => 'required|string',
            'starting' => 'sometimes|required|date',
            'ending' => 'sometimes|required|date|after_or_equal:starting',

        ];
    }

    protected function prepareForValidation()
    {
        $ending = new Carbon($this->ending);

        $this->merge([
            'starting' =>new Carbon($this->starting),
            'ending' => $ending->setTime(23, 59, 0),
        ]);
    }    
}
