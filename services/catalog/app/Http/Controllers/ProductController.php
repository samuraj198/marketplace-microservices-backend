<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ImageService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public  function __construct(private ProductService $productService)
    {}

    public function index(): AnonymousResourceCollection
    {
        $products = $this->productService->getAll();
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $product = $this->productService->store($request->validated());

        return ProductResource::make($product->load([
            'category:id,name',
            'attributeValues.attribute:id,name,slug',
            'attributeValues.value:id,value',
        ]));
    }

    public function update(Product $product, UpdateProductRequest $request): ProductResource
    {
        $updatedProduct = $this->productService->update($product, $request->validated());

        return ProductResource::make($updatedProduct);
    }

    public function show(Product $product): ProductResource
    {
        return ProductResource::make($product->load([
            'category:id,name',
            'attributeValues.attribute:id,name,slug',
            'attributeValues.value:id,value',
        ]));
    }

    public function uploadImage(Product $product,
                                UploadImageRequest $request,
                                ImageService $imageService): JsonResponse
    {
        $path = $imageService->uploadImage($product, $request->validated('img'));

        $product->update(['img' => $path]);

        return response()->json(['image_path' => $path]);
    }

    public function destroy(Product $product): void
    {
        $product->delete();
    }
}
