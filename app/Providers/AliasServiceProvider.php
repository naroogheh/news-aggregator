<?php

namespace App\Providers;

use App\Facades\AgencyFacade;
use App\Facades\CategoryFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        // Add your aliases
        $loader->alias('CategoryHelper', CategoryFacade::class);
        $loader->alias('AgencyHelper', AgencyFacade::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
