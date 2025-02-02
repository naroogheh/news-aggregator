<?php namespace App\Helpers;

use App\Repository\Interfaces\AuthorRepositoryInterface;
use Illuminate\Support\Facades\Log;

class AuthorHelper
{
    private $authorService;

    public function __construct(AuthorRepositoryInterface $authorService)
    {
        $this->authorService = $authorService;
    }

    public function getOrCreateAuthor(string $name)
    {
        $name = trim($name);
        if (strlen($name) < 1) {
            return null;
        }

        // Find author by name
        $author = $this->authorService->findByName($name);

        // If not found, create a new author
        if (!$author) {
            try {
                $author = $this->authorService->insertItem([
                    'name' => $name,
                ]);
            } catch (\Exception $e) {
                Log::error(' Error on AuthorHelper , getOrCreateAuthor '. $e->getMessage());
                return null;
            }
        }

        return $author;
    }
}
