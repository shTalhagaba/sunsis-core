{!! Form::open(['url' => route('support.tickets.index'), 'class' => 'form-horizontal', 'method' => 'GET']) !!}

<div class="row small">
	<div style="float: none; padding-top: 5px;" class="col-sm-12 center-block">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group row {{ $errors->has('id') ? 'has-error' : ''}}">
					{!! Form::label('id', 'ID', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('id', $filters->id, ['class' => 'form-control', 'maxlength' => '10']) !!}
						{!! $errors->first('id', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group row {{ $errors->has('title') ? 'has-error' : ''}}">
					{!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('title', $filters->title, ['class' => 'form-control', 'maxlength' => '150']) !!}
						{!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
            <div class="col-sm-3">
                <div class="form-group row {{ $errors->has('author_id') ? 'has-error' : ''}}">
                    {!! Form::label('author_id', 'Raised By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('author_id', \App\Models\LookupManager::getSupportAuthorsDDL(), $filters->author_id, ['class' => 'form-control', 'placeholder' => '']) !!}
                        {!! $errors->first('author_id', '<p class="text-danger">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group row {{ $errors->has('author_email') ? 'has-error' : ''}}">
                    {!! Form::label('author_email', 'Email', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('author_email', $filters->author_email, ['class' => 'form-control', 'maxlength' => '150']) !!}
                        {!! $errors->first('author_email', '<p class="text-danger">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
			<div class="col-sm-4">
                <div class="form-group row {{ $errors->has('sortBy') ? 'has-error' : ''}}">
                    {!! Form::label('sortBy', 'Sort By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('sortBy', ['id' => 'Ticket ID', 'title' => 'Title', 'created_at' => 'Creation Date'], $filters->sortBy, ['class' => 'form-control']) !!}
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

		<button class="btn btn-sm btn-success" type="submit">
			<i class="ace-icon fa fa-search bigger-110"></i>
			Search
		</button>

		&nbsp; &nbsp; &nbsp;
		<button class="btn btn-sm" type="reset">
			<i class="ace-icon fa fa-undo bigger-110"></i>
			Reset
		</button>

</div>
{!! Form::close() !!}
