<?php

namespace App\Repository;

use App\Filters\CategoryFilter;
use App\Filters\NewsAgencyFilter;
use App\Filters\PublishDateFilter;
use App\Filters\SourceFilter;
use App\Models\News;
use App\Repository\Interfaces\NewsRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Routing\Pipeline;
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

    function filter($filters)
    {
        $query = News::query();
        $query->with(['category', 'newsAgency', 'source']);
        $page = $filters['page'] ?? 1;
        $perPage = $filters['perPage'] ?? 20;
        $data = json_encode($filters);
        $results = app(Pipeline::class)
            ->send($query)
            ->through([
                PublishDateFilter::class . ':' . $data,
                NewsAgencyFilter::class . ':' . $data,
                CategoryFilter::class . ':' . $data,
                SourceFilter::class . ':' . $data,
                // add more filters here
            ])
            ->thenReturn();
        return $results->paginate($perPage, ['*'], 'page', $page);
    }



}
