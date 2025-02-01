<?php
namespace App\Helpers;

use App\Enum\Status;
use App\Models\Category;
use App\Repository\Interfaces\CategoryRepositoryInterface;
use Exception;

class CategoryHelper
{
    private $categoryService;

    public function __construct(CategoryRepositoryInterface $categoryService)
    {
        if (is_null($categoryService)) {
            throw new Exception("CategoryRepositoryInterface به درستی تزریق نشده است.");
        }
        $this->categoryService = $categoryService;
    }

    public function getOrCreateCategory(string $slug, string $name): Category
    {
        $category = $this->categoryService->findBySlug($slug);
        if (!$category) {
            $category = $this->categoryService->insertItem([
                'name' => $name,
                'slug' => $slug,
                'status' => Status::Active->value,
            ]);
        }
        return $category;
    }
}
