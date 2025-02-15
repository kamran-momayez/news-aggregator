<?php

namespace App\Services\NewsSources;

use Illuminate\Support\Facades\Http;

class GuardianService implements NewsSourceInterface
{
    private const string API_URL = 'https://content.guardianapis.com/search';
    public const string SOURCE_NAME = 'GUARDIAN';

    public function fetchArticles(): array
    {
        $response = Http::get(self::API_URL, [
            'api-key' => config('services.guardian.key'),
        ]);

        return $this->getNormalizedArticles($response->json()['response']['results'] ?? []);
    }

    private function getNormalizedArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['webTitle'] ?? null,
                'category' => $article['pillarName'] ?? null,
                'url' => $article['webUrl'] ?? null,
                'published_at' => $article['webPublicationDate'] ?? null,
                'news_service' => self::SOURCE_NAME,
            ];
        }, $articles);
    }
}
