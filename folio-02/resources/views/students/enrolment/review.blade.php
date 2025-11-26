@extends('layouts.master')

@section('title', 'Single Enrolment - Review Information')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <style>
        hr {
            padding: 0px;
            margin: 0px;
        }

        input[type=checkbox] {
            transform: scale(1.4);
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
        <h1>Enrol Single Learner - Review Information</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Review Information<br>
                        <i class="fa fa-hand-o-right"></i> <small>Confirm information i.e. qualification(s)/portfolio(s) and
                            units.</small><br>
                        <i class="fa fa-hand-o-right"></i> <small>Click on 'Confirm Enrolment' to finish the
                            enrolment.</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-9">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">Training Details</h5>
                        </div>
                        <div class="widget-body">
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
                                            <div class="info-div-value"><span>{{ \Carbon\Carbon::parse($enrolmentDto->epaDate)->format('d/m/Y') }}</span></div>
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
                    </div>
                    <p></p>
                    {!! Form::open([
                        'url' => route('students.singleEnrolment.confirm', $student),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'method' => 'POST',
                        'name' => 'frmEnrolmentConfirm',
                    ]) !!}

                    @foreach ($enrolmentDto->qualifications as $qualificationDto)
                        <div class="col-xs-12">
                            <div class="widget-box ">
                                <div class="widget-header">
                                    <h4 class="widget-title bolder">
                                        <i class="fa fa-graduation-cap"></i> 
                                        {{ $qualificationDto->programmeQualification->qan }} {{ $qualificationDto->programmeQualification->title }}
                                    </h4> &nbsp;                                    
                                    <div class="widget-toolbar">
                                        <a href="#" data-action="collapse"><i
                                                class="ace-icon fa fa-chevron-down"></i></a>
                                    </div>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="profile-user-info">
                                            <div class="profile-info-row">
                                                <div class="profile-info-name">Start Date:</div>
                                                <div class="profile-info-value"><span>{{ Carbon\Carbon::parse($qualificationDto->startDate)->format('d/m/Y') }}</span></div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name">Planned End Date:</div>
                                                <div class="profile-info-value"><span>{{ Carbon\Carbon::parse($qualificationDto->plannedEndDate)->format('d/m/Y') }}</span></div>
                                            </div>
					                        @if ($qualificationDto->programmeQualification->isFsQualification())
                                            <div class="profile-info-row">
                                                <div class="profile-info-name">Tutor:</div>
                                                <div class="profile-info-value"><span>{{ optional(\App\Models\User::find($qualificationDto->fsTutor))->full_name }}</span></div>
                                            </div>
                                            @endif
                                            <div class="profile-info-row">
                                                <div class="profile-info-name">Verifier:</div>
                                                <div class="profile-info-value"><span>{{ optional(\App\Models\User::find($qualificationDto->fsVerifier))->full_name }}</span></div>
                                            </div>
                                        </div>
                            
                                        @foreach ($qualificationDto->programmeQualification->units()->whereIn('id', $enrolmentDto->unitIds)->orderBy('unit_sequence')->get() as $unit)
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <div class="widget-box transparent collapsed">
                                                        <div class="widget-header">
                                                            <h5 class="widget-title">
                                                                <i class="fa fa-folder fa-lg"></i>
                                                                [{{ $unit->unit_owner_ref }},&nbsp;{{ $unit->unique_ref_number }}]
                                                                {{ $unit->title }}
                                                            </h5>
                                                            <div class="widget-toolbar">
                                                                <a href="#" data-action="collapse" title="Click to view the list of criteria of this unit."><i
                                                                        class="ace-icon fa fa-chevron-down"></i></a>
                                                            </div>
                                                        </div>
                                                        <div class="widget-body">
                                                            <div class="widget-main">
                                                                @forelse ($unit->pcs()->orderBy('pc_sequence')->get() as $pc)
                                                                    [{{ $pc->reference }}] {!! nl2br(e($pc->title)) !!}<hr style="margin-top: 10px; margin-bottom: 10px">
                                                                @empty
                                                                    <span class="text-danger">
                                                                        <i class="fa fa-triangle"></i> 
                                                                        <i>This unit ([{{ $unit->unit_owner_ref }},&nbsp;{{ $unit->unique_ref_number }}] {{ $unit->title }}) has no criteria.</i>
                                                                    </span>                                        
                                                                @endforelse
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>            
                                        @endforeach
                                    </div>
                                </div>
                            </div>                            
                            <div class="space-8"></div>
                        </div>
                    @endforeach
                    <div class="col-sm-12 form-actions center">
                        <button class="btn btn-sm btn-default btn-round pull-left" type="button" onclick="window.location.href='{{ route('students.singleEnrolment.step2', $student->id) }}'">
                            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back to Step 2
                        </button> &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-sm btn-success btn-round pull-right" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i> Confirm Enrolment
                        </button>
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

@endsection

@section('page-inline-scripts')

    <script type="text/javascript">

    $("form[name=frmEnrolmentConfirm]").on('submit', function(){
        var form = $(this);
        form.find(':submit').attr("disabled", true);
        form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
        return true;
    });
    
    </script>

@endsection
