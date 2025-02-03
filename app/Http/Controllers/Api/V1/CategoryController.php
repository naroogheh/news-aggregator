<?php

namespace App\Http\Controllers\Api\V1;

use App\Enum\CacheKeys;
use App\Http\Resources\Api\CategoryResource;
use App\Repository\Interfaces\CategoryRepositoryInterface;

class CategoryController
{
    private $categoryRepository;
    function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        // check cache
        $cache_is_exist = cache()->has(CacheKeys::KEY_CATEGOORIES->value);
        if ($cache_is_exist) {
            $categories = cache()->get(CacheKeys::KEY_CATEGOORIES->value);
        }
        else {
            $categories = $this->categoryRepository->getAll();
            // update cache
            cache()->put(CacheKeys::KEY_CATEGOORIES->value, $categories, 60 * 60 * 24);
        }
        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection( $categories),
            'message' => 'categories retrieved successfully.'
        ]);
    }

}
