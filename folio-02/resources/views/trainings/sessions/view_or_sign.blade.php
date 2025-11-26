@extends('layouts.master')

@section('title', 'Complete Delivery Plan Session')

@section('page-content')
<div class="page-header">
   <h1>Delivery Plan Session </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('trainings.show', $training) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        @if(!auth()->user()->isStudent())
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('trainings.sessions.destroy', [$training, $session]),
                'id' => 'frmDeleteSession',
                'style' => 'display: inline;',
                'class' => 'form-inline',
            ]) !!}
            {!! Form::hidden('session_id_to_del', $session->id) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                'data-rel' => 'tooltip',
                'class' => 'btn btn-danger btn-xs btn-round',
                'type' => 'click',
                'id' => 'btnDeleteSession',
            ]) !!}
            {!! Form::close() !!}
        @endif
        <div class="hr hr-12 hr-dotted"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

        <div class="space-12"></div>

        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Delivery Plan Session {{ $session->session_number }} Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="width: 30%;">
                                            {!! nl2br(e($session->session_details_1)) !!}
                                        </td>
                                        <td style="width: 30%;">{!! nl2br(e($session->session_details_2)) !!}</td>
                                        <td style="width: 35%;">
                                            @php 
                                                $hoursTotal = 0;
                                                foreach ($session->ksb as $ksb ) 
                                                {
                                                    echo nl2br(e($ksb->pc_title)) . '<hr style="margin-top: 10px; margin-bottom: 10px">';
                                                    $hoursTotal += $ksb->delivery_hours;
                                                }
                                            @endphp
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box transparent">
                    <div class="widget-header">
                        <h5 class="widget-title">Comments & Signatures</h5>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Actual Date </div>
                                    <div class="info-div-value">
                                        {{ optional($session->actual_date)->format('d/m/Y') }}
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Session Start Time </div>
                                    <div class="info-div-value">
                                        {{ isset($session->session_start_time) ? Carbon\Carbon::parse($session->session_start_time)->format('H:i') : '' }}
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Session End Time </div>
                                    <div class="info-div-value">
                                        {{ isset($session->session_end_time) ? Carbon\Carbon::parse($session->session_end_time)->format('H:i') : '' }}
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> File/Resource </div>
                                    <div class="info-div-value">
                                        @if($session->media->count() > 0)
                                        @include('partials.file_media_well', ['fileMedia' => $session->media->first()])
                                        @endif
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Student Signed </div>
                                    <div class="info-div-value">
                                        @if($session->student_sign)
                                        <i class="fa fa-check text-success fa-2x"></i> <br>
                                        {{ $session->student_sign_date->format('d/m/Y') }}
                                        @else
                                        <i class="fa fa-times text-danger fa-2x"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Student Comments </div>
                                    <div class="info-div-value">{!! nl2br(e($session->student_comments)) !!}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Assessor Signed </div>
                                    <div class="info-div-value">
                                        @if($session->assessor_sign)
                                        <i class="fa fa-check text-success fa-2x"></i> <br>
                                        {{ $session->assessor_sign_date->format('d/m/Y') }}
                                        @else
                                        <i class="fa fa-times text-danger fa-2x"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Assessor Comments </div>
                                    <div class="info-div-value">{!! nl2br(e($session->assessor_comments)) !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->isStudent() && ! $session->student_sign && $training->isEditableByStudent())
        @include('trainings.sessions.learner_form')
        @endif

        @if(auth()->user()->isAssessor() && ! $session->assessor_sign)
        @include('trainings.sessions.assessor_form')
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box transparent">
                    <div class="widget-header">
                        <h5 class="widget-title">Tasks</h5>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <button class="btn btn-sm btn-primary btn-round" type="button" onclick="window.location.href='{{ route('trainings.sessions.tasks.create', [$training, $session]) }}'">
                                <i class="ace-icon fa fa-plus bigger-110"></i> Add Task
                            </button>
                            <div class="space-12"></div>
                            <div class="table-responsive">
                                @forelse ($session->tasks as $task)
                                    <table class="table table-bordered">
                                        <tr>
                                            <td style="width: 30%;">
                                                <strong>{{ $task->title }}</strong>
                                                <br>
                                                {!! nl2br(e($task->details)) !!}
                                            </td>
                                            <td style="width: 30%;">
                                                <strong>Task Status:</strong> {{ $task->statusDescription() }}
                                            </td>
                                            <td style="width: 35%;">
                                                <strong>Task Start Date:</strong> {{ optional($task->start_date)->format('d/m/Y') }}
                                                <br>
                                                <strong>Task End Date:</strong> {{ optional($task->complete_by)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    </table>
                                @empty
                                    <i>No task has been created for this session</i>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
@endsection

@push('after-scripts')
<script>
    $("button#btnDeleteSession").on('click', function(e){
        e.preventDefault();

        var form = $(this).closest('form');

        bootbox.confirm({
            title: 'Sure to Remove?',
            message: 'This action is irreversible, are you sure you want to continue?',
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-xs btn-round'
                },
                confirm: {
                    label: '<i class="fa fa-check-o"></i> Yes Remove',
                    className: 'btn-danger btn-xs btn-round'
                }
            },
            callback: function(result) {
                if (result) {
                    form.submit();
                } 
            }
        });        
    });
</script>
@endpush

