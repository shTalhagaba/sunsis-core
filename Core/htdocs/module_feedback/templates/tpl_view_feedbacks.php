<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Learner Feedbacks</title>
    <link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
    <link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
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
                <div class="Title" style="margin-left: 6px;">Learner Feedbacks</div>
                <div class="ButtonBar">
                    <span class="btn btn-sm btn-info fa fa-pie-chart" onclick="window.location.href='do.php?_action=feedback_dashboard'"></span>
                </div>
                <div class="ActionIconBar">
                    <span class="btn btn-sm btn-info fa fa-filter" onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');showHideBlock('applySavedFilter');" title="Show/hide filters"></span>
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
                <form method="get" action="#" id="applySavedFilter" style="display: none;">
                    <input type="hidden" name="_action" value="view_feedbacks" />
                    <?php echo $view->getSavedFiltersHTML(); ?>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div id="div_filters" style="display: none;">
                    <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="applyFilter" id="applyFilter">
                        <input type="hidden" name="_action" value="view_feedbacks" />
                        <input type="hidden" id="filter_name" name="filter_name" value="" />
                        <input type="hidden" id="filter_id" name="filter_id" value="" />

                        <div id="filterBox" class="clearfix small">
                            <fieldset>
                                <legend>Feedback</legend>
                                <div class="field float"><label>Learner Name:</label><?php echo $view->getFilterHTML('filter_learner_name'); ?></div>
                                <div class="field float"><label>Submitted date between:</label><?php echo $view->getFilterHTML('filter_from_submitted_date'); ?> &nbsp;and&nbsp; <?php echo $view->getFilterHTML('filter_to_submitted_date'); ?></div>
                                <div class="field float"><label>Score Given:</label><?php echo $view->getFilterHTML('filter_q_total'); ?></div>
                            </fieldset>
                            <fieldset>
                                <legend>Options:</legend>
                                <div class="field float"><label>Records per page:</label> <?php echo $view->getFilterHTML(VoltView::KEY_PAGE_SIZE); ?></div>
                                <div class="field float"><label>Order by:</label> <?php echo $view->getFilterHTML(VoltView::KEY_ORDER_BY); ?></div>
                            </fieldset>

                            <fieldset>
                                <input type="submit" value="Apply" />&nbsp;<input type="button" onclick="resetViewFilters(document.forms['applyFilter']);" value="Reset" />
                            </fieldset>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <?php echo $this->renderView($link, $view); ?>
                </div>
            </div>
        </div>
    </div>


    <script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>

    <script language="JavaScript">
        $(function() {
            $('img[title="Show calendar"]').hide();
            $(".DateBox").datepicker();

        });

        function div_filter_crumbs_onclick(div) {
            showHideBlock(div);
            showHideBlock('div_filters');
            showHideBlock('applySavedFilter');
        }
    </script>

</body>

</html>