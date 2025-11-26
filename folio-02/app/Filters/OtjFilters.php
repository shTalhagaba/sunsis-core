<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\Training\Otj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtjFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewTrainingOtj_Filters';

    protected $defaultFilters = [
        'sort_by' => 'otj.updated_at',
        'direction' => 'DESC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'id' => null,
        'status' => null,
        'title' => null,
        'type' => null,
        'date_range' => null,
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
            $this->builder->where('otj.id', '=', $value);
        }
    }

    public function status($value = '')
    {
        if ($value) {
            $this->builder->where('otj.status', '=', $value);
        }
    }

    public function title($value = '')
    {

        if ($value) {
            $this->builder->where('otj.title', '=', $value);
        }
    }

    public function type($value = '')
    {
        if ($value) {
            $this->builder->where('otj.type', '=', $value);
        }
    }


    public function date_range($value = '')
    {
        if (!$value) {
            return;
        }

        $dates = explode(' to ', $value);

        if (count($dates) === 2) {
            // ✅ Range filter
            [$from, $to] = $dates;
            $this->builder->whereBetween('otj.date', [$from, $to]);
        } else {
            // ✅ Single date filter
            $this->builder->whereDate('otj.date', $value);
        }
        
    }


    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'DESC';
        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['trs_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'otj.updated_at' => 'Date',
        ];

        $OtjStatus = [
            Otj::STATUS_ACCEPTED  => 'Accepted',
            Otj::STATUS_AWAITING  => 'Awaiting',
            Otj::STATUS_SUBMITTED => 'Submitted',
            Otj::STATUS_REFERRED => 'Referred',
        ];

        $otjStatusLabel = \Form::label('status', 'OTJ Status', ['class' => 'control-label']);
        $otjStatusField = \Form::select('status', $OtjStatus, $this->filters()['status'], ['class' => 'form-control', 'placeholder' => '']);

        $titleLabel = \Form::label('title', 'Title', ['class' => 'control-label']);
        $titleField = \Form::text('title', $this->filters()['title'], ['class' => 'form-control', 'maxlength' => '12']);

        $dateLabel = \Form::label('date_range', 'Date Range', ['class' => 'control-label']);
        $dateField = \Form::text('date_range', $this->filters()['date_range'] ?? null, [
            'class' => 'form-control',
            'id' => 'date_range',
            'placeholder' => 'Select date range'
        ]);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending',], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);



        $html = <<<HTML

<div class="row">
    <div class="col-md-4">
        $otjStatusLabel
        $otjStatusField
    </div>
    <div class="col-md-4">
        $titleLabel
        $titleField
    </div>
    <div class="col-md-4">
        $dateLabel
        $dateField
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

        // === Inject Flatpickr Assets ===
        $html .= <<<HTML

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#date_range", {
        mode: "range",
        dateFormat: "Y-m-d",
        allowInput: true
    });
});
</script>
HTML;

        return $html;
    }
}