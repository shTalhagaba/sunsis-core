@extends('layouts.master')

@section('title', 'Create New Evidence')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
<style>
/* Start by setting display:none to make this hidden.
   Then we position it in relation to the viewport window
   with position:fixed. Width, height, top and left speak
   for themselves. Background we set to 80% white with
   our animation centered, and no-repeating */
   .modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 )
                url('{{ asset('images/ajax-loader.gif') }}')
                50% 50%
                no-repeat;
}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading .modal {
    overflow: hidden;
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal {
    display: block;
}
</style>
@endsection

@section('page-content')
<div class="page-header">
   <h1>Create Evidence <i class="fa fa-file-text"></i> <small>{{ $training->system_ref }}</small></h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('trainings.show', $training) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        <div class="hr hr-12 hr-dotted"></div>

        @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

        <div class="space-12"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box widget-color-green">
                    <div class="widget-header"><h4 class="widget-title">Evidence Details</h4></div>
                    {!! Form::open([
                        'url' => route('trainings.evidences.store', $training),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'frmEvidence'])
                    !!}
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="alert alert-danger print-error-msg" style="display:none">
                                <ul><li>Here</li></ul>
                            </div>
                            <div class="row">

                                <div class="col-sm-2">
                                    <span>Evidence Type:</span>
                                    <div class="radio">
                                        <label>
                                            <input name="evidence_type" type="radio" class="ace input-lg" value="rowEvidenceFile" checked />
                                            <span class="lbl"> File Upload</span>
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input name="evidence_type" type="radio" class="ace input-lg" value="rowEvidenceURL" />
                                            <span class="lbl"> External URL</span>
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input name="evidence_type" type="radio" class="ace input-lg" value="rowEvidenceRef" />
                                            <span class="lbl"> Reference to evidence</span>
                                        </label>
                                    </div>
                                    <div style="margin-top: 10px; justify-content: center; display: flex;">
                                        <button type="button" class="btn btn-xs btn-info btn-round" 
                                            onclick="window.location.href='{{ route('trainings.evidences.create', $training) }}?_type=typed'">   
                                            Typed Evidence
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-10" style="border-left: 1px solid #333;">
                                    <div class="form-group row {{ $errors->has('evidence_file') ? 'has-error' : ''}}" id="rowEvidenceFile">
                                        {!! Form::label('evidence_file', 'File', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {{-- <input type="file" class="form-control" name="evidence_file" id="evidence_file"> --}}
                                            <div class="needsclick dropzone" id="document-dropzone">

                                            </div>
                                        </div>
                                    </div>
                                    <div id="preview-template" class="hide">
                                        <div class="dz-preview dz-file-preview">
                                            <div class="dz-image">
                                                <img data-dz-thumbnail="" />
                                            </div>

                                            <div class="dz-details">
                                                <div class="dz-size">
                                                    <span data-dz-size=""></span>
                                                </div>

                                                <div class="dz-filename">
                                                    <span data-dz-name=""></span>
                                                </div>
                                            </div>

                                            <div class="dz-progress">
                                                <span class="dz-upload" data-dz-uploadprogress=""></span>
                                            </div>

                                            <div class="dz-error-message">
                                                <span data-dz-errormessage=""></span>
                                            </div>

                                            <div class="dz-success-mark">
                                                <span class="fa-stack fa-lg bigger-150">
                                                    <i class="fa fa-circle fa-stack-2x white"></i>

                                                    <i class="fa fa-check fa-stack-1x fa-inverse green"></i>
                                                </span>
                                            </div>

                                            <div class="dz-error-mark">
                                                <span class="fa-stack fa-lg bigger-150">
                                                    <i class="fa fa-circle fa-stack-2x white"></i>

                                                    <i class="fa fa-remove fa-stack-1x fa-inverse red"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('evidence_url') ? 'has-error' : ''}}" id="rowEvidenceURL" style="display: none;">
                                        {!! Form::label('evidence_url', 'Evidence URL', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('evidence_url', null, ['class' => 'form-control', 'id' => 'evidence_url']) !!}
                                            {!! $errors->first('evidence_url', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('evidence_ref') ? 'has-error' : ''}}" id="rowEvidenceRef" style="display: none;">
                                        {!! Form::label('evidence_ref', 'Evidence Reference', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('evidence_ref', null, ['class' => 'form-control', 'id' => 'evidence_ref']) !!}
                                            {!! $errors->first('evidence_ref', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('evidence_name') ? 'has-error' : ''}}">
                                        {!! Form::label('evidence_name', 'Evidence Name', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('evidence_name', null, ['class' => 'form-control', 'id' => 'evidence_name', 'maxlength' => 250, 'required' => 'required']) !!}
                                            {!! $errors->first('evidence_name', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('evidence_desc') ? 'has-error' : ''}}">
                                        {!! Form::label('evidence_desc', 'Evidence Description', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('evidence_desc', null, ['class' => 'form-control', 'rows' => '3', 'id' => 'evidence_desc', 'maxlength' => 1500]) !!}
                                            {!! $errors->first('evidence_desc', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('learner_comments') ? 'has-error' : ''}}">
                                        {!! Form::label('learner_comments', 'Learner Comments', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('learner_comments', null, ['class' => 'form-control', 'rows' => '5', 'id' => 'learner_comments', 'maxlength' => 500]) !!}
                                            {!! $errors->first('learner_comments', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('tr_dp_task_id') ? 'has-error' : '' }}">
                                        {!! Form::label('tr_dp_task_id', 'Delivery Plan Task', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('tr_dp_task_id', $trDpTasks, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                            {!! $errors->first('tr_dp_task_id', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('learner_declaration') ? 'has-error' : ''}}">
                                        {!! Form::label('learner_declaration', 'Tick this box to confirm that this is your own work', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            <div class="checkbox">
                                                <label class="block">
                                                <input name="learner_declaration" type="checkbox" class="ace input-lg" value="1" required>
                                                <span class="lbl bigger-120"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="widget-toolbox padding-8 clearfix">
                            <div class="center">
                                <button id="btnSubmitFrmEvidence" class="btn btn-sm btn-success btn-round" type="submit">
                                    <i class="ace-icon fa fa-save bigger-110"></i>Save Evidence
                                </button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal"><!-- Place at bottom of page --></div>
        <!-- PAGE CONTENT ENDS -->

    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">

$('input[type=radio][name=evidence_type]').on('change', function(){
    $('input[type=radio][name=evidence_type]').each(function(){
        $('#'+this.value).hide();
    });
    $('#'+this.value).show();


    if(Dropzone.forElement('.dropzone').files.length > 0)
        Dropzone.forElement('.dropzone').removeAllFiles(true);

});

var training_record_id = '{{ $training->id }}';
var uploadedDocumentMap = {};

  Dropzone.options.documentDropzone = {
    url: '{{ route("evidences.storeMedia") }}',
    maxFilesize: {{ config('medialibrary.max_file_size')/1048576 }}, // MB
    acceptedFiles: '{{ implode(",.", config('medialibrary.allowed_extensions')) }}',
    addRemoveLinks: true,
    dictDefaultMessage :
        '<span class="bigger-150 bolder"><i class="ace-icon fa fa-caret-right red"></i> Drop files</span> to upload \
        <span class="smaller-80 grey">(or click)</span> <br /> \
        <i class="upload-icon ace-icon fa fa-cloud-upload blue fa-3x"></i>'
    ,
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    success: function (file, response) {
        $('form').append('<input type="hidden" name="evidence_file[]" value="' + response.name + '">');
        uploadedDocumentMap[file.name] = response.name;
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.success(response.original_name + ': ' + response.message);
    },
    removedfile: function (file) {
        file.previewElement.remove();
        var name = '';
        if (typeof file.file_name !== 'undefined') {
            name = file.file_name;
        } else {
            name = uploadedDocumentMap[file.name];
        }
        $('form').find('input[name="evidence_file[]"][value="' + name + '"]').remove();

        $.ajax({
            type: 'POST',
            url: '{{ route("evidences.removeMedia") }}',
            data: {name: name,request: 2, _token: "{{ csrf_token() }}", training_record_id: training_record_id},
            success: function(data){
                console.log(data.message);
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.success(data.message);
            },
            error: function(error) {
                alert(error.responseText);
            }
        });

    },
    init: function () {
        this.on("sending", function(file, xhr, formData) {
            formData.append("training_record_id", training_record_id);
        });
    }
  };

$(function(){

    $('#frmEvidence').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
            evidence_name: {
                required: true,
                minlength: 10
            },
            evidence_desc: {
                minlength: 10
            },
            evidence_url: {
                required: function(element) {
                    return $('input[type=radio][name=evidence_type]:checked').val() == "rowEvidenceURL";
                }
            },
            evidence_ref: {
                required: function(element) {
                    return $('input[type=radio][name=evidence_type]:checked').val() == "rowEvidenceRef";
                }
            }
        },

        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },

        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },

        errorPlacement: function (error, element) {
            if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else
                error.insertAfter(element);
        },

        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                dataType: "json",
                data: $(form).serialize(),
                success: function(response) {
                    console.log(' in success');
                    if($.isEmptyObject(response.error))
                    {
                        $('#btnSubmitFrmEvidence').attr('disabled', 'disabled');
                        window.location.href="{{ route('trainings.show', $training) }}";
                    }
                    else
                    {
                        printErrorMsg(response.error);
                    }
                },
                error: function (response) {
                    printErrorMsg(response.responseJSON.errors);
                    var errors = '';
                    $.each(response.responseJSON.errors, function(field, errorDetails){
                        errors += '\r\n' + errorDetails[0];
                    });
                    alert(response.status + ': ' + response.statusText + '\r\n' +  errors + '\r\n' + 'Please refresh the page and try again or raise a support request if problem persists.');
                }
            });
        }
    });

});

function printErrorMsg (msg)
{
    $(".print-error-msg").find("ul").html('');
    $(".print-error-msg").css('display','block');
    $.each( msg, function( key, value ) {
        $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
    });
}

$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
    ajaxStop: function() { $body.removeClass("loading"); }
});
</script>
@endsection

