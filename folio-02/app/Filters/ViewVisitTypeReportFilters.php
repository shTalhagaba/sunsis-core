<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ViewVisitTypeReportFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewVisiTypetReport_Filters';

    protected $defaultFilters = [
        'sort_by' => 'tr_dp_sessions.id',
        'direction' => 'ASC',
        'per_page' => '50',
    ];

    protected $viewFilters = [
        'actual_date' => null,
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

    public function actual_date($value = '')
    {
        if (!$value) return;

        $dates = explode(' to ', $value);

        if (count($dates) === 2) {
            [$from, $to] = $dates;
            $this->builder->whereBetween('tr_dp_sessions.actual_date', [$from, $to]);
        } elseif (count($dates) === 1) {
            $this->builder->whereDate('tr_dp_sessions.actual_date', $dates[0]);
        }
    }


    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'ASC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['otj_per_page' => $value]);
    }

    public function render()
    {
        $startSateLabel = \Form::label('actual_date', 'Session Date', ['class' => 'control-label']);
        $startDateField = \Form::text('actual_date', $this->filters()['actual_date'] ?? null, [
            'class' => 'form-control',
            'id' => 'date_range',
            'placeholder' => 'Select date range'
        ]);

        $html = <<<HTML

<div class="row">
    <div class="col-md-4">
        $startSateLabel
        $startDateField
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