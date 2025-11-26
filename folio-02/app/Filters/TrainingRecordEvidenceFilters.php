<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\Lookups\TrainingStatusLookup;
use Illuminate\Http\Request;

class TrainingRecordEvidenceFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewTrainingRecordEvidences_Filters';

    protected $defaultFilters = [
        'sort_by' => 'students.firstnames',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'learner_ref' => null,
        'uln' => null,
        'firstnames' => null,
        'surname' => null,
        'to_start_date' => null,
        'to_planned_end_date' => null,
        'from_start_date' => null,
        'from_planned_end_date' => null,
        'training_status' => TrainingStatusLookup::STATUS_CONTINUING,
        'evidence_status' => null,
        'primary_assessor' => null,
        'secondary_assessor' => null,
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

    public function learner_ref($value = '')
    {
        if($value)
        {
            $this->builder->where('tr.learner_ref', '=', $value);
        }
    }

    public function from_start_date($value = '')
    {
        if($value)
        {
            $this->builder->whereDate('tr.start_date', '>=', $value);
        }
    }

    public function to_start_date($value = '')
    {
        if($value)
        {
            $this->builder->whereDate('tr.start_date', '<=', $value);
        }
    }

    public function from_planned_end_date($value = '')
    {
        if($value)
        {
            $this->builder->whereDate('tr.planned_end_date', '>=', $value);
        }
    }

    public function to_planned_end_date($value = '')
    {
        if($value)
        {
            $this->builder->whereDate('tr.planned_end_date', '<=', $value);
        }
    }

    public function firstnames($value = '')
    {
        if($value)
        {
            $this->builder->where('students.firstnames', 'LIKE', '%' . $value . '%');
        }
    }

    public function surname($value = '')
    {
        if($value)
        {
            $this->builder->where('students.surname', 'LIKE', '%' . $value . '%');
        }
    }

    public function uln($value = '')
    {
        if($value)
        {
            $this->builder->where('students.uln', '=', $value);
        }
    }    

    public function training_status($value = '')
    {
        if($value)
        {
            $this->builder->where('tr.status_code', '=', $value);
        }
    }

    public function evidence_status($value = '')
    {
        if($value)
        {
            $this->builder->where('tr_evidences.evidence_status', '=', $value);
        }
    }

    public function primary_assessor($value = '')
    {
        if($value)
        {
            $this->builder->where('tr.primary_assessor', '=', $value);
        }
    }

    public function secondary_assessor($value = '')
    {
        if($value)
        {
            $this->builder->where('tr.secondary_assessor', '=', $value);
        }
    }

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['evidences_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'students.firstnames' => 'First Name',
            'students.surname' => 'Surname',
            'tr.start_date' => 'Training Start Date',
            'tr.planned_end_date' => 'Training Planned End Date',
            'tr_evidences.created_at' => 'Evidence Submitted Date',
            'latest_evidence_assessments.created_at' => 'Evidence Assessed Date',
        ];

        $trainingStatus = LookupManager::getTrainingRecordStatus();
        $assessors = LookupManager::getAssessors();

        $refLabel = \Form::label('learner_ref', 'Learner Reference', ['class' => 'control-label']);
        $refField = \Form::text('learner_ref', $this->filters()['learner_ref'], ['class' => 'form-control', 'maxlength' => '12']);

        $ulnLabel = \Form::label('uln', 'Unique Learner Number', ['class' => 'control-label']);
        $ulnField = \Form::text('uln', $this->filters()['uln'], ['class' => 'form-control', 'maxlength' => '10']);

        $firstnamesLabel = \Form::label('firstnames', 'First Name', ['class' => 'control-label']);
        $firstnamesField = \Form::text('firstnames', $this->filters()['firstnames'], ['class' => 'form-control', 'maxlength' => '70']);

        $surnameLabel = \Form::label('surname', 'Surname', ['class' => 'control-label']);
        $surnameField = \Form::text('surname', $this->filters()['surname'], ['class' => 'form-control', 'maxlength' => '70']);

        $fromStartDateLabel = \Form::label('from_start_date', 'From Start Date', ['class' => 'control-label']);
        $fromStartDateField = \Form::date('from_start_date', $this->filters()['from_start_date'], ['class' => 'form-control']);

        $toStartDateLabel = \Form::label('to_start_date', 'To Start Date', ['class' => 'control-label']);
        $toStartDateField = \Form::date('to_start_date', $this->filters()['to_start_date'], ['class' => 'form-control']);

        $fromPlannedEndDateLabel = \Form::label('from_planned_end_date', 'From Planned End Date', ['class' => 'control-label']);
        $fromPlannedEndDateField = \Form::date('from_planned_end_date', $this->filters()['from_planned_end_date'], ['class' => 'form-control']);

        $toPlannedEndDateLabel = \Form::label('to_planned_end_date', 'To Planned End Date', ['class' => 'control-label']);
        $toPlannedEndDateField = \Form::date('to_planned_end_date', $this->filters()['to_planned_end_date'], ['class' => 'form-control']);

        $trainingStatusLabel = \Form::label('training_status', 'Training Status', ['class' => 'control-label']);
        $trainingStatusField = \Form::select('training_status', $trainingStatus, $this->filters()['training_status'] ?? TrainingStatusLookup::STATUS_CONTINUING, ['class' => 'form-control', 'placeholder' => '']);

        $evidenceStatusLabel = \Form::label('evidence_status', 'Training Evidence Status', ['class' => 'control-label']);
        $evidenceStatusField = \Form::select('evidence_status', LookupManager::getTrainingEvidenceStatusList(), $this->filters()['evidence_status'], ['class' => 'form-control', 'placeholder' => '']);

        $primaryAssessorLabel = \Form::label('primary_assessor', 'Primary Assessor', ['class' => 'control-label']);
        $primaryAssessorField = \Form::select('primary_assessor', $assessors, $this->filters()['primary_assessor'], ['class' => 'form-control', 'placeholder' => '']);

        $secondaryAssessorLabel = \Form::label('secondary_assessor', 'Secondary Assessor', ['class' => 'control-label']);
        $secondaryAssessorField = \Form::select('secondary_assessor', $assessors, $this->filters()['secondary_assessor'], ['class' => 'form-control', 'placeholder' => '']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending', ], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        $html = <<<HTML

<div class="row">
    <div class="col-md-3">
        $trainingStatusLabel
        $trainingStatusField
    </div>
    <div class="col-md-3">
        $evidenceStatusLabel
        $evidenceStatusField
    </div>
    <div class="col-md-3">
        $primaryAssessorLabel
        $primaryAssessorField
    </div>
    <div class="col-md-3">
        $secondaryAssessorLabel
        $secondaryAssessorField
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        $refLabel
        $refField
    </div>
    <div class="col-md-3">
        $ulnLabel
        $ulnField
    </div>
    <div class="col-md-3">
        $firstnamesLabel
        $firstnamesField
    </div>
    <div class="col-md-3">
        $surnameLabel
        $surnameField
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        $fromStartDateLabel
        $fromStartDateField
    </div>
    <div class="col-md-3">
        $toStartDateLabel
        $toStartDateField
    </div>
    <div class="col-md-3">
        $fromPlannedEndDateLabel
        $fromPlannedEndDateField
    </div>
    <div class="col-md-3">
        $toPlannedEndDateLabel
        $toPlannedEndDateField
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        $sortByLabel
        $sortByField
    </div>
    <div class="col-md-3">
        $sortDirectionLabel
        $sortDirectionField
    </div>
    <div class="col-md-3">
        $perPageLabel
        $perPageField
    </div>
</div>

HTML;



        return $html;
    }

}