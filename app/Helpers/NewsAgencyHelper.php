<?php
namespace App\Helpers;

use App\Enum\Status;
use App\Models\Category;
use App\Models\NewsAgency;
use App\Repository\Interfaces\CategoryRepositoryInterface;
use App\Repository\Interfaces\NewsAgencyRepositoryInterface;
use Exception;

class NewsAgencyHelper
{
    private $newsAgencyService;

    public function __construct(NewsAgencyRepositoryInterface $newsAgencyService)
    {
        $this->newsAgencyService = $newsAgencyService;
    }

    public function getOrCreateAgency(string $slug, string $title, string $category): NewsAgency
    {
        $agency = $this->newsAgencyService->findBySlug($slug);
        if (!$agency) {
            $agency = $this->newsAgencyService->insertItem([
                'title' => $title,
                'slug' => $slug,
                'category' => $category,
            ]);
        }
        return $agency;
    }
}
