<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class AttributeService
{
    public function getAll(): Collection
    {
        return Attribute::with('values')->get();
    }

    public function getForCategory(Category $category): Collection
    {
        return $category->attributes()->with('values')->get();
    }

    public function store(array $data): Attribute
    {
        $attribute = Attribute::create($data);

        return $attribute;
    }

    public function syncAttributes(Category $category, array $data): void
    {
        $category->attributes()->sync($data ?? []);
    }

    public function update(Attribute $attribute, array $data): Attribute
    {
        $attribute->update($data);

        return $attribute;
    }
}
