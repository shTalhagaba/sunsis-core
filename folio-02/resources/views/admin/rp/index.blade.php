@extends('layouts.master')

@section('title', 'Roles & Permissions')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('rp.index') }}
@endsection

@section('page-content')
<div class="page-header"><h1>Roles & Permissions</h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        @include('partials.session_message')

        <div class="row">
            <div class="col-sm-12">
                <div class="tabbable">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active">
                            <a data-toggle="tab" href="#roles">
                                <i class="green ace-icon fa fa-user bigger-120"></i> Roles <span class="badge badge-info">{{ $roles->count() }}</span>
                            </a>
                        </li>
                        <li>
                            <a data-toggle="tab" href="#permissions">
                                <i class="green ace-icon fa fa-key bigger-120"></i> Permissions <span class="badge badge-info">{{ $permissions->count() }}</span>
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="roles" class="tab-pane fade in active">
                            <p><span class="btn btn-sm btn-white btn-bold btn-primary btn-round" onclick="window.location.href='{{ route('roles.create') }}'"><i class="fa fa-plus"></i> Add New Role</span></p>
                            <div class="table-responsive">
                                <table id="simple-table" class="table  table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%;">Name</th><th style="width: 40%;">Description</th><th>Permissions</th><th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($roles AS $role)
                                        <tr>
                                            <td>{{ $role->name }}</td>
                                            <td>{{ $role->description }}</td>
                                            <td>
                                                @if ($role->permissions->count() == $permissions->count())
                                                    All
                                                @else
                                                @forelse($role->permissions AS $permission)
                                                <i class="ace-icon fa fa-key"></i> {{ $permission->name }}</span><br>
                                                @empty
                                                <i>No permissions are found for this role</i>
                                                @endforelse
                                                @endif
                                            </td>
                                            <td>
                                                @if ($role->name != 'Student')
                                                <p>
                                                    <button type="button" class="btn btn-white btn-primary btn-round btn-xs" onclick="window.location.href='{{ route('roles.edit', $role->id) }}'">
                                                        <i class="ace-icon fa fa-edit blue"></i> Edit
                                                    </button>
                                                    {{-- {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                                                    {!! Form::button('<i class="ace-icon fa fa-trash-o orange"></i> Delete', ['class' => 'btn btn-white btn-warning btn-round btn-xs', 'type' => 'submit']) !!}
                                                    {!! Form::close() !!} --}}
                                                </p>
                                                @else
                                                <i>Protected role (cannot be edited)</i>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="6">No role has been found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="permissions" class="tab-pane fade">
                            <div class="table-responsive">
                                {{-- <p><span class="btn btn-sm btn-white btn-bold btn-primary btn-round" onclick="window.location.href='{{ route('permissions.create') }}'"><i class="fa fa-plus"></i> Add New Permission</span></p> --}}

                                <table id="simple-table" class="table  table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th><th>Description</th>
                                            {{-- <th style="width: 20%;"></th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($permissions AS $permission)
                                        <tr>
                                            <td>{{ $permission->name }}</td>
                                            <td>{{ $permission->description }}</td>
                                            {{-- <td>
                                                <p>
                                                    <button style="display: inline;" type="button" class="btn btn-white btn-primary btn-round btn-xs" onclick="window.location.href='{{ route('permissions.edit', $permission->id) }}'">
                                                        <i class="ace-icon fa fa-edit blue"></i> Edit
                                                    </button>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id], 'style' => 'display: inline; margin: 0; padding: 0;' ]) !!}
                                                    {!! Form::button('<i class="ace-icon fa fa-trash-o orange"></i> Delete', ['class' => 'btn btn-xs btn-white btn-warning btn-round', 'type' => 'submit']) !!}
                                                    {!! Form::close() !!}
                                                </p>
                                            </td> --}}
                                        </tr>
                                        @empty
                                        <tr><td colspan="6">No permission has been found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.col -->
        </div>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>
@endsection
