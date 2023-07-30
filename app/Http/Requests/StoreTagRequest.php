<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
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
        return $this->slug;
    }

    public function getCategoryId(): int
    {
        return $this->category;
    }

    public function rules()
    {
        return [            
            'name' => [
                'required',
                'string',
                Rule::unique('tags', 'name')->ignore($this->tag?->id)
            ],
            'slug' => [
                'required',
                'string',
                Rule::unique('tags', 'slug')->ignore($this->tag?->id)
            ],
            'category' => 'required|numeric|exists:categories,id',
            'active' => 'required|boolean'        
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug)
        ]);
    }
}
