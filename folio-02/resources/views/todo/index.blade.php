@extends('layouts.master')

@section('title', 'Todo Tasks')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Your Todo Tasks</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('todo_tasks.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Add New Task
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main small">
                        <small> @include('todo.filters')</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="table-header">List of your todo tasks</div>

            <div class="table-responsive">
                <table id="tblTodoTasks" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks AS $task)
                            <tr>
                                <td>
                                    <button
                                        class="btn btn-minier btn-round btn-primary {{ $task->completed ? 'btn-white' : '' }} btnToggleTaskStatus"
                                        title="{{ $task->completed ? 'Mark as active' : 'Mark as completed' }}"
                                        data-task-id="{{ $task->id }}">
                                        &nbsp;<i
                                            class="fa fa-{{ $task->completed ? 'exclamation-circle' : 'check' }}"></i>&nbsp;
                                    </button>
                                    <button class="btn btn-minier btn-round btn-info" title="View details of this item"
                                        onclick="window.location.href='{{ route('todo_tasks.show', $task) }}'">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    @if($task->createdByUser->id === auth()->user()->id)
                                    <button class="btn btn-minier btn-round btn-primary" title="Edit this item"
                                        onclick="window.location.href='{{ route('todo_tasks.edit', $task) }}'">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'url' => route('todo_tasks.destroy', [$task]),
                                        'style' => 'display: inline;',
                                        'class' => 'form-inline frmDeleteTask',
                                    ]) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', [
                                        'class' => 'btn btn-danger btn-minier btn-round btnDeleteTask',
                                        'id' => 'btnDeleteTask' . $task->id,
                                        'type' => 'submit',
                                        'style' => 'display: inline',
                                    ]) !!}
                                    {!! Form::close() !!}
                                    @endif
                                </td>
                                <td id="tdTaskTitle" style="{{ $task->completed ? 'text-decoration: line-through;' : '' }}">{{ $task->title }}</td>
                                <td style="{{ $task->completed ? 'text-decoration: line-through;' : '' }}">{!! nl2br(e($task->description)) !!}</td>
                                <td>{!! $task->completed ? '<i class="fa fa-check-circle fa-2x green"></i>' : '<i class="fa fa-exclamation-circle fa-2x blue"></i>' !!}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">No records found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $tasks])
            </div>
            {!! Form::open([
                'method' => 'POST',
                'name' => 'frmToggleTaskStatus',
            ]) !!}
            {!! Form::hidden('_method', 'PATCH') !!}
            {!! Form::hidden('title', null) !!}
            {!! Form::hidden('completed', null) !!}
            {!! Form::close() !!}
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script>
        $("button.btnToggleTaskStatus").on('click', function(e) {
            e.preventDefault();

            var url = '{{ route('todo_tasks.update', ':taskId') }}';
            var form = $("form[name=frmToggleTaskStatus]");
            form.attr("action",  url.replace(':taskId', $(this).attr('data-task-id')) );

            var title = form.find('input[name="title"]');
            title.val( $(this).closest('tr').find('td#tdTaskTitle').html() );

            var completed = form.find('input[name="completed"]');
            completed.val($(this).hasClass('btn-white') ? '0' : '1');
            
            form.submit();
        });

        $('.btnDeleteTask').on('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            bootbox.confirm({
                title: 'Confirm Delete?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel'
                    },
                    confirm: {
                        label: '<i class="fa fa-trash-o"></i> Yes Delete',
                        className: 'btn-danger'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $('.loader').show();
                        $(form).find(':submit').attr("disabled", true);
                        $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
                        form.submit();
                    }
                }
            });
        });        
    </script>
@endpush
