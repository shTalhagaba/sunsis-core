
<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Create Applicant</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>

<body>
    <div class="row">
        <div class="col-lg-12">
            <div class="banner">
                <div class="Title" style="margin-left: 6px;">Create Applicant</div>
                <div class="ButtonBar">
                    <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>

                </div>
                <div class="ActionIconBar">
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php $_SESSION['bc']->render($link); ?>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-sm-8 col-sm-offset-1">
            <form class="form-horizontal" action="do.php?_action=create_bc_registration" method="post"
            onsubmit="return validate(this);">
                <input type="hidden" name="_action" value="create_bc_registration">
                <input type="hidden" name="subaction" value="create">

                <div class="box box-default">
                    <div class="box-header with-border">
                        <span class="box-title">Create Applicant</span>
                        <p class="small text-info">
                            <i class="fa fa-info-circle"></i> Create applicant with basic details and send Registration form email.
                        </p>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="text" class="col-sm-4 control-label fieldLabel_compulsory">First Name(s):</label>
                            <div class="col-sm-8">
                                <input class="form-control compulsory" type="text" name="firstnames"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text" class="col-sm-4 control-label fieldLabel_compulsory">Surname:</label>
                            <div class="col-sm-8">
                                <input class="form-control compulsory" type="text" name="surname"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text" class="col-sm-4 control-label fieldLabel_compulsory">Email Address:</label>
                            <div class="col-sm-8">
                                <input class="form-control compulsory" type="text" name="home_email"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text" class="col-sm-4 control-label fieldLabel_optional">Course:</label>
                            <div class="col-sm-8">
                                <?php echo HTML::selectChosen('course_id', $courses, null, true); ?>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button class="btn btn-md btn-primary" type="submit">Save & Email</button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>


    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>



    <script language="JavaScript">

        $(document).ready(function() {



        });

        function validate(form)
        {
            return validateForm(form);
        }


    </script>

</body>

</html>