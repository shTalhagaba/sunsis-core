
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Qualifications</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
    </style>
</head>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="banner">
            <div class="Title" style="margin-left: 6px;">Qualifications</div>
            <div class="ButtonBar">
                <?php if($_SESSION['user']->isAdmin() || (DB_NAME=="am_lead" && $_SESSION['user']->type == User::TYPE_MANAGER)){ ?>
                    <button class="btn btn-default btn-xs" onclick="window.location.href='do.php?_action=edit_qualification';"><i class="fa fa-plus"></i> Create New Qualification</button>
                <?php } ?>
            </div>
            <div class="ActionIconBar">
                <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');showHideBlock('applySavedFilter');" title="Show/hide filters"></span>
                <span class="btn btn-sm btn-info fa fa-file-excel-o" onclick="exportToExcel('view_ViewQualifications');" title="Export to .CSV file"></span>
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
        <?php echo $view->getFilterCrumbs() ?>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <div id="div_filters" style="display: none;">
                <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="applyFilter" id="applyFilter">
                    <input type="hidden" name="_action" value="view_qualifications" />
                    <input type="hidden" id="filter_name" name="filter_name" value="" />
                    <input type="hidden" id="filter_id" name="filter_id" value="" />

                    <div id="filterBox" class="clearfix small">
                        <fieldset>
                            <legend>Qualification</legend>
                            <div class="field float"><label>Number (QAN):</label><?php echo $view->getFilterHTML('filter_qan'); ?></div>
                            <div class="field float"><label>Title:</label><?php echo $view->getFilterHTML('filter_title'); ?></div>
                            <div class="field float"><label>Type:</label><?php echo $view->getFilterHTML('filter_qualification_type'); ?></div>
                            <div class="field float"><label>Sector Subject Area:</label><?php echo $view->getFilterHTML('filter_qualification_mainarea'); ?></div>
                            <div class="field float"><label>Sector Subject Sub-area:</label><?php echo $view->getFilterHTML('filter_qualification_subarea'); ?></div>
                            <div class="field float"><label>Awarding Body:</label><?php echo $view->getFilterHTML('filter_awarding_body'); ?></div>
                            <div class="field float"><label>Level:</label><?php echo $view->getFilterHTML('filter_level'); ?></div>
                            <div class="field float"><label>Status:</label><?php echo $view->getFilterHTML('filter_status'); ?></div>
                            <div class="field float"><label>Active:</label><?php echo $view->getFilterHTML('by_active'); ?></div>
                        </fieldset>
                        <fieldset>
                            <legend>Options</legend>
                            <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></div>
                            <div class="field float"><label>Order by:</label> <?php echo $view->getFilterHTML(View::KEY_ORDER_BY); ?></div>
                        </fieldset>

                        <fieldset>
                            <input type="submit" value="Apply"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <?php echo $view->render($link, $view->getSelectedColumns($link)); ?>
            </div>
        </div>
    </div>
</div>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

<script language="JavaScript">

    function div_filter_crumbs_onclick(div)
    {
        showHideBlock(div);
        showHideBlock('div_filters');
        showHideBlock('applySavedFilter');
    }
</script>

</body>
</html>