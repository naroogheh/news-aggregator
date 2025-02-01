<?php
namespace App\Filters;

class SourceFilter
{
    public function handle($query, $next, $filters)
    {
        $filters =json_decode($filters, true);
        if (isset($filters['source_id'])) {
            $query->where('source_id', $filters['source_id']);
        }
        return $next($query);
    }
}
