@extends('layouts.master')

@section('title', 'Enrol Student')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

<style type="text/css">
	.avatar {
		vertical-align: middle;
		width: 50px;
		height: 50px;
		border-radius: 50%;
	}
</style>
@endsection

@section('breadcrumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-home home-icon"></i>
			<a href="#">Home</a>
		</li>
		<li class="active">Enrol Student</li>
	</ul><!-- /.breadcrumb -->

</div>
@endsection

@section('page-content')
<div class="page-header">
	<h1>
		Enrol Single Learner
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i>
			create training record for the student {{ $student->surname }}, {{ $student->firstnames }}
		</small>
	</h1>
</div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

		@include('partials.session_message')
        @include('partials.session_error')


		<div class="row">
			<div class="col-sm-12">
				<div class="space"></div>

				<div class="col-xs-12 col-sm-8">
					<div class="widget-box">
						<div class="widget-header"><h4 class="smaller">Enrolment Form</h4></div>
						<div class="widget-body">
							<div class="widget-main">
								{!! Form::open(['url' => route('students.enrolSingleLearner', $student), 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST']) !!}
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group row required {{ $errors->has('start_date') ? 'has-error' : ''}}">
											{!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::date('start_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
												{!! $errors->first('start_date', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row required {{ $errors->has('planned_end_date') ? 'has-error' : ''}}">
											{!! Form::label('planned_end_date', 'Planned End Date', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::date('planned_end_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
												{!! $errors->first('planned_end_date', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row required {{ $errors->has('employer_location') ? 'has-error' : ''}}">
											{!! Form::label('employer_location', 'Employer', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::select('employer_location', \App\Models\LookupManager::getEmployersLocationsDDL(), $student->employer_location, ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
												{!! $errors->first('employer_location', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row required {{ $errors->has('primary_assessor') ? 'has-error' : ''}}">
											{!! Form::label('primary_assessor', 'Primary Assessor', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::select('primary_assessor', $assessors, null, ['class' => 'form-control', 'required' => 'required']) !!}
												{!! $errors->first('primary_assessor', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row {{ $errors->has('secondary_assessor') ? 'has-error' : ''}}">
											{!! Form::label('secondary_assessor', 'Secondary Assessor', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::select('secondary_assessor', ['' => ''] + $assessors, null, ['class' => 'form-control']) !!}
												{!! $errors->first('secondary_assessor', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row {{ $errors->has('tutor') ? 'has-error' : ''}}">
											{!! Form::label('tutor', 'Tutor', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::select('tutor', ['' => ''] + $verifiers, null, ['class' => 'form-control']) !!}
												{!! $errors->first('tutor', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row {{ $errors->has('verifier') ? 'has-error' : ''}}">
											{!! Form::label('verifier', 'Verifier', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::select('verifier', ['' => ''] + $verifiers, null, ['class' => 'form-control']) !!}
												{!! $errors->first('verifier', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-bordered">
											<tr><th><abbr >Qualification Number</abbr> & Title</th><th>Start Date</th><th>Planned End Date</th></tr>
											@for($i = 1; $i <= 5; $i++)
											<tr>
												<td>
													{!! Form::select('q'.$i, $qualifications, '', ['class' => 'form-control', 'placeholder' => '']) !!}
													{!! $errors->first('q'.$i, '<p class="text-danger">:message</p>') !!}
												</td>
												<td>
													{!! Form::date('qd_start_date'.$i, null, ['class' => 'form-control', 'id' => 'qd_start_date'.$i]) !!}
													{!! $errors->first('qd_start_date'.$i, '<p class="text-danger">:message</p>') !!}
												</td>
												<td>
													{!! Form::date('qd_planned_end_date'.$i, null, ['class' => 'form-control', 'id' => 'qd_planned_end_date'.$i]) !!}
													{!! $errors->first('qd_planned_end_date'.$i, '<p class="text-danger">:message</p>') !!}
												</td>
											</tr>
											@endfor
										</table>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<div class="clearfix form-actions center">

											<button class="btn btn-sm btn-success" type="submit">
												<i class="ace-icon fa fa-save bigger-110"></i>
												Create Training Record
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn btn-sm" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i>
												Reset
											</button>

										</div>
									</div>
								</div>

								{!! Form::close() !!}

							</div> {{-- widget main close --}}
						</div>
					</div>

				</div>

				<div class="col-xs-12 col-sm-3 center">
					<div>
						<span class="profile-picture">
							<img class="avatar img-responsive" src="{{ asset('uploads/avatars') }}/{{ $student->avatar }}" alt="{{ $student->firstnames }}" />
						</span>

						<div class="space-4"></div>

						<div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
							<div class="inline position-relative">
								<span class="white">{{ $student->firstnames  }} {{ $student->surname  }}</span>
							</div>
						</div>
					</div>

					<div class="hr hr16 dotted"></div>

					<div class="profile-user-info">
						<div class="profile-info-row">
							<div class="profile-info-name">Primary <i class="ace-icon fa fa-envelope bigger-120 pink"></i></div>
							<div class="profile-info-value"><span>{{ $student->primary_email }}</span></div>
						</div>
						@if($student->secondary_email != '')
						<div class="profile-info-row">
							<div class="profile-info-name">Secondary <i class="ace-icon fa fa-envelope bigger-120 pink"></i></div>
							<div class="profile-info-value"><span>{{ $student->secondary_email }}</span></div>
						</div>
						@endif
						<div class="profile-info-row">
							<div class="profile-info-name">ULN:</div>
							<div class="profile-info-value"><span>{{ $student->uln }}</span></div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name">NI:</div>
							<div class="profile-info-value"><span>{{ $student->ni }}</span></div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name">Telephone:</div>
							<div class="profile-info-value"><span>{{ $student->homeAddress()->telephone ?? '' }}</span></div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name">Mobile:</div>
							<div class="profile-info-value"><span>{{ $student->homeAddress()->mobile ?? '' }}</span></div>
						</div>
						<div class="profile-info-row">
							<div class="profile-info-name">Training Records Count:</div>
							<div class="profile-info-value"><span>{{ $student->training_records()->count() }}</span></div>
						</div>
					</div>
				</div>



			</div>
		</div>


		<!-- PAGE CONTENT ENDS -->
	</div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>

@endsection

@section('page-inline-scripts')

<script type="text/javascript">

	$('input[type=date][name^=qd_]').on('focus', function(){
		if(this.value == '')
		{
			if(this.name.indexOf('start_date') != -1)
				this.value = $('input[type=date][name=start_date]').val();
			if(this.name.indexOf('end_date') != -1)
				this.value = $('input[type=date][name=planned_end_date]').val();
		}
	});

	$('select[name^=q]').on('change', function(){
		var current = this;
		var alreadySelected = false;
		$('select[name^=q]').each(function(){
			if(current.name != this.name && current.value != '' && this.value != '' && current.value == this.value)
			{
				alreadySelected = true;
				return;
			}
		});
		if(alreadySelected)
		{
			alert('You have already selected this qualification.')
			current.value = '';
		}
	});
</script>

@endsection

