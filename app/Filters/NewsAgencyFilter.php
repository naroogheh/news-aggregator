<?php
namespace App\Filters;

class NewsAgencyFilter
{
    public function handle($query, $next, $filters)
    {
        $filters =json_decode($filters, true);
        if (isset($filters['news_agency_id'])) {
            $query->where('news_agency_id', $filters['news_agency_id']);
        }
        return $next($query);
    }
}
