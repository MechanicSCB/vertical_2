<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

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

    /**
     * MEILISEARCH
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        $array = [
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'vendor' => $this->vendor,
            'params' => json_decode($this->params ?? '[]', 1),
        ];

        //$params = json_decode($this->params ?? '[]', 1);
        //$array = [...$array, ...$params];

        return $array;
    }

}
