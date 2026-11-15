<?php

namespace App\Rules;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\CategoryAttribute;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckPossibleAttributes implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function __construct(private int $category_id)
    {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $categoryAttributeSlugs = Attribute::whereHas('categories', function ($query) {
            $query->where('categories.id', $this->category_id);
        })->pluck('slug')->toArray();

        $pickedAttributes = array_keys($value);

        $invalidIDs = array_diff($pickedAttributes, $categoryAttributeSlugs);

        if (!empty($invalidIDs)) {
            $fail("Вы выбрали неверные атрибуты для этой категории товара");
        }
    }
}
