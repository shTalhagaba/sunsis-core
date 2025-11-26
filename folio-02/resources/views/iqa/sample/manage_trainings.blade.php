@extends('layouts.master')

@section('title', 'IQA Sample Plan')

@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Manage IQA Sample Plan Students</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('iqa_sample_plans.show', $plan) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_error')

            @include('partials.session_message')

            <div class="row">
                <div class="col-sm-5">
                    @include('iqa.sample.plan_basic_details')
                </div>
                <div class="col-sm-7">
                    {!! Form::open([
                        'url' => route('iqa_sample_plans.trainings.update', [$plan]),
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmIqaSamplePlanAddStudent',
                        'name' => 'frmIqaSamplePlanAddStudent',
                    ]) !!}
                    {!! Form::hidden('iqa_sample_id', $plan->id) !!}

                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">Select training records for this IQA Sample Plan</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <table class="table table-bordered table-hover display" id="tblLearners">
                                    <thead>
                                        <tr>
                                            <th>Select</th><th>Name</th><th>Status</th><th>Dates</th><th>Primary Assessor</th>
                                        </tr>    
                                    </thead>
                                    <tbody>
                                        @forelse ($trainingRecords as $training)
                                        <tr>
                                            <td>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="trainings[]" value="{{ $training->id }}" class="ace input-lg" type="checkbox" 
                                                            {{ in_array($training->id, $selectedTrainingIds) ? 'checked' : '' }} />
                                                        <span class="lbl"> </span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="bolder">
                                                {{ $training->firstnames }} {{ $training->surname }}
                                            </td>
                                            <td>
                                                @include('trainings.partials.training_status_label', ['statusCode' => $training->status_code])
                                            </td>
                                            <td>
						<span class="text-info">Programme: </span>{{ $training->programme_title }}<br>
                                                <span class="text-info">Start Date: </span>{{ Carbon\Carbon::parse($training->start_date)->format('d/m/Y') }}<br>
                                                <span class="text-info">Planned End Date: </span>{{ Carbon\Carbon::parse($training->planned_end_date)->format('d/m/Y') }}<br>
                                                <span class="text-info">Actual End Date: </span>{{ $training->actual_end_date ? Carbon\Carbon::parse($training->actual_end_date)->format('d/m/Y') : '' }}
                                            </td>
                                            <td>
                                                {{ App\Models\LookupManager::getAssessors($training->primary_assessor) }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td>
                                                <span class="text-info"><i class="fa fa-info-circle"></i> No learners to add.</span>
                                            </td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        @endforelse    
                                    </tbody>
                                </table>        
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit" {{ $trainingRecords->count() === 0 ? 'disabled' : '' }}>
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Training Records
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-plugin-scripts')
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
@endsection

@section('page-inline-scripts')
    <script>
        $("form[name=frmIqaSamplePlanAddStudent]").on('submit', function(){
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });

        $(function(){
            $(function() {
                $('#tblLearners').DataTable({
                    "lengthChange": false,
                    "paging": false,
                    "info": false,
                    "order": []
                });
            });
        });        
    </script>
@endsection
