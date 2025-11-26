<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Groups</title>
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

        function checkAll(t)
        {
	        div = document.getElementById("data");
	        elements = div.getElementsByTagName('input');
	        elementsRow = div.getElementsByTagName('tr');
	        for(var i = 0; i < elements.length; i++)
	        {
		        if(elements[i].type == "checkbox")
		        {
			        if(t.checked)
				        elements[i].checked = true;
			        else
				        elements[i].checked = false;
		        }
	        }
        }

        function getInternetExplorerVersion()
        {
	        var rv = -1;
	        if (navigator.appName == 'Microsoft Internet Explorer')
	        {
		        var ua = navigator.userAgent;
		        var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
		        if (re.exec(ua) != null)
			        rv = parseFloat( RegExp.$1 );
	        }
	        else if (navigator.appName == 'Netscape')
	        {
		        var ua = navigator.userAgent;
		        var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
		        if (re.exec(ua) != null)
			        rv = parseFloat( RegExp.$1 );
	        }
	        return rv;
        }


    function saveGrp()
    {
        myForm = document.forms[1];
        buttons = myForm.elements['evidenceradio'];
	    evidence_id = '';
        internaltitle = '';
        selected = 0;
        xml = Array();
        tobedeleted = Array();
        x = 0;
        y = 0;
	    if(getInternetExplorerVersion() == -1)
	    {
		    if(buttons instanceof HTMLInputElement)
		    {
				selected = 1;
			    evidence_id = myForm.elements['evidenceradio'].value;
			    tobedeleted[0] = evidence_id;
			    if(myForm.elements['evidenceradio'].checked)
			        xml[0] = evidence_id;
			    window.location.href='do.php?_action=save_group_members&groups=' + xml + '&tobedeleted=' + tobedeleted + '&tr_id=' + <?php echo $tr_id;?>
		    }
		    else if(buttons instanceof NodeList)
		    {
			    for(var i = 0; i<buttons.length; i++)
			    {
				    selected = 1;
				    evidence_id =  buttons[i].value;

				    if(buttons[i].checked)
				    {
					    xml[x] = evidence_id;
					    x++;
				    }
				    tobedeleted[y] = evidence_id;
				    y++;
			    }
			    window.location.href='do.php?_action=save_group_members&groups=' + xml + '&tobedeleted=' + tobedeleted + '&tr_id=' + <?php echo $tr_id;?>
		    }
	    }
	    else
	    {
		    var objType = buttons.toString.call(buttons);
		    if(objType != '[object HTMLInputElement]')
		    {
			    for(var i = 0; i<buttons.length; i++)
			    {
				    selected = 1;
				    evidence_id =  buttons[i].value;

				    if(buttons[i].checked)
				    {
					    xml[x] = evidence_id;
					    x++;
				    }
				    tobedeleted[y] = evidence_id;
				    y++;
			    }
			    window.location.href='do.php?_action=save_group_members&groups=' + xml + '&tobedeleted=' + tobedeleted + '&tr_id=' + <?php echo $tr_id;?>
		    }
		    else
		    {
			    selected = 1;
			    evidence_id = myForm.elements['evidenceradio'].value;
			    tobedeleted[0] = evidence_id;
			    if(myForm.elements['evidenceradio'].checked)
				    xml[0] = evidence_id;
			    window.location.href='do.php?_action=save_group_members&groups=' + xml + '&tobedeleted=' + tobedeleted + '&tr_id=' + <?php echo $tr_id;?>
		    }
	    }
    }
    </script>


</head>

<body>
<div class="banner">
    <div class="Title"><?php echo $learner; ?></div>
    <div class="ButtonBar">
        <button onclick="saveGrp(this);">Save</button>
    </div>
    <div class="ActionIconBar">
        <button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.location.href='do.php?_action=view_groups&format=csv'" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
    </div>
</div>

<?php echo $view->getFilterCrumbs(); ?>

<div id="div_filters" style="display:none">
    <form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <input type="hidden" name="_action" value="edit_learner_group" />
        <input type="hidden" name="tr_id" value="<?php echo $tr_id;?>" />
        <table>
            <tr>
                <td>Records per page: </td>
                <td><?php echo $view->getFilterHTML(View::KEY_PAGE_SIZE); ?></td>
            </tr>
        </table>
        <fieldset>
            <legend>Dates</legend>
            <div class="field">
                <label>Groups started between</label><?php echo $view->getFilterHTML('start_date'); ?>
                &nbsp;and <?php echo $view->getFilterHTML('end_date'); ?>
            </div>
            <div class="field">
                <label>Groups ended between </label><?php echo $view->getFilterHTML('target_start_date'); ?>
                &nbsp;and <?php echo $view->getFilterHTML('target_end_date'); ?>
            </div>
        </fieldset>

        <fieldset>
            <div class="field float">
                <label>Group FS Tutor:</label><?php echo $view->getFilterHTML('filter_tutor'); ?>
            </div>
            <div class="field float">
                <label>Status:</label><?php echo $view->getFilterHTML('filter_record_status'); ?>
            </div>
            <div class="field float">
                <label>Qualification:</label><?php echo $view->getFilterHTML('filter_qualification'); ?>
            </div>
        </fieldset>

        <input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" />
    </form>
</div>
<form>
<div id="data" align="center" style="margin-top:50px;">
    <?php echo $view->render($link, $tr_id); ?>
</div>
</form>
</body>
</html>
