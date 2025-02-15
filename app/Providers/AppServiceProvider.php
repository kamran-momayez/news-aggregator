<?php

namespace App\Providers;

use App\Services\NewsSources\GuardianService;
use App\Services\NewsSources\NewsApiService;
use App\Services\NewsSources\NewsSourceAggregatorService;
use App\Services\NewsSources\NewYorkTimesService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsSourceAggregatorService::class, function () {
            return new NewsSourceAggregatorService(
                [
                    NewsApiService::SOURCE_NAME,
                    GuardianService::SOURCE_NAME,
                    NewYorkTimesService::SOURCE_NAME
                ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
