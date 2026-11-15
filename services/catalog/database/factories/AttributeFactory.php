<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use function Laravel\Prompts\select;

/**
 * @extends Factory<Attribute>
 */
class AttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data_type = fake()->randomElement(["string", "integer", "select", "float", "boolean"]);

        return [
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'data_type' => $data_type,
            'options' => $data_type == 'select' ? [
                'color' => ["red", "green", "blue", "yellow"],
                'size' => ["xs", "s", "m", "l", "xl", "xxl"],
            ] : null,
        ];
    }
}
