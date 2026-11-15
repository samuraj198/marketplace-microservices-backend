<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\CategoryAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CategoryAttribute>
 */
class CategoryAttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'attribute_id' => Attribute::factory()
        ];
    }
}
