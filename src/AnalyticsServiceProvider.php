<?php

namespace Nurmuhammet\Analytics;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Nurmuhammet\Analytics\Http\Middleware\Analytics;

class AnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/analytics.php' => config_path('analytics.php'),
            ], 'analytics-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/analytics'),
            ], 'analytics-assets');
        }

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Middleware
        Route::middlewareGroup('analytics', [
            Analytics::class,
        ]);

        // Routes
        Route::group($this->routeConfig(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });

        // Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'analytics');
    }

    protected function routeConfig(): array
    {
        return [
            'namespace' => 'Nurmuhammet\Analytics\Http\Controllers',
            'prefix' => config('analytics.prefix'),
            'middleware' => config('analytics.middleware'),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/analytics.php', 'analytics'
        );
    }
}
