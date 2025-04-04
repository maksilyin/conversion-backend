<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileCategoryResource extends JsonResource
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
            'name' => $this->getTranslate('name'),
            'slug' => $this->slug,
            'icon' => $this->icon,
            'icon_image' => $this->icon_image,
            'excerpt' => $this->getTranslate('excerpt'),
            'description' => $this->getTranslate('description'),
            'formats' => $this->formats->map(function ($format) {
                return [
                    'id' => $format->id,
                    'name' => $format->name,
                    'extended_name' => $format->extended_name,
                    'extension' => $format->extension,
                    'icon' => $format->icon,
                    'icon_image' => $format->icon_image,
                    'color' => $format->color,
                    'mime_type' => $format->mime_type,
                    'convertible' => $format->convertible->map(function ($convertible) {
                        return [
                            'id' => $convertible->id,
                            'name' => $convertible->name,
                            'extension' => $convertible->extension,
                        ];
                    }),
                    'convertible_types' => $format->convertibleCategory->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'slug' => $category->slug,
                        ];
                    }),
                ];
            }),
            'seo' => property_exists($this, 'seo') ? $this->metaTags : null,
        ];
    }
}
