@extends('layouts.master')

@section('title', 'Create IQA Sample Plan')

@section('page-content')
    <div class="page-header">
        <h1>Create IQA Sample Plan</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ route('iqa_sample_plans.index') }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>
            
            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-7">
                    <div class="space"></div>

                    {!! Form::open([
                        'url' => route('iqa_sample_plans.store'),
                        'class' => 'form-horizontal',
                        'id' => 'frmIqaSamplePlan',
                        'name' => 'frmIqaSamplePlan',
                    ]) !!}
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">IQA Sample Basic Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @if(auth()->user()->isVerifier())
                                {!! Form::hidden('verifier_id', auth()->user()->id) !!}
                                @else
                                <div class="form-group row required {{ $errors->has('verifier_id') ? 'has-error' : '' }}">
                                    {!! Form::label('verifier_id', 'Verifier', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select(
                                            'verifier_id',
                                            $verifiers,
                                            null,
                                            ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'verifier_id'],
                                        ) !!}
                                        {!! $errors->first('verifier_id', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                @endif
                                <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                                    {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('title', null, ['class' => 'form-control', 'required', 'maxlength' => '70']) !!}
                                        {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('type') ? 'has-error' : '' }}">
                                    {!! Form::label('type', 'Type', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select(
                                            'type',
                                            App\Models\IQA\IqaSamplePlan::getTypeList(),
                                            null,
                                            ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'type'],
                                        ) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row required {{ $errors->has('completed_by_date') ? 'has-error' : '' }}">
                                    {!! Form::label('completed_by_date', 'Completed By Date', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::date('completed_by_date', null, ['class' => 'form-control', 'required']) !!}
                                        {!! $errors->first('completed_by_date', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('programme_id') ? 'has-error' : '' }}">
                                    {!! Form::label('programme_id', 'Programme', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select(
                                            'programme_id',
                                            $programmes,
                                            null,
                                            ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'programme_id'],
                                        ) !!}
                                        {!! $errors->first('programme_id', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('qualifications') ? 'has-error' : '' }}">
                                    {!! Form::label('qualifications', 'Qualifications', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        <table class="table table-bordered" id="tblQuals">
                                            <tbody id="tblQualsBody">
                                                <tr id="infoRow"><td colspan="2"><i>select programme to bring qualifications</i></td></tr>
                                                <tr id="loadingRow" style="display: none;"><td colspan="2" class="text-info"><i class="fa fa-spin fa-refresh fa-2x"></i> fetching qualifications ...</td></tr>
                                                <tr id="noQualsRow" style="display: none;"><td colspan="2" class="text-warning"><i class="fa fa-warning"></i> No qualifications are associated to your selected programme.</td></tr>
                                            </tbody>
                                        </table>
                                        {!! $errors->first('qualifications', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Basic Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-inline-scripts')

    <script>
        $(function() {

            $("select[name=programme_id]").on("change", function(e){
                e.preventDefault();
                $("#tblQuals").find("tr:gt(2)").remove();
                $("tr#infoRow").hide();
                $("tr#loadingRow").hide();
                $("tr#noQualsRow").hide();

                if(this.value === '')
                {
                    $("tr#infoRow").show();
                    return;
                }

                $("tr#infoRow").hide();
                $.ajax({
                    beforeSend: function() {
                        $("tr#loadingRow").show();
                    },
                    url:'{{ route("getProgrammeQualificationsForIqaSample") }}',
                    type: 'get',
                    data: {programme_id: this.value}
                }).done(function(data) {
                    var qualsCount = 0;
                    $.each(data.data, function(key, value){
                        let dynamicRowHTML = `
                        <tr> 
                            <td> 
                                <input type="checkbox" name="qualifications[]" value="${key}" />                                
                            </td> 
                            <td> 
                                ${value} 
                            </td> 
                        </tr>`;
                        $('#tblQualsBody').append(dynamicRowHTML);
                        qualsCount++;
                    });
                    if(qualsCount === 0)
                    {
                        $("tr#noQualsRow").show();
                    }
                }).fail(function(jqXHR, textStatus, errorThrown){
                    var response = JSON.parse(jqXHR.responseText);
                    var errorString = '<ul>';
                    $.each( response.errors, function( key, value) {
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';
                    bootbox.alert({
                        title: "Error: " + errorThrown,
                        message: errorString
                    });
                }).always(function(){
                    $("tr#loadingRow").hide();
                });
            });

            $("form[name=frmIqaSamplePlan]").on('submit', function(){
                var form = $(this);
                form.find(':submit').attr("disabled", true);
                form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                return true;
            });

        });
        
    </script>

@endsection
