<?php

namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Services\NewsSources\NewsSourceAggregatorService;
use Illuminate\Pagination\LengthAwarePaginator;

class ArticleService
{
    public function __construct(
        protected ArticleRepository $repository,
        protected NewsSourceAggregatorService $aggregator
    ) {
    }

    public function fetchAndStoreArticles(): void
    {
        $articles = $this->aggregator->fetchArticlesFromAllSources();
        foreach ($articles as $article) {
            $this->repository->store($article);
        }
    }

    public function searchArticles(array $filters): LengthAwarePaginator
    {
        return $this->repository->search($filters);
    }
}
