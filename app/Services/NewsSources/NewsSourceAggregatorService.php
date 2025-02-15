<?php

namespace App\Services\NewsSources;

class NewsSourceAggregatorService
{
    protected array $sources;

    public function __construct(array $selectedSources)
    {
        foreach ($selectedSources as $source) {
            $this->sources[] = NewsSourceFactory::create($source);
        }
    }

    public function fetchArticlesFromAllSources(): array
    {
        $articles = [];

        foreach ($this->sources as $source) {
            if ($source instanceof NewsSourceInterface) {
                $articles = array_merge($articles, $source->fetchArticles());
            }
        }

        return $articles;
    }
}

