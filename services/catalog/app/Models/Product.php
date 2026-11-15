<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(["name", "description", "category_id", "quantity", "price", "article"])]
// Продукты(товары)
class Product extends Model
{
    use hasFactory, SoftDeletes;

    protected $casts = [
        "attributes" => "array",
        "price" => "decimal:2"
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }
}
