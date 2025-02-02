<?php

namespace App\Console\Commands;

use App\Jobs\ProcessSourceJob;
use App\Service\SourceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Aggregator extends Command
{
    protected $signature = 'aggregator:run';
    protected $description = 'This command reads news from sources and dispatches jobs for each source.';

    private $sourceService;

    public function __construct(SourceService $sourceService)
    {
        parent::__construct();
        $this->sourceService = $sourceService;
    }

    public function handle()
    {
        try {
            $sources = $this->getCachedSources();

            if ($sources->isEmpty()) {
                $this->info("No sources found to dispatch.");
                return;
            }

            $sources->each(function ($source) {
                ProcessSourceJob::dispatch($source)->onQueue('sources');
            });

            $this->info("Dispatched {$sources->count()} sources to the queue.");
        } catch (\Exception $e) {
            $this->error("An error occurred: {$e->getMessage()}");
            Log::error('Aggregator command failed: ' . $e->getMessage());
        }
    }

    private function getCachedSources()
    {
        return Cache::remember('news_sources', 3600, function () {
            return $this->sourceService->getAll();
        });
    }
}
