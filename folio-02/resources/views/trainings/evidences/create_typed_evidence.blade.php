@extends('layouts.master')

@section('title', 'Create New Evidence')

@section('page-content')
    <div class="page-header">
        <h1>
            Create New Evidence
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                here you can type submission to create new evidence
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_error')
            @include('partials.session_message')

            <div id="row">
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::open([
                        'url' => route('trainings.evidences.store', $training),
                        'id' => 'frmEvidence',
                        'files' => true
                    ]) !!}
                    {!! Form::hidden('evidence_type', 'typed') !!}
                    {!! Form::hidden('training_id', $training->id) !!}
                    
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="smaller">Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-group required {{ $errors->has('evidence_name') ? 'has-error' : '' }}">
                                    {!! Form::label('evidence_name', 'Evidence Name', ['class' => 'control-label']) !!}
                                    {!! Form::text('evidence_name', null, ['class' => 'form-control', 'required', 'maxlength' => '100']) !!}
                                    {!! $errors->first('evidence_name', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group required {{ $errors->has('evidence_desc') ? 'has-error' : '' }}">
                                    {!! Form::label('evidence_desc', 'Evidence Description', ['class' => 'control-label']) !!}
                                    {!! Form::text('evidence_desc', null, ['class' => 'form-control', 'required', 'maxlength' => '100']) !!}
                                    {!! $errors->first('evidence_desc', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group required {{ $errors->has('evidence_text_content') ? 'has-error' : '' }}" id="formGroupTextControl">
                                    {!! Form::label('evidence_text_content_html', 'Content', ['class' => 'control-label']) !!}
                                    <div style="border: 1px solid gray; border-radius: 3px">
                                        <div class="wysiwyg-editor" id="evidence_text_content_html" name="evidence_text_content_html"></div>
                                    </div>                                    
                                    {!! $errors->first('evidence_text_content', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group required {{ $errors->has('learner_comments') ? 'has-error' : '' }}">
                                    {!! Form::label('learner_comments', 'Comments', ['class' => 'control-label']) !!}
                                    {!! Form::textarea('learner_comments', null, ['class' => 'form-control', 'required', 'rows' => 3, 'maxlength' => '500']) !!}
                                    {!! $errors->first('learner_comments', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div class="form-group required {{ $errors->has('learner_declaration') ? 'has-error' : '' }}">
                                    {!! Form::label('learner_declaration', 'Tick this box to confirm that this is your own work', ['class' => 'control-label']) !!}
                                    <div class="checkbox">
                                        <label class="block">
                                        <input name="learner_declaration" type="checkbox" class="ace input-lg" value="1" required id="learner_declaration">
                                        <span class="lbl bigger-120"></span>
                                        </label>
                                    </div>
                                    {!! $errors->first('learner_comments', '<p class="text-danger">:message</p>') !!}
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

            $('#evidence_text_content_html').ace_wysiwyg({
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
                    {name:'createLink', className:'btn-pink btn-round'},
                    {name:'unlink', className:'btn-pink btn-round'},
                    null,
                    {name:'insertImage', className:'btn-success btn-round'},
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
            
            $("form#frmEvidence").on("submit", function(){
                const form = this;
                var hidden_input = $('<input type="hidden" name="evidence_text_content" />').appendTo('#frmEvidence');
                var html_content = $('#evidence_text_content_html').html();
                hidden_input.val( html_content );
                $(form).find(':submit').attr("disabled", true);
                $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                $(form).closest('div.widget-box').widget_box('reload');
                form.submit();
            });
            
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

        var oldContent = {!! json_encode(old('evidence_text_content')) !!};
        if (oldContent) 
        {
            $('#evidence_text_content_html').html(oldContent);
        }

    </script>

@endsection
