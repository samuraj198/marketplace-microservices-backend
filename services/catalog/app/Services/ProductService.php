<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getAll(): Collection
    {
        return Product::with([
            "category:id,name",
            "attributeValues.attribute:id,name,slug",
            "attributeValues.value:id,value",
        ])->get();
    }

    public function store(array $data): Product
    {
        $product = DB::transaction(function () use ($data) {
            $product = Product::create($data);

            if (!empty($data['attributes'])) {
                $product->attributeValues()->createMany(
                    array_map(fn($attr) => [
                            'attribute_id' => $attr['attribute_id'],
                            'value_id' => $attr['value_id']
                    ], $data['attributes'])
                );
            }

            return $product;
        });

        return $product->load([
            "category:id,name",
            "attributeValues.attribute:id,name,slug",
            "attributeValues.value:id,value",
        ]);
    }

    public function update(Product $product, array $data): Product
    {
        DB::transaction(function () use ($product, $data) {
            $product->update($data);

            if (array_key_exists('attributes', $data)) {
                $product->attributeValues()->delete();

                if (!empty($data['attributes'])) {
                    $product->attributeValues()->createMany(
                        array_map(fn($attr) => [
                            'attribute_id' => $attr['attribute_id'],
                            'value_id' => $attr['value_id']
                        ], $data['attributes'])
                    );
                }
            }
        });

        return $product->load([
            "category:id,name",
            "attributeValues.attribute:id,name,slug",
            "attributeValues.value:id,value",
        ]);
    }
}
