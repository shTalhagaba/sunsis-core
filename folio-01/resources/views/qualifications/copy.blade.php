@extends('layouts.master')
@section('title', 'Qualifications')
@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('qualifications.copy', $qualification) }}
@endsection

@section('page-content')
<div class="page-header"><h1>{{ $qualification->title }}</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="well well-sm">
                <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('qualifications.show', [$qualification]) }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                </button>
            </div>

            <div class="col-sm-12">

                <div class="widget-box">
                    {!! Form::open([
                        'method' => 'POST',
                        'url' => route('qualifications.copy', $qualification),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmCopyQualification']) !!}
                    <div class="widget-header"><h4 class="smaller">Copy Qualification</h4></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="form-group row required {{ $errors->has('new_qualification_title') ? 'has-error' : ''}}">
                                {!! Form::label('new_qualification_title', 'New Qualification Title', ['class' => 'col-sm-3 control-label
                                no-padding-right']) !!}
                                <div class="col-sm-9">
                                    {!! Form::text('new_qualification_title', $qualification->title . ' Copy', ['class' => 'form-control inputLimiter',
                                    'required', 'maxlength' => '250']) !!}
                                    {!! $errors->first('new_qualification_title', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <h5>Select the units and pcs you want to copy</h5>
                                @foreach($qualification->units AS $unit)
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        <th class="center">
                                            <div class="checkbox">
                                                <label>
                                                    <input checked onclick = "chkUnitClicked(this);" name="chkUnit[]" id="chkUnit{{ $unit->id }}" value="{{ $unit->id }}" class="ace ace-checkbox-2 chkUnit" type="checkbox" />
                                                    <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        </th>
                                        <th class="brown"><i class="fa fa-folder fa-lg"></i><h5 style="display: inline;"> {{ $unit->title }}</h5>
                                    </tr>
                                    @foreach($unit->pcs AS $pc)
                                    <tr>
                                        <td class="center">
                                            <div class="checkbox">
                                                <label>
                                                    <input checked onclick = "chkPCClicked(this);" name="chkPC[]" id="pc{{ $pc->id }}OfUnit{{ $unit->id }}" value="{{ $pc->id }}" class="ace ace-checkbox-2 chkPC" type="checkbox" />
                                                    <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="blue"><i class="fa fa-folder-open"></i> {{ $pc->title }}</span></td>
                                    </tr>
                                    @endforeach
                                </table>
                                @endforeach
                            </div>
                        </div>
                        <div class="widget-toolbox padding-8 clearfix">
                            <div class="center">

                                <button class="btn btn-sm btn-round btn-success" type="button" id="btnSubmitForm">
                                    <i class="ace-icon fa fa-save bigger-110"></i>
                                    Create Copy
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn btn-sm btn-round" type="button" onclick="window.history.back();">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Cancel
                                </button>

                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>


            </div>
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">

	$(function(){

			$('[data-rel=tooltip]').tooltip();

            $("button[id=btnSubmitForm]").on('click', function(e){
                e.preventDefault();
                $(this).attr('disabled', true);
                $(this).html('<i class="fa fa-refresh fa-spin"></i> Creating Copy ...');
                var myForm = $(this).closest('form');
                myForm.submit();
            });

	});

    function chkUnitClicked(element)
    {
        var unit_number = element.id.replace('chkUnit', '');
        if(element.checked)
        {
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                $(this).prop('checked', true);
            });
        }
        else
        {
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                $(this).prop('checked', false);
            });
        }
    }

    function chkPCClicked(element)
    {
        var unit_number = element.id.replace('pc'+element.value+'OfUnit', '');

        if(element.checked) // if pc is clicked then check the Unit checkbox too.
        {
            $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
        }
        else // if all pcs of a unit are unticked then untick the unit
        {
            var allPCUnChecked = true;
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                if(this.checked)
                {
                    allPCUnChecked = false;
                    return false;
                }
            });
            if(allPCUnChecked)
            {
                $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', false);
            }
        }
    }

</script>
@endsection
