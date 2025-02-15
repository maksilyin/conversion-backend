<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileFormatDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'extended_name' => $this->extended_name,
            'extension' => $this->extension,
            'icon' => $this->icon,
            'icon_image' => $this->icon_image,
            'excerpt' => $this->getTranslate('excerpt'),
            'description' => $this->getTranslate('description'),
            'color' => $this->color,
            'mime_type' => $this->mime_type,
            'type' => $this->category ? [
                'id' => $this->category->id,
                'name' => $this->category->getTranslate('name'),
                'slug' => $this->category->slug,
            ] : null,
            'convertible' => $this->convertible->map(function ($convertible) {
                return [
                    'id' => $convertible->id,
                    'name' => $convertible->name,
                    'extension' => $convertible->extension,
                ];
            }),
            'convertible_types' => $this->convertibleCategory->map(function ($category) {
                return [
                    'id' => $category->id,
                    'slug' => $category->slug,
                ];
            }),
            'seo' => property_exists($this, 'seo') ? $this->metaTags : null,
        ];
    }
}
