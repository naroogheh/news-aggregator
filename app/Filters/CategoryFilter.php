<?php
namespace App\Filters;

class CategoryFilter
{
    public function handle($query, $next, $filters)
    {
        $filters =json_decode($filters, true);
        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        return $next($query);
    }
}
