<?php

namespace App\Repository;

use App\Enum\Status;
use App\Models\Source;
use App\Repository\Interfaces\SourceRepositoryInterface;

class SourceRepository implements SourceRepositoryInterface
{
    private $model ;

    function __construct(Source $model)
    {
        $this->model = $model;
    }
    public function getAll()
    {
        return $this->model->where('status', Status::Active->value)->get();
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function changeStatus(int $id, int $status){
        $find = $this->model->find($id);
        if (!$find) {
            return false;
        }

            $this->model->where('id', $id)->update(
                [
                    'status' => $status
                ]
            );
            return true;

    }

}
