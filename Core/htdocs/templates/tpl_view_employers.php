<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Qualifications</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <!--[if IE]>
    <link rel="stylesheet" href="/common-ie.css" type="text/css"/>
    <![endif]-->
    <script type="text/javascript">
        var GB_ROOT_DIR = "/assets/js/greybox/";
    </script>
    <script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
    <script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
    <script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
    <link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" media="print" href="/print.css" />
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
                    $("#dataMatrix").tablesorter();
                }
        );
    </script>


    <script src="/js/table-export.js" type="text/javascript"></script>

    <script language="JavaScript">

        function div_filter_crumbs_onclick(div)
        {
            showHideBlock(div);
            showHideBlock('div_filters');
        }

        /*
        function changeColumns()
        {
            var myForm = document.forms[0];

            data = 'view=' + <?php echo "'" . $view->getViewName() . "'"; ?>;
	var request = ajaxRequest('do.php?_action=ajax_delete_columns',data);
	
	for(a = 0; a<myForm.length; a++)
	{	
		data = 'view=' + <?php echo "'" . $view->getViewName() . "'"; ?>
			+ '&colum=' + myForm[a].parentNode.title
			+ '&visible=' + ((myForm[a].checked==true)?1:0);
		
		if(myForm[a].checked==false)	
			var request = ajaxRequest('do.php?_action=ajax_save_columns',data);
	}	

	var myForm = document.forms[1];
	myForm.submit();
}
*/

        function changeColumns()
        {
            var viewName = "<?php echo $view->getViewName()?>";
            var $checkboxes = $('input[type="checkbox"][name^="columns"]:not(:checked)'); // find unchecked boxes
            var columns = new Array();
            for(var i = 0; i < $checkboxes.length; i++)
            {
                var obj = {
                    view:viewName,
                    colum:$checkboxes[i].parentNode.title,
                    visible:0
                };
                columns.push(obj);
            }
            var json = JSON.stringify(columns);
            var post = "json=" + encodeURIComponent(json) + "&view=" + encodeURIComponent(viewName);
            var client = ajaxRequest("do.php?_action=ajax_save_columns", post);
            if(client){
                window.location.reload();
            }
        }


    </script>

</head>

<body onload='$(".loading-gif").hide();' >
<div class="banner">
    <div class="Title">Employers</div>
    <div class="ButtonBar">
        <?php if($_SESSION['user']->type==7 || $_SESSION['user']->isAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==23 || $_SESSION['user']->type==24  || (($_SESSION['user']->type==22 || $_SESSION['user']->type==User::TYPE_ADMIN)  AND DB_NAME == 'am_pathway')){ ?>
        <button onclick="window.location.href='do.php?_action=edit_employer';"> New </button>
        <?php } ?>
    </div>
    <div class="ActionIconBar">
        <button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="exportToExcel('view_ViewGroupEmployers');" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="showHideBlock('div_addLesson');" title="Choose columns you want to see"><img src="/images/btn-columns.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div id="div_addLesson" align="center" style="margin-top:10px;margin-bottom:20px;<?php echo 'display:none'; ?>">
        <table border="0" style="margin:10px;background-color:silver; border:1px solid black;padding:3px;">
            <tr>
                <td><?php echo HTML::checkBoxGrid('columns', $view->getColumns($link), $view->getSelectedColumnsNumbers($link), 10); ?></td>
                <td>
                    <div style="margin:20px 0px 20px 10px">
                        <span class="button" onclick="changeColumns();"> Go </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</form>

<div id="div_filters" style="display:none">

    <form method="get" action="#" id="applySavedFilter">
        <input type="hidden" name="_action" value="view_employers" />
        <?php echo $view->getSavedFiltersHTML(); ?>
    </form>

    <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
        <input type="hidden" name="_action" value="view_employers" />
        <input type="hidden" id="filter_name" name="filter_name" value="" />
        <input type="hidden" id="filter_id" name="filter_id" value="" />

        <div id="filterBox" class="clearfix">

            <fieldset>
                <legend>General</legend>
                <div class="field float">
                    <label>Organisation Name:</label><?php echo $view->getFilterHTML('filter_name'); ?>
                </div>
                <div class="field float">
                    <label>Active:</label><?php echo $view->getFilterHTML('by_active'); ?>
                </div>
                <div class="field float">
                    <label>Delivery Partner:</label><?php echo $view->getFilterHTML('filter_provider'); ?>
                </div>
                <div class="field float">
                    <label>Manufacturer/ Brand/ Group Employer:</label><?php echo $view->getFilterHTML('filter_manufacturer'); ?>
                </div>
				<div class="field float">
					<label>Retailer Code</label><?php echo $view->getFilterHTML('filter_retailer_code'); ?>
				</div>
                <div class="field float">
                    <label>District:</label><?php echo $view->getFilterHTML('filter_district'); ?>
                </div>
                <div class="field float">
                    <label>Contract</label><?php echo $view->getFilterHTML('filter_contract'); ?>
                </div>
                <div class="field float">
        		<label>Tag:</label><?php echo $view->getFilterHTML('filter_tag'); ?>
    		</div>
            </fieldset>
            <fieldset>
                <div class="field float">
                    <label>Learners:</label><?php echo $view->getFilterHTML('by_apprentices'); ?>
                </div>
                <div class="field float">
                    <label>Continuing Learners:</label><?php echo $view->getFilterHTML('by_cont_trs'); ?>
                </div>
                <div class="field float">
                    <label>Lead Referral:</label><?php echo $view->getFilterHTML('filter_lead_referral'); ?>
                </div>
                <div class="field float">
                    <label>EDRS:</label><?php echo $view->getFilterHTML('filter_edrs'); ?>
                </div>
                <div class="field float">
                    <label>With or without EDRS:</label><?php echo $view->getFilterHTML('filter_by_edrs_present'); ?>
                </div>
				<div class="field float">
					<label>With or without notes:</label><?php echo $view->getFilterHTML('by_crm_notes'); ?>
				</div>
                <?php if(DB_NAME == "ams" || DB_NAME == "am_baltic") {?>
                <div class="field float">
                    <label>Due Diligence:</label><?php echo $view->getFilterHTML('filter_by_due_diligence'); ?>
                </div>
                <?php } ?>
                <div class="field newrow">
                    <label>Sector:</label><?php echo $view->getFilterHTML('filter_sector'); ?>
                </div>
				<div class="field">
					<label>Levy Employer:</label><?php echo $view->getFilterHTML('filter_levy_employer'); ?>
				</div>
                <div class="field float"><label>Address Line 3:</label> <?php echo $view->getFilterHTML('filter_address_line_3'); ?></div>
						<div class="field float"><label>Address Line 4:</label> <?php echo $view->getFilterHTML('filter_county'); ?></div>
                <div class="field float">
                    <label>Postcode:</label><?php echo $view->getFilterHTML('filter_postcode'); ?>
                </div>
                <div class="field float">
                    <label>Region:</label><?php echo $view->getFilterHTML('filter_region'); ?>
                </div>
				<div class="field newrow"></div>
				<div class="field float">
					<label>Number of employees between</label><?php echo $view->getFilterHTML('filter_from_employees'); ?>
					&nbsp;and <?php echo $view->getFilterHTML('filter_to_employees'); ?>
				</div>
                <?php if(DB_NAME == "am_baltic") {?>
                <div class="field float">
                    <label>Source:</label><?php echo $view->getFilterHTML('filter_source'); ?>
                </div>
                <?php } ?>

            </fieldset>

            <fieldset>
                <legend>Health &amp; Safety</legend>
                <div class="field float">
                    <label>With or without:</label><?php echo $view->getFilterHTML('by_hands'); ?>
                </div>
                <div class="field float">
                    <label>Timeliness:</label><?php echo $view->getFilterHTML('by_health_safety_timeliness'); ?>
                </div>
                <div class="field float">
                    <label>Compliance:</label><?php echo $view->getFilterHTML('by_health_safety_compliance'); ?>
                </div>
                <div class="field float">
                    <label>H&S Paperwork:</label><?php echo $view->getFilterHTML('by_paperwork'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Vacancies</legend>
                <div class="field float">
                    <label>With or without:</label><?php echo $view->getFilterHTML('by_vacancies'); ?>
                </div>
                <?php if(DB_NAME=="ams" || DB_NAME=="am_baltic") {?>
                <div class="field">
                    <label>Vacancies created between</label><?php echo $view->getFilterHTML('filter_from_creation_date'); ?>
                    &nbsp;and <?php echo $view->getFilterHTML('filter_to_creation_date'); ?>
                </div>
                <?php } ?>
            </fieldset>
            <fieldset>
                <legend>CRM Status</legend>
                <div class="field float">
                    <label>With Status:</label><?php echo $view->getFilterHTML('filter_crmstatus'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Options</legend>
                <div class="field float">
                    <label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
                </div>
                <div class="field float">
                    <label>Sort by:</label><?php echo $view->getFilterHTML('order_by'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>Dates</legend>
                <div class="field float">
                    <label>Having PL insurance date between</label><?php echo $view->getFilterHTML('start_date'); ?>
                    &nbsp;and <?php echo $view->getFilterHTML('end_date'); ?>
                </div>
                <div class="field float">
                    <label>PL Date Timeliness:</label><?php echo $view->getFilterHTML('by_pl_date_timeliness'); ?>
                </div>
		<?php if(DB_NAME == "am_duplex") { ?>
                            <div class="field"><label>Created between&nbsp;</label><?php echo $view->getFilterHTML('from_created_date'); ?>&nbsp;and&nbsp;<?php echo $view->getFilterHTML('to_created_date'); ?></div>
                        <?php } ?>
            </fieldset>
            <?php if(DB_NAME=="ams" || DB_NAME=="am_baltic") {?>
            <fieldset>
                <legend>Primary Contact</legend>
                <div class="field float">
                    <label>Contact Name:</label><?php echo $view->getFilterHTML('filter_by_p_contact_name'); ?>
                </div>
                <div class="field float">
                    <label>Contact Telephone:</label><?php echo $view->getFilterHTML('filter_by_p_contact_tel'); ?>
                </div>
                <div class="field float">
                    <label>Contact Mobile:</label><?php echo $view->getFilterHTML('filter_by_p_contact_mob'); ?>
                </div>
                <div class="field float">
                    <label>Contact Email:</label><?php echo $view->getFilterHTML('filter_by_p_contact_email'); ?>
                </div>
            </fieldset>
            <fieldset>
                <legend>CRM Contact</legend>
                <div class="field float">
                    <label>Contact Name:</label><?php echo $view->getFilterHTML('filter_by_c_contact_name'); ?>
                </div>
                <div class="field float">
                    <label>Contact Telephone:</label><?php echo $view->getFilterHTML('filter_by_c_contact_tel'); ?>
                </div>
                <div class="field float">
                    <label>Contact Mobile:</label><?php echo $view->getFilterHTML('filter_by_c_contact_mob'); ?>
                </div>
                <div class="field float">
                    <label>Contact Email:</label><?php echo $view->getFilterHTML('filter_by_c_contact_email'); ?>
                </div>
            </fieldset>
            <?php } ?>
            <fieldset>
                <input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[1]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
            </fieldset>
        </div>
    </form>
</div>

<!-- <span class="button" onclick="window.location.replace('do.php?_action=get_employer&emp_group_id=<?php //echo rawurlencode($id); ?>');"> Import Employer</span> -->
<!-- <span class="button" onclick="window.location.replace('do.php?_action=get_employer_dettach&emp_group_id=<?php //echo rawurlencode($id); ?>');"> Dettach Employer</span> -->
<div align="left" style="margin-top:10px;">

    <?php
    echo $view->render($link, $view->getSelectedColumns($link)); ?>
</div>

</body>
</html>
