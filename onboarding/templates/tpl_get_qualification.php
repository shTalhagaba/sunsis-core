<?php /* @var $view View */ ?>
<?php /* @var $framework Framework */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Add/Remove Qualifications</title>
    <link rel="stylesheet" href="css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        input[type=checkbox], input[type=radio] {
            transform: scale(1.4);
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Add/Remove Qualifications</div>
            <div class="ButtonBar">
                <button class="btn btn-default btn-xs" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>'"><i class="fa fa-arrow-circle-o-left"></i> Back</button>
                <?php if($_SESSION['user']->type != User::TYPE_SYSTEM_VIEWER){?>
                    <button class="btn btn-default btn-xs" onclick="save();"><i class="fa fa-save"></i> Update</button>
                <?php } ?>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-refresh" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <?php $_SESSION['bc']->render($link); ?>
    </div>
</div>

<div class="container-fluid">

    <p><br></p>
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <table class="table table-bordered">
                <tr>
                    <th>Framework/Standard:</th><td class="bg-success"><?php echo $framework->title; ?></td>
                    <th>Type:</th><td class="bg-success"><?php echo DAO::getSingleValue($link, "SELECT CONCAT(ProgType, ' ' , ProgTypeDesc) FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '{$framework->framework_type}'"); ?></td>
                    <th>Duration (months):</th><td class="bg-success"><?php echo $framework->duration_in_months; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="table-responsive">
                <form name="frmAddRemoveQuals" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?_action=add_remove_framework_quals">
                    <input type="hidden" name="_action" value="add_remove_framework_quals">
                    <input type="hidden" name="framework_id" value="<?php echo $framework->id; ?>">
                    <?php echo $view->renderWithTitle($link, $fid); ?>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

    $(function(){
        $("input[type=checkbox]").on("change", function(){
            var row = $(this).closest("tr");
            if(this.checked)
                row.addClass('bg-warning');
            else
                row.removeClass('bg-warning');
        });
    });

    function save()
    {/*
        var main_aim = $("input[type=radio][name=main_aim_radio]:checked").val();
        if(main_aim === undefined || main_aim.trim() == '')
        {
            alert('Please select the main aim among your selected qualifications.');
            return;
        }

        var valid_main_aim = false;
        var all_proportions_given = true;
        var all_durations_given = true;

        $("input[type=checkbox]:checked").each(function(){
            var val = $(this).val();
            if(val == main_aim)
                valid_main_aim = true;
            if($("input[name=proportion"+val+"]").val().trim() === '')
                all_proportions_given = false;
            if($("input[name=duration"+val+"]").val().trim() === '')
                all_durations_given = false;
        });

        if(!valid_main_aim)
        {
            alert('Please select the main aim among your selected qualifications.');
            return;
        }

        if(!all_proportions_given)
        {
            alert('Please provide proportion for your selected qualifications.');
            return;
        }

        if(!all_durations_given)
        {
            alert('Please provide duration for your selected qualifications.');
            return;
        }
*/
        myForm = document.forms["frmAddRemoveQuals"];
        myForm.submit();
    }

</script>

</body>
</html>