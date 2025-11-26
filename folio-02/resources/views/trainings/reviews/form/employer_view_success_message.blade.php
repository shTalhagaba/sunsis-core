<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Folio | @yield('title', 'Review Form Submitted')</title>

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

    <!--[if lte IE 9]>
        <link rel="stylesheet" href="{{ asset('assets/css/ace-ie.min.css') }}" />
        <![endif]-->

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
                        <div class="error-container">
                            <div class="well center">
                               <h1 class="grey lighter smaller">
                                  Progress Review Submitted Successfully
                               </h1>
                   
                               <hr />
                   
                               <div class="space"></div>
                   
                               <div>
                                  <h4 class="lighter smaller">Thanks for completing the form, it has now been submitted successfully.</h4>
                                  <h5 class="lighter smaller">Please close this tab/window.</h5>
                               </div>
                   
                               <hr />
                               <div class="space"></div>
                   
                            </div>
                         </div>
                    </div>
                </div>
            </div><!-- /.page-content -->
        </div><!-- /.main-content -->
        <!-- footer area -->
        @include('layouts.footer')

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
</body>

</html>
