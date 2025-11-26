<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\Qualifications\Qualification;
use App\Models\Tags\Tag;
use Illuminate\Http\Request;

class QualificationFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewQualification_Filters';

    protected $defaultFilters = [
        'sort_by' => 'qualifications.title',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'keyword' => null,
        'level' => null,
        'ssa' => null,
        'type' => null,
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
            $this->builder->where(function($query) use ($value) {
                $query->where('title', 'LIKE', '%' . $value . '%')
                    ->orWhere('qan', 'LIKE', '%' . str_replace('/', '', $value) . '%');
            });
        }
    }
    
    public function level($value = '')
    {
        if($value)
        {
            $this->builder->where('level', $value);
        }
    }

    public function ssa($value = '')
    {
        if($value)
        {
            $this->builder->where('ssa', $value);
        }
    }

    public function type($value = '')
    {
        if($value)
        {
            $this->builder->where('type', $value);
        }
    }

    public function status($value = '')
    {
        if($value)
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
        session(['qualifications_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'qualifications.title' => 'Qualification Title',
            'qualifications.qan' => 'Qualification Number',
            'qualifications.level' => 'level',
        ];
        
        $keywordLabel = \Form::label('keyword', 'Keyword/Number', ['class' => 'control-label']);
        $keywordField = \Form::text('keyword', $this->filters()['keyword'], ['class' => 'form-control', 'maxlength' => '150']);

        $levelLabel = \Form::label('level', 'Level', ['class' => 'control-label']);
        $levelField = \Form::select('level', Qualification::getQualificationLevels(), $this->filters()['level'], ['class' => 'form-control']);

        $ssaLabel = \Form::label('ssa', 'Sector Subject Area', ['class' => 'control-label']);
        $ssaField = \Form::select('ssa', Qualification::getQualificationSSA(), $this->filters()['ssa'], ['class' => 'form-control']);

        $typeLabel = \Form::label('type', 'Type', ['class' => 'control-label']);
        $typeField = \Form::select('type', Qualification::getQualificationTypes(), $this->filters()['type'], ['class' => 'form-control']);

        $statusLabel = \Form::label('status', 'Status', ['class' => 'control-label']);
        $statusField = \Form::select('status', Qualification::getQualificationStatus(), $this->filters()['status'], ['class' => 'form-control']);

        $tagLabel = \Form::label('tag', 'Tag', ['class' => 'control-label']);
        $tagField = \Form::select('tag', Tag::whereType('Qualification')->orderBy('name')->pluck('name', 'id')->toArray(), $this->filters()['tag'], ['class' => 'form-control', 'placeholder' => '']);

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
        $levelLabel
        $levelField
    </div>
    <div class="col-md-4">
        $tagLabel
        $tagField
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        $ssaLabel
        $ssaField
    </div>
    <div class="col-md-4">
        $typeLabel
        $typeField
    </div>
    <div class="col-md-4">
        $statusLabel
        $statusField
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