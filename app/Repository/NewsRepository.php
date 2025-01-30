<?php

namespace App\Repository;

use App\Enum\Status;
use App\Models\Source;
use App\Repository\Interfaces\NewsRepositoryInterface;
use App\Repository\Interfaces\SourceRepositoryInterface;

class NewsRepository implements NewsRepositoryInterface
{



    function insertItem($params)
    {
        Source::create($params);
    }

    public function getAll()
    {
        return Source::where('status', Status::Active->value)->get();
    }

    public function getById($id)
    {
        return Source::find($id);
    }



}
