<?php

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ArticleRepository
{
    private const int CACHE_DURATION_MINUTES = 10;
    private const int PER_PAGE = 10;

    public function store(array $data): void
    {
        Article::updateOrCreate(
            ['url' => $data['url']], // Avoid duplicates
            $data
        );

        Cache::flush();
    }

    public function search(array $filters): LengthAwarePaginator
    {
        $cacheKey = 'articles_'.md5(json_encode($filters));

        return Cache::remember($cacheKey, now()->addMinutes(self::CACHE_DURATION_MINUTES), function () use ($filters) {
            return $this->buildQuery($filters)->paginate(self::PER_PAGE);
        });
    }

    private function buildQuery(array $filters): Builder
    {
        return Article::query()
            ->when($filters['source'] ?? null, fn(Builder $q, $source) => $q->where('source', $source)
            )
            ->when($filters['category'] ?? null, fn(Builder $q, $category) => $q->where('category', $category)
            )
            ->when($filters['author'] ?? null, fn(Builder $q, $author) => $q->where('author', $author)
            )
            ->when($filters['date'] ?? null, fn(Builder $q, $date) => $q->whereDate('published_at', $date)
            )
            ->when(
                $filters['news_service'] ?? null,
                fn(Builder $q, $newsService) => $q->where('news_service', $newsService)
            )
            ->orderByDesc('published_at');
    }
}
