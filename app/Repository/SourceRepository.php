<?php

namespace App\Repository;

use App\Enum\Status;
use App\Models\Source;
use App\Repository\Interfaces\SourceRepositoryInterface;

class SourceRepository implements SourceRepositoryInterface
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

    public function changeStatus(int $id, int $status)
    {
        $find = Source::find($id);
        if (!$find) {
            return false;
        }

        Source::where('id', $id)->update(
            [
                'status' => $status
            ]
        );
        return true;

    }


}
