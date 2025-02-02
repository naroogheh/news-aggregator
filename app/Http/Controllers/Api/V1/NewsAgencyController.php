<?php

namespace App\Http\Controllers\Api\V1;

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
        $cache_is_exist = cache()->has('news_agencies');
        if ($cache_is_exist) {
            $newsAgencies = cache()->get('news_agencies');
        }
        else {
            $newsAgencies = $this->newsAgencyRepository->getAll();
            // update cache
            cache()->put('news_agencies', $newsAgencies, 60 * 60 * 24);
        }
        return response()->json([
            'success' => true,
            'data' => SourceResource::collection( $newsAgencies),
            'message' => 'Agencies retrieved successfully.'
        ]);
    }

}
