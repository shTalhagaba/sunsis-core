<?php /* @var $organisation Organisation */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $sub->id == ''?'Add Sub Department':'Edit Sub Department'; ?></title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
        #postcode{text-transform:uppercase}
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $sub->id == ''?'Add Sub Department':'Edit Sub Department'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
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

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-7">
            <div class="callout">
                <span class="lead text-bold"><?php echo $organisation->legal_name; ?></span>
            </div>
            <form class="form-horizontal" name="frmSubDepartment" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="_action" value="save_sub_department" />
                <input type="hidden" name="id" value="<?php echo $sub->id; ?>" />
                <input type="hidden" name="linked_dept_id" value="<?php echo $sub->linked_dept_id; ?>" />

                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Sub Department Details</h2>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="dept_code" class="col-sm-4 control-label fieldLabel_compulsory">Sub Department Code:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="dept_code" id="dept_code" value="<?php echo $sub->dept_code; ?>" maxlength="8" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dept_name" class="col-sm-4 control-label fieldLabel_compulsory">Sub Department Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="dept_name" id="dept_name" value="<?php echo $sub->dept_name; ?>" maxlength="100" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pm_name" class="col-sm-4 control-label fieldLabel_compulsory">Programme Manager Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="pm_name" id="pm_name" value="<?php echo $sub->pm_name; ?>" maxlength="80" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pm_telephone" class="col-sm-4 control-label fieldLabel_compulsory">Programme Manager Telephone:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="pm_telephone" id="pm_telephone" value="<?php echo $sub->pm_telephone; ?>" maxlength="80" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pm_email" class="col-sm-4 control-label fieldLabel_compulsory">Programme Manager Email:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control compulsory" name="pm_email" id="pm_email" value="<?php echo $sub->pm_email; ?>" maxlength="100" />
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="col-sm-5">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h2 class="box-title">Other Sub Departments</h2>
                </div>
                <div class="box-body">
                    <?php echo $this->renderOtherDepartments($link, $organisation->id); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>

<script language="JavaScript">

    function save()
    {
        var myForm = document.forms["frmSubDepartment"];
        if(!validateForm(myForm))
        {
            return;
        }

        myForm.submit();
    }

    $(function(){
    });
</script>

</body>
</html>