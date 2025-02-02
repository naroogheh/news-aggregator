<?php
namespace App\Helpers;

use App\Repository\Interfaces\AuthorRepositoryInterface;

class AuthorHelper
{
    private $authorService;

    public function __construct(AuthorRepositoryInterface $authorService)
    {
        $this->authorService = $authorService;
    }

    public function getOrCreateAuthor( string $name)
    {
        if(strlen(trim($name)) < 1) {
            return null;
        }
        $category = $this->authorService->findByName($name);
        if (!$category) {
            $category = $this->authorService->insertItem([
                'name' => $name,
            ]);
        }
        return $category;
    }
}
