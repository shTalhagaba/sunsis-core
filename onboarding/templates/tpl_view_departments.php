<?php /* @var $view View */ ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sub Departments</title>
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
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Sub Departments</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
                <?php if($_SESSION['user']->isAdmin()) { ?><span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=edit_department';"><i class="fa fa-plus"></i> Add New</span><?php } ?>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewDepartments');" title="Export to .csv file"></span>
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
<div class="row">
    <div class="col-lg-12">
        <?php echo $view->getFilterCrumbs(); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
                <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
                    <tr>
                        <td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 9); ?></td>
                        <td>
                            <div style="margin:20px 0px 20px 10px">
                                <span class="button" onclick="changeColumns();"> Go </span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="small" id="div_filters" style="display: none;">
            <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="frmFilters">
                <input type="hidden" name="_action" value="view_departments" />

                <div id="filterBox" class="clearfix">
                    <fieldset>
                        <div class="field float"><label>Dept. Name:</label> <?php echo $view->getFilterHTML('filter_legal_name'); ?></div>
                        <div class="field float"><label>Postcode:</label> <?php echo $view->getFilterHTML('filter_postcode'); ?></div>
                    </fieldset>
                    <fieldset>
                        <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                        <div class="field float"><label>Sort by:</label> <?php echo $view->getFilterHTML('order_by'); ?></div>
                    </fieldset>

                    <fieldset>
                        <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['frmFilters']);" value="Reset" />&nbsp;
                    </fieldset>
                </div>
            </form>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-12">

            <?php $view->render($link, $view->getSelectedColumns($link)); ?>
        </div>
    </div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

    $(function(){
        $('#tblDepartments').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": false,
            "autoWidth": true
        });
        $('img[title="Show calendar"]').hide();
        $(".DateBox").datepicker();
    });

    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('div_filters');
    }
</script>

</body>
</html>