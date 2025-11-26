@extends('layouts.master')

@section('title', 'View OTJH entry')

@section('page-content')
    <div class="page-header">
        <h1>
            IQA Sampling
        </h1>
    </div>
    <div class="widget-box transparent ui-sortable-handle collapsed">
        <div class="widget-header widget-header-small">
            <h5 class="widget-title smaller">Search Filters</h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse"><i class="ace-icon fa bigger-125 fa-chevron-down"></i></a>
            </div>
        </div>
        <div class="widget-header widget-header-small">
        </div>

        <div class="widget-body" style="display: none;">
            <div class="widget-main small">
                <small>
                    <form method="GET" action="http://folio-local.test/iqa_sample_plans" accept-charset="UTF-8"
                        class="form-horizontal" role="form" name="formFilters">

                        <input name="_reset" type="hidden" value="0">


                        <div class="row">
                            <div class="col-md-4">
                                <label for="title" class="control-label">Title</label>
                                <input class="form-control" maxlength="150" name="title" type="text" id="title">
                            </div>
                            <div class="col-md-4">
                                <label for="verifier_id" class="control-label">Verifier</label>
                                <select class="form-control" id="verifier_id" name="verifier_id">
                                    <option selected="selected" value=""></option>
                                    <option value="17">Khushnood Khan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="learning_aim" class="control-label">Learning Aim</label>
                                @php
                                    $aims = App\Models\Programmes\ProgrammeQualification::orderBy('title')
                                        ->pluck('title', 'id')
                                        ->toArray();
                                @endphp
                                {!! Form::select('learning_aim', $aims, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="sort_by" class="control-label">Sort By</label>
                                <select class="form-control" id="sort_by" name="sort_by">
                                    <option value="iqa_sample_plans.created_at">Plan Creation Date</option>
                                    <option value="iqa_sample_plans.completed_by_date" selected="selected">Plan Completed By
                                        Date</option>
                                    <option value="iqa_sample_plans.type">Plan Type</option>
                                    <option value="iqa_sample_plans.status">Plan Status</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="direction" class="control-label">Order</label>
                                <select class="form-control" id="direction" name="direction">
                                    <option value="ASC" selected="selected">Ascending</option>
                                    <option value="DESC">Descending</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="per_page" class="control-label">Records per Page</label>
                                <select class="form-control" id="per_page" name="per_page">
                                    <option value="10">10</option>
                                    <option value="20" selected="selected">20</option>
                                    <option value="30">30</option>
                                    <option value="40">40</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <div class="clearfix" style="margin-top: 5px;">
                            <button class="btn btn-sm btn-round btn-primary" type="submit">
                                <i class="ace-icon fa fa-search bigger-110"></i>
                                Search
                            </button>
                            &nbsp; &nbsp; &nbsp;
                            <button class="btn btn-sm btn-round btn-default" type="button"
                                onclick="resetViewFilters(this);">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Reset
                            </button>
                        </div>

                    </form>
                    <hr>

                </small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tblSamplePlans" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>IQA Personnel</th>
                            <th>Learning Aim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Khushnood Khan - Adult Care Worker Level 2</td>
                            <td>Khushnood Khan</td>
                            <td>Adult Care Worker Level 2</td>
                        </tr>
                        <tr>
                            <td>Cathryn Butler - Children, Young People and Families Practitioner Level 4</td>
                            <td>Cathryn Butler</td>
                            <td>Children, Young People and Families Practitioner Level 4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
