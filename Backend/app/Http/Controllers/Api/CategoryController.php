<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Support\Str;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = $request->user()
            ->categories()
            ->with('transactions')
            ->get();
            return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $request->user()->categories()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'color_code' => $request->color_code,
            'icon' => $request->icon,
            'is_active' => true,
        ]);
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);
        return new CategoryResource($category);
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type,
            'color_code' => $request->color_code,
            'icon' => $request->icon,
        ]);

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return response()->noContent();
    }
}
