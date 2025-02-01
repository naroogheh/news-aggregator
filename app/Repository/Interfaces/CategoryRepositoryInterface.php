<?php

namespace App\Repository\Interfaces;

interface CategoryRepositoryInterface
{
    function insertItem($params);
    public function getAll();
    public function getById(int $id);


}
