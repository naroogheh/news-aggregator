<?php

namespace App\Repository\Interfaces;

interface NewsRepositoryInterface
{
    function insertItem($params);
    public function getAll();
    public function getById(int $id);

}
