{!! Form::hidden('message_id', $message_id ?? null) !!}

<div class="row">
    <div class="col-sm-12">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="widget-title">Compose Message</h4>
            </div>
            <div class="widget-body">
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button type="button" class="btn btn-xs btn-white btn-primary" id="btnSend">
                            <span class="bigger-110">Send Message</span>
                            <i class="ace-icon fa fa-arrow-right bigger-125 green"></i>
                        </button>
                        <button type="button" class="btn btn-xs btn-white btn-primary" id="btnDraft">
                            <i class="ace-icon fa fa-floppy-o bigger-125"></i>
                            <span class="bigger-110">Save Draft</span>
                        </button>
                    </div>
                </div>
                <div class="widget-main">
                    <div class="form-group row required {{ $errors->has('to_id') ? 'has-error' : ''}}">
                        {!! Form::label('to_id', 'To', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('to_id', $recipients, $to_id ??null, ['class' => 'form-control chosen-select', 'required', 'placeholder' => '']) !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('cc') ? 'has-error' : ''}}">
                        {!! Form::label('cc', 'Cc', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('cc[]', $recipients, null, ['class' => 'form-control chosen-select', 'required', 'multiple' => 'multiple']) !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('subject') ? 'has-error' : ''}}">
                        {!! Form::label('subject', 'Subject', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('subject', $subject ?? null, ['class' => 'form-control inputLimiter', 'maxlength' => 250]) !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('content') ? 'has-error' : ''}}">
                        {!! Form::label('message', 'Message *', ['class' => 'col-sm-4']) !!}
                        <div class="col-sm-12">
                            {!! Form::textarea('content', $content ?? null, ['class' => 'form-control', 'rows' => 15]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
