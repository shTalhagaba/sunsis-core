@extends('layouts.master')

@section('page-inline-styles')


@endsection

@section('title', 'Create Learning Resource')

@section('page-content')
    <div class="page-header">
        <h1>
            Create Learning Resource
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                add a new learning resource in the system
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('learning_resources.index') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_error')
            @include('partials.session_message')

            <div id="row">
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::open([
                        'url' => route('learning_resources.store'),
                        'id' => 'frmLearningResource',
                        'files' => true
                    ]) !!}
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="smaller">Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-group required {{ $errors->has('resource_type') ? 'has-error' : '' }}">
                                    {!! Form::label('resource_type', 'Type', ['class' => 'control-label']) !!}
                                    {!! Form::select('resource_type', $resourceTypes, null, ['class' => 'form-control ', 'placeholder' => '', 'required']) !!}
                                    {!! $errors->first('resource_type', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group required {{ $errors->has('resource_name') ? 'has-error' : '' }}">
                                    {!! Form::label('resource_name', 'Name', ['class' => 'control-label']) !!}
                                    {!! Form::text('resource_name', null, ['class' => 'form-control', 'required', 'maxlength' => '100']) !!}
                                    {!! $errors->first('resource_name', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group required {{ $errors->has('resource_short_description') ? 'has-error' : '' }}">
                                    {!! Form::label('resource_short_description', 'Short Description', ['class' => 'control-label']) !!}
                                    {!! Form::textarea('resource_short_description', null, ['class' => 'form-control', 'required', 'maxlength' => '1000', 'rows' => 3]) !!}
                                    {!! $errors->first('resource_short_description', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group {{ $errors->has('is_featured') ? 'has-error' : '' }}">
                                    {!! Form::label('is_featured', 'Featured Resource', ['class' => 'control-label']) !!}
                                    <div>
                                        <input name="is_featured" class="ace ace-switch ace-switch-2" type="checkbox" value="1">
                                        <span class="lbl"></span>
                                    </div>
                                </div>
                                <div class="form-group required {{ $errors->has('learning_resource_file') ? 'has-error' : '' }}" id="formGroupFileControl" style="display: none;">
                                    {!! Form::label('learning_resource_file', 'File', ['class' => 'control-label']) !!}
                                    {{-- @include(
                                        'partials.ace_file_control',
                                        ['aceFileControlRequired' => false, 'aceFileControlId' => 'learning_resource_file', 'aceFileControlName' => 'learning_resource_file']
                                    ) --}}
                                    {!! Form::file('learning_resource_file', ['id' => 'learning_resource_file', 'class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('learning_resource_file', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group required {{ $errors->has('resource_url') ? 'has-error' : '' }}" id="formGroupUrlControl" style="display: none;">
                                    {!! Form::label('resource_url', 'URL', ['class' => 'control-label']) !!}
                                    {!! Form::text('resource_url', null, ['class' => 'form-control', 'maxlength' => '2048']) !!}
                                    {!! $errors->first('resource_url', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group required {{ $errors->has('resource_content') ? 'has-error' : '' }}" id="formGroupTextControl" style="display: none;">
                                    {!! Form::label('resource_content_html', 'Content', ['class' => 'control-label']) !!}
                                    <div style="border: 1px solid gray; border-radius: 3px">
                                        <div class="wysiwyg-editor" id="resource_content_html" name="resource_content_html"></div>
                                    </div>                                    
                                    {!! $errors->first('resource_content', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i>
                                        Save Information
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                    <small class="text-info">{{ config('medialibrary.max_file_size') }}</small>
                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-inline-scripts')

    <script src="{{ asset('assets/js/jquery.hotkeys.index.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-wysiwyg.min.js') }}"></script>

    <script type="text/javascript">
        $(function(){

            const TYPE_FILE_UPLOAD = {{ App\Models\Lookups\LearningResourceTypeLookup::TYPE_FILE_UPLOAD }};
            const TYPE_URL = {{ App\Models\Lookups\LearningResourceTypeLookup::TYPE_URL }};
            const TYPE_TEXT = {{ App\Models\Lookups\LearningResourceTypeLookup::TYPE_TEXT }};

            const formGroupFileControl = $("div#formGroupFileControl");
            const formGroupUrlControl = $("div#formGroupUrlControl");
            const formGroupTextControl = $("div#formGroupTextControl");

            $('#resource_content_html').ace_wysiwyg({
                toolbar:
                [
                    {name:'font', className:'btn-round'},
                    null,
                    {name:'fontSize', className:'btn-round'},
                    null,
                    {name:'bold', className:'btn-info btn-round'},
                    {name:'italic', className:'btn-info btn-round'},
                    {name:'strikethrough', className:'btn-info btn-round'},
                    {name:'underline', className:'btn-info btn-round'},
                    null,
                    {name:'insertunorderedlist', className:'btn-success btn-round'},
                    {name:'insertorderedlist', className:'btn-success btn-round'},
                    {name:'outdent', className:'btn-purple btn-round'},
                    {name:'indent', className:'btn-purple btn-round'},
                    null,
                    {name:'justifyleft', className:'btn-primary btn-round'},
                    {name:'justifycenter', className:'btn-primary btn-round'},
                    {name:'justifyright', className:'btn-primary btn-round'},
                    {name:'justifyfull', className:'btn-inverse btn-round'},
                    null,
                    // {name:'createLink', className:'btn-pink btn-round'},
                    {name:'unlink', className:'btn-pink btn-round'},
                    null,
                    // {name:'insertImage', className:'btn-success btn-round'},
                    null,
                    'foreColor',
                    null,
                    {name:'undo', className:'btn-grey btn-round'},
                    {name:'redo', className:'btn-grey btn-round'}
                ],
                'wysiwyg': {
                    fileUploadError: showErrorAlert
                }
            });
            
            $("form#frmLearningResource").on("submit", function(){
                const form = this;
                var hidden_input = $('<input type="hidden" name="resource_content" />').appendTo('#frmLearningResource');
                var html_content = $('#resource_content_html').html();
                hidden_input.val( html_content );
                $(form).find(':submit').attr("disabled", true);
                $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                $(form).closest('div.widget-box').widget_box('reload');
                form.submit();
            });
            
            $("select[name=resource_type]").on('change', function(){
                formGroupFileControl.hide().find("input,textarea,select").prop("required", false);
                formGroupUrlControl.hide().find("input,textarea,select").prop("required", false);
                formGroupTextControl.hide().find("input,textarea,select").prop("required", false);


                // Show the relevant control group based on the selected value
                switch (parseInt(this.value, 10)) {
                    case TYPE_FILE_UPLOAD:
                        formGroupFileControl.show()
                            .find("input,textarea,select").prop("required", true);
                        break;
                    case TYPE_URL:
                        formGroupUrlControl.show()
                            .find("input,textarea,select").prop("required", true);
                        break;
                    case TYPE_TEXT:
                        formGroupTextControl.show()
                            .find("input,textarea,select").prop("required", true);
                        break;
                }
            });

            @if($errors->any())
                @if($errors->has('learning_resource_file'))
                    formGroupFileControl.show();
                @endif
                @if($errors->has('resource_url'))
                    formGroupUrlControl.show();
                @endif
                @if($errors->has('resource_content'))
                    formGroupTextControl.show();
                @endif
            @endif

        });

        function showErrorAlert (reason, detail) 
        {
            var msg = '';
            if (reason === 'unsupported-file-type') 
            { 
                msg = "Unsupported format " +detail; 
            }
            else 
            {
                console.log("error uploading file", reason, detail);
            }
            
            $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
                '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	    }

        var oldContent = {!! json_encode(old('resource_content')) !!};
        if (oldContent) 
        {
            $('#resource_content_html').html(oldContent);
        }

    </script>

@endsection
