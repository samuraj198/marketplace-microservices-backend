<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['product_id', 'attribute_id', 'value_id'])]
// Связочная таблица - связывает товар, атрибут и значение атрибута
class ProductAttributeValue extends Model
{
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function value()
    {
        return $this->belongsTo(AttributeValue::class, 'value_id');
    }
}
