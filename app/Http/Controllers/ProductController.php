<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductIndex;
use App\Http\Requests\Product\ProductStore;
use App\Http\Requests\Product\ProductUpdate;
use App\Models\Category;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductIndex $request): ResourceCollection
    {
        return ProductResource::collection(
            Product::with('categories')->when(
                    $request->validated('name'),
                    function (Builder $q) use ($request) {
                        $q->where(
                            'products.name',
                            'LIKE',
                            '%'.$request->validated('name').'%'
                        );
                    }
                )->when(
                    $request->validated('category_id'),
                    function (Builder $q) use ($request) {
                        $q->whereRelation(
                            'categories', 'categories.id',
                            $request->validated('category_id')
                        );
                    }
                )->when(
                    $request->validated('category_name'),
                    function (Builder $q) use ($request) {
                        $q->whereRelation(
                            'categories', 'categories.name',
                            'LIKE',
                            '%'.$request->validated('category_name').'%'
                        );
                    }
                )->when(
                    $request->validated('prices'),
                    function ($q) use ($request) {
                        $q->whereBetween(
                            'products.price',
                            explode(',', $request->validated('prices'))
                        );
                    }
                )->when(
                    ($request->validated('is_published') !== null),
                    function (Builder $q) use ($request) {
                        $q->where('products.published', $request->validated('is_published'));
                    }
                )->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStore $request): JsonResource
    {
        $product = new Product([
            'name' => $request->validated('name'),
            'price' => $request->validated('price'),
            'published' => $request->validated('published'),
        ]);
        DB::transaction(function () use ($product, $request) {
            $product->save();
            $product->categories()->attach($request->validated('categories'));
        });
        return new ProductResource($product->load('categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdate $request, string $id)
    {
        $validated = collect($request->validated());
        $categories = $validated->pull('categories');
        $product = Product::query()->where('id', $id)->first();
        DB::transaction(function () use ($product, $validated, $categories) {
            $product->categories()->detach();
            $product->update($validated->toArray());
            $product->categories()->attach($categories);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        $product = Product::where('id', $id)->first();
        !$product ?: $product->delete();
    }
}
