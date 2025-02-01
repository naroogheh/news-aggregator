<?php
namespace App\Filters;

class PublishDateFilter
{
    public function handle($query, $next, $filters)
    {
        $filters =json_decode($filters, true);
        if (isset($filters['date_from']) && isset($filters['date_to'])) {
            $query->whereBetween('publish_date', [$filters['date_from'], $filters['date_to']]);
        }
        return $next($query);
    }
}
