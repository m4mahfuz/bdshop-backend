<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class QuantityCheck implements Rule
{
    
    protected $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }
    
    public function passes($attribute, $value)
    {
        $product = Product::find($this->productId);

        return $product->inventory->quantity >= $value ?: false;
    }

    public function message()
    {
        return 'Required quantity, :input is not available';
    }
}
