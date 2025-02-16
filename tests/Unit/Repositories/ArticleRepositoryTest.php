<?php

namespace Tests\Unit\Repositories;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ArticleRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_stores_an_article_and_flushes_cache()
    {
        $repository = new ArticleRepository();
        $data = [
            'url' => 'https://example.com/article',
            'title' => 'Example Article',
            'content' => 'This is an example article.',
            'source' => 'Example Source',
            'category' => 'Example Category',
            'author' => 'John Doe',
            'news_service' => 'NYT',
            'published_at' => now(),
        ];

        Cache::shouldReceive('flush')->once();

        $repository->store($data);

        $this->assertDatabaseHas('articles', ['url' => 'https://example.com/article']);
    }

    public function test_search_filters_articles_and_caches_results()
    {
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(new LengthAwarePaginator([], 0, 10));

        $repository = new ArticleRepository();
        $filters = [
            'source' => 'Example Source',
            'category' => 'Example Category',
            'author' => 'John Doe',
            'date' => '2025-02-15',
            'news_service' => 'NYT',
        ];

        $result = $repository->search($filters);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_search_uses_cache()
    {
        $cachedResult = new LengthAwarePaginator([], 0, 10);
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($cachedResult);

        $repository = new ArticleRepository();
        $filters = [
            'source' => 'Example Source',
            'category' => 'Example Category',
            'author' => 'John Doe',
            'date' => '2025-02-15',
            'news_service' => 'NYT',
        ];

        $result = $repository->search($filters);

        $this->assertSame($cachedResult, $result);
    }

    public function test_search_filters_by_source()
    {
        Article::factory()->create([
            'url' => 'https://example.com/article-1',
            'title' => 'Example Article',
            'source' => 'Example Source',
            'news_service' => 'NYT',
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-2',
            'title' => 'Another Article',
            'source' => 'Another Source',
            'news_service' => 'GUARDIAN'
        ]);

        $repository = new ArticleRepository();
        $filters = ['source' => 'Example Source'];

        $result = $repository->search($filters);

        $this->assertCount(1, $result->items());
        $this->assertEquals('Example Source', $result->items()[0]->source);
    }

    public function test_search_filters_by_category()
    {
        Article::factory()->create([
            'url' => 'https://example.com/article-1',
            'title' => 'Example Article',
            'source' => 'Example Source',
            'news_service' => 'NYT',
            'category' => 'Example Category'
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-2',
            'title' => 'Another Article',
            'source' => 'Another Source',
            'news_service' => 'GUARDIAN',
            'category' => 'Another Category'
        ]);

        $repository = new ArticleRepository();
        $filters = ['category' => 'Example Category'];

        $result = $repository->search($filters);

        $this->assertCount(1, $result->items());
        $this->assertEquals('Example Category', $result->items()[0]->category);
    }

    public function test_search_filters_by_author()
    {
        Article::factory()->create([
            'url' => 'https://example.com/article-1',
            'title' => 'Example Article',
            'source' => 'Example Source',
            'news_service' => 'NYT',
            'author' => 'John Doe'
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-2',
            'title' => 'Another Article',
            'source' => 'Another Source',
            'news_service' => 'GUARDIAN',
            'author' => 'Jane Doe'
        ]);

        $repository = new ArticleRepository();
        $filters = ['author' => 'John Doe'];

        $result = $repository->search($filters);

        $this->assertCount(1, $result->items());
        $this->assertEquals('John Doe', $result->items()[0]->author);
    }

    public function test_search_filters_by_date()
    {
        Article::factory()->create([
            'url' => 'https://example.com/article-1',
            'title' => 'Example Article',
            'source' => 'Example Source',
            'news_service' => 'NYT',
            'published_at' => '2025-02-15'
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-2',
            'title' => 'Another Article',
            'source' => 'Another Source',
            'news_service' => 'GUARDIAN',
            'published_at' => '2025-02-16'
        ]);

        $repository = new ArticleRepository();
        $filters = ['date' => '2025-02-15'];

        $result = $repository->search($filters);

        $this->assertCount(1, $result->items());
        $this->assertEquals('2025-02-15', $result->items()[0]->published_at->toDateString());
    }

    public function test_search_filters_by_news_service()
    {
        Article::factory()->create([
            'url' => 'https://example.com/article-1',
            'title' => 'Example Article',
            'source' => 'Example Source',
            'news_service' => 'NYT',
            'published_at' => '2025-02-15'
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-2',
            'title' => 'Another Article',
            'source' => 'Another Source',
            'news_service' => 'GUARDIAN',
            'published_at' => '2025-02-16'
        ]);

        $repository = new ArticleRepository();
        $filters = ['news_service' => 'NYT'];

        $result = $repository->search($filters);

        $this->assertCount(1, $result->items());
        $this->assertEquals('NYT', $result->items()[0]->news_service);
    }
}
