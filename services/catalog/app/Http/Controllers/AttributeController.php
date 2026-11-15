<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\SyncAttributesRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use App\Models\Category;
use App\Services\AttributeService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class AttributeController extends Controller
{
    public function __construct(private AttributeService $attributeService)
    {}

    public function index()
    {
        $attributes = $this->attributeService->getAll();

        return AttributeResource::collection($attributes);
    }

    public function store(StoreAttributeRequest $request): AttributeResource
    {
        $attribute = $this->attributeService->store($request->validated());

        return AttributeResource::make($attribute);
    }

    public function getForCategory(Category $category): AnonymousResourceCollection
    {
        $attributes = $this->attributeService->getForCategory($category);

        return AttributeResource::collection($attributes);
    }

    public function syncAttributes(Category $category, SyncAttributesRequest $request): Response
    {
        $this->attributeService->syncAttributes($category, $request->validated('attributes', []));

        return \response()->noContent();
    }

    public function update(Attribute $attribute, UpdateAttributeRequest $request): AttributeResource
    {
        $updatedAttribute = $this->attributeService->update($attribute, $request->validated());

        return AttributeResource::make($updatedAttribute->load('values'));
    }

    public function destroy(Attribute $attribute): Response
    {
        $attribute->delete();

        return \response()->noContent();
    }
}
