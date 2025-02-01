<?php

namespace App\Repository;

use App\Models\News;
use App\Repository\Interfaces\NewsRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewsRepository implements NewsRepositoryInterface
{


    function insertItem($params)
    {
        return News::create($params);
    }

    function batchInsert($items)
    {
        try {
            DB::table('news')->insert($items);
            return true;
        } catch (QueryException $e) {
            // مدیریت خطا
            Log::error('ERRORR ON NewsRepository batchInsert', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getAll()
    {
        return News::all();
    }

    public function getById($id)
    {
        return News::find($id);
    }



}
