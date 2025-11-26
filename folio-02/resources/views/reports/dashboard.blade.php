@extends('layouts.master')

@section('title', 'Reports Dashboard')

@section('page-inline-styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

@endsection

@section('page-content')
    <div class="page-header"></div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="widget-box">
                        <div class="widget-body">
                            <div class="widget-main">
                                {!! Form::open(['method' => 'GET', 'url' => route('reports.dashboard.index'), 'class' => 'form-horizontal']) !!}
                                <div class="form-group row required {{ $errors->has('start') ? 'has-error' : ''}}">
                                    {!! Form::label('start', 'Start Date', ['class' => 'col-sm-2 control-label']) !!}
                                    <div class="col-sm-3">
                                        {!! Form::date('start', $start, ['class' => 'form-control', 'required']) !!}
                                        {!! $errors->first('start', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                    {!! Form::label('end', 'End Date', ['class' => 'col-sm-2 control-label']) !!}
                                    <div class="col-sm-3">
                                        {!! Form::date('end', $end, ['class' => 'form-control', 'required']) !!}
                                        {!! $errors->first('end', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-xs btn-round btn-primary" type="submit">
                                            <i class="ace-icon fa fa-search bigger-110"></i>
                                            Search
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box">
                        <div class="widget-body">
                            <div class="widget-main">

                                @include('reports.partials.trainings_list_widget', ['widgetHeader' => 'Number of Starters', 'tableHeader' => 'List of Starters', 'trainingsList' => $starters, 'exportFileName' => 'Starters'])

                                @include('reports.partials.trainings_list_widget', ['widgetHeader' => 'Number of students currently active', 'tableHeader' => 'List of students currently active', 'trainingsList' => $currentlyActive, 'exportFileName' => 'CurrentlyActive'])    

                                @include('reports.partials.trainings_list_widget', ['widgetHeader' => 'Number of students planned to finish but are still active', 'tableHeader' => 'List of students planned to finish but are still active', 'trainingsList' => $plannedToFinishAndActive, 'exportFileName' => 'PlannedToFinishButActive'])    

                                @include('reports.partials.trainings_list_widget', ['widgetHeader' => 'Number of students planned to finish and have finished learning', 'tableHeader' => 'List of students planned to finish and have finished learning', 'trainingsList' => $plannedToFinishAndCompleted, 'exportFileName' => 'PlannedToFinishAndCompleted'])    

                                @include('reports.partials.trainings_list_widget', ['widgetHeader' => 'Number of withdrawn students', 'tableHeader' => 'List of withdrawn students', 'trainingsList' => $withdrawn, 'exportFileName' => 'Withdrawn'])    

                                @include('reports.partials.trainings_list_widget', ['widgetHeader' => 'Number of break in learning students', 'tableHeader' => 'List of break in learning students', 'trainingsList' => $breakInLearning, 'exportFileName' => 'BreakInLearning'])    

                                @include('reports.partials.trainings_list_widget', ['widgetHeader' => 'Number of achievers', 'tableHeader' => 'List of achievers', 'trainingsList' => $achievers, 'exportFileName' => 'Achievers'])    
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->

@endsection

@section('page-inline-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.export-lnk').forEach(function(button) {
            button.addEventListener('click', function() {
                let tableId = this.getAttribute('data-table');
                let tableFilename = this.getAttribute('data-table-filename');
                let table = document.getElementById(tableId);
                let tableInfo = table.getAttribute('title');

                exportTableToCSVWithInfo(table, tableInfo, tableFilename + '.xlsx');
            });
        });

        function exportTableToCSVWithInfo(table, info, filename) {
            let tableArray = XLSX.utils.sheet_to_json(XLSX.utils.table_to_sheet(table), {header: 1});
            tableArray.unshift([info]); // Add the information as the first row
            let ws = XLSX.utils.aoa_to_sheet(tableArray);
            let wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
            XLSX.writeFile(wb, filename);
        }
    });
</script>
@endsection