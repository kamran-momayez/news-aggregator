<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     *  Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'source' => $this->source,
            'category' => $this->category,
            'author' => $this->author,
            'url' => $this->url,
            'news_service' => $this->news_service,
            'published_at' => $this->published_at->format('Y-m-d H:i:s'),
        ];
    }
}
