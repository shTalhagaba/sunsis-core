{!! Form::open(['url' => route('employers.index'), 'class' => 'form-horizontal', 'method' => 'GET']) !!}
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
			<div class="col-sm-4">
				<div class="form-group row {{ $errors->has('edrs') ? 'has-error' : ''}}">
					{!! Form::label('edrs', 'EDRS', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('edrs', $filters->edrs, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '15']) !!}
						{!! $errors->first('edrs', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
            </div>
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('sector') ? 'has-error' : ''}}">
                    {!! Form::label('sector', 'Sector', ['class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('sector', \App\Models\Organisations\Organisation::getDDLOrgSectors(true), $filters->sector, ['class' => 'form-control']) !!}
                        {!! $errors->first('sector', '<p class="text-danger">:message</p>') !!}
                    </div>
                </div>
            </div>
		</div>
		<div class="row">
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('active') ? 'has-error' : ''}}">
                    {!! Form::label('active', 'Active', ['class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('active', ['' => 'All', '1' => 'Active Only', '0' => 'Inactive Only'], $filters->active, ['class' => 'form-control']) !!}
                        {!! $errors->first('active', '<p class="text-danger">:message</p>') !!}
                    </div>
                </div>
            </div>
			<div class="col-sm-4">
				<div class="form-group row {{ $errors->has('company_number') ? 'has-error' : ''}}">
					{!! Form::label('company_number', 'Company Number', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('company_number', $filters->company_number, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '15']) !!}
						{!! $errors->first('company_number', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group row {{ $errors->has('vat_number') ? 'has-error' : ''}}">
					{!! Form::label('vat_number', 'VAT Number', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
					<div class="col-sm-8">
						{!! Form::text('vat_number', $filters->vat_number, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '15']) !!}
						{!! $errors->first('vat_number', '<p class="text-danger">:message</p>') !!}
					</div>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group row {{ $errors->has('sortBy') ? 'has-error' : ''}}">
                    {!! Form::label('sortBy', 'Sort By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('sortBy', ['legal_name' => 'Legal Name', 'trading_name' => 'Trading Name', 'created_at' => 'Creation Date'], $filters->sortBy, ['class' => 'form-control']) !!}
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
