<?php

namespace App\Services\NewsSources;

use Illuminate\Support\Facades\Http;

class NewYorkTimesService implements NewsSourceInterface
{
    private const string API_URL = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';
    public const string SOURCE_NAME = 'New York Times';

    public function fetchArticles(): array
    {
        $response = Http::get(self::API_URL, [
            'api-key' => config('services.new_york_times.key'),
        ]);

        return $this->getNormalizedArticles($response->json()['response']['docs'] ?? []);
    }

    private function getNormalizedArticles(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'source' => $article['source'] ?? null,
                'title' => $article['headline']['main'] ?? null,
                'content' => $article['lead_paragraph'] ?? null,
                'category' => $article['section_name'] ?? null,
                'url' => $article['web_url'] ?? null,
                'author' => isset($article['byline']['person'][0]['firstname']) ?
                    $article['byline']['person'][0]['firstname'].' '.$article['byline']['person'][0]['lastname'] : null,
                'published_at' => $article['pub_date'] ?? null,
                'news_service' => self::SOURCE_NAME,
            ];
        }, $articles);
    }
}
