@extends('layouts.master')

@section('title', 'View OTJH entry')

@section('page-content')
   <div class="page-header">
        <h1>
            View OTJH Entry
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                view details about off-the-job-hours record
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if(!auth()->user()->isStudent())
            <button class="btn btn-sm btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.otj.edit', [$training, $otj]) }}'">
                <i class="ace-icon fa fa-edit bigger-110"></i> Edit 
            </button>
            @elseif(auth()->user()->isStudent() && $otj->isEditable() && $training->isEditableByStudent())
            <button class="btn btn-sm btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.otj.edit', [$training, $otj]) }}'">
                <i class="ace-icon fa fa-edit bigger-110"></i> Edit 
            </button>
	    {!! Form::open([
                'method' => 'DELETE',
                'url' => route('trainings.otj.destroy', [$training, $otj]),
                'id' => 'frmDeleteOtj',
                'style' => 'display: inline;',
                'class' => 'form-inline',
            ]) !!}
            {!! Form::hidden('otj_id_to_del', $otj->id) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                'data-rel' => 'tooltip',
                'class' => 'btn btn-danger btn-xs btn-round',
                'type' => 'click',
                'id' => 'btnDeleteOtj',
            ]) !!}
            {!! Form::close() !!}
            @endif
            @if(!auth()->user()->isStudent())
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('trainings.otj.destroy', [$training, $otj]),
                'id' => 'frmDeleteOtj',
                'style' => 'display: inline;',
                'class' => 'form-inline',
            ]) !!}
            {!! Form::hidden('otj_id_to_del', $otj->id) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                'data-rel' => 'tooltip',
                'class' => 'btn btn-danger btn-xs btn-round',
                'type' => 'click',
                'id' => 'btnDeleteOtj',
            ]) !!}
            {!! Form::close() !!}
            @endif
            <div class="hr hr-12 hr-dotted"></div>
            

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">OTJ Hours Entry Details</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name">Title</div>
                                        <div class="info-div-value">{!! nl2br(e($otj->title)) !!}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Type</div>
                                        <div class="info-div-value">
                                            @if ($otj->type != '')
                                                {{ \App\Models\LookupManager::getOtjDdl($otj->type) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Date</div>
                                        <div class="info-div-value">{{ Carbon\Carbon::parse($otj->date)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Start Time</div>
                                        <div class="info-div-value">{{ Carbon\Carbon::parse($otj->start_time)->format('H:i') }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Duration</div>
                                        <div class="info-div-value">{{ App\Helpers\AppHelper::formatMysqlTimeToHoursAndMinutes($otj->duration) }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Details</div>
                                        <div class="info-div-value">{!! nl2br(e($otj->details)) !!}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> File/Evidence </div>
                                        <div class="info-div-value">
                                            @if($otj->media->count() > 0)
                                            <div class="col-xs-12">
                                                @include('partials.model_media_items', ['mediaFiles' => $otj->media, 'model' => $otj])
                                            </div>
                                            @endif
                                            
                                            <div class="col-xs-12">
                                                @include('partials.upload_file_form', [
                                                    'associatedModel' => $otj, 
                                                    'sectionName' => ''
                                                    ])
                                            </div>
            
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Status</div>
                                        <div class="info-div-value">{{ $otj->status }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Is OTJ Log</div>
                                        <div class="info-div-value">{!! $otj->is_otj == 1 ? '<span class="bolder text-success">Yes</span>' : '<span class="bolder text-danger">No</span>' !!}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Assessor Comments</div>
                                        <div class="info-div-value">{!! nl2br(e($otj->assessor_comments)) !!}</div>
                                    </div>
				    <div class="info-div-row">
                                        <div class="info-div-name">KSB Elements</div>
                                        <div class="info-div-value">
                                            @forelse ($selectedKsbElementsDetails as $selectedKsbElementDetails)
                                                {!! nl2br(e($selectedKsbElementDetails->title)) !!}<br>
                                            @empty
                                                <i class="text-info">No KSB elements selected.</i>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
<script>
    $("button#btnDeleteOtj").on('click', function(e){
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
                    label: '<i class="fa fa-check"></i> Yes Remove',
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

