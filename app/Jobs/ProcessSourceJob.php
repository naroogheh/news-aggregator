<?php

namespace App\Jobs;

use App\Service\NewsAgencyService;
use App\Service\SourceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSourceJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $source;
    private $sourceService;
    private $newsAgencyService;

    public function __construct($source)
    {
        $this->source = $source;
    }

    public function handle(SourceService $sourceService, NewsAgencyService $newsAgencyService)
    {
        $this->sourceService = $sourceService;
        $this->newsAgencyService = $newsAgencyService;

        try {
            $params = $this->getParams($this->source);
            $readerClass = "App\\Service\\Readers\\{$this->source->reader_class}";

            if (!class_exists($readerClass)) {
                throw new \Exception("Class {$readerClass} does not exist.");
            }

            $reader = app($readerClass, [
                'source' => $this->source,
                'newsAgencyService' => $this->newsAgencyService,
            ]);

            $articles = $reader->getArticles($params);
            Log::info("Fetched {$this->source->name} articles: " . count($articles));
            if (count($articles)) {
                ArticlesSaverJob::dispatch($articles)->onQueue('articles');
                $this->sourceService->updateLastSyncTime($this->source->id, now()->format('Y-m-d H:i:00'));
            }
        } catch (\Exception $e) {
            Log::error("Error processing source: {$this->source->id}", [
                'message' => $e->getMessage(),
                'source' => $this->source,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function getParams($source): array
    {
        $lastSyncTime = $source->last_sync_time ?: now()->subDays(3)->format('Y-m-d H:i:00');
        return [
            'from' => $lastSyncTime,
            'to' => now()->format('Y-m-d H:i:00'),
        ];
    }
}
