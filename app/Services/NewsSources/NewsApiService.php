<?php

namespace App\Services\NewsSources;

use Illuminate\Support\Facades\Http;

class NewsApiService implements NewsSourceInterface
{
    private const string API_URL = 'https://newsapi.org/v2/top-headlines';
    public const string SOURCE_NAME = 'NEWS API';

    public function fetchArticles(): array
    {
        $response = Http::get(self::API_URL, [
            'apiKey' => config('services.news_api.key'),
            'country' => 'us',
        ]);

        return $this->getNormalizedArticles($response->json()['articles'] ?? []);
    }

    private function getNormalizedArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'source' => $article['source']['name'] ?? null,
                'author' => $article['author'] ?? null,
                'title' => $article['title'] ?? null,
                'url' => $article['url'] ?? null,
                'content' => $article['content'] ?? null,
                'published_at' => $article['publishedAt'] ?? null,
                'news_service' => self::SOURCE_NAME,
            ];
        }, $articles);
    }
}
