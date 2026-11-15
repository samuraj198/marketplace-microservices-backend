<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {}

    public function index(): AnonymousResourceCollection
    {
        $categories = $this->categoryService->getAll();

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = $this->categoryService->store($request->validated());

        return CategoryResource::make($category);
    }

    public function show(Category $category): CategoryResource
    {
        return CategoryResource::make($category);
    }

    public function update(Category $category, UpdateCategoryRequest $request): CategoryResource
    {
        $updatedCategory = $this->categoryService->update($category, $request->validated());

        return CategoryResource::make($updatedCategory);
    }

    public function destroy(Category $category): void
    {
        $category->delete();
    }
}
