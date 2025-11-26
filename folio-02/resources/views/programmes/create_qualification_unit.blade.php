@extends('layouts.master')

@section('title', 'Add Programme Qualification Unit')

@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>

</style>
@endsection

@section('breadcrumbs')

@endsection

@section('page-content')
<div class="page-header">
    <h1>Add Programme Qualification Unit</h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->
    <div class="well well-sm">
        <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('programmes.show', [$programme]) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
        </button>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title">Programme</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div class="info-div info-div-striped">
                            <div class="info-div-row">
                                <div class="info-div-name"> Title </div>
                                <div class="info-div-value"><span>{{ $programme->title }}</span></div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Qualification </div>
                                <div class="info-div-value"><span>{{ $qualification->qan }} - {{ $qualification->title }}</span></div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Units Count </div>
                                <div class="info-div-value"><span>{{ $qualification->units->count() }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-12"></div>

    @include('partials.session_message')

    @include('partials.session_error')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title">Enter Unit Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        {!! Form::open([
                            'name' => 'frmAddUnit',
                            'id' => 'frmAddUnit',
                            'url' => route('programme.qualification.unit.store', [$programme, $qualification]),
                            'class' => 'form-horizontal',
                            'method' => 'post'
                        ]) !!}
                        <div class="form-group row required {{ $errors->has('unit_owner_ref') ? 'has-error' : ''}}">
                            {!! Form::label('unit_owner_ref', 'Onwer Reference', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::text('unit_owner_ref', 'Ref' . (intval($qualification->units()->count())+1), ['class' => 'form-control col-sm-8 inputLimiter', 'required', 'maxlength' => '15']) !!}
                                {!! $errors->first('unit_owner_ref', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('unique_ref_number') ? 'has-error' : ''}}">
                            {!! Form::label('unique_ref_number', 'Unique Reference', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::text('unique_ref_number', null, ['class' => 'form-control col-sm-8 inputLimiter', 'required', 'maxlength' => '15']) !!}
                                {!! $errors->first('unique_ref_number', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                            {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::textarea('title', null, ['class' => 'form-control col-sm-8 inputLimiter', 'required', 'maxlength' => '500', 'rows' => 3]) !!}
                                {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('unit_group') ? 'has-error' : ''}}">
                            {!! Form::label('unit_group', 'Unit Group', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::select('unit_group', \App\Models\Qualifications\QualificationUnit::getDDLUnitGroups(false), null, ['class' => 'form-control col-sm-8', 'required']) !!}
                                {!! $errors->first('unit_group', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('glh') ? 'has-error' : ''}}">
                            {!! Form::label('glh', 'GLH', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::number('glh', 0, ['class' => 'form-control col-sm-8', 'required', 'onkeypress' => 'return isNumberKey(event)', 'maxlength' => '4']) !!}
                                {!! $errors->first('glh', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('unit_credit_value') ? 'has-error' : ''}}">
                            {!! Form::label('unit_credit_value', 'Unit Credit Value', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::number('unit_credit_value', 0, ['class' => 'form-control col-sm-8', 'required', 'onkeypress' => 'return isNumberKey(event)', 'maxlength' => '4']) !!}
                                {!! $errors->first('unit_credit_value', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('learning_outcomes') ? 'has-error' : ''}}">
                            {!! Form::label('learning_outcomes', 'Learning Outcomes', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::textarea('learning_outcomes', '', ['class' => 'form-control col-sm-8 inputLimiter', 'required', 'maxlength' => '250', 'rows' => 3]) !!}
                                {!! $errors->first('learning_outcomes', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <hr>
                        <span class="lead">Add Performance Criteria</span>
                        <div class="table-responsive">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Please note that each pc row is only saved if <strong>PC Title</strong> is given for that row.<br>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Seq.</th><th>PC Reference</th><th>PC Category</th><th style="width: 35%">PC Title</th>
                                        <th style="width: 10%">
                                            <abbr title="Minimum number of required evidences for this PC">Min. Req.</abbr><br>
                                            {!! Form::select('min_req_evidences', range(0,10), '') !!}
                                        </th>
                                        <th>PC Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i = 1; $i <= 20; $i++)
                                    @php
                                        $prefix = 'pc_'.$i.'_';
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $i }}
                                            {{ Form::hidden($prefix.'sequence', $i) }}
                                        </td>
                                        <td>
                                            {!! Form::text($prefix.'reference', '', ['class' => 'form-control inputLimiter', 'maxlength' => '15']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select($prefix.'category', \App\Models\Qualifications\QualificationUnitPC::getDDLEvidenceCategories(), null, ['class' => 'form-control', 'required']) !!}
                                        </td>
                                        <td>
                                            {!! Form::textarea($prefix.'title', '', ['class' => 'form-control inputLimiter', 'maxlength' => '500', 'rows' => '3']) !!}
                                        </td>
                                        <td>
                                            {!! Form::select($prefix.'min_req_evidences', range(0,10), null, ['class' => 'form-control', 'required' => 'required']) !!}
                                        </td>
                                        <td>
                                            {!! Form::textarea($prefix.'description', null, ['class' => 'form-control inputLimiter', 'maxlength' => '500', 'rows' => 3]) !!}
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                        {!! Form::hidden('number_of_pcs', $i-1) !!}
                        <div class="clearfix form-actions center">

                            <button class="btn btn-sm btn-round btn-success" type="submit">
                                <i class="ace-icon fa fa-save bigger-110"></i>
                                Save
                            </button>

                            &nbsp; &nbsp; &nbsp;
                            <button class="btn btn-sm btn-round" type="button" onclick="window.history.back();">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Cancel
                            </button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAGE CONTENT ENDS -->

   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">

$(function(){

    $('[data-rel=tooltip]').tooltip();
    $('[data-rel=popover]').popover({html:true});
    $('.inputLimiter').inputlimiter();

});

var saved_references = @json($saved_references);
function submitForm()
{
    if($.inArray($('input[name=unique_ref_number]').val().trim(), saved_references) !== -1) // if in the already used references
    {
        $('input[name=unique_ref_number]').focus();
        $.alert('Unit unique reference ' + $('input[name=unique_ref_number]').val().trim() + ' is not unique. Please expand existing units panel to see the used references.', 'Unique Reference');
        return false;
    }

    var pc_references = [];
    var valid_form = true;
    $("textarea[name^=pc][name$=title]").each(function(index, element){
        if(element.value.trim() != '')
        {
            var n = element.name.split('_');
            var pc_reference = 'pc_'+n[1]+'_reference';
            if($.inArray($("input[name="+pc_reference+"]").val().trim(), pc_references) !== -1) // if in the references
            {
                $.alert({
                    title: 'Validation Error!',
                    icon: 'fa fa-warning',
                    type: 'red',
                    content: 'PC reference "' + $("input[name="+pc_reference+"]").val().trim() + '" is duplicate in this form.',
                    onDestroy: function(){
                        $("input[name="+pc_reference+"]").focus();
                    }
                });
                return valid_form = false;
            }
            else
            {
                pc_references.push($("input[name="+pc_reference+"]").val().trim());
            }
        }
    });
    if(valid_form)
        document.forms["frmAddUnit"].submit();
}

$("select[name=min_req_evidences]").on('change', function(){
    $("select[name$=min_req_evidences]").val(this.value);
});
</script>
@endsection

