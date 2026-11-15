<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttributeValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $valuesBySlug = [
            'color' => ['Чёрный', 'Белый', 'Красный', 'Синий', 'Зелёный', 'Серый'],
            'clothing_size' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
            'shoe_size' => ['38', '39', '40', '41', '42', '43', '44'],
            'brand' => ['Apple', 'Samsung', 'Xiaomi', 'Nike', 'Adidas', 'Zara'],
            'material' => ['Хлопок', 'Полиэстер', 'Кожа', 'Металл', 'Пластик', 'Стекло'],
            'country' => ['Китай', 'Россия', 'Вьетнам', 'Турция', 'США', 'Германия'],
            'weight' => ['150', '200', '350', '500', '1000', '1500'],
            'warranty' => ['6', '12', '24', '36'],
            'gender' => ['Мужской', 'Женский', 'Унисекс', 'Детский'],
            'season' => ['Лето', 'Зима', 'Демисезон', 'Всесезонный'],
            'screen_size' => ['6.1"', '6.5"', '6.7"', '13.3"', '15.6"', '16.0"'],
            'screen_resolution' => ['1920x1080 (Full HD)', '2560x1440 (2K)', '3840x2160 (4K)'],
            'matrix_type' => ['IPS', 'OLED', 'AMOLED', 'VA'],
            'cpu_cores' => ['4', '6', '8', '10', '12'],
            'ram' => ['4', '8', '16', '32', '64'],
            'storage' => ['64', '128', '256', '512', '1024'],
            'battery_capacity' => ['3000', '4000', '4500', '5000', '6000'],
            'has_nfc' => ['Да', 'Нет'],
            'wireless_charging' => ['Да', 'Нет'],
            'water_resistance' => ['Нет', 'IP54', 'IP67', 'IP68'],
        ];

        foreach ($valuesBySlug as $slug => $values) {
            $attribute = Attribute::where('slug', $slug)->first();

            if (! $attribute) {
                continue;
            }

            foreach ($values as $value) {
                AttributeValue::firstOrCreate([
                    'attribute_id' => $attribute->id,
                    'value' => $value,
                ]);
            }
        }
    }
}
