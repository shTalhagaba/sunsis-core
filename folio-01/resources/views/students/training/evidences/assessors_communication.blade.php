@extends('layouts.master')

@section('title', 'Assess Evidence')

@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>
.circle-icon {
    background: #ffc0c0;
    padding:5px;
    border-radius: 50%;
}
.popover{
        max-width:600px;
    }
</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.evidences.assess', $student, $training_record, $evidence) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Check Evidence <small>{{ $training_record->system_ref }}</small></h1>
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
        <div class="col-sm-12">
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
                                <div class="info-div-name"> Dates & Status </div>
                                <div class="info-div-value">
                                    <span><span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span></span>
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

    <div class="row">
        <div class="col-sm-6">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title smaller">Evidence Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        @include('students.training.evidences.partials.evidence-details', ['_evi_details' => $evidence])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title smaller">Mapped PCs</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div style="max-height: 300px; overflow-y: scroll;" class="small">
                            @forelse ($evidence->mapped_pcs()->orderBy('pc_sequence')->get() as $pc)
                            <li>[{{ $pc->reference }}] {{ \Str::limit($pc->title, 150) }}</li>
                            @empty
                            <i>Not yet mapped to any pc (performance criteria)</i>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @include('partials.session_message')

    @include('partials.session_error')

    <div class="row">
        <div class="col-sm-6">
            @if(!is_null($training_record->secondaryAssessor) &&
            in_array(auth()->user()->id, [$training_record->primaryAssessor->id, $training_record->secondaryAssessor->id])
            )
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title smaller">Enter Your Comments</h5></div>
                <div class="widget-body">
                    {!! Form::open([
                        'url' => route('students.training.evidences.save_assessors_communication', [$student, $training_record, $evidence]),
                        'class' => 'frmEvidenceAssessorCommunication form-horizontal',
                        ]) !!}
                    <div class="widget-main">
                        <div class="form-group row {{ $errors->has('comments') ? 'has-error' : ''}}">
                            <div class="col-sm-12">
                                {!! Form::textarea('comments', null, ['class' => 'form-control', 'rows' => '10', 'required', 'id' => 'comments', 'maxlength' => 800]) !!}
                                {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <button type="submit" class="btn btn-xs btn-success btn-round"><i class="fa fa-save"></i> Save</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            @endif
        </div>
        <div class="col-sm-6">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title smaller">History</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        @foreach(\DB::table('tr_evidence_assessors_comments')->where('evidence_id', $evidence->id)->orderBy('created_at')->get() AS $_m)
                            <div class="itemdiv dialogdiv">
                                <div class="user">
                                    <i class="fa fa-comments"></i>
                                </div>
                                <div class="body">
                                    <div class="time">
                                        <i class="ace-icon fa fa-clock-o"></i>
                                        <span class="green">{{ \Carbon\Carbon::parse($_m->created_at)->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                    <div class="name">
                                        <h4>
                                            @php
                                                $_m_created_by = \App\Models\User::findOrFail($_m->created_by);
                                                echo $_m_created_by->full_name;
                                            @endphp
                                        </h4>
                                    </div>
                                    <div class="text">{!! nl2br(e($_m->comments)) !!}</div>
                                </div>
                            </div>
                        @endforeach
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">



$(function(){
    $('[data-rel=tooltip]').tooltip();
    $('[data-rel=popover]').popover({html:true});




});


</script>
@endsection

