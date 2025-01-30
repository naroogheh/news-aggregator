<?php

namespace App\Repository;

use App\Enum\Status;
use App\Models\NewsAgency;
use App\Repository\Interfaces\NewsAgencyRepositoryInterface;

class NewsAgencyRepository implements NewsAgencyRepositoryInterface
{

    function insertItem($params)
    {
        NewsAgency::create($params);
    }

    public function getAll()
    {
        return NewsAgency::where('status', Status::Active->value)->get();
    }

    public function getById($id)
    {
        return NewsAgency::find($id);
    }



}
