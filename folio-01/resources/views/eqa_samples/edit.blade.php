@extends('layouts.master')

@section('title', 'Edit EQA Sample')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@endsection

@section('breadcrumbs')

@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Edit EQA Sample
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="well well-sm">
                <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('eqa_samples.index') }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                </button>
            </div>

            @include('partials.session_error')

            <div id="row">
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::model($sample, [
                        'method' => 'PATCH',
                        'url' => route('eqa_samples.update', $sample),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmSample']) !!}

                    @include('eqa_samples.form')

                    {!! Form::close() !!}

                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div>
    </div>
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">

        $('.inputLimiter').inputlimiter();

        if(!ace.vars['touch'])
        {
            $('.chosen-select').chosen({allow_single_deselect:true});
            //resize the chosen on window resize

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width': $this.parent().width()});
                    })
                }).trigger('resize.chosen');
            //resize chosen on sidebar collapse/expand
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if(event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': $this.parent().width()});
                })
            });
        }

        $('#frmSample').validate({
            ignore: ":hidden:not(.chosen-select)",
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
                if (element.is("select.chosen-select")) {
                    console.log("placement for chosen");
                    // placement for chosen
                    element.next(".chosen-container").append(error);
                    console.log($('div.chosen-container'));
                    error.insertAfter(element);
                } else {
                    // standard placement
                    error.insertAfter(element);
                }
            }
        });
    </script>

@endsection

