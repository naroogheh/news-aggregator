<?php

namespace App\Repository\Interfaces;

interface SourceRepositoryInterface
{
    public function getAll();
    public function getById(int $id);
     function changeStatus(int $id, int $status);

}
