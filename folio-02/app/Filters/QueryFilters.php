<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilters
{
    protected $request;
    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    abstract protected function getDefaultFilters();
    abstract protected function getViewFilters();
    abstract protected function getFilterKey();

    public function apply(Builder $builder)
    {

        $this->builder = $builder;

        foreach ($this->filters() as $filter => $value) {
            $value = $value ?? $this->getDefaultFilters()[$filter] ?? null;

            if (! method_exists($this, $filter)) {
                continue;
            }

            $this->$filter($value);
        }

        return $this->builder;
    }

    public function filters()
    {
        $allFilters = array_merge($this->getDefaultFilters(), $this->getViewFilters());

        $filters = session()->get($this->getFilterKey(), $allFilters);

        if ($this->request->has('_reset') && $this->request->_reset == '2') {
            $filters = $allFilters;
        }

        foreach ($this->request->all() as $key => $value) {
            if (array_key_exists($key, $filters)) {
                $filters[$key] = $value;
            }
        }

        if ($this->request->has('_reset') && $this->request->_reset == '1') {
            $filters = $allFilters;
        }

        session()->put($this->getFilterKey(), $filters);

        return $filters;
    }
}
