<?php

namespace App\Repository;

use App\Models\Author;
use App\Repository\Interfaces\AuthorRepositoryInterface;


class AuthorRepository implements AuthorRepositoryInterface
{

    function insertItem($params)
    {
        Author::create($params);
    }

    public function getAll()
    {
        return Author::all();
    }

    public function getById($id)
    {
        return Author::find($id);
    }



}
