<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Folio | Login</title>
    <meta name="description" content="User login page" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/fonts.googleapis.com.css') }}" />
    <!-- ace styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/ace.min.css') }}" />
    <!--[if lte IE 9]>
      <link rel="stylesheet" href="{{ asset('assets/css/ace-part2.min.css') }}" />
      <![endif]-->
    <link rel="stylesheet" href="{{ asset('assets/css/ace-rtl.min.css') }}" />
    <!--[if lte IE 9]>
      <link rel="stylesheet" href="{{ asset('assets/css/ace-ie.min.css') }}" />
      <![endif]-->
    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
      <script src="{{ asset('assets/js/html5shiv.min.js') }}"></script>
      <script src="{{ asset('assets/js/respond.min.js') }}"></script>
      <![endif]-->

    <style>
        .l-st {
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
        }
    </style>

</head>

<body class="login-layout blur-login">

    <div class="main-container">
        <div class="main-content">

            <div class="row">
                <div class="col-sm-12">
                    <div class="login-container" style="width: 75%">
                        <div class="center">
                            <h1>
                                <img class="img-rounded" height="80px;" src="{{ asset('images/logos/FolioLogo1.png') }}"
                                    alt="Folio">
                            </h1>
                            <h4 class="blue" id="id-company-text">
                                {{ App\Facades\AppConfig::get('FOLIO_CLIENT_NAME') }}</h4>
                        </div>
                        <div class="space-6"></div>
                        <div class="position-relative">

                            <div id="login-box" class="login-box visible widget-box no-border"
                                style="border-radius: 5px;">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header blue lighter bigger">
                                            Reset Your Password
                                        </h4>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="alert alert-info">
                                                    <p>Your password must meet the following guidelines:</p>
                                                    <ul style="margin-left: 15px;">
                                                        <li>be at least 12 characters and no more than 25</li>
                                                        <li>contain one number from [0-9]</li>
                                                        <li>contain one lowercase letter [a-z]</li>
                                                        <li>contain one uppercase letter [A-Z]</li>
                                                    </ul>
                                                </div>
                                                <div class="space-6"></div>
                                                @include('partials.session_error')
                                                <form method="POST" action="{{ route('password.update') }}">
                                                    @csrf

                                                    <input type="hidden" name="token" value="{{ $token }}">
                                                    <input type="hidden" name="username" value="{{ $username }}">
                                                    <input type="hidden" name="email" value="{{ $email }}">

                                                    <fieldset>
                                                        <div class="form-group row">
                                                            <label for="password"
                                                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                                            <div class="col-md-8">
                                                                <input id="password" type="password"
                                                                    class="form-control @error('password') is-invalid @enderror"
                                                                    name="password" required
                                                                    maxlength="25"
                                                                    autocomplete="new-password">

                                                                @error('password')
                                                                    <span class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="password-confirm"
                                                                class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                                            <div class="col-md-8">
                                                                <input id="password-confirm" type="password"
                                                                    class="form-control" name="password_confirmation"
                                                                    required autocomplete="new-password">
                                                            </div>
                                                        </div>

                                                        <div class="clearfix">
                                                            <div class="space-4"></div>
                                                            <button type="submit"
                                                                class="btn btn-sm btn-primary btn-round btn-block">
                                                                <span
                                                                    class="bigger-110">{{ __('Reset Password') }}</span>
                                                            </button>
                                                        </div>
                                                        <div class="space-4"></div>
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.widget-main -->
                                </div>
                                <!-- /.widget-body -->
                            </div>
                            <!-- /.login-box -->

                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.main-content -->
        <div class="footer">
            <div class="footer-inner">
                <div class="footer-content bg-success">
                    <span class="bigger-120">
                        <div class="navbar-header pull-left">
                            <img class="img-rounded" height="40px;" src="{{ asset('images/logos/FolioLogo1.png') }}"
                                alt="">
                        </div>
                        Perspective (UK) Ltd. <i class="fa fa-copyright"></i> {{ date('Y') }} | <a href="#"
                            id="privBtn">Privacy</a>
                    </span>
                </div>
            </div>
        </div>

        <div class="modal fade" id="privModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" style="padding:10px 10px; max-height: 450px; overflow-y: scroll;">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-center">
                                <h3 class="text-bold text-center">Privacy Statement</h3>
                                </p>
                                <p class="text-bold">Introduction</p>
                                <p>Perspective (UK) Ltd are committed to protecting the privacy of and maintaining the
                                    confidentiality, integrity and availability of all the data we hold in accordance
                                    with company policy and the law including the General Data Protection Regulations.
                                    All employees and entities working on behalf of Perspective Ltd are subject to these
                                    requirements.</p>
                                <p>This Privacy Statement explains:</p>
                                <ol style="margin-left: 15px; margin-bottom: 10px;">
                                    <li>What does this statement cover?</li>
                                    <li>How we collect and process personal information</li>
                                    <li>What information do we collect?</li>
                                    <li>How long will data be stored for?</li>
                                    <li>Who do we share data with?</li>
                                    <li>What are your rights?</li>
                                    <li>How secure is your personal information</li>
                                    <li>Data Controller and Data Processor explained</li>
                                    <li>Registration with the ICO</li>
                                </ol>
                                <p><i>It is important to point out that we may amend this Privacy Statement from time to
                                        time. We will post any changes here</i></p>
                                <p class="text-bold">1. What does this statement cover?</p>
                                <p>This statement applies to personal data which we process on behalf of and strictly
                                    upon instruction of our client, the Controller.</p>
                                <p class="text-bold">2. How do we process personal information?</p>
                                <p>Perspective (UK) Ltd process data as required to deliver product support and
                                    maintenance services to our customers. The processing of data is undertaken in line
                                    with our contractual agreement with our client. We process data on behalf of and
                                    with direct instruction from our client, the Controller.</p>
                                <p>Personal Information about you or individuals within your organisation is used to
                                    enable us to provide access, support and maintenance services in line with our
                                    contractual agreement. This data is collected and provided by the Administrators of
                                    your system. </p>
                                <p class="text-bold">3. What information do we process?</p>
                                <p>The information we process about individuals within your organisation is limited to
                                    what is necessary in order
                                    to provide you access to our system and provide our client with audit tracking
                                    information (your access and
                                    changes made within our system). </p>
                                <p style="margin-left: 35px;" class="text-bold"><i>The information we process a
                                        minimum of:</i></p>
                                <ul style="margin-left: 35px; margin-bottom: 10px; font-style: italic;">
                                    <li>IP Addresses</li>
                                    <li>Dates and times you have accessed our services</li>
                                    <li>Audit trail information on changes you make within our system</li>
                                    <li>First Name</li>
                                    <li>Surname</li>
                                    <li>Email Address</li>
                                    <li>Contact Phone Number</li>
                                </ul>
                                <p>The information we process about learners is limited to the data our client enters
                                    onto our system</p>
                                <p style="margin-left: 35px;" class="text-bold"><i>The information we process is a
                                        minimum of:</i></p>
                                <ul style="margin-left: 35px; margin-bottom: 10px; font-style: italic;">
                                    <li>First Name</li>
                                    <li>Surname</li>
                                    <li>Home School</li>
                                    <li>DOB</li>
                                    <li>Gender</li>
                                    <li>Ethnicity</li>
                                </ul>
                                <p class="text-bold">4. How long will data be stored for?</p>
                                <p>The data we process will be stored within our system for the entirety of the
                                    contractual agreement with our
                                    client or until our client wishes to delete this information.</p>
                                <p class="text-bold">5. Who do we share data with?
                                </p>
                                <p>Relevant members of Perspective Ltd staff will need to access your data, as required,
                                    to perform duties
                                    specified within our contract.</p>
                                <p>In addition we use contractors and service providers to process your data on our
                                    behalf for the purposes
                                    described in this policy. We contractually require our service providers to adhere
                                    to the General Data
                                    Protection Regulations. We do not allow our data processors to disclose your data to
                                    others without our
                                    authorisation or to use it for their own purposes.</p>
                                <p style="margin-left: 35px; font-style: italic;">
                                    3rd Party service providers who process your data are: <br>
                                    Sensical Services Ltd - Hosting Service - UK Based
                                </p>
                                <p class="text-bold">6. What are your rights?</p>
                                <p>Under the General Data Protection Regulations you retain various rights in respect to
                                    your personal data. You
                                    will need to contact your Data Controller to exercise these rights.</p>
                                <ul style="margin-left: 15px; margin-bottom: 10px;">
                                    <li>
                                        <p>Right to rectification if inaccurate or incomplete</p>
                                        <p style="font-style: italic;"> If you feel the personal data we process is
                                            inaccurate or incomplete you have the right to request thisis amended.</p>
                                    </li>
                                    <li>
                                        <p>Data Subject Access Request (DSAR)</p>
                                        <p style="font-style: italic;">You have the right to request confirmation of
                                            whether or not your personal data is being processed
                                            and if so what information is being processed. A DSAR will need to be made
                                            in writing and if a request is made we will ask you to verify your identity
                                            before we can process your request. </p>
                                    </li>
                                    <li>
                                        <p>Right to erasure of data</p>
                                        <p style="font-style: italic;">In specific circumstances you have the right of
                                            erasure of the personal data we process.
                                        </p>
                                    </li>
                                    <li>
                                        <p>Right to restrict processing</p>
                                        <p style="font-style: italic;">Under specific conditions you have the right to
                                            restrict to further processing of your personal data.</p>
                                    </li>
                                    <li>
                                        <p>Right to object to processing
                                        </p>
                                        <p style="font-style: italic;">Under specific conditions you have the right to
                                            object to the processing of your personal data</p>
                                    </li>
                                    <li>
                                        <p>Right Data Portability</p>
                                        <p style="font-style: italic;">Under specific conditions you have the right to
                                            receive your personal data back from a controller in a
                                            machine-readable format</p>
                                    </li>
                                    <li>
                                        In addition to the specified rights above you have the right to be notified of
                                        any breaches which may
                                        impact your personal data. You also have a right to lodge a complaint about the
                                        processing of your
                                        personal data with the Information Commissioners Office.
                                    </li>
                                </ul>
                                <p class="text-bold">7. How secure is the personal information we process?</p>
                                <p>Perspective Ltd is committed to ensuring all data is protected and securely held.
                                </p>
                                <p>Personal Data submitted via our systems is protected using Https, SSL Certificates
                                    and Sitewide TLS.
                                </p>
                                <p class="text-bold">8. Data Controller and Data Processor</p>
                                <p>Perspective Ltd does not own, collect or direct the use of client data within our
                                    systems. Perspective Ltd will
                                    only process data upon written instructions of our client. This means that our
                                    client is the Data Controller and
                                    Perspective Ltd is the Data Processor.</p>
                                <p class="text-bold">9. Registration with the ICO</p>
                                <p>Perspective Ltd is registered with the Information Commissioners Office (ICO). Our
                                    registration number is
                                    Z1587800.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"> Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.main-container -->
    <!-- basic scripts -->
    <!--[if !IE]> -->
    <script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
    <!-- <![endif]-->
    <!--[if IE]>
      <script src="{{ asset('assets/js/jquery-1.11.3.min.js') }}"></script>
      <![endif]-->
    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement) document.write(
            "<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
    </script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <!-- inline scripts related to this page -->
    <script type="text/javascript">
        jQuery(function($) {
            $("#privBtn").click(function() {
                $("#privModal").modal();
            });
        });
    </script>


</body>

</html>
