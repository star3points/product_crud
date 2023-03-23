<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = Category::factory(5)->create();
        $products = Product::factory(15)->create();
        $products->each(function ($product) use ($categories) {
            $product->categories()->attach(
                $categories->random(2, 5)
                    ->pluck('id')->toArray()
            );
        });
    }
}
