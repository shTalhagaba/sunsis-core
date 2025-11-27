<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Folio | @yield('title')</title>

    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

    <!-- page specific plugin styles -->
    @section('page-plugin-styles')
    @show

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

		.loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 999999;
            background-color: #fff;
            text-align: center;
        }

        .loader-gif {
            width: 20%;
            position: relative;
            top: 30%;
        }
    </style>
    <!-- inline styles related to this page -->
    @section('page-inline-styles')
    @show

    <!-- Scripts -->
    <!-- <script src="https://kit.fontawesome.com/a2c32245af.js"></script> -->

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
      <script src="{{ asset('assets/js/html5shiv.min.js') }}"></script>
      <script src="{{ asset('assets/js/respond.min.js') }}"></script>
      <![endif]-->

    <script src="{{ asset('assets/js/ace-extra.js') }}"></script>
</head>

<body class="no-skin">
	<div class="loader">
        <img src="{{ asset('images/loading51.gif') }}" class="loader-gif" />
    </div>
    <!-- top navigation -->
    @include('layouts.top-navbar')

    <div class="main-container ace-save-state" id="main-container">
        <script type="text/javascript">
            try{ace.settings.loadState('main-container')}catch(e){}
        </script>

        <!-- sidebar navigation -->
        @include('layouts.sidebar')

        <!-- main-content -->
        <div class="main-content">
            <div class="main-content-inner">
                @section('breadcrumbs')
                @show

                <div class="page-content">
                    <div class="ace-settings-container" id="ace-settings-container">
                        <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                            <i class="ace-icon fa fa-cog bigger-130"></i>
                        </div>

                        <div class="ace-settings-box clearfix" id="ace-settings-box">
                            <div class="pull-left width-50">
                                <div class="ace-settings-item">
                                    <div class="pull-left">
                                        <select id="skin-colorpicker" class="ace ace-save-state hide">
                                            <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                            <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                            <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                            <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                        </select>
                                    </div>
                                    <span>&nbsp; Choose Skin</span>
                                </div>

                                <div class="ace-settings-item">
                                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state"
                                        id="ace-settings-navbar" autocomplete="off" />
                                    <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                                </div>

                                <div class="ace-settings-item">
                                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state"
                                        id="ace-settings-sidebar" autocomplete="off" />
                                    <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                                </div>

                                <div class="ace-settings-item">
                                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state"
                                        id="ace-settings-breadcrumbs" autocomplete="off" />
                                    <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                                </div>

                            </div><!-- /.pull-left -->

                            <div class="pull-left width-50">
                                <div class="ace-settings-item">
                                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state"
                                        id="ace-settings-hover" autocomplete="off" />
                                    <label class="lbl" for="ace-settings-hover"> Submenu on Hover</label>
                                </div>

                                <div class="ace-settings-item">
                                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state"
                                        id="ace-settings-compact" autocomplete="off" />
                                    <label class="lbl" for="ace-settings-compact"> Compact Sidebar</label>
                                </div>

                                <div class="ace-settings-item">
                                    <input type="checkbox" class="ace ace-checkbox-2 ace-save-state"
                                        id="ace-settings-highlight" autocomplete="off" />
                                    <label class="lbl" for="ace-settings-highlight"> Alt. Active Item</label>
                                </div>
                            </div><!-- /.pull-left -->
                        </div><!-- /.ace-settings-box -->
                    </div><!-- /.ace-settings-container -->

                    @section('page-content')
                    @show
                </div>
            </div>
        </div><!-- main-content ends -->

        <!-- footer -->
        @include('layouts.footer')

        <a href="#" title="Go to top" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
            <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
        </a>
    </div><!-- /.main-container -->

    <!-- basic scripts -->
    <!--[if !IE]> -->
    <script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
    <!-- <![endif]-->

    <!--[if IE]>
      <script src="{{ asset('assets/js/jquery-1.11.3.min.js') }}"></script>
      <![endif]-->

    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('assets/js/jquery.mobile.custom.min.js') }}'>"+"<"+"/script>");
    </script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootbox.js') }}"></script>

    <!-- page specific plugin scripts -->
    @section('page-plugin-scripts')
    @show

    <!-- ace scripts -->
    <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
    <script src="{{ asset('assets/js/acet.js') }}"></script>

    <script type="text/javascript">
        function viewrow_onmouseover(row, event)
         {
            row.oldBackgroundColor = row.style.backgroundColor;
            row.style.backgroundColor = '#FFD9B5'; //'#BFE08F';
            row.style.cursor = 'pointer';
         }

         function viewrow_onmouseout(row, event)
         {
            if(row.oldBackgroundColor)
            {
               row.style.backgroundColor = row.oldBackgroundColor;
            }
            else
            {
               row.style.backgroundColor = '';
            }
         }

         function isNumberKey(evt)
         {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return false;
            return true;
         }

         function isNumericKey(evt)
         {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 46 && charCode > 31
            && (charCode < 48 || charCode > 57))
            return true;
            return false;
         }
         $('#logout-form-sidebar').submit(function(e) {
            var currentForm = this;
            e.preventDefault();
            bootbox.confirm({
                title: "Confirmation",
                message: "Are you sure you want to logout from system?",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: "btn-sm",
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: "btn-success btn-sm",
                    }
                },
                callback: function(result) {
                    if(result)
                        currentForm.submit();
                }
            });
        });
    </script>
    <!-- inline scripts related to this page -->
    @section('page-inline-scripts')
    @show

    <script type="text/javascript">
        if('ontouchstart' in document.documentElement)
        {
            document.write("<script src='{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}'>"+"<"+"/script>");
        }

		window.addEventListener('load', function () {
            loader_fade_out();
        });

        function loader_fade_out()
        {
            $('.loader').fadeOut();
        }
    </script>

</body>

</html>
