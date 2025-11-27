@extends('layouts.master')

@section('title', 'Single Enrolment - Step 3')

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

@section('breadcrumbs')
{{ Breadcrumbs::render('students.singleEnrolment.step3.show', $student) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Enrol Single Learner - Final Step</h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Final Step<br>
                    <i class="fa fa-hand-o-right"></i> <small>Confirm information i.e. qualification(s)/portfolio(s),
                        units and performance criteria.</small><br>
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
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Programme </div>
                                    <div class="info-div-value">{{ $tr->programme->title }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Dates </div>
                                    <div class="info-div-value"><span>{{ $tr->start_date }} -
                                            {{ $tr->planned_end_date }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Employer </div>
                                    <div class="info-div-value"><span>{{ $tr->employer->legal_name }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Primary Assessor </div>
                                    <div class="info-div-value"><span>{{ $tr->primaryAssessor->full_name }}</span></div>
                                </div>
                                @if(!is_null($tr->secondary_assessor))
                                <div class="info-div-row">
                                    <div class="info-div-name"> Secondary Assessor </div>
                                    <div class="info-div-value"><span>{{ $tr->secondaryAssessor->full_name }}</span>
                                    </div>
                                </div>
                                @endif
                                @if(!is_null($tr->tutor))
                                <div class="info-div-row">
                                    <div class="info-div-name"> Tutor </div>
                                    <div class="info-div-value"><span>{{ $tr->tutorUser->full_name }}</span></div>
                                </div>
                                @endif
                                <div class="info-div-row">
                                    <div class="info-div-name"> Verifier </div>
                                    <div class="info-div-value"><span>{{ $tr->verifierUser->full_name }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p></p>
                {!! Form::open(['url' => route('students.singleEnrolment.step3.store', $student), 'class' =>
                'form-horizontal',
                'role' => 'form', 'method' => 'POST']) !!}
                {!! Form::hidden('portfolios', json_encode($portfolios)) !!}
                {!! Form::hidden('selectedUnits', json_encode($selectedUnits)) !!}
                {!! Form::hidden('selectedPCs', json_encode($selectedPCs)) !!}

                @foreach($portfolios AS $key => $value)
                @php $_q = App\Models\Programmes\ProgrammeQualification::findOrFail($key) @endphp
                <div class="col-xs-12">
                    <div class="widget-box transparent collapsed">
                        <div class="widget-header">
                            <h5 class="widget-title"><i class="fa fa-graduation-cap"></i> {{ $_q->qan }}
                                {{ $_q->title }}</h5> &nbsp;
                            <span class="badge badge-success">M:
                                {{ $_q->units()->where('unit_group', 1)->count() }}</span>
                            <span class="badge badge-info"
                                title="Number of units you chose from the optional list in previous step.">
                                O: {{ $_q->units()->where('unit_group', 2)->whereIn('id', $selectedUnits)->count() }}
                            </span>
                            <div class="widget-toolbar">
                                <a data-action="collapse" href="#"><i class="ace-icon fa fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        @foreach($_q->units()->whereIn('id',
                                        $selectedUnits)->orderBy('unit_sequence')->get() AS $unit)
                                        <tr>
                                            @if ($unit->getOriginal('unit_group') == 1)
                                            <th class="text-success"><i class="fa fa-folder fa-lg"></i>
                                                <strong>{{ $unit->title }}</strong></th>
                                            @else
                                            <th class="text-info"><i class="fa fa-folder fa-lg"></i>
                                                <strong>{{ $unit->title }}</strong></th>
                                            @endif
                                        </tr>
                                        @foreach($unit->pcs()->whereIn('id',
                                        $selectedPCs)->orderBy('pc_sequence')->get() AS $pc)
                                        <tr>
                                            @if ($unit->getOriginal('unit_group') == 1)
                                            <td class="text-success"><i class="fa fa-folder-open"></i>
                                                {{ $pc->title }}</span></td>
                                            @else
                                            <td class="text-info"><i class="fa fa-folder-open"></i>
                                                {{ $pc->title }}</span></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="space-8"></div>
                </div>
                @endforeach
                <div class="col-sm-12 form-actions center">
                    <button class="btn btn-sm btn-success btn-round" type="submit">
                        <i class="ace-icon fa fa-arrow-right bigger-110"></i> Save Enrolment
                    </button> &nbsp; &nbsp; &nbsp;
                    <button class="btn btn-sm btn-round" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i> Reset
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-xs-3 center">
                <div>
                    <span class="profile-picture">
                        <img class="avatar img-responsive" src="{{ asset($student->avatar_url) }}"
                            alt="{{ $student->firstnames }}" />
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
                        <div class="profile-info-value"><span>{{ $student->homeAddress()->telephone ?? '' }}</span>
                        </div>
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

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>

@endsection

@section('page-inline-scripts')

<script type="text/javascript">


</script>

@endsection
