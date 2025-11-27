{!! Form::open(['url' => route('users.index'), 'class' => 'form-horizontal', 'method' => 'GET']) !!}
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
				<div class="form-group row {{ $errors->has('user_type') ? 'has-error' : ''}}">
					{!! Form::label('user_type', 'System User Type', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::select('user_type', \App\Models\LookupManager::getUserTypes(), $filters->user_type, ['class' => 'form-control', 'placeholder' => '']) !!}
						{!! $errors->first('user_type', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
			</div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('gender') ? 'has-error' : ''}}">
					{!! Form::label('gender', 'Gender', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::select('gender', \App\Models\LookupManager::getGenderDDL(), $filters->gender, ['class' => 'form-control', 'placeholder' => '']) !!}
						{!! $errors->first('gender', '<p class="text-danger">:message</p>') !!}
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
                <div class="form-group row {{ $errors->has('email') ? 'has-error' : ''}}">
					{!! Form::label('email', 'Email', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('email', $filters->email, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '150']) !!}
						{!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
        </div>
        <div class="row">
			<div class="col-sm-4">
                <div class="form-group row {{ $errors->has('sortBy') ? 'has-error' : ''}}">
                    {!! Form::label('sortBy', 'Sort By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('sortBy', ['surname' => 'Surname', 'firstnames' => 'First Name', 'created_at' => 'Creation Date'], $filters->sortBy, ['class' => 'form-control']) !!}
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
