<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Смартфоны'],
            ['name' => 'Ноутбуки'],
            ['name' => 'Футболки'],
            ['name' => 'Кроссовки'],
            ['name' => 'Наушники'],
        ];

        foreach ($data as $item) {
            Category::firstOrCreate($item);
        }
    }
}
