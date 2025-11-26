<?php

namespace App\Filters;

use App\Models\LookupManager;
use App\Models\Organisations\Organisation;
use App\Models\Tags\Tag;
use Illuminate\Http\Request;

class EmployerFilters extends QueryFilters
{
    protected $request;

    protected $filterKey = 'ViewEmployers_Filters';

    protected $defaultFilters = [
        'sort_by' => 'orgs.legal_name',
        'direction' => 'ASC',
        'per_page' => '20',
    ];

    protected $viewFilters = [
        'keyword' => null,
        'edrs' => null,
        'sector' => null,
        'active' => null,
        'company_number' => null,
        'vat_number' => null,
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

    public function keyword($value = '')
    {
        if($value)
        {
            $this->builder->where(function ($query) use ($value){
                return $query
                    ->where('legal_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('trading_name', 'LIKE', '%' . $value . '%');
            });
        }
    }

    public function edrs($value = '')
    {
        if($value)
        {
            $this->builder->where('edrs', $value);
        }
    }

    public function sector($value = '')
    {
        if($value)
        {
            $this->builder->where('sector', $value);
        }
    }

    public function active($value = '')
    {
        if($value != '' )
        {
            $this->builder->where('active', $value);
        }
    }

    public function company_number($value = '')
    {
        if($value)
        {
            $this->builder->where('company_number', $value);
        }
    }

    public function vat_number($value = '')
    {
        if($value)
        {
            $this->builder->where('vat_number', $value);
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
        session(['employers_per_page' => $value]);
    }

    public function render()
    {
        $sortByOptions = [
            'orgs.legal_name' => 'Legal Name',
            'orgs.trading_name' => 'Trading Name',
        ];
        
        $keywordLabel = \Form::label('keyword', 'Keyword/Number', ['class' => 'control-label']);
        $keywordField = \Form::text('keyword', $this->filters()['keyword'], ['class' => 'form-control', 'maxlength' => '150']);

        $edrsLabel = \Form::label('edrs', 'EDRS', ['class' => 'control-label']);
        $edrsField = \Form::text('edrs', $this->filters()['edrs'], ['class' => 'form-control', 'maxlength' => '10']);

        $sectorLabel = \Form::label('sector', 'Sector', ['class' => 'control-label']);
        $sectorField = \Form::select('sector', Organisation::getDDLOrgSectors(true), $this->filters()['sector'], ['class' => 'form-control', 'placeholder' => '']);

        $statusLabel = \Form::label('active', 'Status', ['class' => 'control-label']);
        $statusField = \Form::select('active', ['1' => 'Active', '0' => 'Not Active'], $this->filters()['active'], ['class' => 'form-control', 'placeholder' => '']);

        $companyNumberLabel = \Form::label('company_number', 'Company Number', ['class' => 'control-label']);
        $companyNumberField = \Form::text('company_number', $this->filters()['company_number'], ['class' => 'form-control', 'maxlength' => '10']);

        $vatNumberLabel = \Form::label('vat_number', 'VAT Number', ['class' => 'control-label']);
        $vatNumberField = \Form::text('vat_number', $this->filters()['vat_number'], ['class' => 'form-control', 'maxlength' => '10']);

        $tagLabel = \Form::label('tag', 'Tag', ['class' => 'control-label']);
        $tagField = \Form::select('tag', Tag::whereType('Organisation')->orderBy('name')->pluck('name', 'id')->toArray(), $this->filters()['tag'], ['class' => 'form-control', 'placeholder' => '']);

        $sortByLabel = \Form::label('sort_by', 'Sort By', ['class' => 'control-label']);
        $sortByField = \Form::select('sort_by', $sortByOptions, $this->filters()['sort_by'], ['class' => 'form-control']);

        $sortDirectionLabel = \Form::label('direction', 'Order', ['class' => 'control-label']);
        $sortDirectionField = \Form::select('direction', ['ASC' => 'Ascending', 'DESC' => 'Descending', ], $this->filters()['direction'], ['class' => 'form-control']);

        $perPageLabel = \Form::label('per_page', 'Records per Page', ['class' => 'control-label']);
        $perPageField = \Form::select('per_page', LookupManager::getPerPageDDL(), $this->filters()['per_page'], ['class' => 'form-control']);

        $html = <<<HTML

<div class="row">
    <div class="col-md-4">
        $keywordLabel
        $keywordField
    </div>
    <div class="col-md-4">
        $edrsLabel
        $edrsField
    </div>
    <div class="col-md-4">
        $sectorLabel
        $sectorField
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        $statusLabel
        $statusField
    </div>
    <div class="col-md-3">
        $companyNumberLabel
        $companyNumberField
    </div>
    <div class="col-md-3">
        $vatNumberLabel
        $vatNumberField
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