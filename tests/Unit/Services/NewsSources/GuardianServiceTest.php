<?php

namespace Tests\Unit\Services\NewsSources;

use App\Services\NewsSources\GuardianService;
use Illuminate\Support\Facades\Http;
use ReflectionClass;
use Tests\TestCase;

class GuardianServiceTest extends TestCase
{
    public function test_fetch_articles()
    {
        $response = [
            'response' => [
                'results' => [
                    [
                        'webTitle' => 'Example Article 1',
                        'pillarName' => 'News',
                        'webUrl' => 'https://example.com/article-1',
                        'webPublicationDate' => '2025-02-15T12:00:00Z',
                    ],
                    [
                        'webTitle' => 'Example Article 2',
                        'pillarName' => 'Sports',
                        'webUrl' => 'https://example.com/article-2',
                        'webPublicationDate' => '2025-02-16T12:00:00Z',
                    ],
                ],
            ],
        ];

        $reflection = new ReflectionClass(GuardianService::class);
        $apiUrl = $reflection->getConstant('API_URL');

        Http::fake([$apiUrl => Http::response($response)]);

        $service = new GuardianService();

        $articles = $service->fetchArticles();

        $this->assertCount(2, $articles);
        $this->assertEquals('Example Article 1', $articles[0]['title']);
        $this->assertEquals('News', $articles[0]['category']);
        $this->assertEquals('https://example.com/article-1', $articles[0]['url']);
        $this->assertEquals('2025-02-15T12:00:00Z', $articles[0]['published_at']);
        $this->assertEquals(GuardianService::SOURCE_NAME, $articles[0]['news_service']);
    }
}
