<?php

namespace App\Providers;

use App\Repository\Interfaces\NewsAgencyRepositoryInterface;
use App\Repository\Interfaces\SourceRepositoryInterface;
use App\Repository\NewsAgencyRepository;
use App\Repository\SourceRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SourceRepositoryInterface::class, SourceRepository::class);
        $this->app->bind(NewsAgencyRepositoryInterface::class, NewsAgencyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
