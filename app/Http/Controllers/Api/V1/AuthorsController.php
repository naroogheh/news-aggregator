<?php

namespace App\Http\Controllers\Api\V1;

use App\Enum\CacheKeys;
use App\Http\Resources\Api\AuthorResource;
use App\Repository\AuthorRepository;

class AuthorsController
{
    private $authorRepository;
    function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function index()
    {
        // check cache
        $cache_is_exist = cache()->has(CacheKeys::KEY_AUTHORS->value);
        if ($cache_is_exist) {
            $authors = cache()->get(CacheKeys::KEY_AUTHORS->value);
        }
        else {
            $authors = $this->authorRepository->getAll();
            // update cache
            cache()->put(CacheKeys::KEY_AUTHORS->value, $authors, 60 * 60 * 24);
        }
        return response()->json([
            'success' => true,
            'data' => AuthorResource::collection( $authors),
            'message' => 'authors retrieved successfully.'
        ]);
    }

}
