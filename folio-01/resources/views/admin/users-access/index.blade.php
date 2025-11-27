@extends('layouts.master')

@section('title', 'Users & Access')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
   <ul class="breadcrumb">
      <li>
         <i class="ace-icon fa fa-home home-icon"></i>
         <a href="#">Home</a>
      </li>
      <li class="active">Users & Access</li>
   </ul><!-- /.breadcrumb -->

   <div class="nav-search" id="nav-search">
      <form class="form-search">
         <span class="input-icon">
            <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
            <i class="ace-icon fa fa-search nav-search-icon"></i>
         </span>
      </form>
   </div><!-- /.nav-search -->
</div>
@endsection

@section('page-content')
<div class="page-header">
   <h1>
      Users & Access
      <small>
         <i class="ace-icon fa fa-angle-double-right"></i>
         manage users and their access
      </small>
   </h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
      <!-- PAGE CONTENT BEGINS -->

      <div class="clearfix">
        <div class="pull-right tableTools-container"></div>
      </div>
      <div class="table-header">
        List of all the users in the system
      </div>
      <div class="table-responsive">
           <table id="tblUsers" class="table table-striped table-bordered table-hover">
               <thead>
                   <tr>
                       <th>User</th>
                       <th>System User Type</th>
                       <th>Roles</th>
                       <th>Permissions</th>
                       <th>Total Logins</th>
                       <th>Last Login</th>
                       <th></th>
                   </tr>
               </thead>
               <tbody>
                   @forelse($users AS $user)
                   <tr>
                     <td>
                        {{ $user->surname }}, {{ $user->firstnames }}<br>
                        <span class="ace-icon fa fa-user"></span> {{ $user->email }}
                     </td>
                     <td><span class="label label-info">{{ \App\Models\LookupManager::getUserTypes($user->user_type) }}</span></td>
                     <td>
                        @forelse($user->roles AS $role)
                        <code>{{ $role->name }}</code>
                        @empty
                        <i>No role has been assigned to the user.</i>
                        @endforelse
                     </td>
                     <td>
                        @forelse($user->permissions AS $permission)
                        <p><span class="label label-info">{{ $permission->name }}</span></p>
                        @empty
                        <i>No permission has been granted to the user.</i>
                        @endforelse
                     </td>
                     <td class="center">{{ $user->authentications->count() }}</td>
                     <td>
                        {{ $user->lastLoginAt() }}<br>
                        {{ $user->lastLoginIp() }}
                     </td>
                     <td>
                        <p>
                           <button type="button" class="btn btn-white btn-primary btn-bold btn-xs" onclick="window.location.href='{{ route('users-access.manage-user-access', $user->id) }}'">
                              <i class="ace-icon fa fa-edit bigger-120 blue"></i>
                              Manage
                           </button>

                           {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $user->id] ]) !!}
                           {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120 orange"></i> Delete', ['class' => 'btn btn-white btn-warning btn-bold btn-xs', 'type' => 'submit']) !!}
                           {!! Form::close() !!}
                        </p>
                     </td>
                   </tr>
                   @empty
                   <tr><td colspan="7">No user found in the system.</td></tr>
                   @endforelse
               </tbody>
           </table>
      </div>
      <div class="well well-sm">
        {{ $users->appends($_GET)->links() }}<br>
        Showing <strong>{{ ($users->currentpage()-1)*$users->perpage()+1 }}</strong>
        to <strong>{{ $users->currentpage()*$users->perpage() >
        $users->total() ? $users->total() :
        $users->currentpage()*$users->perpage() }}</strong>
        of <strong>{{ $users->total() }}</strong> entries
      </div>

      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
@endsection

