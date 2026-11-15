<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttributeValueRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Resources\AttributeValueResource;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Services\AttributeValueService;
use Illuminate\Http\Response;

class AttributeValueController extends Controller
{
    public function __construct(private AttributeValueService $attributeValueService)
    {}

    public function store(Attribute $attribute, StoreAttributeValueRequest $request): AttributeValueResource
    {
        $attributeValue = $this->attributeValueService->store($attribute, $request->validated('value'));

        return AttributeValueResource::make($attributeValue);
    }

    public function update(AttributeValue $attributeValue, UpdateAttributeRequest $request): AttributeValueResource
    {
        $updatedValue = $this->attributeValueService->update(
            $attributeValue,
            $request->validated());

        return AttributeValueResource::make($updatedValue);
    }

    public function destroy(AttributeValue $attributeValue): Response
    {
        $attributeValue->delete();

        return response()->noContent();
    }
}
