<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Цвет', 'slug' => 'color'],
            ['name' => 'Размер одежды', 'slug' => 'clothing_size'],
            ['name' => 'Размер обуви', 'slug' => 'shoe_size'],
            ['name' => 'Бренд', 'slug' => 'brand'],
            ['name' => 'Материал', 'slug' => 'material'],
            ['name' => 'Страна производства', 'slug' => 'country'],
            ['name' => 'Вес (г)', 'slug' => 'weight'],
            ['name' => 'Гарантия (мес.)', 'slug' => 'warranty'],
            ['name' => 'Пол', 'slug' => 'gender'],
            ['name' => 'Сезон', 'slug' => 'season'],
            ['name' => 'Диагональ экрана', 'slug' => 'screen_size'],
            ['name' => 'Разрешение экрана', 'slug' => 'screen_resolution'],
            ['name' => 'Тип матрицы', 'slug' => 'matrix_type'],
            ['name' => 'Количество ядер процессора', 'slug' => 'cpu_cores'],
            ['name' => 'Оперативная память (ГБ)', 'slug' => 'ram'],
            ['name' => 'Встроенная память (ГБ)', 'slug' => 'storage'],
            ['name' => 'Ёмкость аккумулятора (мАч)', 'slug' => 'battery_capacity'],
            ['name' => 'Поддержка NFC', 'slug' => 'has_nfc'],
            ['name' => 'Беспроводная зарядка', 'slug' => 'wireless_charging'],
            ['name' => 'Степень влагозащиты', 'slug' => 'water_resistance'],
        ];

        foreach ($data as $item) {
            Attribute::firstOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
