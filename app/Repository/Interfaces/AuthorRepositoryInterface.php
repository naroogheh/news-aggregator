<?php

namespace App\Repository\Interfaces;

interface AuthorRepositoryInterface
{
    function insertItem($params);
    public function getAll();
    public function getById(int $id);
    function findByName($name);

}
