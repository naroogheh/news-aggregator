<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ArticleFilterRequest;
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
        return response()->json(
            $items
        );
    }
}
