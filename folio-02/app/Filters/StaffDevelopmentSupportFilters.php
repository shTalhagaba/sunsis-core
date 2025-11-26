<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\StaffDevelopment\StaffDevelopmentSupport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffDevelopmentSupportFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewStaffDevelopmentSupport_Filters';

    protected $defaultFilters = [
        'sort_by' => 'staff_development_support.provision_date',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'support_type' => null,
        'provision_date' => null,
        'support_to_id' => null,
        'support_from_id' => null,
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

    public function support_type($value = '')
    {
        if($value)
        {
            $this->builder->where('support_type', 'LIKE', '%' . $value . '%');
        }
    }

    public function provision_date($value = '')
    {
        if($value)
        {
            $this->builder->where('provision_date', $value);
        }
    }

    public function support_to_id($value = '')
    {
        if($value || $value == "0")
        {
            $this->builder->where('support_to_id', $value);
        }
    }
    
    public function support_from_id($value = '')
    {
        if($value || $value == "0")
        {
            $this->builder->where('support_from_id', $value);
        }
    }

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['staff_development_support_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'staff_development_support.provision_date' => 'Date',
        ];

        $supportToList = User::orderBy('users.firstnames')
            ->whereIn('users.id', StaffDevelopmentSupport::pluck('support_to_id')->toArray())
            ->join('lookup_user_types', 'users.user_type', '=', 'lookup_user_types.id')
            ->select('users.id', DB::raw('CONCAT(users.firstnames, " ", users.surname, " [", lookup_user_types.description, "]") AS user_detail'))
            ->pluck('user_detail', 'id')
            ->toArray();

        $supportFromList = User::orderBy('users.firstnames')
            ->whereIn('users.id', StaffDevelopmentSupport::pluck('support_from_id')->toArray())
            ->join('lookup_user_types', 'users.user_type', '=', 'lookup_user_types.id')
            ->select('users.id', DB::raw('CONCAT(users.firstnames, " ", users.surname, " [", lookup_user_types.description, "]") AS user_detail'))
            ->pluck('user_detail', 'id')
            ->toArray();

        $supportTypeLabel = \Form::label('support_type', 'Support Type', ['class' => 'control-label']);
        $supportTypeField = \Form::text('support_type', $this->filters()['support_type'], ['class' => 'form-control', 'maxlength' => '50']);

        $provisionDateLabel = \Form::label('provision_date', 'Date', ['class' => 'control-label']);
        $provisionDateField = \Form::date('provision_date', $this->filters()['provision_date'], ['class' => 'form-control']);

        $supportToLabel = \Form::label('support_to_id', 'Support To', ['class' => 'control-label']);
        $supportToField = \Form::select('support_to_id', $supportToList, $this->filters()['support_to_id'], ['class' => 'form-control', 'placeholder' => '']);

        $supportFromLabel = \Form::label('support_from_id', 'Support From', ['class' => 'control-label']);
        $supportFromField = \Form::select('support_from_id', $supportFromList, $this->filters()['support_from_id'], ['class' => 'form-control', 'placeholder' => '']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending', ], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        if(auth()->user()->isAdmin())
        {
            $html = <<<HTML
<div class="row">
    <div class="col-md-3">
        $supportTypeLabel
        $supportTypeField
    </div>
    <div class="col-md-3">
        $provisionDateLabel
        $provisionDateField
    </div>
    <div class="col-md-3">
        $supportToLabel
        $supportToField
    </div>
    <div class="col-md-3">
        $supportFromLabel
        $supportFromField
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
        }
        else
        {
            $html = <<<HTML
<div class="row">
    <div class="col-md-3">
        $supportTypeLabel
        $supportTypeField
    </div>
    <div class="col-md-3">
        $provisionDateLabel
        $provisionDateField
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
        }
        return $html;
    }

}