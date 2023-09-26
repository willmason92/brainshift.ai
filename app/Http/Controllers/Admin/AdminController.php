<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class AdminController extends Controller
{
    /**
     * Applies pagination limit to the collection
     *
     * @param $data
     * @param $limit
     * @return mixed
     */
    protected function applyPagination($data, $limit)
    {
        return $data->limit($limit);
    }

    /**
     * Applies filter parameters to the collection
     *
     * @param $data
     * @param $searchParams
     * @return array
     */
    protected function applyFilters($data, $searchParams)
    {
        $conditions = [];

        foreach ($searchParams as $key => $value) {
            if (! empty($value)) {
                $conditions[] = $data->where($key, 'like', '%'.$value.'%');
            }
        }

        return $conditions;
    }

    /**
     * Applies sort to the collection
     *
     * @param $data
     * @param $sortBy
     * @param $sortOrder
     * @return mixed
     */
    protected function applySort($data, $sortBy, $sortOrder)
    {
        return $data->orderBy($sortBy, $sortOrder);
    }

    protected function getDataType(string $type)
    {
        $type = "\App\Models\{$type}";

        return new $type();
    }
}
