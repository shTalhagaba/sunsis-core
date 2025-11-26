<?php

namespace App\Filters;

use App\Models\LookupManager;
use Illuminate\Http\Request;

class NotificationFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewNotifications_Filters';

    protected $defaultFilters = [
        'sort_by' => 'notifications.created_at',
        'direction' => 'DESC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'from_created_at' => null,
        'to_created_at' => null,
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

    public function from_created_at($value = '')
    {
        if($value)
        {
            $this->builder->whereDate('notifications.created_at', '>=', $value);
        }
    }

    public function to_created_at($value = '')
    {
        if($value)
        {
            $this->builder->whereDate('notifications.created_at', '<=', $value);
        }
    }

    public function sort_by($column)
    {
        $direction = isset($this->filters()['direction']) ? $this->filters()['direction'] : 'DESC';

        $this->builder->orderBy($column, $direction);
    }

    public function per_page($value = '')
    {
        session(['notifications_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'notifications.created_at' => 'Created Date',
            'notifications.read_at' => 'Read Date',
        ];
        
        $fromCreatedDateLabel = \Form::label('from_created_at', 'From Created Date', ['class' => 'control-label']);
        $fromCreatedDateField = \Form::date('from_created_at', $this->filters()['from_created_at'], ['class' => 'form-control']);

        $toCreatedDateLabel = \Form::label('to_created_at', 'To Created Date', ['class' => 'control-label']);
        $toCreatedDateField = \Form::date('to_created_at', $this->filters()['to_created_at'], ['class' => 'form-control']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending', ], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        $html = <<<HTML

<div class="row">
    <div class="col-md-4">
        $fromCreatedDateLabel
        $fromCreatedDateField
    </div>
    <div class="col-md-4">
        $toCreatedDateLabel
        $toCreatedDateField
    </div>
    <div class="col-md-4">

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