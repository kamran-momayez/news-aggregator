<?php

namespace App\Services\NewsSources;

interface NewsSourceInterface
{
    public function fetchArticles(): array;
}
