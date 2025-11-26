
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BKSB Testing</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link href="/assets/adminlte/plugins/datatables/jquery.dataTables.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">BKSB Testing</div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
            </div>
            <div class="ActionIconBar">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <form name="frmBksb" action="do.php?_action=ajax_bksb" method="post">
        <div class="row">

            <div class="col-sm-8">
                <table class="table table-bordered table-condensed">
                    <tr>
                        <th>Access Key: </th>
                        <td>
                            <input class="form-control" type="text" name="AccessKey" value="8F10C4DE" />
                        </td>
                    </tr>
                    <tr>
                        <th>Secret: </th>
                        <td>
                            <input class="form-control" type="text" name="Secret" value="6966AA486913489397C744D2" />
                        </td>
                    </tr>
                </table>
                <hr>
            </div>

            <div class="col-sm-8">

                <table class="table table-bordered">
                    <tr>
                        <td>
                            Given Details:
                        </td>
                        <td>
<!--                            <p>2302849</p>-->
                            <p>Adam Roughley 2148453</p>
                            <p>Ethan Keen 2205740</p>
                        </td>
                    </tr>
                    <tr>
                        <th>Select API: </th>
                        <td>
                            <select name="Api" class="form-control">
                                <option value="test">Test</option>
                                <option value="version">Version</option>
                                <option value="getUserIdByUsername">Get user id by username</option>
                                <option value="getUsernameById">Get username by user id</option>
                                <option value="initialAssessment">Get initial assessment</option>
                                <option value="isUsernameExists">Is username exists</option>
                                <option value="getAutoLoginLink">Get Auto Login Link</option>
                                <option value="getCourses">Get Courses</option>
                                <option value="diagnosticAssessment">Get Diagnostic Assessment</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Enter Username: </th>
                        <td>
                            <input type="text" name="Username" value="" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <th>Enter User Id: </th>
                        <td>
                            <input type="text" name="UserId" value="" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="button" class="btn btn-primary btn-md" value="Test API" onclick="submitForm();">
                        </td>
                    </tr>
                </table>

                <hr>
            </div>



            <div class="col-sm-8">
                <label for="">API Result:</label>
                <div class="apiResult text-bold text-info" style="font-size: x-large"></div>
            </div>

        </div>
    </form>

</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

    $(function(){

    });

    function submitForm()
    {
        $(".apiResult").html('');

        var frmBksb = document.forms["frmBksb"];

        var client = ajaxPostForm(frmBksb);

        if(client)
        {
            $(".apiResult").html((client.responseText));
        }
        else
        {
            alert(client);
        }
    }

</script>

<?php //if (is_null($bksb_details)) { ?>
<!--    <div class="row">-->
<!--        <div class="col-sm-6">-->
<!--            <p><br></p>-->
<!--            <span class="text-info">-->
<!--                <i class="fa fa-info-circle fa-lg"></i> This learner has not been linked/registered with BKSB yet.-->
<!--            </span>-->
<!---->
<!--            <p><br></p>-->
<!---->
<!--            <form name="frmSearchLearnerInBksb" role="form" action="--><?php //echo $_SERVER['PHP_SELF']; ?><!--" method="post">-->
<!--                <input type="hidden" name="_action" value="ajax_bksb">-->
<!--                <input type="hidden" name="subaction" value="findSimilarUsers">-->
<!--                <input type="hidden" name="studentRef" value="--><?php //echo $vo->ob_username; ?><!--">-->
<!--                <input type="hidden" name="email" value="--><?php //echo $vo->home_email; ?><!--">-->
<!--                <span class="btn btn-primary btn-md" onclick="registerLearnerToForskills();"><i-->
<!--                            class="fa fa-cog"></i> Click to register </span>-->
<!--            </form>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<?php //} ?>


</body>
</html>