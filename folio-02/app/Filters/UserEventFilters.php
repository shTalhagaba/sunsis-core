<?php

namespace App\Filters;

use App\Helpers\AppHelper;
use App\Models\LookupManager;
use App\Models\Lookups\UserTypeLookup;
use App\Models\User;
use App\Models\UserEvents\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserEventFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewUserEvent_Filters';

    protected $defaultFilters = [
        'sort_by' => 'user_events.start',
        'direction' => 'DESC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'title' => null,
        'event_type' => null,
        'event_status' => null,
        'task_status' => null,
        'task_type' => null,
        'type' => null,
        'from_start_date' => null,
        'to_start_date' => null,
        'personal' => null,
        'assign_iqa_id' => null,
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
        if ($value) {
            $this->builder->where('title', 'LIKE', '%' . $value . '%');
        }
    }

    public function event_type($value = '')
    {
        if ($value) {
            $this->builder->where('event_type', $value);
        }
    }

    public function task_type($value = '')
    {
        if ($value !== null && $value !== '') {
            $this->builder->where('task_type', $value);
        }
    }

    public function assign_iqa_id($value = null)
    {
        if ($value !== null) {
            $this->builder->where('assign_iqa_id', $value);
        }
    }

    public function type($value = '')
    {
        if ($value) {
            $this->builder->where('type', $value);
        }
    }

    public function event_status($value = '')
    {
        if ($value || $value == "0") {
            $this->builder->where('event_status', $value);
        }
    }

    public function task_status($value = '')
    {
        if ($value || $value == "0") {
            $this->builder->where('task_status', $value);
        }
    }

    public function personal($value = '')
    {
        if ($value || $value == "0") {
            $this->builder->where('personal', $value);
        }
    }

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['user_events_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'user_events.start' => 'Start Date&Time',
            'user_events.title' => 'Title',
            'user_events.type' => 'Type',
        ];

        $eventTypes = AppHelper::getUserEventsTypes();
        $taskTypes = AppHelper::getUserTaskTypes();
        $verifiers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $keywordLabel = \Form::label('title', 'Title', ['class' => 'control-label']);
        $keywordField = \Form::text('title', $this->filters()['title'], ['class' => 'form-control', 'maxlength' => '150']);

        $eventTypeLabel = \Form::label('event_type', 'Event Type', ['class' => 'control-label']);
        $eventTypeField = \Form::select('event_type', $eventTypes, $this->filters()['event_type'], ['class' => 'form-control', 'placeholder' => '']);

        $taskTypeLabel = \Form::label('task_type', 'Task Type', ['class' => 'control-label']);
        $taskTypeField = \Form::select('task_type', $taskTypes, $this->filters()['task_type'] ?? null, ['class' => 'form-control', 'placeholder' => '']);

        $typeLabel = \Form::label('type', 'Type', ['class' => 'control-label']);
        $typeField = \Form::select('type', ['task' => 'Task', 'event' => 'Event'], $this->filters()['type'] ?? null, ['class' => 'form-control', 'placeholder' => '']);

        $statusLabel = \Form::label('event_status', 'Event Status', ['class' => 'control-label']);
        $statusField = \Form::select('event_status', [UserEvent::STATUS_BOOKED => 'Booked', UserEvent::STATUS_CANCELLED => 'Cancelled', UserEvent::STATUS_CLOSED => 'Closed'], $this->filters()['event_status'], ['class' => 'form-control', 'placeholder' => '']);

        $taskStatusLabel = \Form::label('task_status', 'Task Status', ['class' => 'control-label']);
        $taskStatusField = \Form::select('task_status', [UserEvent::STATUS_ASSIGNED => 'Assigned', UserEvent::STATUS_COMPLETED => 'Completed', UserEvent::STATUS_SIGNOFF => 'Sign-off'], $this->filters()['task_status'] ?? null, ['class' => 'form-control', 'placeholder' => '']);


        $verifierLabel = \Form::label('assign_iqa_id', 'Assign IQA', ['class' => 'control-label']);
        $verifiersField = \Form::select('assign_iqa_id', $verifiers, $this->filters()['assign_iqa_id'] ?? null, ['class' => 'form-control', 'placeholder' => '']);

        $personalLabel = \Form::label('personal', 'Personal', ['class' => 'control-label']);
        $personalField = \Form::select('personal', [0 => 'No', 1, 'Yes'], $this->filters()['personal'], ['class' => 'form-control', 'placeholder' => '']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending',], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        $html = <<<HTML
            <div class="row">
                <div class="col-md-3">
                    $keywordLabel
                    $keywordField
                </div>
                <div class="col-md-3">
                    $eventTypeLabel
                    $eventTypeField
                </div>
                <div class="col-md-3">
                    $statusLabel
                    $statusField
                </div>
                <div class="col-md-3">
                    $personalLabel
                    $personalField
                </div>
        HTML;

        // ✅ PHP condition instead of Blade
        if (auth()->user()->isQualityManager()) {
            $html .= <<<HTML
                <div class="col-md-3">
                    $typeLabel
                    $typeField
                </div>
                <div class="col-md-3">
                    $taskTypeLabel
                    $taskTypeField
                </div>
                <div class="col-md-3">
                    $taskStatusLabel
                    $taskStatusField
                </div>
                <div class="col-md-3">
                    $verifierLabel
                    $verifiersField
                </div>
            HTML;
        }

        $html .= <<<HTML
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