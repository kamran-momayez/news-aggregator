<?php

namespace Tests\Unit\Services\NewsSources;

use App\Services\NewsSources\NewsApiService;
use Illuminate\Support\Facades\Http;
use ReflectionClass;
use Tests\TestCase;

class NewsApiServiceTest extends TestCase
{
    public function test_fetch_articles()
    {
        $response = [
            'articles' => [
                [
                    'source' => ['name' => 'Example Source 1'],
                    'author' => 'Author 1',
                    'title' => 'Example Article 1',
                    'url' => 'https://example.com/article-1',
                    'content' => 'Content of article 1',
                    'publishedAt' => '2025-02-15T12:00:00Z',
                ],
                [
                    'source' => ['name' => 'Example Source 2'],
                    'author' => 'Author 2',
                    'title' => 'Example Article 2',
                    'url' => 'https://example.com/article-2',
                    'content' => 'Content of article 2',
                    'publishedAt' => '2025-02-16T12:00:00Z',
                ],
            ],
        ];

        $reflection = new ReflectionClass(NewsApiService::class);
        $apiUrl = $reflection->getConstant('API_URL');

        Http::fake(['*'.$apiUrl.'*' => Http::response($response)]);

        $service = new NewsApiService();

        $articles = $service->fetchArticles();

        $this->assertCount(2, $articles);
        $this->assertEquals('Example Article 1', $articles[0]['title']);
        $this->assertEquals('Example Source 1', $articles[0]['source']);
        $this->assertEquals('Author 1', $articles[0]['author']);
        $this->assertEquals('https://example.com/article-1', $articles[0]['url']);
        $this->assertEquals('Content of article 1', $articles[0]['content']);
        $this->assertEquals('2025-02-15T12:00:00Z', $articles[0]['published_at']);
        $this->assertEquals(NewsApiService::SOURCE_NAME, $articles[0]['news_service']);
    }
}
