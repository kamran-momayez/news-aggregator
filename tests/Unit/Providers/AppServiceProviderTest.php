<?php

namespace Tests\Unit\Providers;

use App\Providers\AppServiceProvider;
use App\Services\NewsSources\NewsSourceAggregatorService;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class AppServiceProviderTest extends TestCase
{
    public function test_registers_news_source_aggregator_service_as_singleton()
    {
        $provider = new AppServiceProvider($this->app);
        $provider->register();

        $service1 = App::make(NewsSourceAggregatorService::class);
        $service2 = App::make(NewsSourceAggregatorService::class);

        $this->assertSame($service1, $service2);

    }
}
