<?php

namespace App\Http\Resources;

use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "category" => $this->whenLoaded('category', function () {
                return [
                    "id" => $this->category->id,
                    "name" => $this->category->name
                ];
            }),
            "attributes" => $this->whenLoaded('attributeValues', function () {
                return $this->attributeValues->map(fn ($item) => [
                    'id' => $item->attribute->id,
                    'name' => $item->attribute->name,
                    'slug' => $item->attribute->slug,
                    'value' => $item->value->value
                ]);
            }),
            "quantity" => $this->quantity,
            "img" => $this->img,
            "price" => $this->price,
            "article" => $this->article,
            "created_at" => $this->created_at
        ];
    }
}
