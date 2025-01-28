<?php

namespace App\Service;

use App\Repository\Interfaces\SourceRepositoryInterface;

class SourceService
{
    private $sourceRepository;


    function __construct(SourceRepositoryInterface $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    function insertItem($params)
    {
        return $this->sourceRepository->insertItem($params);
    }

    public function getAll()
    {
        return $this->sourceRepository->getAll();
    }

    public function getById($id)
    {
        return $this->sourceRepository->getById($id);
    }

    public function changeStatus(int $id, int $status){
        return $this->sourceRepository->changeStatus($id, $status);
    }

}
