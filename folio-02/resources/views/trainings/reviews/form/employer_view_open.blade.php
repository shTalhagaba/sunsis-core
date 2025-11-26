<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Folio | @yield('title', 'Progress Review Form')</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="{{ asset('assets/css/fonts.googleapis.com.css') }}" />

    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/ace.min.css') }}" class="ace-main-stylesheet"
        id="main-ace-style" />

    <!--[if lte IE 9]>
            <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
        <![endif]-->
    <link rel="stylesheet" href="{{ asset('assets/css/ace-skins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ace-rtl.min.css') }}" />

    <!--[if lte IE 9]>
        <link rel="stylesheet" href="{{ asset('assets/css/ace-ie.min.css') }}" />
        <![endif]-->

    <style type="text/css">
        .form-group.required .control-label:after {
            content: " *";
            color: red;
        }

        input {
            border-radius: 5px;
        }

        .info-div {
            width: 100%;
            display: table;
            width: 98%;
            width: calc(100% - 24px);
            margin: 0 auto;
        }

        .info-div-row {
            display: table-row
        }

        .info-div-name,
        .info-div-value {
            display: table-cell;
            border-top: 1px dotted #D5E4F1
        }

        .info-div-name {
            text-align: right;
            padding: 6px 10px 6px 4px;
            font-weight: 400;
            color: #667E99;
            background-color: transparent;
            /* width: 210px; */
            vertical-align: middle
        }

        .info-div-value {
            padding: 6px 4px 6px 6px
        }

        .info-div-value>span+span:before {
            display: inline;
            /* content: ","; */
            margin-left: 1px;
            margin-right: 3px;
            color: #666;
            border-bottom: 1px solid #FFF
        }

        .info-div-value>span+span.editable-container:before {
            display: none
        }

        .info-div-row:first-child .info-div-name,
        .info-div-row:first-child .info-div-value {
            border-top: none
        }

        .info-div-striped {
            border: 1px solid #DCEBF7
        }

        .info-div-striped .info-div-name {
            color: #336199;
            background-color: #EDF3F4;
            border-top: 1px solid #F7FBFF
        }

        .info-div-striped .info-div-value {
            border-top: 1px dotted #DCEBF7;
            padding-left: 12px
        }
    </style>
    <!-- Scripts -->
    <!-- <script src="https://kit.fontawesome.com/a2c32245af.js"></script> -->

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
        <script src="{{ asset('assets/js/html5shiv.min.js') }}"></script>
        <script src="{{ asset('assets/js/respond.min.js') }}"></script>
        <![endif]-->

    <script src="{{ asset('assets/js/ace-extra.min.js') }}"></script>
</head>

<body class="no-skin">
    <div class="navbar navbar-fixed-top" id="navbar">
        <div id="navbar-container" class="navbar-container">
            <div class="navbar-header pull-left">
                <img class="img-rounded" height="40px;" src="{{ asset('images/logos/'.App\Facades\AppConfig::get('FOLIO_LOGO_NAME')) }}" alt="Logo">
            </div>
            <a href="#" class="navbar-brand" style="margin-left: 30%">
                <small> Review Form  </small>
            </a>
        </div>
    </div>

    <div class="main-container" id="main-container">
        <div class="main-content">
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">

                        @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

                        @include('trainings.reviews.partials.review_basic_details', ['review' => $review])

                        <div class="space-12"></div>

                        @include('trainings.reviews.form.' . $formFolder . '.' . $formVersionFolder . '.show')

                        @include('partials.session_message')

                        @include('partials.session_error')

                        @if ($reviewForm->readyForEmployerSign())
                            {!! Form::open([
                                'method' => 'PATCH',
                                'url' => route('reviews.storeSignatureForm', [
                                    'training_id' => $training->id,
                                    'review_id' => $review->id,
                                    'form_id' => $reviewForm->id,
                                    'user_id' => $signatory->id,
                                ]),
                                'class' => 'form-horizontal',
                                'role' => 'form',
                                'id' => 'frmReview',
                            ]) !!}

                            {!! Form::hidden('training_id', $training->id) !!}
                            {!! Form::hidden('review_id', $review->id) !!}
                            {!! Form::hidden('form_id', $reviewForm->id) !!}
                            {!! Form::hidden('user_id', $signatory->id) !!}

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="widget-box widget-color-green">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Signatures</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div
                                                    class="form-group row required{{ $errors->has('employer_comments') ? 'has-error' : '' }}">
                                                    {!! Form::label('employer_comments', 'Employer comments', [
                                                        'class' => 'col-sm-4 control-label no-padding-right',
                                                    ]) !!}
                                                    <div class="col-sm-8">
                                                        {!! Form::textarea('employer_comments', $formData['employer_comments'] ?? null, [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Employer/Line Manager comments',
                                                            'maxlength' => '1500',
                                                            'required',
                                                        ]) !!}
                                                        {!! $errors->first('employer_comments', '<p class="text-danger">:message</p>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-8 col-sm-offset-4">
                                                        <div class="control-group">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input name="employer_signed" type="checkbox"
                                                                        value="1" required>
                                                                    <span class="lbl bolder"> &nbsp; Tick this option to
                                                                        confirm your signature.</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        {!! $errors->first('employer_signed', '<p class="text-danger">:message</p>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-toolbox padding-8 clearfix">
                                                <div class="center">
                                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div><!-- /.page-content -->
        </div><!-- /.main-content -->
        <!-- footer area -->
        @include('layouts.footer')

        <a href="#" title="Go to top" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
    </div><!-- /.main-container -->
    <!-- list of script files -->
    <!-- basic scripts -->
    <!--[if !IE]> -->
    <script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
    <!-- <![endif]-->

    <!--[if IE]>
        <script src="{{ asset('assets/js/jquery-1.11.3.min.js') }}"></script>
        <![endif]-->
    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement) document.write(
            "<script src='{{ asset('assets/js/jquery.mobile.custom.min.js') }}'>" + "<" + "/script>");
    </script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/acet.js') }}"></script>
</body>

</html>
