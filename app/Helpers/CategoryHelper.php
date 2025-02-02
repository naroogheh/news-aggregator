<?php
namespace App\Helpers;

use App\Enum\Status;
use App\Models\Category;
use App\Repository\Interfaces\CategoryRepositoryInterface;

class CategoryHelper
{
    private $categoryService;

    public function __construct(CategoryRepositoryInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getOrCreateCategory(string $slug, string $title): Category
    {
        $category = $this->categoryService->findBySlug($slug);
        if (!$category) {
            $category = $this->categoryService->insertItem([
                'title' => $title,
                'slug' => $slug,
                'status' => Status::Active->value,
            ]);
        }
        return $category;
    }
}
