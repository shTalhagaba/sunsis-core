<?php

namespace App\Filters;

use App\Models\LookupManager;
use Illuminate\Http\Request;

class FSCourseFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewFSCourse_Filters';

    protected $defaultFilters = [
        'sort_by' => 'fs_courses.updated_at',
        'direction' => 'DESC',
        'per_page' => '50',
    ];

    protected $viewFilters = [
        'keyword' => null,
        'fs_type' => null,
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
            $this->builder->where('title', 'LIKE', '%' . $value . '%');
        }
    }

    public function fs_type($value = '')
    {
        if($value)
        {
            $this->builder->where('fs_type', $value);
        }
    }

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['fs_courses_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'fs_courses.updated_at' => 'Most Recent Update Date',
            'fs_courses.created_at' => 'Creation Date',
            'fs_courses.fs_type' => 'FS Type',
        ];
        
        $keywordLabel = \Form::label('keyword', 'Keyword', ['class' => 'control-label']);
        $keywordField = \Form::text('keyword', $this->filters()['keyword'], ['class' => 'form-control', 'maxlength' => '150']);

        $typeLabel = \Form::label('fs_type', 'FS Type', ['class' => 'control-label']);
        $typeField = \Form::select('fs_type', ['Maths' => 'Maths', 'English' => 'English', 'ICT' => 'ICT'], $this->filters()['fs_type'], ['class' => 'form-control', 'placeholder' => '']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending', ], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        $html = <<<HTML

<div class="row">
    <div class="col-md-4">
        $keywordLabel
        $keywordField
    </div>
    <div class="col-md-4">
        $typeLabel
        $typeField
    </div>
    <div class="col-md-4">
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        $sortByLabel
        $sortByField
    </div>
    <div class="col-md-4">
        $sortDirectionLabel
        $sortDirectionField
    </div>
    <div class="col-md-4">
        $perPageLabel
        $perPageField
    </div>
</div>

HTML;



        return $html;
    }

}