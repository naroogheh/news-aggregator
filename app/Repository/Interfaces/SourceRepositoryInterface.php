<?php

namespace App\Repository\Interfaces;

interface SourceRepositoryInterface
{
    function insertItem($params);
    public function getAll();
    public function getById(int $id);
    function changeStatus(int $id, int $status);

}
