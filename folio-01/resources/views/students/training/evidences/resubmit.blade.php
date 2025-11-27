@extends('layouts.master')

@section('title', 'Resubmit Evidence')

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

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.evidences.resubmit', $student, $training_record, $evidence) }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>Resubmit Evidence <i class="fa fa-file-text"></i> <small>{{ $training_record->system_ref }}</small></h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.training.show', [$student, $training_record]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Learner Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Learner </div>
                                    <div class="info-div-value"><span>{{ $student->full_name }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Primary Email </div>
                                    <div class="info-div-value">
                                        <span>
                                            <i class="fa fa-envelope blue bigger-110"></i> {{ $student->primary_email }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Employer </div>
                                    <div class="info-div-value"><span>{{ $student->employer->legal_name }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Training Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Status </div>
                                    <div class="info-div-value"><span><span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span></span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Dates </div>
                                    <div class="info-div-value">
                                        <span>{{ $training_record->start_date }} - {{ $training_record->planned_end_date }}</span>
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Portfolio(s) </div>
                                    <div class="info-div-value">
                                        @foreach($training_record->portfolios AS $portfolio)
                                        <span><i class="fa fa-graduation-cap"></i> {{ $portfolio->qan }} - {{ $portfolio->title }}</span><br>
                                        @endforeach
                                    </div>
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
            <div class="col-sm-7">
                <div class="widget-box widget-color-blue2 light-border">
                    <div class="widget-header"><h5 class="widget-title">Evidence Resubmission Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="alert alert-danger print-error-msg" style="display:none">
                                <ul></ul>
                            </div>
                            <div class="row">
                                {!! Form::model($evidence->getAttributes(), [
                                    'method' => 'PATCH',
                                    'url' => route('students.training.evidences.saveResubmit', [$student, $training_record, $evidence]),
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'id' => 'frmEvidence'])
                                !!}
                                {!! Form::hidden('evidence_type', null) !!}
                                <div class="col-sm-12">
                                    @if ($evidence->getOriginal('evidence_type') == 1)
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
                                    @elseif($evidence->getOriginal('evidence_type') == 2)
                                    <div class="form-group row {{ $errors->has('evidence_url') ? 'has-error' : ''}}" id="rowEvidenceURL">
                                        {!! Form::label('evidence_url', 'Evidence URL', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('evidence_url', null, ['class' => 'form-control', 'id' => 'evidence_url']) !!}
                                            {!! $errors->first('evidence_url', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    @else
                                    <div class="form-group row {{ $errors->has('evidence_ref') ? 'has-error' : ''}}" id="rowEvidenceRef">
                                        {!! Form::label('evidence_ref', 'Evidence Reference', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('evidence_ref', null, ['class' => 'form-control', 'id' => 'evidence_ref']) !!}
                                            {!! $errors->first('evidence_ref', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="form-group row required {{ $errors->has('evidence_name') ? 'has-error' : ''}}">
                                        {!! Form::label('evidence_name', 'Evidence Name', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('evidence_name', null, ['class' => 'form-control inputLimiter', 'id' => 'evidence_name', 'maxlength' => 250, 'required' => 'required']) !!}
                                            {!! $errors->first('evidence_name', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('evidence_desc') ? 'has-error' : ''}}">
                                        {!! Form::label('evidence_desc', 'Evidence Description', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('evidence_desc', null, ['class' => 'form-control inputLimiter', 'rows' => '3', 'id' => 'evidence_desc', 'maxlength' => 500]) !!}
                                            {!! $errors->first('evidence_desc', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('learner_comments') ? 'has-error' : ''}}">
                                        {!! Form::label('learner_comments', 'Learner Comments', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::textarea('learner_comments', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'learner_comments', 'maxlength' => 500]) !!}
                                            {!! $errors->first('learner_comments', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('assessment_method') ? 'has-error' : ''}}">
                                        {!! Form::label('assessment_method', 'Assessment Method', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('assessment_method', \App\Models\Training\TrainingRecordEvidence::getDDLEvidenceAssessmentMethods(), null, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                                            {!! $errors->first('assessment_method', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('learner_declaration') ? 'has-error' : ''}}">
                                        {!! Form::label('learner_declaration', 'Tick this box to confirm that this is your own work', ['class' => 'col-sm-4 control-label small']) !!}
                                        <div class="col-sm-8">
                                            <div class="checkbox">
                                                <label class="block">
                                                <input name="learner_declaration" type="checkbox" class="ace input-lg" value="1" required>
                                                <span class="lbl bigger-120"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions center">
                                        <button class="btn btn-sm btn-success btn-round" type="submit">
                                            <i class="ace-icon fa fa-save bigger-110"></i>Save Evidence
                                        </button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="widget-box">
                    <div class="widget-header"><h5 class="widget-title smaller">Assessor Comments</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Evidence Status </div>
                                    <div class="profile-info-value">
                                        <span><label class="label label-md label-danger arrowed-in arrowed-in-right">{{ $evidence->evidence_status }}<label></span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Assessor Comments </div><div class="profile-info-value"><span><small>{!! nl2br($evidence->assessor_comments) !!}</small></span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> </div>
                                    <div class="profile-info-value">
                                        @if($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_URL)
                                            <a href="{{ $evidence->evidence_url }}" target="_blank">{{ $evidence->evidence_url }}</a>
                                        @elseif($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_REFERENCE)
                                            {{ $evidence->evidence_ref }}
                                        @elseif($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_FILE)
                                            @foreach($evidence->media AS $mediaItem)
                                            @php
                                            $file_details = 'File Size: ' . $mediaItem->size . '<br>';
                                            $file_details .= '<i class=\'fa fa-clock-o\'></i> ' . \Carbon\Carbon::parse($mediaItem->updated_at)->format('d/m/Y H:i:s') . '<br>';
                                            @endphp
                                            <a href="{{ route('files.download',  $mediaItem) }}">
                                                <i
                                                data-trigger="hover"
                                                data-rel="popover"
                                                data-original-title="{{ $mediaItem->file_name }}"
                                                data-content="{{ $file_details }}"
                                                class='fa {{ \App\Models\LookupManager::getFileIcon($mediaItem->file_name) }} fa-2x'></i>
                                            </a> &nbsp;
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"></div>
                                    <div class="profile-info-value"><a class="btn btn-round btn-sm btn-info btn-block" href="{{ route('evidences.downloadArchive',  $evidence) }}">Download All</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">

var training_record_id = '{{ $training_record->id }}';
var uploadedDocumentMap = {};

  Dropzone.options.documentDropzone = {
    url: '{{ route("evidences.storeMedia") }}',
    maxFilesize: 10, // MB
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
    $('.inputLimiter').inputlimiter();

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

        messages: {
            email: {
                required: "Please provide a valid email.",
                email: "Please provide a valid email."
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
                data: $(form).serialize(),
                success: function(response) {

                    if($.isEmptyObject(response.error))
                    {
                        window.location.href="{{ route('students.training.show', [$student, $training_record]) }}";
                    }
                    else
                    {
                        printErrorMsg(response.error);
                    }
                },
                error: function (response) {
                    alert(response);
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
$(function(){
    $('[data-rel=tooltip]').tooltip();
    $('[data-rel=popover]').popover({
        html:true,
        placement:"auto"
    });
});
</script>
@endsection

