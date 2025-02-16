<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_successful_response()
    {
        $response = $this->getJson('/api/articles');

        $response->assertStatus(200);
    }

    public function test_index_returns_articles()
    {
        Article::factory()->create([
            'url' => 'https://example.com/article-1',
            'title' => 'Example Article',
            'source' => 'Example Source',
            'news_service' => 'NYT',
            'published_at' => '2025-02-15T12:00:00Z',
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-2',
            'title' => 'Another Article',
            'source' => 'Another Source',
            'news_service' => 'GUARDIAN',
            'published_at' => '2025-02-15T12:00:00Z',
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-3',
            'title' => 'Another Article 3',
            'source' => 'Another Source 3',
            'news_service' => 'NewsAPI',
            'published_at' => '2025-02-15T12:00:00Z',
        ]);

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_index_applies_filters()
    {
        Article::factory()->create([
            'url' => 'https://example.com/article-1',
            'title' => 'Example Article',
            'source' => 'Example Source',
            'news_service' => 'NYT',
            'published_at' => '2025-02-15T12:00:00Z',
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-2',
            'title' => 'Another Article',
            'source' => 'Another Source',
            'news_service' => 'GUARDIAN',
            'published_at' => '2025-02-15T12:00:00Z',
        ]);
        Article::factory()->create([
            'url' => 'https://example.com/article-3',
            'title' => 'Another Article 3',
            'source' => 'Another Source 3',
            'news_service' => 'NewsAPI',
            'published_at' => '2025-02-15T12:00:00Z',
        ]);

        $response = $this->getJson('/api/articles?source=Another Source');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['source' => 'Another Source']);
    }
}
