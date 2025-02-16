<?php

namespace Tests\Unit\Services;

use App\Repositories\ArticleRepository;
use App\Services\ArticleService;
use App\Services\NewsSources\NewsSourceAggregatorService;
use Illuminate\Pagination\LengthAwarePaginator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ArticleServiceTest extends TestCase
{
    /** @var ArticleRepository|MockObject */
    protected $repository;

    /** @var NewsSourceAggregatorService|MockObject */
    protected $aggregator;

    /** @var ArticleService */
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(ArticleRepository::class);
        $this->aggregator = $this->createMock(NewsSourceAggregatorService::class);
        $this->service = new ArticleService($this->repository, $this->aggregator);
    }

    public function test_fetch_and_store_articles()
    {
        $articles = [
            ['url' => 'https://example.com/article-1', 'title' => 'Example Article 1'],
            ['url' => 'https://example.com/article-2', 'title' => 'Example Article 2'],
        ];

        $this->aggregator->expects($this->once())
            ->method('fetchArticlesFromAllSources')
            ->willReturn($articles);

        $this->repository->expects($this->exactly(count($articles)))
            ->method('store')
            ->willReturnCallback(function ($article) use (&$articles) {
                $expected = array_shift($articles);
                $this->assertSame($expected, $article);
            });

        $this->service->fetchAndStoreArticles();
    }

    public function test_search_articles()
    {
        $filters = ['source' => 'Example Source'];
        $paginator = new LengthAwarePaginator([], 0, 10);

        $this->repository->expects($this->once())
            ->method('search')
            ->with($filters)
            ->willReturn($paginator);

        $result = $this->service->searchArticles($filters);

        $this->assertSame($paginator, $result);
    }
}
