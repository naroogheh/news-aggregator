<?php

namespace App\Observers;

use App\Models\Source;
use Illuminate\Support\Facades\Cache;
use App\Enum\CacheKeys;

class SourceObserver
{
    /**
     * Handle the Source "created" event.
     *
     * @param Source $source
     * @return void
     */
    public function created(Source $source): void
    {
        $this->deleteCache();
    }

    /**
     * Handle the Source "updated" event.
     *
     * @param Source $source
     * @return void
     */
    public function updated(Source $source): void
    {
        $this->deleteCache();
    }

    /**
     * Handle the Source "deleted" event.
     *
     * @param Source $source
     * @return void
     */
    public function deleted(Source $source): void
    {
        $this->deleteCache();
    }

    /**
     * Delete the sources cache.
     *
     * @return void
     */
    private function deleteCache(): void
    {
        Cache::forget(CacheKeys::KEY_SOURCES->value);
    }
}
