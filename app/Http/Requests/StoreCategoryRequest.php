<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    
    // public function authorize()
    // {
    //     // return false;
    //     return true;
    // }

    
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string', 
                Rule::unique('categories')->ignore($this->category?->id)
            ],
            'description' => 'nullable|sometimes|string',
        ];
    }
}
