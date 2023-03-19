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

    public function getImages(): array
    {
        $additionalImagesPath = storage_path("app/public/images/products/cropped/add/{$this['code']}");
        $additionalImages = [];

        if(file_exists($additionalImagesPath)){
            $additionalImages = array_filter(scandir($additionalImagesPath),
                fn($v) => str_starts_with($v, $this['code']));
            $additionalImages = array_map(fn($v) => "/storage/images/products/cropped/add/{$this['code']}/$v",$additionalImages);
        }

        return ["/storage/images/products/cropped/{$this['code']}.jpg", ...$additionalImages];
    }

    public function getPreviews(): array
    {
        $additionalPreviewsPath = storage_path("app/public/images/products/s220/add/{$this['code']}");
        $additionalPreviews = [];

        if(file_exists($additionalPreviewsPath)) {
            $additionalPreviews = array_filter(scandir($additionalPreviewsPath),
                fn($v) => str_starts_with($v, $this['code']));
            $additionalPreviews = array_map(fn($v) => "/storage/images/products/s220/add/{$this['code']}/$v", $additionalPreviews);
        }
        return ["/storage/images/products/s220/{$this['code']}.jpg", ...$additionalPreviews];
    }

    /**
     * MEILISEARCH
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        $array = [
            'code' => $this->code,
            'name' => $this->name,
            'slug' => $this->slug,
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
