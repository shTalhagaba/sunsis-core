<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="smaller">Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="form-group row required {{ $errors->has('status') ? 'has-error' : ''}}">
                        {!! Form::label('status', 'Status', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('status', ['1' => 'Active', '0' => 'Not Active'], null, ['class' => 'form-control']) !!}
                            {!! $errors->first('status', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('title', null, ['class' => 'form-control',
                            'required', 'maxlength' => '100']) !!}
                            {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('duration') ? 'has-error' : ''}}">
                        {!! Form::label('duration', 'Duration (enter number of months)', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('duration', null, ['class' => 'form-control', 'min' => 0, 'required']) !!}
                            {!! $errors->first('duration', '<p class="text-danger">:message</p>') !!}
                            <span class="help-block text-info"><i class="fa fa-info-circle"></i> This should not include EPA duration.</span>
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('epa_duration') ? 'has-error' : ''}}">
                        {!! Form::label('epa_duration', 'EPA Duration (enter number of months)', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('epa_duration', null, ['class' => 'form-control', 'min' => 0, 'required']) !!}
                            {!! $errors->first('epa_duration', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('programme_type') ? 'has-error' : ''}}">
                        {!! Form::label('programme_type', 'Programme Type', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('programme_type', \App\Models\Lookups\ProgrammeTypeLookup::getSelectData(), null, ['class' => 'form-control ', 'placeholder' => '']) !!}
                            {!! $errors->first('programme_type', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('reference_number') ? 'has-error' : ''}}">
                        {!! Form::label('reference_number', 'Reference Number', ['class' => 'col-sm-4 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('reference_number', null, ['class' => 'form-control', 'maxlength' => '8']) !!}
                            {!! $errors->first('reference_number', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('lars_standard_code') ? 'has-error' : ''}}">
                        {!! Form::label('lars_standard_code', 'LARS Standard Code', ['class' => 'col-sm-4 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('lars_standard_code', null, ['class' => 'form-control', 'maxlength' => '8']) !!}
                            {!! $errors->first('lars_standard_code', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('otj_hours') ? 'has-error' : ''}}">
                        {!! Form::label('otj_hours', 'Off-the-job Hours', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('otj_hours', null, ['class' => 'form-control', 'min' => 0]) !!}
                            {!! $errors->first('otj_hours', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('first_review') ? 'has-error' : ''}}">
                        {!! Form::label('first_review', 'First Review (enter weeks)', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('first_review', null, ['class' => 'form-control', 'min' => 0]) !!}
                            {!! $errors->first('first_review', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('review_frequency') ? 'has-error' : ''}}">
                        {!! Form::label('review_frequency', 'Review Frequency (every [x] weeks after the first review)', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('review_frequency', null, ['class' => 'form-control', 'min' => 0]) !!}
                            {!! $errors->first('review_frequency', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('leeway') ? 'has-error' : ''}}">
                        {!! Form::label('leeway', 'Leeway Period to calculate the Target Progress (enter weeks)', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('leeway', $programme->leeway ?? 0, ['class' => 'form-control', 'min' => 0]) !!}
                            {!! $errors->first('leeway', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>

                    {{-- @if(App\Facades\AppConfig::get('SUNESIS-INTEGRATION') == "ON")
                    <div class="form-group row {{ $errors->has('sunesis_framework_id') ? 'has-error' : ''}}">
                        {!! Form::label('sunesis_framework_id', 'Sunesis Programme', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('sunesis_framework_id', App\Helpers\SunesisHelper::getDropdown('frameworks', 'id', 'title'), null, ['class' => 'form-control', 'placeholder' => '']) !!}
                            {!! $errors->first('sunesis_framework_id', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    @endif --}}
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
    </div>

</div>

