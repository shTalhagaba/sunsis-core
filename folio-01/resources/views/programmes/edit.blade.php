@extends('layouts.master')

@section('title', 'Edit Programme')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('programmes.edit', $programme) }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>
      Edit Programme
   </h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('programmes.show', $programme) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>
        @include('partials.session_message')
        @include('partials.session_error')

        <div id="row">
            <div class="col-xs-12">
               <div class="space"></div>

               {!! Form::model($programme->getAttributes(), [
                   'method' => 'PATCH',
                   'url' => route('programmes.update', $programme),
                   'class' => 'form-horizontal',
                   'role' => 'form',
                   'id' => 'frmProgramme']) !!}

                   @include('programmes.form', ['showQualDDL' => false])

               {!! Form::close() !!}

            </div><!-- /.span -->
         </div><!-- /.user-profile -->


      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">

$('.inputLimiter').inputlimiter();


</script>

@endsection

