<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Laravel\Scout\Searchable;

class Category extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['title', 'slug'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug ??= $category->title;
        });
    }

    // Relations
    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            set: function (string $value) {
                    $slug = $original = Str::slug($value, '-', 'ru');
                    $count = 2;

                    /** @noinspection PhpUndefinedMethodInspection */
                    while (static::where('slug', $slug)->exists()) {
                        $slug = "{$original}-" . $count++;
                    }

                    return $slug;
                }
        );
    }

    /**
     * MEILISEARCH
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
        ];
    }
}
