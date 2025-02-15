<?php

namespace App\Jobs;

use App\Services\ArticleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchArticlesJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
    }

    public function handle(ArticleService $articleService): void
    {
        $articleService->fetchAndStoreArticles();
    }
}
