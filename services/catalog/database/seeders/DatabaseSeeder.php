<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategoriesSeeder::class,
            AttributesSeeder::class,
            AttributeValuesSeeder::class,
            CategoryAttributeSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
