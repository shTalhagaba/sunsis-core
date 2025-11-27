<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="smaller">Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                                 {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::text('title', null, ['class' => 'form-control inputLimiter', 'required', 'maxlength' => '200']) !!}
                                     {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('assessor') ? 'has-error' : ''}}">
                                {!! Form::label('assessor', 'Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('assessor', $assessors, null, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                    {!! $errors->first('assessor', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('due_date') ? 'has-error' : ''}}">
                                 {!! Form::label('due_date', 'Due Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::date('due_date', null, ['class' => 'form-control', 'required']) !!}
                                     {!! $errors->first('due_date', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('start_time') ? 'has-error' : ''}}">
                                 {!! Form::label('start_time', 'Start Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::time('start_time', null, ['class' => 'form-control', 'required']) !!}
                                     {!! $errors->first('start_time', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('end_time') ? 'has-error' : ''}}">
                                 {!! Form::label('end_time', 'End Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::time('end_time', null, ['class' => 'form-control', 'required']) !!}
                                     {!! $errors->first('end_time', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            {{-- <div class="form-group row required {{ $errors->has('meeting_date') ? 'has-error' : ''}}">
                                 {!! Form::label('meeting_date', 'Meeting Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::date('meeting_date', null, ['class' => 'form-control', 'required']) !!}
                                     {!! $errors->first('meeting_date', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div> --}}
                            <div class="form-group row required {{ $errors->has('type_of_review') ? 'has-error' : ''}}">
                                 {!! Form::label('type_of_review', 'Type of Review', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                    {!! Form::select('type_of_review', $review_types, null, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                     {!! $errors->first('type_of_review', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            <div class="form-group row {{ $errors->has('portfolio_id') ? 'has-error' : ''}}">
                                 {!! Form::label('portfolio_id', 'Portfolio/Qualification', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                    {!! Form::select('portfolio_id', $portfolios, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                     {!! $errors->first('portfolio_id', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group row {{ $errors->has('comments') ? 'has-error' : ''}}">
                                {!! Form::label('comments', 'Comments', ['class' => 'col-sm-12']) !!}
                                <div class="col-sm-12">
                                    {!! Form::textarea('comments', null, ['class' => 'form-control inputLimiter', 'rows' => '15', 'id' => 'details', 'maxlength' => 500]) !!}
                                    {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>Save Information
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
