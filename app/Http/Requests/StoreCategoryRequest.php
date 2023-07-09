<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    
    // public function authorize()
    // {
    //     // return false;
    //     return true;
    // }

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

    public function getParentId(): ?int
    {
        return $this->parent_id;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
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
                Rule::unique('categories', 'name')->ignore($this->category?->id)
            ],
            'slug' => [
                'required',
                'string', 
                Rule::unique('categories', 'slug')->ignore($this->category?->id)
            ],
            'description' => 'nullable|sometimes|string',
            'parent_id' => 'nullable|sometimes|int',
            'active' => 'required|boolean',
            'icon' => 'nullable|sometimes|string',
            'image' => 'sometimes|string',
            'meta.*' => 'nullable',
            'meta.title' => 'sometimes|string',
            'meta.description' => 'sometimes|string',
            'meta.keywords' => 'sometimes|array',
            // 'meta.description' => 'required|sometimes|string',
            // 'meta.keywords' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug)
        ]);
    }
}
