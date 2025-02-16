<?php

namespace Tests\Unit\Services\NewsSources;

use App\Services\NewsSources\NewYorkTimesService;
use Illuminate\Support\Facades\Http;
use ReflectionClass;
use Tests\TestCase;

class NewYorkTimesServiceTest extends TestCase
{
    public function test_fetch_articles()
    {
        $response = [
            'response' => [
                'docs' => [
                    [
                        'source' => 'New York Times',
                        'headline' => ['main' => 'Example Article 1'],
                        'lead_paragraph' => 'Content of article 1',
                        'section_name' => 'News',
                        'web_url' => 'https://example.com/article-1',
                        'byline' => ['person' => [['firstname' => 'Author', 'lastname' => 'One']]],
                        'pub_date' => '2025-02-15T12:00:00Z',
                    ],
                    [
                        'source' => 'New York Times',
                        'headline' => ['main' => 'Example Article 2'],
                        'lead_paragraph' => 'Content of article 2',
                        'section_name' => 'Sports',
                        'web_url' => 'https://example.com/article-2',
                        'byline' => ['person' => [['firstname' => 'Author', 'lastname' => 'Two']]],
                        'pub_date' => '2025-02-16T12:00:00Z',
                    ],
                ],
            ],
        ];

        $reflection = new ReflectionClass(NewYorkTimesService::class);
        $apiUrl = $reflection->getConstant('API_URL');

        Http::fake([$apiUrl => Http::response($response)]);

        $service = new NewYorkTimesService();

        $articles = $service->fetchArticles();

        $this->assertCount(2, $articles);
        $this->assertEquals('Example Article 1', $articles[0]['title']);
        $this->assertEquals('New York Times', $articles[0]['source']);
        $this->assertEquals('Content of article 1', $articles[0]['content']);
        $this->assertEquals('News', $articles[0]['category']);
        $this->assertEquals('https://example.com/article-1', $articles[0]['url']);
        $this->assertEquals('Author One', $articles[0]['author']);
        $this->assertEquals('2025-02-15T12:00:00Z', $articles[0]['published_at']);
        $this->assertEquals(NewYorkTimesService::SOURCE_NAME, $articles[0]['news_service']);
    }
}
