@extends('layouts.perspective.master')

@section('title', 'Perspective Support - View Permissions')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')

@endsection

@section('page-content')

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-xs-12">
                <div class="well well-sm">
                    <button class="btn btn-sm btn-white btn-bold btn-primary btn-round" type="button"
                        onclick="window.location.href='{{ route('perspective.support.permissions.create') }}'">
                        <i class="ace-icon fa fa-key bigger-120"></i> Add New Permission
                    </button>
                </div>
            </div>
        </div>

        @include('partials.session_message')

        <div class="table-responsive">
            <table id="simple-table" class="table  table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th><th>Description</th><th style="width: 20%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions AS $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->description }}</td>
                        <td>
                            <p>
                                <button style="display: inline;" type="button" class="btn btn-white btn-primary btn-round btn-xs"
                                onclick="window.location.href='{{ route('perspective.support.permissions.edit', $permission->id) }}'">
                                    <i class="ace-icon fa fa-edit blue"></i> Edit
                                </button>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['perspective.support.permissions.destroy', $permission->id], 'style' => 'display: inline; margin: 0; padding: 0;' ]) !!}
                                {!! Form::button('<i class="ace-icon fa fa-trash-o orange"></i> Delete', ['class' => 'btn btn-xs btn-white btn-danger btn-round', 'type' => 'submit']) !!}
                                {!! Form::close() !!}
                            </p>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6">No permission has been found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
<script>


</script>

@endsection
