<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
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
            'description' => $this->getTranslate('description'),
            'url' => $this->url,
            'image' => $this->icon_image,
            'seo' => $this->metaTags
        ];
    }
}
