<?php

namespace Database\Seeders;

use App\Models\CategoryAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['category_id' => 1, 'attribute_id' => 1],
            ['category_id' => 1, 'attribute_id' => 4],
            ['category_id' => 1, 'attribute_id' => 11],
            ['category_id' => 1, 'attribute_id' => 13],
            ['category_id' => 1, 'attribute_id' => 14],
            ['category_id' => 1, 'attribute_id' => 15],
            ['category_id' => 1, 'attribute_id' => 16],
            ['category_id' => 1, 'attribute_id' => 18],

            // Категория 2 (Ноутбуки)
            ['category_id' => 2, 'attribute_id' => 1],
            ['category_id' => 2, 'attribute_id' => 4],
            ['category_id' => 2, 'attribute_id' => 5],
            ['category_id' => 2, 'attribute_id' => 11],
            ['category_id' => 2, 'attribute_id' => 15],
            ['category_id' => 2, 'attribute_id' => 16],

            // Категория 3 (Футболки)
            ['category_id' => 3, 'attribute_id' => 1],
            ['category_id' => 3, 'attribute_id' => 2],
            ['category_id' => 3, 'attribute_id' => 4],
            ['category_id' => 3, 'attribute_id' => 5],
            ['category_id' => 3, 'attribute_id' => 9],
            ['category_id' => 3, 'attribute_id' => 10],
        ];

        foreach ($data as $item) {
            CategoryAttribute::firstOrCreate($item);
        }
    }
}
