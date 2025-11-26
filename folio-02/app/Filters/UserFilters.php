<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\User;
use Illuminate\Http\Request;

class UserFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewUsers_Filters';

    protected $defaultFilters = [
        'sort_by' => 'users.firstnames',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'firstnames' => null,
        'surname' => null,
        'user_type' => null,
        'gender' => null,
        'ni' => null,
        'email' => null,
        'web_access' => 1,
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

    public function user_type($value = '')
    {
        if($value)
        {
            $this->builder->where('user_type', $value);
        }
    }

    public function web_access($value = '')
    {
        if($value != '')
        {
            $this->builder->where('web_access', $value);
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

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['users_per_page' => $value]);
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

        $userTypeLabel = \Form::label('user_type', 'User Type', ['class' => 'control-label']);
        $userTypeField = \Form::select('user_type', LookupManager::getUserTypes(), $this->filters()['user_type'], ['class' => 'form-control', 'placeholder' => '']);

        $webAccessLabel = \Form::label('web_access', 'Web Access', ['class' => 'control-label']);
        $webAccessField = \Form::select('web_access', [0 => 'Disabled', 1 => 'Enabled'], $this->filters()['web_access'], ['class' => 'form-control', 'placeholder' => '']);

        $niLabel = \Form::label('ni', 'National Insurance', ['class' => 'control-label']);
        $niField = \Form::text('ni', $this->filters()['ni'], ['class' => 'form-control', 'maxlength' => '18']);

        $emailLabel = \Form::label('email', 'Email', ['class' => 'control-label']);
        $emailField = \Form::text('email', $this->filters()['email'], ['class' => 'form-control', 'maxlength' => '70']);

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
        $userTypeLabel
        $userTypeField
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        $webAccessLabel
        $webAccessField
    </div>
    <div class="col-md-4">
        $niLabel
        $niField
    </div>
    <div class="col-md-4">
        $emailLabel
        $emailField
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