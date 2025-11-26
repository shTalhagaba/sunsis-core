
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>View Employers</title>
    <link rel="stylesheet" href="css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        body {

        }
    </style>
</head>
<body class="table-responsive">
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Employers</div>
            <div class="ButtonBar">
                <span class="btn btn-xs btn-default" onclick="window.location.href='do.php?_action=edit_employer';"><i class="fa fa-plus"></i> Add New</span>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('applyFilter');showHideBlock('div_filter_crumbs');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewEmployers');" title="Export to .csv file"></span>
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
        <div id="applyFilter" style="display:none">

            <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applySavedFilter" name="applyFilter">
                <input type="hidden" name="_action" value="view_employers" />
                <input type="hidden" id="filter_name" name="filter_name" value="" />
                <input type="hidden" id="filter_id" name="filter_id" value="" />

                <div id="filterBox" class="clearfix">
                    <fieldset>
                        <div class="field float"><label>Legal Name:</label> <?php echo $view->getFilterHTML('filter_legal_name'); ?></div>
                        <div class="field float"><label>EDRS:</label> <?php echo $view->getFilterHTML('filter_edrs'); ?></div>
                        <div class="field float"><label>Postcode:</label> <?php echo $view->getFilterHTML('filter_postcode'); ?></div>
                        <div class="field float"><label>Active:</label> <?php echo $view->getFilterHTML('filter_active'); ?></div>
                        <div class="field float"><label>Levy:</label> <?php echo $view->getFilterHTML('filter_levy_employer'); ?></div>
                        <!-- <div class="field float"><label>Pipeline/Onboarding:</label> <?php //echo $view->getFilterHTML('filter_pipe_onboard'); ?></div> -->
                        <!-- <div class="field float"><label>Business Dev. Officer:</label> <?php //echo $view->getFilterHTML('filter_bdo'); ?></div> -->
                        <div class="field float"><label>Sector:</label> <?php echo $view->getFilterHTML('filter_sector'); ?></div>
                        <div class="field float"><label>Agreement Status:</label> <?php echo $view->getFilterHTML('filter_agreement_status'); ?></div>
                        <div class="field float"><label>Employer Liability Insurance:</label> <?php echo $view->getFilterHTML('filter_el_expiry'); ?></div>
                    </fieldset>
                    <fieldset>
                        <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                        <div class="field float"><label>Sort by:</label> <?php echo $view->getFilterHTML('order_by'); ?></div>
                    </fieldset>

                    <fieldset>
                        <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />&nbsp;
                    </fieldset>
                </div>
            </form>
        </div>

    </div>
    <div class="row">
        <?php echo $view->render($link); ?>
    </div>
</div>

<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript">

    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('applyFilter');
    }

    function resetViewFilters()
    {
        var form = document.getElementById('applySavedFilter');

        form.reset();

        for(var i = 0; i < form.elements.length; i++)
        {
            if(form.elements[i].resetToDefault)
            {
                form.elements[i].resetToDefault();
            }
        }
    }
</script>

</body>
</html>