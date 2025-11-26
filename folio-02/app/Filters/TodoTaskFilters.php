<?php

namespace App\Filters;

use App\Models\LookupManager;
use Illuminate\Http\Request;

class TodoTaskFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewTodoTasks_Filters';

    protected $defaultFilters = [
        'sort_by' => 'todo_tasks.updated_at',
        'direction' => 'DESC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'keyword' => null,
        'completed' => null,
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
            $this->builder->where(function($query) use ($value) {
                $query->where('title', 'LIKE', '%' . $value . '%')
                    ->orWhere('description', 'LIKE', '%' . $value . '%');
            });
        }
    }

    public function completed($value = '')
    {
        if($value || $value == "0")
        {
            $this->builder->where('completed', $value);
        }
    }

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['todo_tasks_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'todo_tasks.updated_at' => 'Updated At',
            'todo_tasks.title' => 'Title',
            'todo_tasks.created_at' => 'Created At',
        ];
        
        $keywordLabel = \Form::label('keyword', 'Keyword/Number', ['class' => 'control-label']);
        $keywordField = \Form::text('keyword', $this->filters()['keyword'], ['class' => 'form-control', 'maxlength' => '150']);

        $completedLabel = \Form::label('completed', 'Completed', ['class' => 'control-label']);
        $completedField = \Form::select('completed', ['1' => 'Completed', '0' => 'Not Completed'], $this->filters()['completed'], ['class' => 'form-control', 'placeholder' => '']);

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
    <div class="col-md-2">
        $completedLabel
        $completedField
    </div>
    <div class="col-md-2">
        $sortByLabel
        $sortByField
    </div>
    <div class="col-md-2">
        $sortDirectionLabel
        $sortDirectionField
    </div>
    <div class="col-md-2">
        $perPageLabel
        $perPageField
    </div>
</div>

HTML;



        return $html;
    }

}