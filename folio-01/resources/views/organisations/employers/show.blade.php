@extends('layouts.master')

@section('title', 'Employer')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('organisations.employers.show', $organisation) }}
@endsection

@section('page-content')
<div class="page-header"><h1>{{ $organisation->legal_name }}</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('employers.index') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
            <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('employers.edit', $organisation) }}'">
                <i class="ace-icon fa fa-edit bigger-120 blue"></i> Edit Employer
            </button>
            <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="document.forms['frmLocation'].reset();$('#location-modal-form').modal('show')">
                <i class="ace-icon fa fa-plus bigger-120 blue"></i> Add Location
            </button>
            <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="document.forms['frmOrgContact'].reset();$('#org-contact-modal-form').modal('show')">
                <i class="ace-icon fa fa-plus bigger-120 blue"></i> Add CRM Contact
            </button>
        </div>
        @include('partials.session_message')
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-box">
                    <div class="widget-header"><h4 class="widget-title">Employer Details</h4></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Trading Name </div><div class="profile-info-value"><span>{{ $organisation->trading_name }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Short Name </div><div class="profile-info-value"><span>{{ $organisation->short_name }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Company Number </div><div class="profile-info-value"><span>{{ $organisation->company_number }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> VAT Number </div><div class="profile-info-value"><span>{{ $organisation->vat_number }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Sector </div><div class="profile-info-value"><span>{{ $organisation->sector }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> EDRS </div><div class="profile-info-value"><span>{{ $organisation->edrs }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Active </div><div class="profile-info-value"><span>{{ $organisation->active == 1 ? 'Yes' : 'No' }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="tabbable">
                    <ul id="myTab" class="nav nav-tabs tab-color-blue background-blue padding-18 tab-size-bigger">
                        <li class="active">
                            <a href="#tabLocations" data-toggle="tab">Locations <span class="badge badge-info">{{ $organisation->locations->count() }}</span></a>
                        </li>
                        <li>
                            <a href="#tabLearners" data-toggle="tab">Learners <span class="badge badge-info">{{ $organisation->students->count() }}</span></a>
                        </li>
                        <li>
                            <a href="#tabContacts" data-toggle="tab">CRM Contacts <span class="badge badge-info">{{ $organisation->contacts->count() }}</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane in active" id="tabLocations">
                            <div class="row">
                                <div class="col-sm-4">
                                    @include('organisations.employers.partials.location', ['location' => $organisation->mainLocation()])
                                </div>
                                @php $i = 1; @endphp
                                @foreach($organisation->locations()->where('is_legal_address', '!=', 1)->get() AS $location)
                                @php
                                if($i == 3)
                                {
                                    echo '</div><div class="row">';
                                    $i = 0;
                                }
                                @endphp
                                <div class="col-sm-4">
                                    @include('organisations.employers.partials.location', ['location' => $location])
                                </div>
                                @php $i++; @endphp
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane" id="tabLearners">
                            <div class="table-responsive">
                                @include('organisations.employers.partials.learners')
                            </div>
                        </div>
                        <div class="tab-pane" id="tabContacts">
                            <div class="table-responsive">
                                @include('organisations.employers.partials.contacts')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="location-modal-form" class="modal" tabindex="-1">
	<form name="frmLocation" id="frmLocation" action="{{ route('locations.save', $organisation) }}" method="POST">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="blue bigger">Location Form - <small>Please fill the following form fields</small></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						@csrf
						@include('organisations.employers.location-modal-form')
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
<div id="org-contact-modal-form" class="modal" tabindex="-1">
	<form name="frmOrgContact" id="frmOrgContact" action="{{ route('organisation_contacts.save', $organisation) }}" method="POST">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="blue bigger">CRM Contact Form - <small>Please fill the following form fields</small></h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						@csrf
						@include('organisations.employers.org-contact-modal-form')
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
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">
$('[data-rel=tooltip]').tooltip();
$('[data-rel=popover]').popover({
    html:true,
    placement:"auto"
});

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

function prepareLocationModalForEdit(location_id)
{
  document.forms['frmLocation'].reset();
	$.ajax({
		type:'POST',
		url:'{{ route("locations.detail", $organisation) }}',
		data: {location_id},
		success:function(data){
			$.each( JSON.parse(data), function( key, value ) {
				$('#frmLocation #'+key).val(value);
			});
			$('#location-modal-form').modal('show');
		},
		error:function(data){
			alert(data);
		}
	});
}

function prepareOrgContactModalForEdit(contact_id)
{
  document.forms['frmOrgContact'].reset();
	$.ajax({
		type:'POST',
		url:'{{ route("organisation_contacts.detail", $organisation) }}',
		data: {contact_id},
		success:function(data){
			$.each( JSON.parse(data), function( key, value ) {
				$('#frmOrgContact #'+key).val(value);
			});
			$('#org-contact-modal-form').modal('show');
		},
		error:function(data){
			alert(data);
		}
	});
}

</script>

@endsection

