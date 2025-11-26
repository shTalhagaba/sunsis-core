<?php /* @var $vo Contract */ ?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $vo->id == ''?'Create Allocation':'Edit Allocation'; ?></title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .ui-datepicker .ui-datepicker-title select {
            color: #000;
        }
        .disabled{
            pointer-events:none;
            opacity:0.4;
        }
    </style>
</head>
<body>

<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;"><?php echo $vo->id == ''?'Create Allocation':'Edit Allocation'; ?></div>
            <div class="ButtonBar">
                <span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <span class="btn btn-sm btn-default" onclick="saveFrmAllocation();"><i class="fa fa-save"></i> Save</span>
                <?php if($vo->id!='') { ?>
                <span class="btn btn-sm btn-default" onclick="window.location.href='do.php?_action=read_allocation&allocation_id=<?php echo $vo->id; ?>';"><i class="fa fa-arrow-circle-o-left"></i> Analysis </span>
                <?php } ?>
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
    <form class="form-horizontal" name="frmAllocation" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name="_action" value="save_allocation" />
        <input type="hidden" name="id" value="<?php echo $vo->id ?>" />
        <div class="col-md-8">

            <div class="box box-primary">

                <div class="box-body">
                    <!--<div class="form-group">
                        <label for="active" class="col-sm-4 control-label fieldLabel_compulsory">Active:</label>
                        <div class="col-sm-8">
                            <?php
                            //echo $vo->active == '1' ?
                            //    '<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" checked="checked" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />':
                            //    '<input value="1" class="yes_no_toggle" type="checkbox" name="active" data-toggle="toggle" data-on="Yes" data-off="No" data-onstyle="success" data-offstyle="danger" />';
                            ?>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label for="text" class="col-sm-4 control-label fieldLabel_compulsory">Title:</label>
                        <div class="col-sm-8">
                            <input class="form-control compulsory" type="text" name="title" value="<?php echo htmlspecialchars((string)$vo->title ?: ''); ?>" />
                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label for="contract_year" class="col-sm-4 control-label fieldLabel_compulsory">Contract Holder:</label>
                        <div class="col-sm-8">
                            <div class="callout">
                                <div class="form-group">
                                    <label for="contract_holder" class="col-sm-4 control-label fieldLabel_compulsory">Org. Name:</label>
                                    <div class="col-sm-8">
                                        <?php //echo HTML::selectChosen('contract_holder', $providers, $vo->contract_holder, true, true); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ukprn" class="col-sm-4 control-label fieldLabel_optional">Org. UKPRN:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control optional" type="text" name="ukprn" id="ukprn" value="<?php //echo htmlspecialchars((string)$vo->ukprn); ?>" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label for="contract_year" class="col-sm-4 control-label fieldLabel_compulsory">Period & Dates:</label>
                        <div class="col-sm-8">
                            <div class="callout">
                                <div class="form-group">
                                    <label for="input_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Allocation Start Date:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::datebox('start_date', $vo->start_date, true); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input_end_date" class="col-sm-4 control-label fieldLabel_compulsory">Allocation End Date:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::datebox('end_date', $vo->end_date, true); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input_end_date" class="col-sm-4 control-label fieldLabel_compulsory">Learners started from:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::datebox('learner_start_date', $vo->learner_start_date, true); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="input_end_date" class="col-sm-4 control-label fieldLabel_compulsory">Learners started to:</label>
                                    <div class="col-sm-8">
                                        <?php echo HTML::datebox('learner_end_date', $vo->learner_end_date, true); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contract_year" class="col-sm-4 control-label fieldLabel_compulsory">Funding:</label>
                        <div class="col-sm-8">
                            <div class="callout">
                                <div class="form-group">
                                    <label for="proportion" class="col-sm-4 control-label fieldLabel_compulsory">Allocation Amount:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control compulsory" type="text" id = "allocation_amount" name="allocation_amount" onkeypress="return numbersonly(this, event);" value="<?php echo htmlspecialchars((string)$vo->allocation_amount); ?>" maxlength="10" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label for="description" class="col-sm-12 fieldLabel_optional">Description:</label>
                        <div class="col-sm-12">
                            <textarea name="description" id="description" rows="10" style="width: 100%;"><?php echo $vo->description; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<br>
<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script language="JavaScript">

    $(function() {

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('#input_start_date').attr('class', 'datepicker compulsory form-control');
        $('#input_end_date').attr('class', 'datepicker compulsory form-control');
    });

    function saveFrmAllocation()
    {
        var frmAllocation = document.forms["frmAllocation"];
        //if(validateForm(frmAllocation) == false)
        //{
        //    return false;
        //}
        frmAllocation.submit();
    }

    function contract_year_onchange(year)
    {
        if(year.value == '')
        {
            document.getElementById('input_start_date').value = '';
            document.getElementById('input_end_date').value = '';
            return ;
        }
        var y = parseInt(year.value);
        var ny = y+1;
        document.getElementById('input_start_date').value = '01/08/' + y;
        document.getElementById('input_end_date').value = '31/07/' + ny;
    }

    function contract_holder_onchange(contractholder)
    {
        var request = ajaxBuildRequestObject();
        request.open("GET", expandURI('do.php?_action=ajax_get_ukprn&id=' + contractholder.value), false);
        request.setRequestHeader("x-ajax", "1");
        request.send(null);

        if(request.status == 200)
        {
            var ukprn = request.responseText;
            if(ukprn != 'error')
            {
                document.getElementById('ukprn').value = ukprn;
            }
            else
            {
            }
        }
        else
        {
            ajaxErrorHandler(request);
        }
    }
</script>

</body>
</html>