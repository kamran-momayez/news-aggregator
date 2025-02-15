<?php

namespace App\Services\NewsSources;

class NewsSourceFactory
{
    public static function create(string $source): NewsSourceInterface
    {
        return match ($source) {
            NewsApiService::SOURCE_NAME => new NewsApiService(),
            GuardianService::SOURCE_NAME => new GuardianService(),
            NewYorkTimesService::SOURCE_NAME => new NewYorkTimesService(),
            default => throw new \InvalidArgumentException("Unsupported news source: $source")
        };
    }
}

