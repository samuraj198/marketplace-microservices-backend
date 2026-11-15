<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
// Атрибуты товаров (характеристики) - только название
class Attribute extends Model
{
    use HasFactory;

    protected $casts = [
        'options' => 'array'
    ];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
