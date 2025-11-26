{!! Form::open(['url' => route('eqa_samples.index'), 'class' => 'form-horizontal', 'method' => 'GET']) !!}
<div class="row small">
	<div style="float: none; padding-top: 5px;" class="col-sm-12 center-block">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group row {{ $errors->has('keyword') ? 'has-error' : ''}}">
					{!! Form::label('keyword', 'Keyword', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('keyword', $filters->keyword, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '150']) !!}
						{!! $errors->first('keyword', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('sortBy') ? 'has-error' : ''}}">
                    {!! Form::label('sortBy', 'Sort By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('sortBy', ['title' => 'Title', 'created_at' => 'Creation Date'], $filters->sortBy, ['class' => 'form-control']) !!}
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
