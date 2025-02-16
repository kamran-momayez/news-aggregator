<?php

namespace Tests\Unit\Services\NewsSources;

use App\Services\NewsSources\GuardianService;
use App\Services\NewsSources\NewsApiService;
use App\Services\NewsSources\NewsSourceAggregatorService;
use ReflectionClass;
use Tests\TestCase;

class NewsSourceAggregatorServiceTest extends TestCase
{
    public function test_fetch_articles_from_all_sources()
    {
        $source1 = $this->createMock(GuardianService::class);
        $source1->method('fetchArticles')->willReturn([
            [
                'title' => 'Example Article 1',
                'source' => 'Source 1',
                'author' => 'Author 1',
                'url' => 'https://example.com/article-1',
                'content' => 'Content of article 1',
                'published_at' => '2025-02-15T12:00:00Z',
                'news_service' => 'SOURCE_1',
            ],
        ]);

        $source2 = $this->createMock(NewsApiService::class);
        $source2->method('fetchArticles')->willReturn([
            [
                'title' => 'Example Article 2',
                'source' => 'Source 2',
                'author' => 'Author 2',
                'url' => 'https://example.com/article-2',
                'content' => 'Content of article 2',
                'published_at' => '2025-02-16T12:00:00Z',
                'news_service' => 'SOURCE_2',
            ],
        ]);

        $aggregatorMock = $this->getMockBuilder(NewsSourceAggregatorService::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock();

        $reflection = new ReflectionClass(NewsSourceAggregatorService::class);
        $property = $reflection->getProperty('sources');
        $property->setValue($aggregatorMock, [$source1, $source2]);

        $articles = $aggregatorMock->fetchArticlesFromAllSources();

        $this->assertCount(2, $articles);
        $this->assertEquals('Example Article 1', $articles[0]['title']);
        $this->assertEquals('Source 1', $articles[0]['source']);
        $this->assertEquals('Author 1', $articles[0]['author']);
        $this->assertEquals('https://example.com/article-1', $articles[0]['url']);
        $this->assertEquals('Content of article 1', $articles[0]['content']);
        $this->assertEquals('2025-02-15T12:00:00Z', $articles[0]['published_at']);
        $this->assertEquals('SOURCE_1', $articles[0]['news_service']);

        $this->assertEquals('Example Article 2', $articles[1]['title']);
        $this->assertEquals('Source 2', $articles[1]['source']);
        $this->assertEquals('Author 2', $articles[1]['author']);
        $this->assertEquals('https://example.com/article-2', $articles[1]['url']);
        $this->assertEquals('Content of article 2', $articles[1]['content']);
        $this->assertEquals('2025-02-16T12:00:00Z', $articles[1]['published_at']);
        $this->assertEquals('SOURCE_2', $articles[1]['news_service']);
    }
}
