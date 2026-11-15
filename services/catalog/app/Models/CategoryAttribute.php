<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['category_id', 'attribute_id'])]
// Привязка атрибутов к категории (какие атрибуты можно использовать для определенной категории)
class CategoryAttribute extends Model
{
    protected $table = 'category_attribute';
}
