<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\Tags\Tag;
use Illuminate\Http\Request;

class StudentFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewStudents_Filters';

    protected $defaultFilters = [
        'sort_by' => 'users.firstnames',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'firstnames' => null,
        'surname' => null,
        'gender' => null,
        'ni' => null,
        'uln' => null,
        'email' => null,
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

    public function firstnames($value = '')
    {
        if($value)
        {
            $this->builder->where('firstnames', 'LIKE', $value . '%');
        }
    }

    public function surname($value = '')
    {
        if($value)
        {
            $this->builder->where('surname', 'LIKE', $value . '%');
        }
    }

    public function gender($value = '')
    {
        if($value)
        {
            $this->builder->where('gender', $value);
        }
    }

    public function ni($value = '')
    {
        if($value)
        {
            $this->builder->where('ni', $value);
        }
    }

    public function uln($value = '')
    {
        if($value)
        {
            $this->builder->where('uln', $value);
        }
    }

    public function email($value = '')
    {
        if($value)
        {
            $this->builder->where(function($query) use ($value){
                return $query
                    ->where('email', $value)
                    ->orWhere('primary_email', $value)
                    ->orWhere('secondary_email', $value);
            });
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
        session(['students_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'users.firstnames' => 'First Name',
            'users.surname' => 'Surname',
        ];
        
        $firstnamesLabel = \Form::label('firstnames', 'First Name', ['class' => 'control-label']);
        $firstnamesField = \Form::text('firstnames', $this->filters()['firstnames'], ['class' => 'form-control', 'maxlength' => '70']);

        $surnameLabel = \Form::label('surname', 'Surname', ['class' => 'control-label']);
        $surnameField = \Form::text('surname', $this->filters()['surname'], ['class' => 'form-control', 'maxlength' => '70']);

        $ulnLabel = \Form::label('uln', 'Unique Learner Number', ['class' => 'control-label']);
        $ulnField = \Form::text('uln', $this->filters()['uln'], ['class' => 'form-control', 'maxlength' => '10']);

        $genderLabel = \Form::label('gender', 'Gender', ['class' => 'control-label']);
        $genderField = \Form::select('gender', LookupManager::getGenderDDL(), $this->filters()['gender'], ['class' => 'form-control', 'placeholder' => '']);

        $niLabel = \Form::label('ni', 'National Insurance', ['class' => 'control-label']);
        $niField = \Form::text('ni', $this->filters()['ni'], ['class' => 'form-control', 'maxlength' => '18']);

        $emailLabel = \Form::label('email', 'Email', ['class' => 'control-label']);
        $emailField = \Form::text('email', $this->filters()['email'], ['class' => 'form-control', 'maxlength' => '70']);

        $tagLabel = \Form::label('tag', 'Tag', ['class' => 'control-label']);
        $tagField = \Form::select('tag', Tag::whereType('Student')->orderBy('name')->pluck('name', 'id')->toArray(), $this->filters()['tag'], ['class' => 'form-control', 'placeholder' => '']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending', ], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        $html = <<<HTML

<div class="row">
    <div class="col-md-4">
        $firstnamesLabel
        $firstnamesField
    </div>
    <div class="col-md-4">
        $surnameLabel
        $surnameField
    </div>
    <div class="col-md-4">
        $ulnLabel
        $ulnField
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        $genderLabel
        $genderField
    </div>
    <div class="col-md-3">
        $niLabel
        $niField
    </div>
    <div class="col-md-3">
        $emailLabel
        $emailField
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