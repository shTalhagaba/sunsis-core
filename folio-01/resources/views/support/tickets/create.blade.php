@extends('layouts.master')

@section('title', 'Raise New Ticket')

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
{{ Breadcrumbs::render('support.tickets.create') }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>Create New Ticket <small><i class="ace-icon fa fa-angle-double-right"></i> use this functionality to raise a support ticket.</small></h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box widget-color-blue2 light-border">
                <div class="widget-header"><h5 class="widget-title">Ticket Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        {!! Form::open(['url' => route('support.tickets.store'), 'class' => 'form-horizontal', 'files' => true, 'id' => 'frmTicket']) !!}
                        {!! Form::hidden('status_id', \App\Models\Support\Status::TYPE_NEW) !!}
                        {!! Form::hidden('author_id', Auth::user()->id) !!}
                        <div class="form-group row required {{ $errors->has('category_id') ? 'has-error' : ''}}">
                            {!! Form::label('category_id', 'Category', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::select('category_id', \App\Models\Support\Category::getList(), null, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                                {!! $errors->first('category_id', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('priority_id') ? 'has-error' : ''}}">
                            {!! Form::label('priority_id', 'Priority', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::select('priority_id', \App\Models\Support\Priority::getList(), null, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                                {!! $errors->first('priority_id', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                            {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::text('title', null, ['class' => 'form-control inputLimiter', 'required' => 'required', 'maxlength' => 150]) !!}
                                {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('author_email') ? 'has-error' : ''}}">
                            {!! Form::label('author_email', 'Your Email', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::email('author_email', \Auth::user()->primary_email, ['class' => 'form-control inputLimiter', 'required' => 'required', 'maxlength' => 200]) !!}
                                {!! $errors->first('author_email', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('content') ? 'has-error' : ''}}">
                            {!! Form::label('content', 'Content', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::textarea('content', null, ['class' => 'form-control inputLimiter', 'id' => 'content', 'maxlength' => 850, 'required' => 'required']) !!}
                                {!! $errors->first('content', '<p class="text-danger">:message</p>') !!}
                                <span class="help-inline"><span class="middle small">Please include details of the action you were attempting to complete.</span></span>
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('attachments') ? 'has-error' : ''}}">
                            {!! Form::label('attachments', 'Attachments', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                <div class="needsclick dropzone" id="document-dropzone">

                                </div>
                                <span class="help-inline"><span class="middle small">If there is a screenshot or document you can send us to help us understand your query, please attach it here</span></span>
                            </div>
                        </div>
                        <div class="clearfix form-actions center">
                            <button class="btn btn-sm btn-success" type="submit">
                                 Send <i class="ace-icon fa fa-angle-double-right"></i>
                            </button>
                        </div>

                        {!! Form::close() !!}
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

var uploadedDocumentMap = {};
  Dropzone.options.documentDropzone = {
    url: '{{ route("support.tickets.storeMedia") }}',
    maxFilesize: 5, // MB
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
      $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">');
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
      $('form').find('input[name="attachments[]"][value="' + name + '"]').remove();

        $.ajax({
            type: 'POST',
            url: '{{ route("support.tickets.removeMedia") }}',
            data: {name: name,request: 2, _token: "{{ csrf_token() }}"},
            success: function(data){
                console.log(data.message);
            },
            error: function(error) {
                alert(error.responseText);
            }
        });

    },
    init: function () {

    }
  };

  $(function(){
    $('.inputLimiter').inputlimiter();

    $('#frmTicket').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,

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
                    window.location.href="{{ route('support.tickets.index') }}";
                },
                error: function (response) {
                    console.log(response);
                }
            });
        }

    });

  });

    $body = $("body");

    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }
    });

</script>
@endsection

