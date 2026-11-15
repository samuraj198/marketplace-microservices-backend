<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeValueService
{
    public function store(Attribute $attribute, string $value): AttributeValue
    {
        return $attribute->values()->create([
            'value' => $value
        ]);
    }

    public function update(AttributeValue $attributeValue, array $data): AttributeValue
    {
        $attributeValue->update($data);

        return $attributeValue;
    }
}
