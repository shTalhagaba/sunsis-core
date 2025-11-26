@extends('layouts.master')

@section('title', 'Create Employer')

@section('page-plugin-styles')
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('employers.create') }}
@endsection

@section('page-content')
<div class="page-header"><h1>Create Employer</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('employers.index') }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                </button>
                <div class="hr hr-12 hr-dotted"></div>
            </div>
        </div>
        @include('partials.session_message')
        @include('partials.session_error')
        <div class="row">
            <div class="col-xs-12">
            <div class="space"></div>

            {!! Form::open(['url' => route('employers.store'), 'class' => 'form-horizontal', 'files' => true, 'id' => 'frmEmployer']) !!}
                @include('organisations.form')
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
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>

@endsection

@section('page-inline-scripts')

<script>
    $(function(){

    $('.inputLimiter').inputlimiter();

    $('#frmEmployer').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,

        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },

        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },

        errorPlacement: function (error, element) {
            if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else
                error.insertAfter(element);
        }
    });
});
</script>

@endsection

