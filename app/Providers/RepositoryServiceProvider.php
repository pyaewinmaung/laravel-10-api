<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('App\Models\Article\ArticleRepositoryInterface','App\Models\Article\ArticleRepository');

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}