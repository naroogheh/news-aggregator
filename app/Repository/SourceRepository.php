<?php

namespace App\Repository;

use App\Enum\Status;
use App\Models\Source;
use App\Repository\Interfaces\SourceRepositoryInterface;

class SourceRepository implements SourceRepositoryInterface
{



    function insertItem($params)
    {
        $slug = $params['slug'];
        $find = $this->findBySlug($slug);
        if ($find) return false;
        return Source::create($params);
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

    function updateLastSyncTime($id,$time)
    {
        $find = $this->getById($id);
        if (!$find) return false;
        $find->last_sync_time = $time;
        $find->save();
        return true;
    }

    private function findBySlug($slug)
    {
        return Source::where('slug', $slug)->first();
    }


}
