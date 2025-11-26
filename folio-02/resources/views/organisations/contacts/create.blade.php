@extends('layouts.master')

@section('title', 'Create Contact')

@section('page-content')
    <div class="page-header">
        <h1>Create Contact</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="{{ $cancelLink }};">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>
            @include('partials.session_message')
            @include('partials.session_error')
            <div class="row">
                <div class="col-sm-5">
                    @include('organisations.organisation_basic_details')
                </div>

                <div class="col-sm-7">
                    {!! Form::open([
                        'url' => route('organisations.contacts.store', [$organisation]),
                        'class' => 'form-horizontal',
                        'id' => 'frmContact',
                    ]) !!}
                    @include('organisations.contacts.form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>

@endsection

@section('page-inline-scripts')

    <script>
        $(function() {

            $('#frmContact').validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,

                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },

                success: function(e) {
                    $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                    $(e).remove();
                },

                errorPlacement: function(error, element) {
                    error.insertAfter(element);                        
                }
            });
        });
    </script>

@endsection
