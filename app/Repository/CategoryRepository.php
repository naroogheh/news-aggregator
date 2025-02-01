<?php

namespace App\Repository;

use App\Models\Category;
use App\Repository\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements categoryRepositoryInterface
{


    function insertItem($params)
    {
        $model = new Category();
        $model->fill($params);
        $model->save();
        return $model;
    }

    public function getAll()
    {
        return category::all();
    }

    public function getById(int $id)
    {
        return Category::find($id);
    }

    public function findBySlug($slug)
    {
        return Category::where('slug', $slug)->first();
    }
}
