@extends('layouts.master')

@section('title', 'IQA Sample Plan')

@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Manage IQA Sample Plan Units</h1>
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
                        'url' => route('iqa_sample_plans.units.update', [$plan]),
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmIqaSamplePlanAddUnit',
                        'name' => 'frmIqaSamplePlanAddUnit',
                    ]) !!}
                    {!! Form::hidden('iqa_sample_id', $plan->id) !!}

                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">Select Units for this IQA Sample Plan</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @foreach ($programmeQualifications as $programmeQualification)
                                <div class="widget-box border transparent" style="border:1px solid black; padding: 0.5%">
                                    <div class="widget-header">
                                        <h5 class="widget-title smaller">
                                            <label>
                                                <input name="chkQual[]" id="chkQual{{ $programmeQualification->qan }}" value="{{ $programmeQualification->id }}" 
                                                    class="ace input-lg chkQual" type="checkbox" />
                                                <span class="lbl"> &nbsp; {{ $programmeQualification->qan }}: {{ $programmeQualification->title }}</span>
                                            </label>
                                        </h5>
                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main table-responsive">
                                            <table class="table table-bordered table-hover display" id="tblQual{{ $programmeQualification->qan }}">
                                                <thead>
                                                    <tr><th>Select</th><th>Unit Title</th></tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($programmeQualification->units as $programmeQualificationUnit)
                                                        @php
                                                        $checkboxValue = (object) [
                                                            'unit_group' => $programmeQualificationUnit->unit_group,
                                                            'unit_owner_ref' => $programmeQualificationUnit->unit_owner_ref,
                                                            'unique_ref_number' => $programmeQualificationUnit->unique_ref_number,
                                                            'title' => $programmeQualificationUnit->title,
                                                            'glh' => $programmeQualificationUnit->glh,
                                                            'unit_credit_value' => $programmeQualificationUnit->unit_credit_value,
                                                            'system_code' => $programmeQualificationUnit->system_code,
                                                            'qual_qan' => $programmeQualification->qan,
                                                            'qual_title' => $programmeQualification->title,
                                                            'qual_min_glh' => $programmeQualification->min_glh,
                                                            'qual_max_glh' => $programmeQualification->max_glh,
                                                            'qual_glh' => $programmeQualification->glh,
                                                            'qual_total_credits' => $programmeQualification->total_credits,
                                                        ];
                                                        $checkboxValue = json_encode($checkboxValue);
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="programmeQualificationUnits[]" id="unit{{ $programmeQualificationUnit->id }}OfQual{{ $programmeQualification->qan }}" 
                                                                        value="{{ $checkboxValue }}" class="ace input-lg" type="checkbox" />
                                                                    <span class="lbl"> </span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            [{{ $programmeQualificationUnit->unit_owner_ref }}] [{{ $programmeQualificationUnit->unique_ref_number }}] 
                                                            {!! nl2br(e($programmeQualificationUnit->title)) !!}
                                                        </td>
                                                    </tr>
                                                    @empty
                                                        <tr><td></td><td><span class="text-info"><i class="fa fa-info-circle"></i> No units availble to add.</span></td></tr>
                                                    @endforelse                                                        
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Units
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
        $("form[name=frmIqaSamplePlanAddUnit]").on('submit', function(){
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });

        $(function(){
           
            $('input[type=checkbox][id^=chkQual]').on('click', function(){
                var qualQan = this.id.replace('chkQual', '');
                if(this.checked)
                {
                    $("input[type='checkbox'][id$='OfQual"+qualQan+"']").each(function() {
                        $(this).prop('checked', true);
                    });
                }
                else
                {
                    $("input[type='checkbox'][id$='OfQual"+qualQan+"']").each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            $(function() {
                $('table.display').DataTable({
                    "lengthChange": false,
                    "paging": false,
                    "info": false,
                    "order": [],
                    "fnDrawCallback": function ( oSettings ) {
                        $(oSettings.nTHead).hide();
                    }
                });
            });
            
        });
    </script>
@endsection
