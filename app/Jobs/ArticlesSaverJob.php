<?php

namespace App\Jobs;

use App\Models\News;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArticlesSaverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $newsData;

    /**
     * Create a new job instance.
     *
     * @param array $newsData
     */
    public function __construct(array $newsData)
    {
        $this->newsData = $newsData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if (!empty($this->newsData)) {
            foreach (array_chunk($this->newsData, 500) as $chunk) {
                News::insert($chunk);
            }
        }
    }
}
