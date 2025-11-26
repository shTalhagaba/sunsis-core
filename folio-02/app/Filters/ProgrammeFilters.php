<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\Lookups\ProgrammeTypeLookup;
use App\Models\Tags\Tag;
use Illuminate\Http\Request;

class ProgrammeFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewProgrammes_Filters';

    protected $defaultFilters = [
        'sort_by' => 'programmes.title',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'keyword' => null,
        'programme_type' => null,
        'status' => null,
        'tag' => null,
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

    public function programme_type($value = '')
    {
        if($value)
        {
            $this->builder->where('programme_type', $value);
        }
    }

    public function status($value = '')
    {
        if($value || $value == "0")
        {
            $this->builder->where('status', $value);
        }
    }

    public function tag($value = '')
    {
        if($value)
        {
            $this->builder->whereHas('tags', function($tag) use ($value){
                return $tag->where('id', $value);
            });
        }
    }

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['programmes_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'programmes.title' => 'Title',
            'programmes.programme_type' => 'Type',
        ];
        
        $keywordLabel = \Form::label('keyword', 'Keyword/Number', ['class' => 'control-label']);
        $keywordField = \Form::text('keyword', $this->filters()['keyword'], ['class' => 'form-control', 'maxlength' => '150']);

        $typeLabel = \Form::label('programme_type', 'Programme Type', ['class' => 'control-label']);
        $typeField = \Form::select('programme_type', ProgrammeTypeLookup::getSelectData(), $this->filters()['programme_type'], ['class' => 'form-control', 'placeholder' => '']);

        $statusLabel = \Form::label('status', 'Status', ['class' => 'control-label']);
        $statusField = \Form::select('status', ['1' => 'Active', '0' => 'Not Active'], $this->filters()['status'], ['class' => 'form-control']);

        $tagLabel = \Form::label('tag', 'Tag', ['class' => 'control-label']);
        $tagField = \Form::select('tag', Tag::whereType('Programme')->orderBy('name')->pluck('name', 'id')->toArray(), $this->filters()['tag'], ['class' => 'form-control', 'placeholder' => '']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending', ], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        $html = <<<HTML

<div class="row">
    <div class="col-md-3">
        $keywordLabel
        $keywordField
    </div>
    <div class="col-md-3">
        $typeLabel
        $typeField
    </div>
    <div class="col-md-3">
        $statusLabel
        $statusField
    </div>
    <div class="col-md-3">
        $tagLabel
        $tagField
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