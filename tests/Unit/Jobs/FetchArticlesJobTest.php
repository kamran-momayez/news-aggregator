<?php

namespace Tests\Unit\Jobs;

use App\Jobs\FetchArticlesJob;
use App\Services\ArticleService;
use Tests\TestCase;

class FetchArticlesJobTest extends TestCase
{
    public function test_handle_calls_fetch_and_store_articles_on_article_service()
    {
        $articleService = $this->createMock(ArticleService::class);
        $articleService->expects($this->once())
            ->method('fetchAndStoreArticles');

        $job = new FetchArticlesJob();
        $job->handle($articleService);
    }
}
