<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Programmes\Programme;
use App\Models\Tags\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingRecordFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewTrainingRecords_Filters';

    protected $defaultFilters = [
        'sort_by' => 'students.firstnames',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'id' => null,
        'student_id' => null,
        'programme_id' => null,
        'employer_id' => null,
        'employer_location' => null,
        'learner_ref' => null,
        'uln' => null,
        'gender' => null,
        'ni' => null,
        'status_code' => TrainingStatusLookup::STATUS_CONTINUING,
        'primary_assessor' => null,
        'secondary_assessor' => null,
        'verifier' => null,
        'tutor' => null,
        'firstnames' => null,
        'surname' => null,
        'email' => null,
        'to_start_date' => null,
        'to_planned_end_date' => null,
        'to_actual_end_date' => null,
        'from_start_date' => null,
        'from_planned_end_date' => null,
        'from_actual_end_date' => null,
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

    public function id($value = '')
    {
        if ($value) {
            $this->builder->where('tr.id', '=', $value);
        }
    }

    public function student_id($value = '')
    {
        if ($value) {
            $this->builder->where('tr.student_id', '=', $value);
        }
    }

    public function programme_id($value = '')
    {
        if ($value) {
            $this->builder->where('tr.programme_id', '=', $value);
        }
    }

    public function employer_id($value = '')
    {
        if ($value) {
            $this->builder->where('tr.employer_id', '=', $value);
        }
    }

    public function employer_location($value = '')
    {
        if ($value) {
            $this->builder->where('tr.employer_location', '=', $value);
        }
    }

    public function learner_ref($value = '')
    {
        if ($value) {
            $this->builder->where('tr.learner_ref', '=', $value);
        }
    }

    public function status_code($value = '')
    {
        if ($value) {
            $this->builder->where('tr.status_code', '=', $value);
        }
    }

    public function primary_assessor($value = '')
    {
        if ($value) {
            $this->builder->where('tr.primary_assessor', '=', $value);
        }
    }

    public function secondary_assessor($value = '')
    {
        if ($value) {
            $this->builder->where('tr.secondary_assessor', '=', $value);
        }
    }

    public function verifier($value = '')
    {
        if ($value) {
            $trIds = DB::table('portfolios')->where('fs_verifier_id', auth()->user()->id)->pluck('tr_id')->toArray();
            $this->builder->where(function ($query) use ($value, $trIds) {
                return $query
                    ->where('tr.verifier', $value)
                    ->orWhereIn('tr.id', $trIds);
            });
        }
    }

    public function tutor($value = '')
    {
        if ($value) {
            $trIds = DB::table('portfolios')->where('fs_tutor_id', auth()->user()->id)->pluck('tr_id')->toArray();
            $this->builder->where(function ($query) use ($value, $trIds) {
                return $query
                    ->where('tr.tutor', $value)
                    ->orWhereIn('tr.id', $trIds);
            });
        }
    }

    public function from_start_date($value = '')
    {
        if ($value) {
            $this->builder->whereDate('tr.start_date', '>=', $value);
        }
    }

    public function to_start_date($value = '')
    {
        if ($value) {
            $this->builder->whereDate('tr.start_date', '<=', $value);
        }
    }

    public function from_planned_end_date($value = '')
    {
        if ($value) {
            $this->builder->whereDate('tr.planned_end_date', '>=', $value);
        }
    }

    public function to_planned_end_date($value = '')
    {
        if ($value) {
            $this->builder->whereDate('tr.planned_end_date', '<=', $value);
        }
    }

    public function from_actual_end_date($value = '')
    {
        if ($value) {
            $this->builder->whereDate('tr.actual_end_date', '>=', $value);
        }
    }

    public function to_actual_end_date($value = '')
    {
        if ($value) {
            $this->builder->whereDate('tr.actual_end_date', '<=', $value);
        }
    }

    public function firstnames($value = '')
    {
        if ($value) {
            $this->builder->where('students.firstnames', 'LIKE', '%' . $value . '%');
        }
    }

    public function surname($value = '')
    {
        if ($value) {
            $this->builder->where('students.surname', 'LIKE', '%' . $value . '%');
        }
    }

    public function email($value = '')
    {
        if ($value) {
            $this->builder->where(function ($query) use ($value) {
                return $query
                    ->where('students.email', $value)
                    ->orWhere('students.primary_email', $value)
                    ->orWhere('students.secondary_email', $value);
            });
        }
    }

    public function gender($value = '')
    {
        if ($value) {
            $this->builder->where('students.gender', '=', $value);
        }
    }

    public function ni($value = '')
    {
        if ($value) {
            $this->builder->where('students.ni', '=', $value);
        }
    }

    public function uln($value = '')
    {
        if ($value) {
            $this->builder->where('students.uln', '=', $value);
        }
    }

    public function tag($value = '')
    {
        if ($value) {
            $this->builder->whereHas('tags', function ($tag) use ($value) {
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
        session(['trs_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'students.firstnames' => 'First Name',
            'students.surname' => 'Surname',
            'tr.start_date' => 'Training Start Date',
            'tr.planned_end_date' => 'Training Planned End Date',
        ];

        $programmes = Programme::orderBy('title')->pluck('title', 'id')->toArray();
        $assessors = LookupManager::getAssessors();
        $tutors = LookupManager::getTutors();
        $verifiers = LookupManager::getVerifiers();
        $employers = LookupManager::getEmployersLocationsDDL();
        $trainingStatus = LookupManager::getTrainingRecordStatus();

        $trainingStatusLabel = \Form::label('status_code', 'Training Status', ['class' => 'control-label']);
        $trainingStatusField = \Form::select('status_code', $trainingStatus, $this->filters()['status_code'], ['class' => 'form-control', 'placeholder' => '']);

        $refLabel = \Form::label('learner_ref', 'Learner Reference', ['class' => 'control-label']);
        $refField = \Form::text('learner_ref', $this->filters()['learner_ref'], ['class' => 'form-control', 'maxlength' => '12']);

        $ulnLabel = \Form::label('uln', 'Unique Learner Number', ['class' => 'control-label']);
        $ulnField = \Form::text('uln', $this->filters()['uln'], ['class' => 'form-control', 'maxlength' => '10']);

        $programmeLabel = \Form::label('programme_id', 'Programme', ['class' => 'control-label']);
        $programmeField = \Form::select('programme_id', $programmes, $this->filters()['programme_id'], ['class' => 'form-control', 'placeholder' => '']);

        $firstnamesLabel = \Form::label('firstnames', 'First Name', ['class' => 'control-label']);
        $firstnamesField = \Form::text('firstnames', $this->filters()['firstnames'], ['class' => 'form-control', 'maxlength' => '70']);

        $surnameLabel = \Form::label('surname', 'Surname', ['class' => 'control-label']);
        $surnameField = \Form::text('surname', $this->filters()['surname'], ['class' => 'form-control', 'maxlength' => '70']);

        $employerLabel = \Form::label('employer_location', 'Employer', ['class' => 'control-label']);
        $employerField = \Form::select('employer_location', $employers, $this->filters()['employer_location'], ['class' => 'form-control', 'placeholder' => '']);

        $primaryAssessorLabel = \Form::label('primary_assessor', 'Primary Assessor', ['class' => 'control-label']);
        $primaryAssessorField = \Form::select('primary_assessor', $assessors, $this->filters()['primary_assessor'], ['class' => 'form-control', 'placeholder' => '']);

        $secondaryAssessorLabel = \Form::label('secondary_assessor', 'Secondary Assessor', ['class' => 'control-label']);
        $secondaryAssessorField = \Form::select('secondary_assessor', $assessors, $this->filters()['secondary_assessor'], ['class' => 'form-control', 'placeholder' => '']);

        $tutorLabel = \Form::label('tutor', 'Tutor', ['class' => 'control-label']);
        $tutorField = \Form::select('tutor', $tutors, $this->filters()['tutor'], ['class' => 'form-control', 'placeholder' => '']);

        $verifierLabel = \Form::label('verifier', 'verifier', ['class' => 'control-label']);
        $verifierField = \Form::select('verifier', $verifiers, $this->filters()['verifier'], ['class' => 'form-control', 'placeholder' => '']);

        $genderLabel = \Form::label('gender', 'Gender', ['class' => 'control-label']);
        $genderField = \Form::select('gender', LookupManager::getGenderDDL(), $this->filters()['gender'], ['class' => 'form-control', 'placeholder' => '']);

        $niLabel = \Form::label('ni', 'National Insurance', ['class' => 'control-label']);
        $niField = \Form::text('ni', $this->filters()['ni'], ['class' => 'form-control', 'maxlength' => '18']);

        $fromStartDateLabel = \Form::label('from_start_date', 'From Start Date', ['class' => 'control-label']);
        $fromStartDateField = \Form::date('from_start_date', $this->filters()['from_start_date'], ['class' => 'form-control']);

        $toStartDateLabel = \Form::label('to_start_date', 'To Start Date', ['class' => 'control-label']);
        $toStartDateField = \Form::date('to_start_date', $this->filters()['to_start_date'], ['class' => 'form-control']);

        $fromPlannedEndDateLabel = \Form::label('from_planned_end_date', 'From Planned End Date', ['class' => 'control-label']);
        $fromPlannedEndDateField = \Form::date('from_planned_end_date', $this->filters()['from_planned_end_date'], ['class' => 'form-control']);

        $toPlannedEndDateLabel = \Form::label('to_planned_end_date', 'To Planned End Date', ['class' => 'control-label']);
        $toPlannedEndDateField = \Form::date('to_planned_end_date', $this->filters()['to_planned_end_date'], ['class' => 'form-control']);

        $fromActualEndDateLabel = \Form::label('from_actual_end_date', 'From Actual End Date', ['class' => 'control-label']);
        $fromActualEndDateField = \Form::date('from_actual_end_date', $this->filters()['from_actual_end_date'], ['class' => 'form-control']);

        $toActualEndDateLabel = \Form::label('to_actual_end_date', 'To Actual End Date', ['class' => 'control-label']);
        $toActualEndDateField = \Form::date('to_actual_end_date', $this->filters()['to_actual_end_date'], ['class' => 'form-control']);

        $tagLabel = \Form::label('tag', 'Tag', ['class' => 'control-label']);
        $tagField = \Form::select('tag', Tag::whereType('TrainingRecord')->orderBy('name')->pluck('name', 'id')->toArray(), $this->filters()['tag'], ['class' => 'form-control', 'placeholder' => '']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending',], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);



        $html = <<<HTML

<div class="row">
    <div class="col-md-3">
        $trainingStatusLabel
        $trainingStatusField
    </div>
    <div class="col-md-3">
        $refLabel
        $refField
    </div>
    <div class="col-md-3">
        $ulnLabel
        $ulnField
    </div>
    <div class="col-md-3">
        $programmeLabel
        $programmeField
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        $firstnamesLabel
        $firstnamesField
    </div>
    <div class="col-md-3">
        $surnameLabel
        $surnameField
    </div>
    <div class="col-md-3">
        $genderLabel
        $genderField
    </div>
    <div class="col-md-3">
        $niLabel
        $niField
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        $primaryAssessorLabel
        $primaryAssessorField
    </div>
    <div class="col-md-3">
        $secondaryAssessorLabel
        $secondaryAssessorField
    </div>
    <div class="col-md-3">
        $tutorLabel
        $tutorField
    </div>
    <div class="col-md-3">
        $verifierLabel
        $verifierField
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
        $fromActualEndDateLabel
        $fromActualEndDateField
    </div>
    <div class="col-md-3">
        $toActualEndDateLabel
        $toActualEndDateField
    </div>
    <div class="col-md-3">
        $employerLabel
        $employerField
    </div>
    <div class="col-md-3">
        $tagLabel
        $tagField
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