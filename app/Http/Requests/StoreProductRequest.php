<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{    
    public function getActive(): bool
    {
        return $this->active;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    public function getSlug(): string
    {
        return Str::slug($this->name);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getUnitQuantity(): int
    {
        return $this->unit_quantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSku(): ?string
    {
        if (is_null($this->sku)) {
            return Str::upper(Str::random(12));
        }
        return Str::upper($this->sku);
    }

    public function getCategoryId(): int
    {
        return $this->category;
    }

    public function getTags(): array
    {
        return $this->tags;
        // return Arr::pluck($this->tags, 'id');
    }

    public function getDiscountId(): ?int
    {
        return $this->discount_id;
    }

    public function getImageName() 
    {
        return $this->image;
    }
    
    public function getMetaTitle(): ?string
    {
        return $this->meta['title'];
    }

    public function getMetaDescription(): ?string
    {
        return $this->meta['description'];
    }

    public function getMetaKeywords(): ?array
    {
        return $this->meta['keywords'];
    }



    public function rules()
    {
        return [
            'name' => [
                'required',
                'string', 
                Rule::unique('products', 'name')->ignore($this->product?->id)
            ],
            'slug' => [
                'required',
                'string', 
                Rule::unique('products', 'slug')->ignore($this->product?->id)
            ],
            'description' => 'nullable|sometimes|string',
            'price' => 'required|numeric',
            'unit' => 'required|string',
            'unit_quantity' => 'required|numeric',
            'quantity' => 'required|numeric',
            'sku' => 'nullable|sometimes|string|max:12',
            // 'category_ids' =>'required|array|exists:categories,id',
            // 'categories' => 'required|array|exists:categories,id',
            'category' => 'required|numeric|exists:categories,id',
            'tags' =>'nullable|sometimes|array|exists:tags,id',
            'discount_id' => 'nullable|sometimes|exists:discounts,id',
             // 'image' => 'required|image|dimensions:min_width=500,min_height=300',
             // 'image' => ['file', 'max:5000', 'mimes:jpg,png'],
           // 'directory' => 'nullable',
            'image' => 'nullable|sometimes|string',
            // 'meta.*' => 'nullable',
            'meta.title' => 'required|string',
            'meta.description' => 'required|string',
            'meta.keywords' => 'required|array',
            'active' => 'required|boolean'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
    }
}
