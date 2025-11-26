@extends('layouts.master')

@section('title', 'Complete Delivery Plan Session')

@section('page-content')
<div class="page-header">
   <h1>Delivery Plan Session Task </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button"
            onclick="window.location.href='{{ route('programmes.show', [$programme]) }}'">
            <i class="ace-icon fa fa-times bigger-110"></i> Close
        </button>
        <button class="btn btn-xs btn-info btn-round" type="button"
            onclick="window.location.href='{{ route('programmes.sessions.tasks.edit', [$programme, $session, $task]) }}'">
            <i class="ace-icon fa fa-edit bigger-110"></i> Edit
        </button>

        {!! Form::open([
            'method' => 'DELETE',
            'url' => route('programmes.sessions.tasks.destroy', [$programme, $session, $task]),
            'id' => 'frmDeleteSessionTask',
            'style' => 'display: inline;',
            'class' => 'form-inline',
        ]) !!}
        {!! Form::hidden('task_id_to_del', $task->id) !!}
        {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
            'data-rel' => 'tooltip',
            'class' => 'btn btn-danger btn-xs btn-round',
            'type' => 'click',
            'id' => 'btnDeleteSessionTask',
        ]) !!}
        {!! Form::close() !!}

        <div class="hr hr-12 hr-dotted"></div>

        @include('partials.session_message')
        @include('partials.session_error')

        @include('programmes.partials.session_detail')

        <div class="space-12"></div>

        @include('programmes.partials.task_detail', ['session' => $session, 'task' => $task])

        @include('programmes.sessions.tasks.uploaded_files', ['task' => $task])
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

@push('after-scripts')
<script>
    $("button#btnDeleteSessionTask").on('click', function(e){
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

