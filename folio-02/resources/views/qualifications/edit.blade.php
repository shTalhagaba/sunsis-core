@extends('layouts.master')
@section('title', 'Edit Qualification')
@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('qualifications.edit', $qualification) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>
        Edit Qualification
        <small>
         <i class="ace-icon fa fa-angle-double-right"></i>
         edit qualification details
      </small>
    </h1>
</div>
<!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('qualifications.show', $qualification) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        <div class="hr hr-12 hr-dotted"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div id="user-profile-3" class="user-profile row">
            <div class="col-sm-12">
                <div class="space"></div>

                {!! Form::model($qualification->getAttributes(), ['method' => 'PATCH', 'url' => route('qualifications.update', $qualification->id),
                'class' => 'form-horizontal', 'role' => 'form']) !!}
                    @include('qualifications.form')
                {!! Form::close() !!}

            </div>
            <!-- /.span -->
        </div>
        <!-- /.user-profile -->


        <!-- PAGE CONTENT ENDS -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
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
