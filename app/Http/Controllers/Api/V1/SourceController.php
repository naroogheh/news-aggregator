<?php

namespace App\Http\Controllers\Api\V1;

use App\Enum\CacheKeys;
use App\Http\Resources\Api\SourceResource;
use App\Repository\Interfaces\SourceRepositoryInterface;

class SourceController
{
    private $sourceRepository;
    function __construct(SourceRepositoryInterface $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    public function index()
    {
        // check cache
        $cache_is_exist = cache()->has(CacheKeys::KEY_SOURCES->value);
        if ($cache_is_exist) {
            $sources = cache()->get(CacheKeys::KEY_SOURCES->value);
        }
        else {
            $sources = $this->sourceRepository->getAll();
            // update cache
            cache()->put(CacheKeys::KEY_SOURCES->value, $sources, 60 * 60 * 24);
        }
        return response()->json([
            'success' => true,
            'data' => SourceResource::collection( $sources),
            'message' => 'Sources retrieved successfully.'
        ]);
    }

}
