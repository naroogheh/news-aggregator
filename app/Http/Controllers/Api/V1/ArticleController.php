<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ArticleFilterRequest;
use App\Http\Resources\Api\ArticleResource;
use App\Service\NewsService;

class ArticleController extends Controller
{

    private $newsService;
    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function filter(ArticleFilterRequest $request)
    {
        $filters = $request->validated();
        $items = $this->newsService->filter($filters);
    
        $totalItems = count($items);
        $page = $filters['page'] ?? 1;
        $pageSize = $filters['page_size'] ?? 10;
        $totalPages = ceil($totalItems / $pageSize);
    
        return response()->json([
            'status' => true,
            'data' => [
                'items' => ArticleResource::collection($items),
                'total' => $totalItems,
                'page' => $page,
                'page_size' => $pageSize,
                'total_pages' => $totalPages,
            ],
            'message' => 'Successfully fetched data',
        ]);
    }
}
