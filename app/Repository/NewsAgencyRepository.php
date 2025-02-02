<?php

namespace App\Repository;

use App\Enum\Status;
use App\Models\NewsAgency;
use App\Repository\Interfaces\NewsAgencyRepositoryInterface;

class NewsAgencyRepository implements NewsAgencyRepositoryInterface
{

    function insertItem($params)
    {
        $slug = $params['slug'];
        $find = $this->findBySlug($slug);
        if ($find) return false;
        return NewsAgency::create($params);
    }

    public function getAll()
    {
        return NewsAgency::where('status', Status::Active->value)->get();
    }

    public function getById($id)
    {
        return NewsAgency::find($id);
    }

    public function findBySlug(string $slug)
    {
        return NewsAgency::where('slug', $slug)->first();
    }


}
