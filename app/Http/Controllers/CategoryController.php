<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CategoryStore;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStore $request): JsonResource
    {
        $category = new Category($request->validated());
        $category->save();
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        $category = Category::where('id', $id)->first();
        !$category ?: $category->delete();
    }
}
