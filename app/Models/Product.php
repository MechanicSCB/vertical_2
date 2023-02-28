<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getDefaultNodeUrl(): string
    {
        return Node::query()->where('category_id', $this->category->id)->first()->url;
    }
}
