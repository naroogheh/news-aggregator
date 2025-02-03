<?php

namespace App\Http\Controllers\Api\V1;

use App\Enum\CacheKeys;
use App\Http\Resources\Api\SourceResource;
use App\Repository\Interfaces\NewsAgencyRepositoryInterface;

class NewsAgencyController
{
    private $newsAgencyRepository;
    function __construct(NewsAgencyRepositoryInterface $newsAgencyRepository)
    {
        $this->newsAgencyRepository = $newsAgencyRepository;
    }

    public function index()
    {
        // check cache
        $cache_is_exist = cache()->has(CacheKeys::KEY_NEWS_AGENCIES->value);
        if ($cache_is_exist) {
            $newsAgencies = cache()->get(CacheKeys::KEY_NEWS_AGENCIES->value);
        }
        else {
            $newsAgencies = $this->newsAgencyRepository->getAll();
            // update cache
            cache()->put(CacheKeys::KEY_NEWS_AGENCIES->value, $newsAgencies, 60 * 60 * 24);
        }
        return response()->json([
            'success' => true,
            'data' => SourceResource::collection( $newsAgencies),
            'message' => 'Agencies retrieved successfully.'
        ]);
    }

}
