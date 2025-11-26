<?php if(DB_NAME=="am_edexcel") { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification</title>
<link rel="stylesheet" href="qm/css/common.css" type="text/css"/>
<link rel="stylesheet" href="qm/css/print.css" media="print" type="text/css"/>
<link rel="stylesheet" href="qm/css/jquery-ui-1.8rc3.custom.css" media="screen" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="qm/js/common.js"></script>

<script language="javascript">
$(function(){
	// Tabs
	$('#tabs').tabs();

	$('.uibutton').button();
	$('#dialog').dialog({
					autoOpen: false,
					width: 600,
					buttons: {
						"Ok": function() { 
							$(this).dialog("close"); 
						}, 
						"Cancel": function() { 
							$(this).dialog("close"); 
						} 
					},
					modal: true
				});
	$('#dialog_link').button().click(function(){
					$('#dialog').dialog('open');
					return false;
				});

	$('#saveFilter').button({icons: {primary: 'ui-icon-disk'}});
});


function div_filter_crumbs_onclick()
{
	showHideBlock('div_filters');
}
</script>
</head>
<body>

    <div class="banner">
        <table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
            <tr class="head">
                <td valign="bottom">Qualification Manager</td>
                <td valign="bottom" align="right" class="Timestamp"></td>
            </tr>
        </table>
    </div>
<div class="button_bar">
      <table border="0" cellspacing="0" cellpadding="0" height="100%" width="100%">
        <tr>
          <td valign="top" align="left" class="left"><div class="button_wrap">
            <div class="button" id='savebutton' onclick="window.location.href='do.php?_action=edit_qualification';">New</div>
          </div></td>
          <td valign="top" align="right" class="right"><span class="button_start"></span>
          	<img src="qm/images/filter_button.gif"  onclick="showHideBlock('div_filters');" title="Show/hide filters" id="filter_button" />
          	<img src="qm/images/printer_button.gif" onclick="window.print()" title="Print-friendly view" />
          	<img src="qm/images/excel_button.gif" onclick="exportToExcel('view_ViewQualifications');" title="Export to .CSV file" />
          	<img src="qm/images/refresh_button.gif" onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)" />
          </td>
        </tr>
      </table>
</div>

<?php $_SESSION['bc']->render($link); ?>
<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>
	
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_qm_qualifications" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />	
	
		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Qualification</legend>
				<div class="field float">
					<label>Type:</label><?php echo $view->getFilterHTML('filter_qualification_type'); ?>
				</div>
				<div class="field float">
					<label>Sector subject area:</label><?php echo $view->getFilterHTML('filter_qualification_mainarea'); ?>
				</div>
				<div class="field float">
					<label>Sector subject sub-area:</label><?php echo $view->getFilterHTML('filter_qualification_subarea'); ?>
				</div>							
			</fieldset>				
			<fieldset>
				<legend>Misc.</legend>
				<div class="field float">
					<label>Awarding body:</label><?php echo $view->getFilterHTML('filter_awarding_body'); ?>
				</div>
				<div class="field float">
					<label>Level:</label><?php echo $view->getFilterHTML('filter_level'); ?>
				</div>
				<?php if(DB_NAME!='am_edexcel') {?>
				<div class="field float">
					<label>Accessibility:</label><?php echo $view->getFilterHTML('filter_accessibility'); ?>
				</div>							
				<?php }?>
				<div class="field float">
					<label>Status:</label><?php echo $view->getFilterHTML('filter_status'); ?>
				</div>							
				<div class="field float">
					<label>Centre:</label><?php echo $view->getFilterHTML('filter_centres'); ?>
				</div>							
			</fieldset>	
			<fieldset>
				<legend>Options</legend>
				<div class="field float">
					<label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
				<div class="field float">
					<label>Sort By:</label><?php echo $view->getFilterHTML('order_by'); ?>
				</div>
			</fieldset>			
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
		
	</form>
</div>

<div align="center" style="margin-top:50px;" id="conten">
<?php echo $view->render($link); ?>
</div>

</body>
</html>

<?php } else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualifications</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}

</script>

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

</head>

<body>
<div class="banner">
	<table border=0 cellspacing="5" cellpadding="0" width="100%">
		<tr>
			<td valign="top">Qualification Database</td>
			<td valign="top" align="right" class="Timestamp"></td>
		</tr>
		<tr>
			<td valign="bottom" align="left">
			<?php if($_SESSION['user']->isAdmin()){ ?>			
				<button onclick="window.location.href='do.php?_action=edit_qualification';">New</button>
			<?php } ?>			
			</td>
			<td valign="bottom" align="right">
				<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
				<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
				<!-- <button onclick="window.location.href='do.php?_action=export_current_view_to_excel&key=primaryView'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button> -->
				<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
			</td>
		</tr>
	</table>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php echo $view->getFilterCrumbs() ?>

<div id="div_filters" style="display:none">

	<form method="get" action="#" id="applySavedFilter">
		<input type="hidden" name="_action" value="view_learners" />
		<?php echo $view->getSavedFiltersHTML(); ?>
	</form>
	
	<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
		<input type="hidden" name="_action" value="view_qualifications" />
		<input type="hidden" id="filter_name" name="filter_name" value="" />
		<input type="hidden" id="filter_id" name="filter_id" value="" />	
	
		<div id="filterBox" class="clearfix">
			<fieldset>
				<legend>Qualification</legend>
				<div class="field float">
					<label>Type:</label><?php echo $view->getFilterHTML('filter_qualification_type'); ?>
				</div>
				<div class="field float">
					<label>Sector subject area:</label><?php echo $view->getFilterHTML('filter_qualification_mainarea'); ?>
				</div>
				<div class="field float">
					<label>Sector subject sub-area:</label><?php echo $view->getFilterHTML('filter_qualification_subarea'); ?>
				</div>							
			</fieldset>				
			<fieldset>
				<legend>Misc.</legend>
				<div class="field float">
					<label>Awarding body:</label><?php echo $view->getFilterHTML('filter_awarding_body'); ?>
				</div>
				<div class="field float">
					<label>Level:</label><?php echo $view->getFilterHTML('filter_level'); ?>
				</div>
				<?php if(DB_NAME!='am_edexcel') {?>
				<div class="field float">
					<label>Accessibility:</label><?php echo $view->getFilterHTML('filter_accessibility'); ?>
				</div>							
				<?php }?>
				<div class="field float">
					<label>Status:</label><?php echo $view->getFilterHTML('filter_status'); ?>
				</div>							
			</fieldset>	
			<fieldset>
				<legend>Options</legend>
				<div class="field float">
					<label>Records per page:</label><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?>
				</div>
				<div class="field float">
					<label>Sort By:</label><?php echo $view->getFilterHTML('order_by'); ?>
				</div>
			</fieldset>			
			<fieldset>
				<input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> <input type="button" name="saveFilter" value="Save" onclick="doSaveFilter(); return false;"/>
			</fieldset>
		</div>
		
	</form>
</div>

<div align="center" style="margin-top:50px;">
<?php echo $view->render($link); ?>
</div>

</body>
</html>
<?php } ?>