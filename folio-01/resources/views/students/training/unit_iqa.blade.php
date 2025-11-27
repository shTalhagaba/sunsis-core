@extends('layouts.master')

@section('title', 'IQA Portfolio Unit')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <style>

    </style>
@endsection

@section('breadcrumbs')

@endsection

@section('page-content')
    <div class="page-header">
        <h1>IQA Portfolio Unit <small>{{ $training_record->system_ref }}</small></h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">

            <!-- PAGE CONTENT BEGINS -->
            <div class="well well-sm">
                <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.training.show', [$student, $training_record]) }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                </button>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">Learner Details</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Learner </div>
                                        <div class="info-div-value"><span>{{ $student->full_name }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Primary Email </div>
                                        <div class="info-div-value">
                                    <span>
                                        <i class="fa fa-envelope blue bigger-110"></i> {{ $student->primary_email }}
                                    </span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Employer </div>
                                        <div class="info-div-value"><span>{{ $student->employer->legal_name }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">Training Details</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Status </div>
                                        <div class="info-div-value"><span><span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span></span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Dates </div>
                                        <div class="info-div-value">
                                            <span>{{ $training_record->start_date }} - {{ $training_record->planned_end_date }}</span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Portfolio(s) </div>
                                        <div class="info-div-value">
                                            @foreach($training_record->portfolios AS $portfolio)
                                                <span><i class="fa fa-graduation-cap"></i> {{ $portfolio->qan }} - {{ $portfolio->title }}</span><br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-12"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            {!! Form::open([
                'url' => route('students.training.unit.iqa.store', [$student, $training_record, $unit]),
                'class' => 'frmUnitIqaAssessment form-vertical',
                'name' => 'frmIqaAssessment',
                'id' => 'frmIqaAssessment',
                'method' => 'POST'
            ]) !!}
            {!! Form::hidden('portfolio_unit_id', $unit->id) !!}
            {!! Form::hidden('accepted_pcs', 0) !!}
            {!! Form::hidden('rejected_pcs', 0) !!}

            <div class="row">
                <div class="col-sm-6">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">Unit Details</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Unit References </div>
                                        <div class="info-div-value"><span>{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Unit Title </div>
                                        <div class="info-div-value"><span>{!! nl2br($unit->title) !!}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Portfolio Title </div>
                                        <div class="info-div-value"><span>{{ $unit->portfolio->title }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Unit Group </div>
                                        <div class="info-div-value"><span>{{ \App\Models\LookupManager::getQualificationUnitGroups($unit->unit_group) }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Unit GLH </div>
                                        <div class="info-div-value"><span>{{ $unit->glh }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Unit Credit Value </div>
                                        <div class="info-div-value"><span>{{ $unit->unit_credit_value }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Unit Learning Outcome </div>
                                        <div class="info-div-value"><span>{!! nl2br($unit->learning_outcomes) !!}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">PCs and mapped evidences of this unit</h5></div>
                        <div class="widget-body">
                            Total number of PCs: <strong>{{ $unit->pcs->count() }}</strong><br>
                            Total number of evidences mapped: <strong>{{ count($distinct_evidences) }}</strong><br>
                            @foreach($distinct_evidences->chunk(2) AS $chunk)
                                <div class="row">
                                    @foreach($chunk AS $_evi_id)
                                        @php
                                            $_evi = \App\Models\Training\TrainingRecordEvidence::findOrFail($_evi_id->tr_evidence_id);
                                        @endphp
                                        <div class="col-sm-6">
                                            <div class="well well-sm">
                                                @if($_evi->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_FILE)
                                                    <span>
                                                        <a href="{{ route('files.download',  $_evi->media->first()) }}" target="_blank" style="cursor: pointer;">
                                                            {{ $_evi->media->first()->file_name }}
                                                        </a>
                                                    </span><br>
                                                    <span class="small"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($_evi->media->first()->updated_at)->format('d/m/Y H:i:s') }}</span>
                                                @elseif($_evi->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_URL)
                                                    <span>
                                                        <a href="{{ $_evi->evidence_url }}" target="_blank">
                                                            <i data-trigger="hover"
                                                               data-rel="popover"
                                                               data-original-title="External URL"
                                                               class='fa fa-external-link-square'></i> {{ \Str::limit($_evi->evidence_url, 250) }}
                                                        </a>
                                                    </span>
                                                @elseif($_evi->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_REFERENCE)
                                                    <span>{{ $_evi->evidence_ref }}</span>
                                                @endif
                                                <br>
                                                <span class="ace-settings-btn tn btn-xs btn-info" style="cursor: pointer"
                                                      onclick="window.open('{{ route('students.training.evidence.show', [$student, $training_record, $_evi]) }}', '_blank')">
                                                    <i class="fa fa-folder-open"></i> Detail
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">Performance Criteria of this unit</h5></div>
                        <div class="widget-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 45%;">Title</th>
                                        <th style="width: 20%;">Evidences</th>
                                        <th style="width: 10%;" title="Mapped / Required">Map./Req.</th>
                                        <th style="width: 25%;">&nbsp;&nbsp;Select&nbsp;your&nbsp;action&nbsp;&nbsp;</th>
                                    </tr>
                                    </thead>
                                    @foreach($unit->pcs AS $pc)
                                        <tr>
                                            <td style="width: 75%;">
                                                <i class="fa fa-folder-open"></i> [{{ $pc->reference }}] {!! nl2br($pc->title) !!}</span>
                                            </td>
                                            <td>
                                                @foreach($pc->mapped_evidences AS $evidence)
                                                    @include('students.training.evidences.partials.evidence_popover', ['_evidence_popover' => $evidence])
                                                @endforeach
                                            </td>
                                            <td title="Number of evidences accepted / Number of evidences required">
                                                {{ $pc->mapped_evidences()->where('evidence_status', App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)->count() }}/{{ $pc->min_req_evidences }}
                                            </td>
                                            <td>
                                                @if(in_array($pc->id, $accepted_pcs_in_last_assessment))
                                                    {!! Form::select('pc_iqa_status_'.$pc->id, [1 => 'IQA Accepted', 2 => 'IQA Rejected'], 1, ['class' => 'form-control pc_iqa_status', 'placeholder' => '']) !!}
                                                @elseif(in_array($pc->id, $rejected_pcs_in_last_assessment))
                                                    {!! Form::select('pc_iqa_status_'.$pc->id, [1 => 'IQA Accepted', 2 => 'IQA Rejected'], 2, ['class' => 'form-control pc_iqa_status', 'placeholder' => '']) !!}
                                                @else
                                                    {!! Form::select('pc_iqa_status_'.$pc->id, [1 => 'IQA Accepted', 2 => 'IQA Rejected'], null, ['class' => 'form-control pc_iqa_status', 'placeholder' => '']) !!}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box  widget-color-blue2 light-border">
                        <div class="widget-header"><h5 class="widget-title">IQA Assessment</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> IQA Accepted PCs: </div>
                                        <div class="info-div-value"><span class="lblIqaAcceptedPcs">0</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> IQA Rejected PCs: </div>
                                        <div class="info-div-value"><span class="lblIqaRejectedPcs">0</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Total Sampled: </div>
                                        <div class="info-div-value"><span class="lblIqaTotalSampled">0</span></div>
                                    </div>
                                </div>
                                <p><br></p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h5 class="text-bold text-info">IQA and Assessor Comments</h5>
                                        @foreach($unit->iqa AS $iqa_history)
                                            <div class="itemdiv dialogdiv">
                                                <div class="user">
                                                    <i class="fa fa-comments"></i>
                                                </div>
                                                <div class="body">
                                                    <div class="time">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                        <span class="green">{{ \Carbon\Carbon::parse($iqa_history->created_at)->format('d/m/Y H:i:s') }}</span>
                                                    </div>
                                                    <div class="name">
                                                        <h4>
                                                            @php
                                                                $iqa_created_by = \App\Models\User::findOrFail($iqa_history->user_id);
                                                                echo $iqa_created_by->full_name;
                                                            @endphp
                                                        </h4>
                                                    </div>
                                                    <span class="label label-info">{{ $iqa_history->iqa_type }}</span>
                                                    <div class="text">{!! nl2br($iqa_history->comments) !!}</div>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                    <div class="col-sm-6">
                                        <h5 class="text-bold text-info">EQA Comments</h5>
                                        @forelse($unit->eqa AS $eqa_history)
                                            <div class="itemdiv dialogdiv">
                                                <div class="user">
                                                    <i class="fa fa-comments"></i>
                                                </div>
                                                <div class="body">
                                                    <div class="time">
                                                        <i class="ace-icon fa fa-clock-o"></i>
                                                        <span class="green">{{ \Carbon\Carbon::parse($eqa_history->created_at)->format('d/m/Y H:i:s') }}</span>
                                                    </div>
                                                    <div class="name">
                                                        <h4>
                                                            @php
                                                                $eqa_created_by = \App\Models\User::findOrFail($eqa_history->user_id);
                                                                echo $eqa_created_by->full_name;
                                                            @endphp
                                                        </h4>
                                                    </div>
                                                    <div class="text">{!! nl2br($eqa_history->comments) !!}</div>
                                                </div>
                                            </div>
                                            @empty
                                                <i class="text-info">No EQA comments.</i>
                                        @endforelse

                                    </div>
                                </div>
                                <p><br></p>
                                <p class="text-center">
                                    <span class="alert alert-danger alertIqaRejectedPcs" style="display: none;">
                                        <i class="fa fa-info-circle"></i>
                                        There are <span class="lblIqaRejectedPcs">0</span> rejected PC's. So, the status of this unit
                                        will be 'IQA Rejected'.
                                    </span>
                                    <span class="alert alert-success alertIqaAcceptedPcs" style="display: none;">
                                        <i class="fa fa-info-circle"></i>
                                        There are no rejected PC's. So, the status of this unit
                                        will be 'IQA Accepted'.
                                    </span>
                                </p>
                                <p><br></p>
                                <div class="form-group row {{ $errors->has('comments') ? 'has-error' : ''}}">
                                    {!! Form::label('comments',
                                             'Enter your comments for the assessor. Please explain thoroughly for the assessor to understand your assessment and take corrective actions if required. ',
                                             ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('comments', null, ['class' => 'form-control inputLimiter', 'rows' => '10', 'id' => 'comments', 'maxlength' => 2000]) !!}
                                        {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('iqa_type') ? 'has-error' : ''}}">
                                    {!! Form::label('iqa_type', 'IQA Type', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('iqa_type',
                                            ['Formative' => 'Formative', 'Summative' => 'Summative', 'Observation' => 'Observation', 'Interview' => 'Interview'],
                                            null,
                                            ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'iqa_type']) !!}
                                        {!! $errors->first('iqa_type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('fully_completed') ? 'has-error' : ''}}">
                                    {!! Form::label('fully_completed', 'Fully Completed', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('fully_completed',
                                            ['0' => 'No', '1' => 'Yes'],
                                            null,
                                            ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'fully_completed']) !!}
                                        {!! $errors->first('fully_completed', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            @if($unit->iqa_completed != 1)
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="button" id="btnSubmitIqaAssessment">
                                        <i class="ace-icon fa fa-save bigger-110"></i>
                                        Save Information
                                    </button>&nbsp; &nbsp; &nbsp;
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

        <!-- PAGE CONTENT ENDS -->

        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
    <script type="text/javascript">

        var lblIqaAcceptedPcs = 0;
        var lblIqaRejectedPcs = 0;
        var lblIqaTotalSampled = 0;

        $(function(){
            $('.inputLimiter').inputlimiter();

            $('[data-rel=tooltip]').tooltip();
            $('[data-rel=popover]').popover({html:true});

            $('.pc_iqa_status').on('change', function(){
                $(this).closest('tr').removeClass('bg-success');
                $(this).closest('tr').removeClass('bg-danger');
                if(this.value == '1')
                    $(this).closest('tr').addClass('bg-success');
                else if(this.value == '2')
                    $(this).closest('tr').addClass('bg-danger');

                updateStats();
            });

            screenLoad();
        });

        function screenLoad()
        {
            $('.pc_iqa_status').each(function(){
                $(this).closest('tr').removeClass('bg-success');
                $(this).closest('tr').removeClass('bg-danger');
                if(this.value == '1')
                    $(this).closest('tr').addClass('bg-success');
                else if(this.value == '2')
                    $(this).closest('tr').addClass('bg-danger');
            });

            updateStats();
        }

        function updateStats()
        {
            var a = 0;
            var r = 0;
            $('.pc_iqa_status').each(function(){
               if(this.value == '1')
                   a++;
               else if(this.value == '2')
                   r++;
            });

            $('span.lblIqaAcceptedPcs').html(a);
            $('span.lblIqaRejectedPcs').html(r);
            $('span.lblIqaTotalSampled').html(a+r);

            if(r == 0)
            {
                $('span.alertIqaAcceptedPcs').show();
                $('span.alertIqaRejectedPcs').hide();
            }
            if(r > 0)
            {
                $('span.alertIqaAcceptedPcs').hide();
                $('span.alertIqaRejectedPcs').show();
            }
            $('input[type=hidden][name=accepted_pcs]').val(a);
            $('input[type=hidden][name=rejected_pcs]').val(r);

        }

        $("button#btnSubmitIqaAssessment").click(function(event){
            event.preventDefault();

            if($('input[type=hidden][name=accepted_pcs]').val() == 0 && $('input[type=hidden][name=rejected_pcs]').val() == 0)
            {
                alert('Incomplete assessment, you have not accepted or rejected any PC.');
                return false;
            }
            if($('textarea[name=comments]').val().trim() == '')
            {
                alert('Please provide your comments.');
                $('textarea[name=comments]').focus();
                return false;
            }

            //console.log($("form[name=frmIqaAssessment]").serialize());

            $("form[name=frmIqaAssessment]").submit();

        });
    </script>
@endsection

