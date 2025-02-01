<?php
namespace App\Helpers;

use App\Repository\Interfaces\CategoryRepositoryInterface;

class CategoryHelper
{
    private static $categoryService;

    public static function setCategoryService(CategoryRepositoryInterface $categoryService)
    {
        self::$categoryService = $categoryService;
    }

    public static function getOrCreateCategory($slug, $name)
    {
        $category = self::$categoryService->findBySlug($slug);
        if (!$category) {
            $category = self::$categoryService->insertItem([
                'name' => $name,
                'slug' => $slug
            ]);
        }
        return $category;
    }
}
