<?php

namespace App\Http\Requests;

use App\Rules\CheckPossibleAttributes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string",
            "description" => "required|string",
            "attributes" => ["nullable", "array", new CheckPossibleAttributes($this->category_id)],
            "category_id" => "required|integer|exists:categories,id",
            "quantity" => "sometimes|integer|min:0",
            "price" => "required|numeric|min:0",
            "article" => "required|string|unique:products,article",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Имя товара обязательно для заполнения",
            "description.required" => "Описание товара обязательно для заполнения",
            "attributes.array" => "Характеристики товара должны быть в формате array",
            "category_id.required" => "Идентификатор категории обязателен для заполнения",
            "category_id.exists" => "Категории с таким идентификатором не существует",
            "price.required" => "Цена товара обязательна для заполнения",
            "price.numeric" => "Введенное значение для цены не является числом",
            "price.min" => "Цена товара не может быть отрицательной",
            "article.required" => "Артикул товара обязателен для заполнения",
            "article.unique" => "Артикул товара должен быть уникальным"
        ];
    }
}
