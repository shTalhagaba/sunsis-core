@extends('layouts.perspective.master')

@section('title', 'Licenses')

@section('page-plugin-styles')
@endsection

@section('breadcrumbs')
@endsection

@section('page-content')
<div class="page-header"><h1>Licenses</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-bold btn-primary btn-round" type="button" onclick="document.forms['frmLicense'].reset();$('#license-modal-form').modal('show')">
                <i class="ace-icon fa fa-plus bigger-120"></i> Add New License
            </button>
        </div>
        @include('partials.session_message')

        <div class="table-responsive">
            <table id="tblNotifications" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th><th>PO Number</th><th>Number Of Licenses</th><th>Levy (%)</th><th>Expiry Date</th><th>Created By</th><th>Creation Date</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($licenses AS $license)
                    <tr id="row{{ $license->id }}">
                        <td class="id">{{ $license->id }}</td>
                        <td class="po_number">{{ $license->po_number }}</td>
                        <td class="number_of_licenses">{{ $license->number_of_licenses }}</td>
                        <td class="levy">{{ $license->levy }}</td>
                        <td class="expiry_date">{{ !is_null($license->expiry_date) ? \Carbon\Carbon::parse($license->expiry_date)->format('d/m/Y') : '' }}</td>
                        <td>{{ $license->creator->full_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($license->created_at)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <button type="button" class="btn btn-white btn-primary btn-round btn-sm" onclick="prepareLicenseModalForEdit('{{ $license->id }}');">
                                <i class="ace-icon fa fa-edit blue"></i><span class="bigger-110">Edit</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9">No license.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->
<div id="license-modal-form" class="modal" tabindex="-1">
	<form name="frmLicense" id="frmLicense" action="{{ route('perspective.support.licenses.store') }}" method="POST">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="blue bigger">License Form - <small>Please fill the following form fields</small></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						@csrf
						{!! Form::hidden('id', null, ['id' => 'id']) !!}
                        <div class="form-group row {{ $errors->has('po_number') ? 'has-error' : ''}}">
                            {!! Form::label('po_number', 'PO number', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::text('po_number', null, ['class' => 'form-control ', 'maxlength' => '15']) !!}
                                {!! $errors->first('po_number', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('number_of_licenses') ? 'has-error' : ''}}">
                            {!! Form::label('number_of_licenses', 'Number of Licenses', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::text('number_of_licenses', null, ['class' => 'form-control ', 'required', 'maxlength' => '5', 'onkeypress' => 'return isNumberKey(event);']) !!}
                                {!! $errors->first('number_of_licenses', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('levy') ? 'has-error' : ''}}">
                            {!! Form::label('levy', 'Levy', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::select('levy', range(0, 100), null, ['class' => 'form-control ']) !!}
                                {!! $errors->first('levy', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('expiry_date') ? 'has-error' : ''}}">
                            {!! Form::label('expiry_date', 'Expiry Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                            <div class="col-sm-8">
                                {!! Form::date('expiry_date', null, ['class' => 'form-control ']) !!}
                                {!! $errors->first('expiry_date', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-sm btn-round" data-dismiss="modal">
					<i class="ace-icon fa fa-times"></i> Cancel
				</button>
				<button class="btn btn-sm btn-success btn-round">
					<i class="ace-icon fa fa-check"></i> Save
				</button>
			</div>
		</div>
	</div>
	</form>
</div>
@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
<script>
    $.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

function prepareLicenseModalForEdit(license_id)
{
    $('#row'+license_id).find('td').each (function() {
        if(this.className == 'expiry_date')
        {
            var t = moment($(this).html(), "DD/MM/YYYY");
            $('#frmLicense #'+this.className).val(t.format('YYYY-MM-DD'));
        }
        else
        {
            if(this.className !== '')
                $('#frmLicense #'+this.className).val($(this).html());
        }
        $('#license-modal-form').modal('show');
    });
}
</script>
@endsection
