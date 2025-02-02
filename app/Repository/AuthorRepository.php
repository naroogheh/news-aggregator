<?php

namespace App\Repository;

use App\Models\Author;
use App\Repository\Interfaces\AuthorRepositoryInterface;
use Illuminate\Support\Facades\Log;


class AuthorRepository implements AuthorRepositoryInterface
{

    function insertItem($params)
    {
        try {
            $model = new Author();
            $model->name = $params['name'];
            $model->save();
            return $model;
        }
        catch (\Exception $e) {
            Log::error('Error On AuthorRepository insertItem =>'.$e->getMessage());
            return false;
        }
    }

    public function getAll()
    {
        return Author::all();
    }

    public function getById($id)
    {
        return Author::find($id);
    }

    function findByName($name)
    {
        return Author::where('name', $name)->first();
    }


}
