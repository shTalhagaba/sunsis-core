<?php

namespace App\Filters;

use App\Models\IQA\IqaSamplePlan;
use App\Models\LookupManager;
use App\Models\Programmes\Programme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IqaSamplePlanFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewIqaSamplePlan_Filters';

    protected $defaultFilters = [
        'sort_by' => 'iqa_sample_plans.completed_by_date',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'title' => null,
        'verifier_id' => null,
        'programme_id' => null,
        'type' => null,
        'status' => null,
        'assessor_id' => null,
        'learning_aim_qan' => null,
        'learning_aim_title' => null,
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

    public function title($value = '')
    {
        if($value)
        {
            $this->builder->where('title', 'LIKE', '%' . $value . '%');
        }
    }

    public function verifier_id($value = '')
    {
        if($value)
        {
            $this->builder->where('verifier_id', $value);
        }
    }

    public function learning_aim_title($value = '')
    {
        if($value)
        {
            $this->builder->where('learning_aim_title', 'LIKE', '%' . $value . '%');
        }
    }
    
    public function learning_aim_qan($value = '')
    {
        if($value)
        {
            $this->builder->where('learning_aim_qan', $value);
        }
    }

    public function assessor_id($value = '')
    {
        if($value)
        {
            $this->builder->where('assessor_id', $value);
        }
    }

    public function programme_id($value = '')
    {
        if($value)
        {
            $this->builder->where('programme_id', $value);
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

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['iqa_sample_plans_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'iqa_sample_plans.created_at' => 'Plan Creation Date',
            'iqa_sample_plans.completed_by_date' => 'Plan Completed By Date',
            'iqa_sample_plans.type' => 'Plan Type',
            'iqa_sample_plans.status' => 'Plan Status',
        ];

        $planProgrammeIds = IqaSamplePlan::pluck('programme_id')->toArray();
        $programmes = Programme::whereIn('id', $planProgrammeIds);
        $planVerifierIds = IqaSamplePlan::pluck('verifier_id')->toArray();
        if(auth()->user()->isVerifier())
        {
            $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('id', auth()->user()->id)
                ->pluck('name', 'id')
                ->toArray();
        }
        else
        {
            $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->withActiveAccess()
                ->whereIn('id', $planVerifierIds)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        }

        $planAssessorIds = IqaSamplePlan::pluck('assessor_id')->toArray();
        if(auth()->user()->isAssessor())
        {
            $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('id', auth()->user()->id)
                ->pluck('name', 'id')
                ->toArray();
        }
        else
        {
            $assessors = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->withActiveAccess()
                ->whereIn('id', $planAssessorIds)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        }
        
        $verifierLabel = \Form::label('verifier_id', 'Verifier', ['class' => 'control-label']);
        $verifierField = \Form::select('verifier_id', $verifiers, $this->filters()['verifier_id'], ['class' => 'form-control', 'placeholder' => '']);

        $assessorLabel = \Form::label('assessor_id', 'Assessor', ['class' => 'control-label']);
        $assessorField = \Form::select('assessor_id', $assessors, $this->filters()['assessor_id'], ['class' => 'form-control', 'placeholder' => '']);

        $qanLabel = \Form::label('learning_aim_qan', 'Learning Aim QAN', ['class' => 'control-label']);
        $qanField = \Form::text('learning_aim_qan', $this->filters()['learning_aim_qan'], ['class' => 'form-control', 'maxlength' => '12']);

        $qanTitleLabel = \Form::label('learning_aim_title', 'Learning Aim Title', ['class' => 'control-label']);
        $qanTitleField = \Form::text('learning_aim_title', $this->filters()['learning_aim_title'], ['class' => 'form-control', 'maxlength' => '400']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending', ], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        $html = <<<HTML

<div class="row">
    <div class="col-md-3">
        $verifierLabel
        $verifierField
    </div>
    <div class="col-md-3">
        $assessorLabel
        $assessorField
    </div>
    <div class="col-md-3">
        $qanLabel
        $qanField
    </div>
    <div class="col-md-3">
        $qanTitleLabel
        $qanTitleField
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