<?php

namespace App\Providers;

use App\Helpers\CategoryHelper;
use App\Repository\CategoryRepository;
use App\Repository\Interfaces\CategoryRepositoryInterface;
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

        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);


        $this->app->singleton(CategoryHelper::class, function ($app) {
            CategoryHelper::setCategoryService($app->make(CategoryRepositoryInterface::class));
            return new CategoryHelper();
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
