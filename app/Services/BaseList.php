<?php


namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BaseList
{
    protected function wildCardFilter($data, $queries, $columns)
    {

        /** filter wildcard only filters */
        $wildCardFilters = collect($queries)->filter(function ($query) {
            return !empty($query['value']) && $query['operator'] === '%';
        });

        if ($wildCardFilters->isNotEmpty()) {
            $data->where(function ($query) use ($columns, $wildCardFilters) {
                foreach ($columns as $key => $column) {
                    #only set searchable can be searched
                    if (empty($column['searchable'])) {
                        continue;
                    }
                    #loop all through the wildcard searching
                    #accepting array and string
                    foreach ($wildCardFilters as $wildCardFilter) {
                        $keyword = $wildCardFilter['value'];
                        $searchableField = $column['searchable_field'];
                        if (empty($column['raw'])) {
                            $query->orWhere($searchableField, 'like', "%$keyword%");
                        } else {
                            $query->orWhere(DB::raw("$searchableField"), 'like', "%$keyword%");
                        }
                    }
                }
            });
        }

        return $data;
    }

    protected function customFilters($queries): Collection
    {
        /** filter non-wildcard filters */
        return collect($queries)->filter(function ($query) {
            return !empty($query['value']) && $query['operator'] === '=';
        });
    }

    /** make sure that query as unique id (not ambiguous) */
    public static function selectAll($query)
    {
        return $query->pluck('id');
    }

    /** paginate and sort data */
    public static function paginateData($query, $request)
    {
        return $query->orderBy($request->sort_field, $request->sort_direction)->paginate($request->paginate);
    }
}
