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
                            <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                                {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('title', null, ['class' => 'form-control', 'required', 'maxlength' => '100']) !!}
                                    {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('assessor') ? 'has-error' : '' }}">
                                {!! Form::label('assessor', 'Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('assessor', $assessorList, null, [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'required',
                                    ]) !!}
                                    {!! $errors->first('assessor', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('due_date') ? 'has-error' : '' }}">
                                {!! Form::label('due_date', 'Scheduled Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date('due_date', (isset($review) && !is_null($review->due_date)) ? $review->due_date->format('Y-m-d') : null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                    {!! $errors->first('due_date', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('start_time') ? 'has-error' : '' }}">
                                {!! Form::label('start_time', 'Start Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time('start_time', (isset($review) && !is_null($review->start_time)) ? \Carbon\Carbon::parse($review->start_time)->format('H:i') : null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                    {!! $errors->first('start_time', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('end_time') ? 'has-error' : '' }}">
                                {!! Form::label('end_time', 'End Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time('end_time', (isset($review) && !is_null($review->end_time)) ? \Carbon\Carbon::parse($review->end_time)->format('H:i') : null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
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
                            <div
                                class="form-group row required {{ $errors->has('type_of_review') ? 'has-error' : '' }}">
                                {!! Form::label('type_of_review', 'Type of Review', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('type_of_review', App\Models\LookupManager::getTrainingReviewTypes(), null, [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'required',
                                    ]) !!}
                                    {!! $errors->first('type_of_review', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('portfolio_id') ? 'has-error' : '' }}">
                                {!! Form::label('portfolio_id', 'Portfolio/Qualification', [
                                    'class' => 'col-sm-4 control-label no-padding-right',
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::select(
                                        'portfolio_id',
                                        $training->portfolios()->orderBy('title')->pluck('title', 'id')->toArray(),
                                        null,
                                        ['class' => 'form-control', 'placeholder' => ''],
                                    ) !!}
                                    {!! $errors->first('portfolio_id', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
			                @if(!isset($review))
                            <div class="form-group row {{ $errors->has('review_file_attachment') ? 'has-error' : '' }}">
                                {!! Form::label('review_file_attachment', 'File', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    @include(
                                        'partials.ace_file_control',
                                        ['aceFileControlRequired' => false, 'aceFileControlId' => 'review_file_attachment', 'aceFileControlName' => 'review_file_attachment']
                                    )
                                    {!! $errors->first('review_file_attachment', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group row {{ $errors->has('comments') ? 'has-error' : '' }}">
                                {!! Form::label('comments', 'Comments', ['class' => 'col-sm-12']) !!}
                                <div class="col-sm-12">
                                    {!! Form::textarea('comments', null, [
                                        'class' => 'form-control',
                                        'rows' => '15',
                                        'id' => 'comments',
                                        'maxlength' => 500,
                                    ]) !!}
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
