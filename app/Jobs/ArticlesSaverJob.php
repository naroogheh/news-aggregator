<?php

namespace App\Jobs;

use App\Models\News;
use App\Service\NewsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArticlesSaverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $newsData,$newsService;


    /**
     * Create a new job instance.
     *
     * @param array $newsData
     */
    public function __construct(array $newsData)
    {
        $this->newsData = $newsData;
        $this->newsService = app()->make(NewsService::class);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        echo "saving news\n";
        echo "news count : ".count($this->newsData)."\n";

        if (!empty($this->newsData)) {
            $items = $this->newsData;
            // map to array of news
            $items = array_map(function ($item) {
                return $item->toArray();
            }, $items);

            foreach (array_chunk($items, 500) as $chunk) {
                // batch insert items
                $this->newsService->batchInsert($chunk);

            }
        }
    }
}
