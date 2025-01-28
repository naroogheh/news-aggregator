<?php

namespace App\Console\Commands;

use App\Service\SourceService;
use Illuminate\Console\Command;

class Aggregator extends Command
{
    private $sourceService;
    function __construct( SourceService $sourceService )
    {
        $this->sourceService = $sourceService;
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
                $class = new $class_name($source);
                $class->getArticles($params);
            }
            catch (\Exception $e) {
                dd($e->getMessage());
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
