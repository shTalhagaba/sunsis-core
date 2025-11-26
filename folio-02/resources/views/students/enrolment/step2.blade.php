@extends('layouts.master')

@section('title', 'Single Enrolment - Step 2')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <style>
        hr {
            padding: 0px;
            margin: 0px;
        }

        .avatar {
            vertical-align: middle;
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Enrol Single Learner - Step 2</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Step 2<br>
                        <i class="fa fa-hand-o-right"></i> <small>Select units and elements for each qualification
                            (portfolio) which you have selected in Step 1.</small><br>
                        <i class="fa fa-hand-o-right"></i> <small>Click on 'Continue to Step 3' to proceed to the next
                            step.</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="space-4"></div>

            <div class="row">

            </div>

            <div class="row">
                <div class="col-xs-9">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">Training Details</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Programme </div>
                                        <div class="info-div-value">{{ $enrolmentDto->programme->title }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Duration </div>
                                        <div class="info-div-value">
                                            <span>
                                                {{ \Carbon\Carbon::parse($enrolmentDto->startDate)->format('d/m/Y') }} -
                                                {{ \Carbon\Carbon::parse($enrolmentDto->plannedEndDate)->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> End Point Assessment Date </div>
                                        <div class="info-div-value"><span>{{ !is_null($enrolmentDto->epaDate) ? \Carbon\Carbon::parse($enrolmentDto->epaDate)->format('d/m/Y') : '' }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Employer </div>
                                        <div class="info-div-value">
                                            <span>
                                                {{ $enrolmentDto->employerLocation->organisation->legal_name }}<br>
                                                {{ $enrolmentDto->employerLocation->title }}<br>
                                                {{ $enrolmentDto->employerLocation->address_line_1 }}<br>
                                                {{ $enrolmentDto->employerLocation->postcode }}<br>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Primary Assessor </div>
                                        <div class="info-div-value"><span>{{ $enrolmentDto->primaryAssessor->full_name }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Secondary Assessor </div>
                                        <div class="info-div-value"><span>{{ optional($enrolmentDto->secondaryAssessor)->full_name }}</span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Tutor </div>
                                        <div class="info-div-value"><span>{{ optional($enrolmentDto->tutor)->full_name }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Verifier </div>
                                        <div class="info-div-value"><span>{{ $enrolmentDto->verifier->full_name }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p></p>
                    {!! Form::open([
                        'url' => route('students.singleEnrolment.step2.post', $student),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmEnrolmentS2',
                        'method' => 'POST',
                    ]) !!}
                    <div class="col-xs-12">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Following are the qualifications in your selected programme.
                            You can select which one(s) to add and their optional units.
                        </div>
                        @foreach ($enrolmentDto->programme->qualifications as $programmeQualification)
                        @include('students.enrolment.partials.qualification_selection_panel', [
                            'programmeQualification' => $programmeQualification,
                            'programmeQualificationStartDate' => $enrolmentDto->startDate,
                            'programmeQualificationPlannedEndDate' => $enrolmentDto->plannedEndDate,
                        ])
                        <div class="space-6"></div>
                        @endforeach
                    </div>
                    <div class="col-xs-12 form-actions center">
                        <button class="btn btn-sm btn-default btn-round pull-left" type="button" onclick="window.location.href='{{ route('students.singleEnrolment.step1', $student->id) }}'">
                            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back to Step 1
                        </button> &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-sm btn-primary btn-round pull-right" type="submit">
                            <i class="ace-icon fa fa-arrow-right bigger-110"></i> Continue to Step 3
                        </button> &nbsp; &nbsp; &nbsp;
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-xs-3 center">
                    @include('students.enrolment.partials.student_basic_details')
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
        $(function() {

            $('input[type="checkbox"][name="chkPC[]"]').each(function() {
                if (this.checked) {
                    var unit_number = this.id.replace('pc' + this.value + 'OfUnit', '');
                    $('input[type="checkbox"][id="chkUnit' + unit_number + '"]').prop('checked', true);
                }
            });

            $('input[type=checkbox][id^=chkUnit]').on('click', function() {
                var unit_number = this.id.replace('chkUnit', '');
                if (this.checked) {
                    $("input[type='checkbox'][id$='OfUnit" + unit_number + "']").each(function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $("input[type='checkbox'][id$='OfUnit" + unit_number + "']").each(function() {
                        $(this).prop('checked', false);
                    });
                }

            });

            $('input[type="checkbox"][name="chkPC[]"]').on('click', function() {
                var unit_number = this.id.replace('pc' + this.value + 'OfUnit', '');
                if (this.checked) // if pc is clicked then check the Unit checkbox too.
                {
                    $('input[type="checkbox"][id="chkUnit' + unit_number + '"]').prop('checked', true);
                } else // if all pcs of a unit are unticked then untick the unit
                {
                    var allPCUnChecked = true;
                    $("input[type='checkbox'][id$='OfUnit" + unit_number + "']").each(function() {
                        if (this.checked) {
                            allPCUnChecked = false;
                            return false;
                        }
                    });
                    if (allPCUnChecked) {
                        $('input[type="checkbox"][id="chkUnit' + unit_number + '"]').prop('checked', false);
                    }
                }

            });

        });

        $(function() {
            $('#frmEnrolmentS2').validate({
                rules: {
                    "chkPC[]": {
                        required: true,
                        minLength: 1
                    }
                },
                messages: {
                    "chkPC[]": "Please select at least one performance criteria."
                },
                errorPlacement: function(error, element) {
                    $.alert(error.text(), 'Validation Error');
                }
            });
        });
    </script>

@endsection
