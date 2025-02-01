<?php

namespace App\Repository\Interfaces;

interface CategoryMapRepositoryInterface
{
    function insertItem($params);
    public function getAll();
    public function getById(int $id);


}
