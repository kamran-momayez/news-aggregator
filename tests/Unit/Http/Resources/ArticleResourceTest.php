<?php

namespace Tests\Unit\Http\Resources;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Tests\TestCase;

class ArticleResourceTest extends TestCase
{
    public function test_transforms_an_article_into_an_array()
    {
        $article = new Article([
            'title' => 'Sample Title',
            'content' => 'Sample Content',
            'source' => 'Sample Source',
            'category' => 'Sample Category',
            'author' => 'Sample Author',
            'url' => 'https://example.com',
            'news_service' => 'Sample News Service',
            'published_at' => now(),
        ]);

        $resource = new ArticleResource($article);
        $array = $resource->toArray(request());

        $this->assertEquals([
            'id' => $article->id,
            'title' => 'Sample Title',
            'content' => 'Sample Content',
            'source' => 'Sample Source',
            'category' => 'Sample Category',
            'author' => 'Sample Author',
            'url' => 'https://example.com',
            'news_service' => 'Sample News Service',
            'published_at' => $article->published_at->format('Y-m-d H:i:s'),
        ], $array);
    }
}
