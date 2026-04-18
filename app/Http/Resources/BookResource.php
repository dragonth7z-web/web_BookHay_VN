<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Calculate discount percentage
        $originalPrice = (int) ($this->original_price ?? 0);
        $salePrice = (int) ($this->sale_price ?? 0);
        $discountPercent = $originalPrice > $salePrice
            ? round((($originalPrice - $salePrice) / (int) ($originalPrice ?: 1)) * 100)
            : 0;

        // Determine cover image URL
        $coverImage = $this->cover_image;
        if ($coverImage && !str_starts_with($coverImage, 'http')) {
            $coverImage = asset('storage/' . $coverImage);
        } else {
            $coverImage = $coverImage ?? asset('images/placeholder-book.png');
        }

        return [
            'index' => $this->index, // Managed by collection mapping if needed
            'rank' => $this->rank,   // Managed by collection mapping if needed
            'id' => $this->id,
            'title' => $this->title ?? '',
            'slug' => $this->slug ?? '',
            'cover_image' => $coverImage,
            'authors' => $this->authors ? $this->authors->pluck('name')->toArray() : [],
            'publisher' => $this->publisher?->name ?? '',
            'category' => $this->category_name_override ?? ($this->category?->name ?? ''),
            'sale_price' => $salePrice,
            'original_price' => $originalPrice,
            'discount_percent' => (int) $discountPercent,
            'sold_count' => (int) ($this->period_sold ?? $this->sold_count ?? 0),
            'stock' => (int) ($this->stock ?? 0),
            'short_description' => $this->short_description ?? '',
            'description' => $this->description ?? '',
            'rating_avg' => (float) ($this->rating_avg ?? 0),
            'rating_count' => (int) ($this->rating_count ?? 0),
            'isbn' => $this->isbn ?? '',
            'pages' => (int) ($this->pages ?? 0),
            'weight' => $this->weight ? $this->weight . 'g' : '',
            'language' => $this->language ?? 'Tiếng Việt',
            'published_year' => (int) ($this->published_year ?? 0),
            'cover_type' => isset($this->cover_type->value) ? $this->cover_type->value : ($this->cover_type ?? ''),
            'dimensions' => $this->dimensions ?? '',
            'translator' => $this->translator ?? null,
        ];
    }
}
