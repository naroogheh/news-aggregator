<?php

namespace App\Console\Commands;

use App\Jobs\ArticlesSaverJob;
use App\Service\NewsAgencyService;
use App\Service\SourceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Aggregator extends Command
{
    private $sourceService, $newsAgencyService;
    function __construct( SourceService $sourceService , NewsAgencyService $newsAgencyService)
    {
        $this->sourceService = $sourceService;
        $this->newsAgencyService = $newsAgencyService;
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aggregator:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command read news from sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sources = $this->sourceService->getAll();
        foreach ($sources as $source) {
            try {
                $params = $this->getParams($source);
                $class_name = "App\Service\Readers\\".$source->reader_class;
                if (!class_exists($class_name)) {
                    throw new \Exception("Class {$class_name} does not exist.");
                }
                $class = new $class_name($source, $this->newsAgencyService);
                $articles = $class->getArticles($params);
                dd($articles);
                if (!empty($articles)) {
                    ArticlesSaverJob::dispatch($articles);
                }
            }
            catch (\Exception $e) {
                Log::error("Error processing source on Aggregator : {$source->id}", [
                    'message' => $e->getMessage(),
                    'source' => $source,
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
    }

    function getParams($source)
    {
        $lastSyncTime = $source->last_sync_time;
        if(!$lastSyncTime )
            $lastSyncTime = date('Y-m-d H:i:00',strtotime('-3 day'));
        return [
            'from' => $lastSyncTime,
            'to' => date('Y-m-d H:i:00'),
        ];
    }
}
