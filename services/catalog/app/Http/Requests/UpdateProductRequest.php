<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\CheckPossibleAttributes;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product')->id ?? $this->route('product');
        $categoryId = $this->category_id ?? Product::find($productId)->category_id;

        return [
            "name" => "sometimes|required|string",
            "description" => "sometimes|required|string",
            "attributes" => ["sometimes", "nullable", "array", new CheckPossibleAttributes($categoryId)],
            "category_id" => "sometimes|required|integer|exists:categories,id",
            "quantity" => "sometimes|integer|min:0",
            "price" => "sometimes|required|numeric|min:0",
            "article" => ["sometimes", "required", "string", Rule::unique('products', 'article')
                ->ignore($productId)],
        ];
    }
}
