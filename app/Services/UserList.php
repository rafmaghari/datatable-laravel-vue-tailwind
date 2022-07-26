<?php


namespace App\Services;

use App\Services\BaseList;
use App\Models\User;

class UserList extends BaseList
{
    const COLUMNS = [
        [
            'field' => 'id',
            'label' => 'ID',
            'sortable' => true,
        ],
        [
            'field' => 'last_name',
            'label' => 'Last Name',
            'sortable' => true,
            'searchable' => true,
            'searchable_field' => 'last_name',
        ],
        [
            'field' => 'first_name',
            'label' => 'First Name',
            'sortable' => true,
            'searchable' => true,
            'searchable_field' => 'first_name',
        ],
        [
            'field' => 'age',
            'label' => 'Age',
            'sortable' => true,
        ],
        [
            'field' => 'hobby',
            'label' => 'Hobby',
            'sortable' => true,
        ],
        [
            'field' => 'options',
            'label' => 'Options',
            'has_slot' => true,
        ],
    ];


    public function process($queries, $columns)
    {
        /** main query */
        $data = User::query();

        /** filter custom only filers */
        $customFilters = $this->customFilters($queries);

        if ($customFilters->isNotEmpty()) {
            $data->where(function ($query) use ($customFilters) {
                if (!empty($customFilters['hobby']['value'])) {
                    $query->where('hobby', $customFilters['hobby']['value']);
                }
            });
        }

        /** filter wildcard only filters */
        $data = $this->wildCardFilter($data, $queries, $columns);

        return $data;
    }
}
