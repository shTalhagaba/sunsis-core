<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="smaller">Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('title', null, ['class' => 'form-control inputLimiter',
                            'required', 'maxlength' => '250']) !!}
                            {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('start_date') ? 'has-error' : ''}}">
                        {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4
                        control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::date('start_date', null, ['class' => 'form-control inputLimiter', 'required']) !!}
                            {!! $errors->first('start_date', '<p class="text-danger">:message</p>')
                            !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('end_date') ? 'has-error' : ''}}">
                        {!! Form::label('end_date', 'End Date', ['class' =>
                        'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::date('end_date', null, ['class' => 'form-control inputLimiter', 'required']) !!}
                            {!! $errors->first('end_date', '<p class="text-danger">:message</p>')
                            !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('programme_type') ? 'has-error' : ''}}">
                        {!! Form::label('programme_type', 'Programme Type', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('programme_type', \App\Models\Programmes\Programme::getProgrammeType(), null, ['class' => 'form-control ', 'placeholder' => '']) !!}
                            {!! $errors->first('programme_type', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status', 'Status', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('status', ['1' => 'Active', '0' => 'Not Active'], null, ['class' => 'form-control']) !!}
                            {!! $errors->first('status', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    @if ($showQualDDL)
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group row required {{ $errors->has('qualification_ids') ? 'has-error' : ''}}">
                                {!! Form::label('qualification_ids', 'Qualifications', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('qualification_ids[]', $qualifications, null, ['class' => 'form-control chosen-select', 'required',
                                     'multiple' => 'multiple']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group row {{ $errors->has('comments') ? 'has-error' : ''}}">
                        {!! Form::label('comments', 'Comments', ['class' => 'col-sm-4 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('comments', null, ['class' => 'form-control inputLimiter',
                            'maxlength' => '500']) !!}
                            {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

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
