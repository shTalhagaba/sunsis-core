{!! Form::open(['url' => route('students.training.index'), 'class' => 'form-horizontal', 'method' => 'GET', 'name' => 'frmTrainingFilters']) !!}
{!! Form::hidden('user_type', \App\Models\User::TYPE_STUDENT) !!}
<div class="row">
	<div style="float: none; padding-top: 5px;" class="col-sm-12 center-block">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group row {{ $errors->has('firstnames') ? 'has-error' : ''}}">
					{!! Form::label('firstnames', 'Firstname(s)', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('firstnames', $filters->firstnames, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '150']) !!}
						{!! $errors->first('firstnames', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group row {{ $errors->has('surname') ? 'has-error' : ''}}">
					{!! Form::label('surname', 'Surname', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('surname', $filters->surname, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '150']) !!}
						{!! $errors->first('surname', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
			</div>
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('inc_deactivated') ? 'has-error' : ''}}">
					{!! Form::label('inc_deactivated', 'Include Deactivated', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::select('inc_deactivated', ['No' => 'No', 'Yes' => 'Yes'], $filters->inc_deactivated, ['class' => 'form-control']) !!}
						{!! $errors->first('inc_deactivated', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('email') ? 'has-error' : ''}}">
					{!! Form::label('email', 'Email', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('email', $filters->email, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '150']) !!}
						{!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
            <div class="col-sm-4">
				<div class="form-group row {{ $errors->has('ni') ? 'has-error' : ''}}">
					{!! Form::label('ni', 'National Insurance', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('ni', $filters->ni, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '9']) !!}
						{!! $errors->first('ni', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('uln') ? 'has-error' : ''}}">
					{!! Form::label('uln', 'ULN', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('uln', $filters->uln, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '12']) !!}
						{!! $errors->first('uln', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('primary_assessor') ? 'has-error' : ''}}">
					{!! Form::label('primary_assessor', 'Primary Assessor', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::select('primary_assessor', \App\Models\LookupManager::getAssessors(), $filters->primary_assessor, ['class' => 'form-control', 'placeholder' => '']) !!}
						{!! $errors->first('primary_assessor', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('verifier') ? 'has-error' : ''}}">
					{!! Form::label('verifier', 'IQA', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::select('verifier', \App\Models\LookupManager::getVerifiers(), $filters->verifier, ['class' => 'form-control', 'placeholder' => '']) !!}
						{!! $errors->first('verifier', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('tutor') ? 'has-error' : ''}}">
					{!! Form::label('tutor', 'Tutor', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::select('tutor', \App\Models\LookupManager::getTutors(), $filters->tutor, ['class' => 'form-control', 'placeholder' => '']) !!}
						{!! $errors->first('tutor', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
        </div>
	<div class="row">
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('status_code') ? 'has-error' : ''}}">
					{!! Form::label('status_code', 'Training Status', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::select('status_code', ['1' => 'CONTINUING', '2' => 'COMPLETED', '3' => 'WITHDRAWN', '4' => 'TEMORARILY WITHDRAWN', '5' => 'DEACTIVATED', '6' => 'ASSESSMENT COMPLETE', '7' => 'BREAK IN LEARNING', '8' => 'AWAITING CERTIFICATES']
                            , $filters->status_code, ['class' => 'form-control', 'placeholder' => '']) !!}
						{!! $errors->first('status_code', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('programme_id') ? 'has-error' : ''}}">
					{!! Form::label('programme_id', 'Programme', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::select('programme_id', \App\Models\Programmes\Programme::where('status', 1)->orderBy('title')->pluck('title', 'id')->toArray()
                            , $filters->programme_id, ['class' => 'form-control', 'placeholder' => '']) !!}
						{!! $errors->first('programme_id', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
        </div>
	<div class="row">
            <div class="col-sm-4">
                <div class="form-group row">
					{!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						From: {!! Form::date('from_start_date', $filters->from_start_date, ['class' => 'form-control', 'placeholder' => '']) !!}
						To: {!! Form::date('to_start_date', $filters->to_start_date, ['class' => 'form-control', 'placeholder' => '']) !!}
					</div>
				</div>
            </div>
            <div class="col-sm-4">
                <div class="form-group row">
					{!! Form::label('planned_end_date', 'Planned End Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						From: {!! Form::date('from_planned_end_date', $filters->from_planned_end_date, ['class' => 'form-control', 'placeholder' => '']) !!}
						To: {!! Form::date('to_planned_end_date', $filters->to_planned_end_date, ['class' => 'form-control', 'placeholder' => '']) !!}
					</div>
				</div>
            </div>
            <div class="col-sm-4">
                <div class="form-group row">
					{!! Form::label('actual_end_date', 'Actual End Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						From: {!! Form::date('from_actual_end_date', $filters->from_actual_end_date, ['class' => 'form-control', 'placeholder' => '']) !!}
						To: {!! Form::date('to_actual_end_date', $filters->to_actual_end_date, ['class' => 'form-control', 'placeholder' => '']) !!}
					</div>
				</div>
            </div>
        </div>
		<div class="row">
			<div class="col-sm-4">
                <div class="form-group row {{ $errors->has('sortBy') ? 'has-error' : ''}}">
                    {!! Form::label('sortBy', 'Sort By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('sortBy', ['created_at' => 'Creation Date'], $filters->sortBy, ['class' => 'form-control']) !!}
                        {!! $errors->first('sortBy', '<p class="text-danger">:message</p>') !!}
                    </div>
                </div>
			</div>
			<div class="col-sm-4">
                <div class="form-group row {{ $errors->has('orderBy') ? 'has-error' : ''}}">
                    {!! Form::label('orderBy', 'Sort By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('orderBy', ['ASC' => 'Ascending', 'DESC' => 'Descending'], $filters->orderBy, ['class' => 'form-control']) !!}
                        {!! $errors->first('orderBy', '<p class="text-danger">:message</p>') !!}
                    </div>
                </div>
			</div>
			<div class="col-sm-4">
				<div class="form-group row {{ $errors->has('perPage') ? 'has-error' : ''}}">
					{!! Form::label('perPage', 'Records per Page', ['class' => 'col-sm-4 control-label']) !!}
					<div class="col-sm-8">
						{!! Form::select('perPage', \App\Models\LookupManager::getPerPageDDL(), $filters->perPage, ['class' => 'form-control']) !!}
						{!! $errors->first('perPage', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="clearfix form-actions center">

		<button class="btn btn-sm btn-success btn-round" type="submit">
			<i class="ace-icon fa fa-search bigger-110"></i>
			Search
		</button>

		&nbsp; &nbsp; &nbsp;
		<button class="btn btn-sm btn-round" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Reset
		</button>

</div>
{!! Form::close() !!}
