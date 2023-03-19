<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Node extends Model
{
    use HasFactory;

    public static string $separator = '.';

    protected $appends=['url'];

    // Relations
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Attributes
    protected function children(): Attribute
    {
        $path = @$this->path;

        $children = Node::query()
            ->with('category:id,slug,title')
            ->where('parent_path','=', $path)
            ->orderBy('order')
            ->get(['id','path','parent_path','category_id'])
        ;

        return Attribute::make(
            get: fn() => $children,
        );
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->category->title,
        );
    }

    protected function url(): Attribute
    {
        $slugs = Category::query()
            ->whereIn('id',$categoryIds = explode(Node::$separator, $this->path))
            ->pluck('slug', 'id')
            ->toArray()
        ;

        $url = '/';

        foreach ($categoryIds as $categoryId){
            $url .= $slugs[$categoryId] . '/';
        }

        return Attribute::make(
            get: fn() => $url,
        );
    }

    protected function image(): Attribute
    {
        $imagePath = "/storage/images/categories/{$this->category_id}.jpg";

        // TODO ref
        if (! Storage::exists(str_replace('/storage/', '/public/', $imagePath))) {
            $imagePath = "/storage/images/categories/1572.jpg";

            // TODO slow! must be removed!
            $product = Product::query()->where('category_id', $this->category_id)->first(['code']);

            if($product === null){
                $imagePath = $this->children->first()->image ?? "/storage/images/categories/1572.jpg";

                if (Storage::exists(str_replace('/storage/', '/public/', $imagePath))) {
                    if(! str_ends_with($imagePath, '/1572.jpg')){
                        copy(storage_path("app/public/images/".Str::afterLast($imagePath,'images/')), storage_path("app/public/images/categories/{$this->category_id}.jpg"));
                    }
                }
            }else{
                $imagePath = "/storage/images/products/s220/{$product['code']}.jpg";

                if (Storage::exists(str_replace('/storage/', '/public/', $imagePath))) {
                    copy(storage_path("app/public/images/products/s220/{$product['code']}.jpg"), storage_path("app/public/images/categories/{$this->category_id}.jpg"));
                }
            }
        }

        return Attribute::make(
            get: fn() => $imagePath,
        );
    }
}
