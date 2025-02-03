<?php

namespace App\Providers;

use App\Helpers\AuthorHelper;
use App\Helpers\CategoryHelper;
use App\Helpers\NewsAgencyHelper;
use App\Models\Author;
use App\Models\Category;
use App\Models\NewsAgency;
use App\Models\Source;
use App\Observers\AuthorObserver;
use App\Observers\CategoryObserver;
use App\Observers\NewsAgencyObserver;
use App\Observers\SourceObserver;
use App\Repository\AuthorRepository;
use App\Repository\CategoryRepository;
use App\Repository\Interfaces\AuthorRepositoryInterface;
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
        //register observables
        Source::observe(SourceObserver::class);
        Category::observe(CategoryObserver::class);
        NewsAgency::observe(NewsAgencyObserver::class);
        Author::observe(AuthorObserver::class);


        $this->app->bind(SourceRepositoryInterface::class, SourceRepository::class);
        $this->app->bind(NewsAgencyRepositoryInterface::class, NewsAgencyRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(AuthorRepositoryInterface::class, AuthorRepository::class);


        $this->app->singleton('category-helper', function ($app) {
            return new CategoryHelper($app->make(CategoryRepositoryInterface::class));
        });

        $this->app->singleton('agency-helper', function ($app) {
            return new NewsAgencyHelper($app->make(NewsAgencyRepositoryInterface::class));
        });

        $this->app->singleton('author-helper', function ($app) {
            return new AuthorHelper($app->make(AuthorRepositoryInterface::class));
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
