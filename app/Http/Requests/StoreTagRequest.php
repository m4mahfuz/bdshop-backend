<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreTagRequest extends FormRequest
{      
    
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
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug)
        ]);
    }
}
