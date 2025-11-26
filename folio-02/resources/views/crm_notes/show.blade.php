@extends('layouts.master')

@section('title', 'View CRM Note')

@section('page-content')
   <div class="page-header">
        <h1>
            View CRM Note Entry
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                view details about crm note record
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ $backUrl }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if(!auth()->user()->isStudent())
            <button class="btn btn-sm btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('crm_notes.edit', [$noteableType, $noteable->id, $crmNote->id]) }}'">
                <i class="ace-icon fa fa-edit bigger-110"></i> Edit
            </button>
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('crm_notes.destroy', [$noteableType, $noteable->id, $crmNote->id]),
                'id' => 'frmDeleteCrmNote',
                'style' => 'display: inline;',
                'class' => 'form-inline',
            ]) !!}
            {!! Form::hidden('crm_note_id_to_del', $crmNote->id) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                'data-rel' => 'tooltip',
                'class' => 'btn btn-danger btn-xs btn-round',
                'type' => 'click',
                'id' => 'btnDeleteCrmNote',
            ]) !!}
            {!! Form::close() !!}
            @endif
            <div class="hr hr-12 hr-dotted"></div>
            

            @include('crm_notes.entity_details', ['student' => $student, 'training' => $training])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">CRM Note Details</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name">Created By</div>
                                        <div class="info-div-value">
                                            {{ optional($crmNote->creator)->full_name }}
                                            {!! $crmNote->creator ? '<br>[' . App\Models\Lookups\UserTypeLookup::getDescription($crmNote->creator->user_type) . ']' : '' !!}
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Type of Contact</div>
                                        <div class="info-div-value">
                                            {{ !is_null($crmNote->type_of_contact) ? \App\Models\LookupManager::getCrmTypeOfContacts($crmNote->type_of_contact) : '' }}
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Subject</div>
                                        <div class="info-div-value">{{ !is_null($crmNote->subject) ? \App\Models\LookupManager::getCrmSubjects($crmNote->subject) : '' }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Date of Contact</div>
                                        <div class="info-div-value">{{ optional($crmNote->date_of_contact)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Time of Contact</div>
                                        <div class="info-div-value">{{ $crmNote->time_of_contact }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">By Whom</div>
                                        <div class="info-div-value">{{ $crmNote->by_whom }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Details</div>
                                        <div class="info-div-value">{!! nl2br(e($crmNote->details)) !!}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> {{ \Str::plural('Attachment', $crmNote->media()->count()) }} </div>
                                        <div class="info-div-value">
                                            @if ($crmNote->media()->count() > 0)
                                                @include('partials.model_media_items', ['mediaFiles' => $crmNote->media, 'model' => $crmNote])
                                            @else
                                                <i class="text-info">No file uploaded.</i>                                                 
                                            @endif
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
    $("button#btnDeleteCrmNote").on('click', function(e){
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