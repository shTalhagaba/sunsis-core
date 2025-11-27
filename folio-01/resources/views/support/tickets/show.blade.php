@extends('layouts.master')

@section('title', 'Support Ticket')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<style>

</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('support.tickets.show', $ticket) }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>Support Ticket</h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box transparent ui-sortable-handle">
                <div class="widget-header"><h5 class="widget-title smaller">Ticket Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div class="profile-user-info profile-user-info-striped">
                            <div class="profile-info-row">
                                <div class="profile-info-name"> ID </div><div class="profile-info-value"><span>{{ $ticket->id }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Status </div><div class="profile-info-value"><span class="label label-lg label-{{ $ticket->status->color }}">{{ $ticket->status->description }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Priority </div><div class="profile-info-value"><span class="label label-lg label-{{ $ticket->priority->color }}">{{ $ticket->priority->description }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Category </div><div class="profile-info-value"><span class="label label-lg label-{{ $ticket->category->color }}">{{ $ticket->category->description }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Raised By </div>
                                <div class="profile-info-value">
                                    <span>{{ $ticket->author->full_name }}<br>
                                    <i class="fa fa-envelope"></i> {{ $ticket->author_email }}</span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Title </div><div class="profile-info-value"><span>{{ $ticket->title }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Detail </div><div class="profile-info-value"><span>{!! nl2br($ticket->content) !!}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Attachments </div>
                                <div class="profile-info-value">
                                    @foreach($ticket->media AS $mediaItem)
                                    @php
                                    $file_details = 'File Size: ' . $mediaItem->size . '<br>';
                                    $file_details .= '<i class=\'fa fa-clock-o\'></i> ' . $mediaItem->updated_at . '<br>';
                                    @endphp
                                    <a href="{{ route('files.download',  $mediaItem) }}">
                                        <i
                                        data-trigger="hover"
                                        data-rel="popover"
                                        data-original-title="{{ $mediaItem->file_name }}"
                                        data-content="{{ $file_details }}"
                                        class='fa {{ \App\Models\LookupManager::getFileIcon($mediaItem->file_name) }} fa-2x'></i>
                                    </a> &nbsp;
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            {{-- {!! Form::open(['url' => route('students.training.evidences.saveAssessment', [$student, $training_record, $evidence]), 'class' => 'form-horizontal']) !!}
            <div class="form-group row {{ $errors->has('evidence_status') ? 'has-error' : ''}}">
                {!! Form::label('evidence_status', 'Evidence Status', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::select('evidence_status', \App\Models\Training\TrainingRecordEvidence::getAssessmentStatusDDL(), null, ['class' => 'form-control']) !!}
                    {!! $errors->first('evidence_status', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('assessor_comments') ? 'has-error' : ''}}">
                {!! Form::label('assessor_comments', 'Assessor Comments', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('assessor_comments', null, ['class' => 'form-control', 'rows' => '5', 'id' => 'assessor_comments']) !!}
                    {!! $errors->first('assessor_comments', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="clearfix form-actions center">
                <button class="btn btn-sm btn-success" type="submit">
                    <i class="ace-icon fa fa-save bigger-110"></i>Save Evidence Assessment
                </button>
            </div>
            {!! Form::close() !!} --}}
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

$(function(){
    $('[data-rel=tooltip]').tooltip();
    $('[data-rel=popover]').popover({html:true});
});

</script>
@endsection

