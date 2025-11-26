@extends('layouts.master')

@section('title', 'Logout from other devices')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('logout-other-devices.show') }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>Logout from other devices</h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
      <!-- PAGE CONTENT BEGINS -->

      @include('partials.session_message')
      @include('partials.session_error')

         <div id="user-profile-3" class="user-profile row">
            <div class="col-sm-12">
               <div class="space"></div>

               @if (session('error'))
                   <div class="alert alert-danger">
                       {{ session('error') }}
                   </div>
               @endif
               @if (session('success'))
                   <div class="alert alert-success">
                       {{ session('success') }}
                   </div>
               @endif

                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Your password is required to execute this functionality<br>
                    <i class="fa fa-info-circle"></i> Your current session will remain active, this feature will kill your sessions from other devices.<br>
                </div>

               {!! Form::open(['url' => route('logout-other-devices.done'), 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST', 'id' => 'frmLogoutOtherDevices']) !!}
                     <div class="form-group row required {{ $errors->has('current-password') ? 'has-error' : ''}}">
                         {!! Form::label('current-password', 'Current Password', ['class' => 'col-sm-4 control-label']) !!}
                         <div class="col-sm-4">
                             {!! Form::password('current-password', ['class' => 'form-control', 'required' => 'required']) !!}
                             {!! $errors->first('current-password', '<p class="text-danger">:message</p>') !!}
                         </div>
                     </div>
                     <div class="clearfix form-actions center">
                        <button class="btn btn-sm btn-success" type="submit">
                            <i class="ace-icon fa fa-sign-out bigger-110"></i>Press to logout
                        </button>
                      </div>
               {!! Form::close() !!}

            </div><!-- /.span -->
         </div><!-- /.user-profile -->


      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>

@endsection

@section('page-inline-scripts')


<script>


    $('#frmLogoutOtherDevices').submit(function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm({
            title: "Are you sure?",
            message: "Do you want to logout from other devices?",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm'
                }
            },
            callback: function(result) {
                if(result)
                    currentForm.submit();
            }
        });
    });

</script>

@endsection

