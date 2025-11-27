@extends('layouts.master')

@section('title', 'Edit OTJH entry')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.otj.create', $student, $training_record) }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>
      Edit OTJH Entry
      <small>
         <i class="ace-icon fa fa-angle-double-right"></i>
         add new off-the-job-hours record in the system
      </small>
   </h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.training.show', [$student, $training_record]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>
        @include('partials.session_message')

        @include('partials.session_error')

        <div id="user-profile-3" class="user-profile row">
            <div class="col-sm-12">
               <div class="space"></div>
               {!! Form::model($otj, [
                'method' => 'PATCH',
                'url' => route('students.training.otj.update', [$student, $training_record, $otj]),
                'class' => 'form-horizontal',
                'role' => 'form',
                'name' => 'frmEditOtj',
                'id' => 'frmEditOtj',
                'enctype' => 'multipart/form-data',
                ])
             !!}
                     @include('students.training.otj.form')
               {!! Form::close() !!}

            </div><!-- /.span -->
         </div><!-- /.user-profile -->


      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>

@endsection

@section('page-inline-scripts')

<script type="text/javascript">
    $(function(){
        $('.inputLimiter').inputlimiter();
    });
</script>

@endsection

