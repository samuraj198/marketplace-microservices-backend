<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(["name", "img"])]
// Категории для товаров
class Category extends Model
{
    use SoftDeletes, hasFactory;

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'category_attribute');
    }
}
