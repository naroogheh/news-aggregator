<?php

namespace App\Service;

use App\Filters\SourceFilter;
use App\Models\News;
use App\Repository\NewsRepository;
use Illuminate\Routing\Pipeline;

class NewsService
{
    private $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    function insertItem($params)
    {
       return $this->newsRepository->insertItem($params);
    }

    function batchInsert($items)
    {
        return $this->newsRepository->batchInsert($items);
    }

    function filter($filters)
    {
        return $this->newsRepository->filter($filters);

    }
}
