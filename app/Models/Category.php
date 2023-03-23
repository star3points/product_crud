<?php

namespace App\Models;

use App\Exceptions\RemovedCategoryHasProductsException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($item) {
            if ($item->products->count() !== 0) {
                throw new RemovedCategoryHasProductsException('RemovedCategoryHasProducts');
            }
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
