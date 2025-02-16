<?php

namespace Tests\Unit\Services\NewsSources;

use App\Services\NewsSources\GuardianService;
use App\Services\NewsSources\NewsApiService;
use App\Services\NewsSources\NewYorkTimesService;
use App\Services\NewsSources\NewsSourceFactory;
use PHPUnit\Framework\TestCase;

class NewsSourceFactoryTest extends TestCase
{
    public function test_create_news_api_service()
    {
        $service = NewsSourceFactory::create(NewsApiService::SOURCE_NAME);
        $this->assertInstanceOf(NewsApiService::class, $service);
    }

    public function test_create_guardian_service()
    {
        $service = NewsSourceFactory::create(GuardianService::SOURCE_NAME);
        $this->assertInstanceOf(GuardianService::class, $service);
    }

    public function test_create_new_york_times_service()
    {
        $service = NewsSourceFactory::create(NewYorkTimesService::SOURCE_NAME);
        $this->assertInstanceOf(NewYorkTimesService::class, $service);
    }

    public function test_create_unsupported_service()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported news source: UnsupportedService');
        NewsSourceFactory::create('UnsupportedService');
    }
}
