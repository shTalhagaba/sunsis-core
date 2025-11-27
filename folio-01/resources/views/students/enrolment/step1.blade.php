@extends('layouts.master')

@section('title', 'Enrol Student')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
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
{{ Breadcrumbs::render('students.singleEnrolment.step1.show', $student) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Enrol Single Learner - Step 1</h1></div><!-- /.page-header -->
<div class="row">
	<div class="col-xs-12">
		<!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Step 1<br>
                    <i class="fa fa-hand-o-right"></i> <small>Complete enrolment form and select qualifications (portfolios) for the training.</small><br>
                    <i class="fa fa-hand-o-right"></i> <small>Click on 'Continue to Step 2' to proceed to the next step.</small>
                 </div>
            </div>
        </div>

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
                                {!! Form::open([
                                    'url' => route('students.singleEnrolment.step1.store', $student),
                                    'class' => 'form-horizontal',
                                    'role' => 'form',
                                    'id' => 'frmEnrolmentS1',
                                    'method' => 'POST'
                                ]) !!}
								<div class="row">
									<div class="col-sm-12">
                                        <div class="form-group row required {{ $errors->has('programme_id') ? 'has-error' : ''}}">
											{!! Form::label('programme_id', 'Programme', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::select('programme_id', $programmes, null, ['class' => 'form-control', 'required', 'placeholder' => '']) !!}
												{!! $errors->first('verifier', '<p class="text-danger">:message</p>') !!}
											</div>
                                        </div>
                                        <div class="form-group row required {{ $errors->has('start_date') ? 'has-error' : ''}}">
											{!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::date('start_date', null, ['class' => 'form-control', 'required']) !!}
												{!! $errors->first('start_date', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row required {{ $errors->has('planned_end_date') ? 'has-error' : ''}}">
											{!! Form::label('planned_end_date', 'Planned End Date', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::date('planned_end_date', null, ['class' => 'form-control', 'required']) !!}
												{!! $errors->first('planned_end_date', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row required {{ $errors->has('employer_location') ? 'has-error' : ''}}">
											{!! Form::label('employer_location', 'Employer', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
                                                {!! Form::select('employer_location',
                                                \App\Models\LookupManager::getEmployersLocationsDDL(),
                                                $student->employer_location,
                                                ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'employer_location']) !!}
												{!! $errors->first('employer_location', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row required {{ $errors->has('primary_assessor') ? 'has-error' : ''}}">
											{!! Form::label('primary_assessor', 'Primary Assessor', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::select('primary_assessor', $assessors, null, ['class' => 'form-control', 'required']) !!}
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
												{!! Form::select('tutor', ['' => ''] + $tutors, null, ['class' => 'form-control']) !!}
												{!! $errors->first('tutor', '<p class="text-danger">:message</p>') !!}
											</div>
										</div>
										<div class="form-group row required {{ $errors->has('verifier') ? 'has-error' : ''}}">
											{!! Form::label('verifier', 'Verifier', ['class' => 'col-sm-4 control-label']) !!}
											<div class="col-sm-8">
												{!! Form::select('verifier', ['' => ''] + $verifiers, null, ['class' => 'form-control', 'required']) !!}
												{!! $errors->first('verifier', '<p class="text-danger">:message</p>') !!}
											</div>
                                        </div>
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<div class="clearfix form-actions center">
											<button class="btn btn-sm btn-success btn-round" type="submit">
                                                <i class="ace-icon fa fa-arrow-right bigger-110"></i> Continue to Step 2
											</button>&nbsp; &nbsp; &nbsp;
											<button class="btn btn-sm btn-round" type="reset">
												<i class="ace-icon fa fa-undo bigger-110"></i> Reset
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
							<img class="avatar img-responsive" src="{{ asset($student->avatar_url) }}" alt="{{ $student->firstnames }}" />
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
							<div class="profile-info-name">Primary Email:</div>
							<div class="profile-info-value"><span>{{ $student->primary_email }}</span></div>
						</div>
						@if($student->secondary_email != '')
						<div class="profile-info-row">
							<div class="profile-info-name">Secondary Email:</div>
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
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">

	$(function(){

        $('#frmEnrolmentS1').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                start_date: {
                    required: true
                },
                planned_end_date: {
                    required: true,
                    greaterThan: "#start_date"
                },
                employer_location: {
                    required: true
                },
                primary_assessor: {
                    required: true
                },
                verifier: {
                    required: true
                }
            },

            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },

            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="col-"]');
                    if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else
                    error.insertAfter(element);
            }
        });

        jQuery.validator.addMethod("greaterThan", function(value, element, params) {
            if($(params).val() == '')
                return true;
			if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date($(params).val());
            }
            return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
		}, "Planned end date must be after the start date.");

    });
</script>

@endsection

