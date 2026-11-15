<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();

        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'category_id' => $category->id,
            'quantity' => fake()->numberBetween(1, 20),
            'price' => fake()->randomFloat(2, 100, 100000),
            'article' => fake()->unique()->bothify("ART-######")
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {
            $attributes = $product->category->attributes()->with('values')->get();

            foreach ($attributes as $attribute) {
                $randomValue = $attribute->values->random();

                ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute->id,
                    'value_id' => $randomValue->id,
                ]);
            }
        });
    }
}
