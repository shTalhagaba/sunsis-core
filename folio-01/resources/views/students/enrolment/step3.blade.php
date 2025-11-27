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
</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.singleEnrolment.step3.show', $student) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Enrol Single Learner - Final Step</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Final Step<br>
                    <i class="fa fa-hand-o-right"></i> <small>Confirm information i.e. qualification(s)/portfolio(s), units and performance criteria.</small><br>
                    <i class="fa fa-hand-o-right"></i> <small>Click on 'Confirm Enrolment' to finish the enrolment.</small>
                 </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header"><h4 class="smaller">Student Details</h4></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Name </div>
                                    <div class="profile-info-value"><span>{{ $student->full_name }}</span></div>
                                    <div class="profile-info-name"> Employer </div>
                                    <div class="profile-info-value"><span>{{ $student->employer->legal_name }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> ULN </div>
                                    <div class="profile-info-value"><span>{{ $student->uln }}</span></div>
                                    <div class="profile-info-name"> NI </div>
                                    <div class="profile-info-value"><span>{{ $student->ni }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header"><h4 class="smaller">Selected Qualification(s)</h4></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            @foreach($portfolios AS $key => $value)
                            @php $_q = App\Models\Qualifications\Qualification::findOrFail($key) @endphp
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> QAN & Title </div>
                                    <div class="profile-info-value"><span>{{ $_q->qan }} {{ $_q->title }}</span></div>
                                    <div class="profile-info-name"> Start Date </div>
                                    <div class="profile-info-value">
                                        <span>{{ \Carbon\Carbon::parse($value['start_date'])->format('d/m/Y') }}</span></div>
                                    <div class="profile-info-name"> Planned End Date </div>
                                    <div class="profile-info-value">
                                        <span>{{ \Carbon\Carbon::parse($value['planned_end_date'])->format('d/m/Y') }}</span></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-4"></div>

        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Mandatory units are colored green. Blue units are your selection from the optional list.
                </div>
            </div>
        </div>

        {!! Form::open(['url' => route('students.singleEnrolment.step3.store', $student), 'class' => 'form-horizontal',
        'role' => 'form', 'method' => 'POST']) !!}
        {!! Form::hidden('portfolios', json_encode($portfolios)) !!}
        {!! Form::hidden('selectedUnits', json_encode($selectedUnits)) !!}
        {!! Form::hidden('selectedPCs', json_encode($selectedPCs)) !!}

        @foreach($portfolios AS $key => $value)
        @php $_q = App\Models\Qualifications\Qualification::findOrFail($key) @endphp
        <div class="col-sm-12">
            <div class="widget-box transparent collapsed">
                <div class="widget-header">
                    <h5 class="widget-title"><i class="fa fa-graduation-cap"></i> {{ $_q->qan }} {{ $_q->title }}</h5> &nbsp;
                    <span class="badge badge-success">M: {{ $_q->units()->where('unit_group', 1)->count() }}</span>
                    <span class="badge badge-info" title="Number of units you chose from the optional list in previous step.">
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
                                @foreach($_q->units()->whereIn('id', $selectedUnits)->orderBy('unit_sequence')->get() AS $unit)
                                    <tr>
                                        @if ($unit->getOriginal('unit_group') == 1)
                                        <th class="text-success"><i class="fa fa-folder fa-lg"></i> <strong>{{ $unit->title }}</strong></th>
                                        @else
                                        <th class="text-info"><i class="fa fa-folder fa-lg"></i> <strong>{{ $unit->title }}</strong></th>
                                        @endif
                                    </tr>
                                    @foreach($unit->pcs()->whereIn('id', $selectedPCs)->orderBy('pc_sequence')->get() AS $pc)
                                        <tr>
                                            @if ($unit->getOriginal('unit_group') == 1)
                                            <td class="text-success"><i class="fa fa-folder-open"></i> {{ $pc->title }}</span></td>
                                            @else
                                            <td class="text-info"><i class="fa fa-folder-open"></i> {{ $pc->title }}</span></td>
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
