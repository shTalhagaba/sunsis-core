<?php

namespace App\Filters;

use Illuminate\Http\Request;

class LearningResourceFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'LearningResource_Filters';

    protected $defaultFilters = [
        'sort_by' => 'learning_resources.likes',
        'direction' => 'DESC',
        'per_page' => '50',
    ];

    protected $viewFilters = [
        'keyword' => null,
        'is_featured' => null,
        'resource_type' => null,
        'tags' => null,
    ];

    protected function getDefaultFilters()
    {
        return $this->defaultFilters;
    }

    protected function getViewFilters()
    {
        return $this->viewFilters;
    }

    public function getFilterKey()
    {
        return $this->filterKey;
    }

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function keyword($value = '')
    {
        if($value)
        {
            $this->builder->where(function ($query) use ($value){
                return $query
                    ->where('resource_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('resource_short_description', 'LIKE', '%' . $value . '%');
            });
        }
    }

    public function resource_type($value = '')
    {
        if($value)
        {
            $this->builder->where('resource_type', $value);
        }
    }

    public function is_featured($value = '')
    {
        if($value != '' )
        {
            $this->builder->where('is_featured', $value);
        }
    }

    public function tags($values)
    {
        if(is_array($values) && count($values) > 0)
        {
            $this->builder->whereHas('tags', function($tag) use ($values){
                return $tag->whereIn('id', $values);
            });
        }
    }

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'DESC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['resources_per_page' => $value]);
    }

    public function render()
    {

    }

}