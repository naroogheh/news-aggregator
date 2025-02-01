<?php

namespace App\Service;

use App\Repository\Interfaces\NewsAgencyRepositoryInterface;

class NewsAgencyService
{
    private $newsAgencyRepository;


    function __construct(NewsAgencyRepositoryInterface $newsAgencyRepository)
    {
        $this->newsAgencyRepository = $newsAgencyRepository;
    }

    function insertItem($params)
    {
        return $this->newsAgencyRepository->insertItem($params);
    }

    public function getAll()
    {
        return $this->newsAgencyRepository->getAll();
    }

    public function getById($id)
    {
        return $this->newsAgencyRepository->getById($id);
    }

    public function findBySlug($slug)
    {
        return $this->newsAgencyRepository->findBySlug($slug);
    }

}
