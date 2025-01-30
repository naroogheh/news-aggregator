<?php

namespace App\Repository\Interfaces;

interface NewsAgencyRepositoryInterface
{
    function insertItem($params);
    public function getAll();
    public function getById(int $id);


}
