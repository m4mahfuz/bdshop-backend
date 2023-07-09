<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreCouponRequest extends FormRequest
{    
    // public function getCouponOption(): string
    // {
    //     return $this->coupon_option;
    // }
    public function getActive(): bool
    {
        return $this->active;
    }
    
    public function getCode(): string
    {
        if (strtolower($this->option) === 'automatic') {
            return Str::random(8);
        }
        return $this->code;
    }

    public function getCategoryIds(): ?array
    {
        return $this->categories;
    }

    public function getUsage(): int
    {
        return $this->usage;
    }

    // public function getUsersIds(): array
    // {
    //     return $this->users;
    // }

    public function getAmountType(): int
    {
        return $this->amount_type;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getMinimumSpending(): ?int
    {
        return $this->minimum_spending;
    }

    public function getStartingDate(): ?string
    {
        return $this->starting;
    }

    public function getEndingDate(): string
    {
        return $this->ending;
    }

    public function rules()
    {
        return [
           'active' => 'required|boolean',
           'option' => 'sometimes|required|string',
           'code' => 'nullable|string', 
           // 'categories' => 'sometimes|required|array|exists:categories,id',
           'categories' => 'nullable|array|exists:categories,id',
           'usage' => 'required|numeric', 
           // 'users' => 'sometimes|required|array|exists:users,id',
           'amount_type' => 'required|numeric', 
           'amount' => 'required|numeric',
           'starting' => 'nullable|string',
           'ending' => 'required|string',
           'minimum_spending' => 'nullable|numeric',
           'limit' => 'nullable|numeric'
        ];
    }
}
