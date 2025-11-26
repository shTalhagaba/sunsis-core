<?php /* @var $view View */ ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Contracts</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<!--<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>-->

<!-- Initialise calendar popup -->
<script language="JavaScript">


function div_filter_crumbs_onclick(div)
{
	showHideBlock(div);
	showHideBlock('div_filters');
}
function numbersonly(myfield, e, dec)
{
	var key;
	var keychar;

	if (window.event)
		key = window.event.keyCode;
	else if (e)
		key = e.which;
	else
		return true;

	keychar = String.fromCharCode(key);

// To check if it goes beyond 100
	if(parseFloat(myfield.value+keychar)<0 || parseFloat(myfield.value+keychar)>100)
		return false;

// control keys
	if ((key==null) || (key==0) || (key==8) ||
		(key==9) || (key==13) || (key==27) )
		return true;

// numbers
	else if ((("0123456789").indexOf(keychar) > -1))
		return true;

// decimal point jump
	else if (dec && (keychar == "."))
	{
		myfield.form.elements[dec].focus();
		return false;
	}
	else
		return false;

}

function contract_year_onchange(y)
{
	div = document.getElementById("data");
	elements = div.getElementsByTagName('input');
	for(var i = 0; i < elements.length; i++)
		if(elements[i].type == "checkbox")
			elements[i].checked = false;
	elements = div.getElementsByTagName('tr');
	for(var i = 0; i < elements.length; i++)
	{
		if(elements[i].title == y.value)
			elements[i].style.display = "table-row";
		else if(elements[i].title!='')
			elements[i].style.display = "none";
	}

    query = "SELECT RIGHT(submission,2) FROM central.lookup_submission_dates WHERE contract_year = " + y.value + " AND CURDATE() BETWEEN census_start_date AND last_submission_date;";
    var request = ajaxRequest("do.php?_action=ajax_get_value&id=&query=" + htmlspecialchars(query));
	if(request.responseText!="")
		document.forms[0].submissions.selectedIndex = request.responseText;
	else
		document.forms[0].submissions.selectedIndex = "13";
}

function setContracts()
{
	var y = <?php echo $contract_years[0][0]; ?>;
	div = document.getElementById("data");
	elements = div.getElementsByTagName('tr');
	for(var i = 0; i < elements.length; i++)
	{
		if(elements[i].title == y)
			elements[i].style.display = "table-row";
		else if(elements[i].title!='')
			elements[i].style.display = "none";
	}
}


function checkAll(t)
{
	//var y = <?php echo $contract_years[0][0]; ?>;
	var y = document.getElementById('contract_year').value;
	div = document.getElementById("data");
	elements = div.getElementsByTagName('input');
	elementsRow = div.getElementsByTagName('tr');
	for(var i = 0; i < elements.length; i++)
	{
		if(elements[i].type == "checkbox" && elementsRow[i].title == y)
		{
			if(t.checked)
				elements[i].checked = true;
			else
				elements[i].checked = false;
		}
	}
}
function saveWithCSV()
{
	myForm = document.forms[0];

	var export_only =  document.getElementsByName("export_only[]");

	if(export_only[0].checked)
		document.getElementById("loading").style.display="none";
	else
		document.getElementById("loading").style.display="block";

	var filePath = document.getElementById("file").value;
	//var prevFileName = document.forms[0].selectedFileNameFromList.options[document.forms[0].selectedFileNameFromList.selectedIndex].text;

	if(filePath == "")
	{
		alert("Please provide CSV file");
		document.getElementById("loading").style.display="none";
		return false;
	}

	if(document.forms[0].submissions.options[document.forms[0].submissions.selectedIndex].text == "")
	{
		alert("Please select Submission");
		document.getElementById("loading").style.display="none";
		return false;
	}

	buttons = myForm.elements['evidenceradio'];
	evidence_id = '';
	internaltitle = '';
	selected = 0;

	// Add all the selected qualifications to the framework
	xml = Array();
	x = 0;
	for(var i = 0; i<buttons.length; i++)
	{

		if(buttons[i].checked)
		{
			selected = 1;
			evidence_id =  buttons[i].value;

			xml[x] = evidence_id;
			x++;
		}
	}

	if(selected==0)
	{
		alert("Please select a contract");
		document.getElementById("loading").style.display="none";
		return false;
	}


	f = document.forms[0];
	f.contract.value = xml.join(",");

	var filename = getFilePartsFromFilePath(filePath)["FullName"];

	//if(prevFileName != "")
	//	filename = prevFileName;

	document.getElementById('_action').value='read_pfr';


	f.enctype="multipart/form-data";

	f.submit();

}

function showWorkRoutesReport()
{
	document.getElementById("loading").style.display="block";
	// To find which course is selected
	myForm = document.forms[0];
	buttons = myForm.elements['evidenceradio'];
	evidence_id = '';
	internaltitle = '';
	selected = 0;
	// Add all the selected qualifications to the framework
	xml = Array();
	x = 0;
	for(var i = 0; i<buttons.length; i++)
	{
		if(buttons[i].checked)
		{
			selected = 1;
			evidence_id =  buttons[i].value;
			xml[x] = evidence_id;
			x++;
		}
	}
	if(selected==0)
	{
		alert("Please select a contract");
		return false;
	}
	submission = document.forms[0].submissions.options[document.forms[0].submissions.selectedIndex].text;
	f = document.forms[0];
	f.contract.value = xml.join(",");
	window.location.href='do.php?_action=<?php echo $destination; ?>&contract=' + f.contract.value + '&submission='+submission;
}

function saveImportLearners()
{
    myForm = document.forms[0];

    var filePath = document.getElementById("file").value;
    //var prevFileName = document.forms[0].selectedFileNameFromList.options[document.forms[0].selectedFileNameFromList.selectedIndex].text;

    if(filePath == "")
    {
        alert("Please provide CSV file");
        document.getElementById("loading").style.display="none";
        return false;
    }

    f = document.forms[0];

    var filename = getFilePartsFromFilePath(filePath)["FullName"];

    //if(prevFileName != "")
    //	filename = prevFileName;

    document.getElementById('_action').value='learner_import';

    f.enctype="multipart/form-data";

    f.submit();
}

function saveBulkUpdate()
{
    myForm = document.forms[0];

    var filePath = document.getElementById("file").value;

    if(filePath == "")
    {
        alert("Please provide CSV file");
        document.getElementById("loading").style.display="none";
        return false;
    }

    f = document.forms[0];

    var filename = getFilePartsFromFilePath(filePath)["FullName"];

    document.getElementById('_action').value='bulk_update';

    f.enctype="multipart/form-data";

    f.submit();
}



function save()
{
	document.getElementById("loading").style.display="block";
	// To find which course is selected
	myForm = document.forms[0];
	buttons = myForm.elements['evidenceradio'];
	evidence_id = '';
	internaltitle = '';
	selected = 0;

	// Add all the selected qualifications to the framework
	xml = Array();
	x = 0;
	for(var i = 0; i<buttons.length; i++)
	{

		if(buttons[i].checked)
		{
			selected = 1;
			evidence_id =  buttons[i].value;

			xml[x] = evidence_id;
			x++;
		}
	}

	assessor = document.pre_filters.GetContractsPredictor_assessor.options[document.pre_filters.GetContractsPredictor_assessor.selectedIndex].value;
	employer = document.pre_filters.GetContractsPredictor_employer.options[document.pre_filters.GetContractsPredictor_employer.selectedIndex].value;
	course = document.pre_filters.GetContractsPredictor_course.options[document.pre_filters.GetContractsPredictor_course.selectedIndex].value;
	provider = document.pre_filters.GetContractsPredictor_provider.options[document.pre_filters.GetContractsPredictor_provider.selectedIndex].value;
	active = document.pre_filters.GetContractsPredictor_filter_active.options[document.pre_filters.GetContractsPredictor_filter_active.selectedIndex].value;
	valid = document.pre_filters.GetContractsPredictor_filter_valid.options[document.pre_filters.GetContractsPredictor_filter_valid.selectedIndex].value;
	lsf = document.pre_filters.GetContractsPredictor_filter_lsf.options[document.pre_filters.GetContractsPredictor_filter_lsf.selectedIndex].value;
	zprog = document.pre_filters.GetContractsPredictor_filter_zprog.options[document.pre_filters.GetContractsPredictor_filter_zprog.selectedIndex].value;
	var emp_b_code = "";
	<?php if($destination=="funding_prediction" && (DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo")) {?>
	emp_b_code = document.pre_filters.GetContractsPredictor_filter_emp_b_code.options[document.pre_filters.GetContractsPredictor_filter_emp_b_code.selectedIndex].value;
	<?php }?>
	if(selected==0)
	{
		alert("Please select a contract");
		return false;
	}

	/*	if(document.getElementById('submissions').value=='')
		   {
			   alert("Please select a submission");
			   return false;
		   }
	   */

	submission = document.forms[0].submissions.options[document.forms[0].submissions.selectedIndex].text;


	f = document.forms[0];
	f.contract.value = xml.join(",");
	f.assessor.value = assessor;
	f.employer.value = employer;
	f.course.value = course;
	f.provider.value = provider;

//	f.submit();

	window.location.href='do.php?_action=<?php echo $destination; ?>&contract=' + f.contract.value + '&assessor=' + assessor + '&employer=' + employer + '&provider=' + provider + '&course=' + course + '&submission=' + submission + '&active=' + active + '&zprog=' + zprog + '&lsf=' + lsf + '&valid=' + valid + '&filter_emp_b_code=' + emp_b_code;



}

function getFilePartsFromFilePath(fullPath)
{
	var fileParts = Array();
	var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
	var filename = fullPath.substring(startIndex);
	if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
		filename = filename.substring(1);
	}
	filename = filename.substr(0, filename.lastIndexOf('.'));
	var ext = fullPath.substring(fullPath.lastIndexOf('.') + 1).toLowerCase();
	fileParts["FullName"] = filename + "." + ext;
	fileParts["name"] = filename;
	fileParts["ext"] = ext;
	return fileParts;
}

function checkFileSpecs()
{
	var fullPath = document.getElementById("file").value;
	var fileParts = getFilePartsFromFilePath(fullPath);
	var pattern = /^[a-zA-Z]{3}_[0-9]{8}_[a-zA-Z]{1}[0-9]{2}_[0-9]{4}_[0-9]{2}_[a-zA-Z]{3}$/;
	//alert(fileParts["ext"] == "csv");
	//alert(pattern.test(fileParts["name"]));
	//if(pattern.test(fileParts["name"]) && ((fileParts["ext"] == "csv") || (fileParts["ext"] == "xls")))
	if(fileParts["ext"] == "csv")
	{
		return true;
	}
	else
	{
		alert("Input file is not according to the specifications.");
		document.getElementById("file").value = "";
		return false;
	}
}

function DisableFilesDropDown()
{
	//document.getElementById("selectedFileNameFromList").disabled = true;
}

function RefreshFilesDropDown()
{
	//document.getElementById("selectedFileNameFromList").value = "";
}

function extractSubmissionFromFileName(element)
{
	var fileName = element.value;
	var parts = fileName.split("_");
	//if(parts.length != 6)
	//alert(parts.length);
	if(parseInt(parts[2].substring(1), 10) != 1)
		document.getElementById("submissions").value=parseInt(parts[2].substring(1), 10);
	//document.getElementById("submissions").value=parseInt(parts[2].substring(1), 10) - 1;
}

function evidenceradio_onclick(element)
{
	var row = element.parentNode.parentNode;

	if(element.checked == true)
	{
		row.style.backgroundColor = 'orange';
	}
	else
	{
		row.style.backgroundColor = '';
	}
}

</script>


</head>

<body onload = "setContracts();">
<div class="banner">
	<div class="Title"><?php if($destination=='funding_prediction') echo "Funding Predictor";  if($destination=='edim_reports') echo "EDIM Reports"; if($destination=='retention_reports') echo "Retention Reports"; if($destination=='view_ilr_report') echo "ILR Report";  if($destination=='activity_report') echo "Activity Report";if($destination=='read_pfr') echo "Sunesis Records VS PFR File";if($destination=='work_routes') echo "Work Routes Report";?></div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';"> Close </button>
		<?php
		if(true)
		{
			if($destination == "read_pfr")
			{
				?>
				<button onclick="saveWithCSV();"> Go </button>
				<?php
			}
			elseif($destination == 'work_routes')
			{
				?>
				<button onclick="showWorkRoutesReport();"> Go </button>
				<?php
			}
            if($destination == "learner_import")
            {
                ?>
                <button onclick="saveImportLearners();"> Go </button>
                <?php
            }
            if($destination == "bulk_update")
            {
                ?>
                <button onclick="saveBulkUpdate();"> Go </button>
                <?php
            }
			else
			{
				?>
				<button onclick="save();"> Go </button>
				<?php
			}
		}
		?>
	</div>
	<div class="ActionIconBar">
		<?php if($destination!='read_pfr'){?>
		<button onclick="showHideBlock('div_filters');showHideBlock('div_filter_crumbs');" title="Show/hide filters"><img src="/images/btn-filter.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<?php } ?>
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<?php //echo $view->getFilterCrumbs() ?>


<div name="div_filters" id="div_filters" style="display:none">

	<form name="pre_filters" id="pre_filters" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<input type="hidden" name="_action" id="_action" value="funding_prediction" />
		<input type="hidden" name="contract" value="" />
		<input type="hidden" name="assessor" value="" />
		<input type="hidden" name="employer" value="" />
		<input type="hidden" name="course" value="" />
		<input type="hidden" name="provider" value="" />
		<input type="hidden" name="filter_valid" value="" />
		<input type="hidden" name="filter_active" value="" />
		<input type="hidden" name="filter_lsf" value="" />
		<input type="hidden" name="filter_zprog" value="" />
		<input type="hidden" name="filter_emp_b_code" value="" />
		<table>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td>Assessor:</td>
				<td><?php echo $view->getFilterHTML('assessor'); ?></td>
			</tr>
			<tr>
				<td>Employer:</td>
				<td><?php echo $view->getFilterHTML('employer'); ?></td>
			</tr>
			<?php if($destination == "funding_prediction" && (DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo")) {?>
			<tr>
				<td>Business Code:</td>
				<td><?php echo $view->getFilterHTML('filter_emp_b_code'); ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td>Provider:</td>
				<td><?php echo $view->getFilterHTML('provider'); ?></td>
			</tr>
			<tr>
				<td>Course:</td>
				<td><?php echo $view->getFilterHTML('course'); ?></td>
			</tr>
			<tr>
				<td>Valid:</td>
				<td><?php echo $view->getFilterHTML('filter_valid'); ?></td>
			</tr>
			<tr>
				<td>Active:</td>
				<td><?php echo $view->getFilterHTML('filter_active'); ?></td>
			</tr>
			<tr>
				<td>Learner Support Fund:</td>
				<td><?php echo $view->getFilterHTML('filter_lsf'); ?></td>
			</tr>
			<tr>
				<td>ILRs with ZPROG:</td>
				<td><?php echo $view->getFilterHTML('filter_zprog'); ?></td>
			</tr>
		</table>
		<!--  <input type="submit" value="Go"/>&nbsp;<input type="button" onclick="resetViewFilters(document.forms[0]);" value="Reset" /> -->
</div>
<?php if($destination == "funding_prediction") { ?>
<table>
	<tr>
		<td style="background: #FFE1E1">
			<img src="/images/notice_icon_red.gif" alt="" style="float: left; padding-right: 5px" />
			Please note, whilst we make every effort to ensure funding calculations are as accurate as possible, sometimes figures may not be 100% accurate. Therefore please do not use these figures as a sole source for your budgets etc.
		</td>
	</tr>
</table>
	<?php } ?>
<table style="margin-top:10px">
	<img id="loading"  style="display:none;" src="images/loading.gif" alt="Loading" />
	<?php
	if($destination == "read_pfr" or $destination == "learner_import" or $destination == "bulk_update")
	{?>
		<div id="upload"></div>

		<tr>
			<td><label for="file"><strong>Filename:</strong></label></td>
			<td><input type="file" name="file" id="file" accept="text/csv" onchange="checkFileSpecs();"  /></td>
		</tr>
		<!--
		<tr>
			<td><strong>Previously uploaded files:</strong></td>
			<td>
				<select id="selectedFileNameFromList" name="selectedFileNameFromList" onchange="extractSubmissionFromFileName(this);">
					<option value=''></option>
					<?php
			$directory = DATA_ROOT . '/uploads/' . DB_NAME . '/pfrReconciler';
			if (file_exists($directory))
			{
				$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
				while($it->valid())
				{
					if (!$it->isDot())
					{
						echo "<option value='{$it->getSubPathName()}'>{$it->getSubPathName()}</option>";
						//echo '<br>' . $it->key() . "\n\n";
					}
					$it->next();
				}
			}
			?>
				</select>
			</td>
		</tr>
-->
		<?php

	}
    if($destination == "read_pfr" or $destination == "view_ilr_report" or $destination == "funding_prediction" or $destination == "edim_reports")
    {
    ?>

	<tr>
		<td class="fieldLabel_compulsory">Select submission:</td>
		<td><?php echo HTML::select('submissions', $submissions, $submission, true, true);?></td>

	</tr>
	<tr>
		<td class="fieldLabel_compulsory">Select year to view relevant contracts:</td>
		<td><?php echo HTML::select('contract_year', $contract_years, null, false, true); ?></td>
	</tr>
	<?php if($destination == 'read_pfr') {?>
	<tr>
		<td class="fieldLabel_optional">Export Only:</td>
		<td class="optional"><?php echo HTML::checkbox('export_only', 1, false, true, false); ?></td>
	</tr>
	<?php }} ?>
</table>


<div id="data" align="center" style="margin-top:50px;">
	<?php if($destination=="learner_import") 
			echo $view->render2($link);
			elseif($destination=="bulk_update")
			{
				if($stage==2)
					echo $view->renderBulkUpdate($link);
				else
					echo "";

				echo '<br>';
				
				echo $view->renderBulkUpdateAudit($link);
			}
			else
			echo $view->render($link); ?>
</div>
</form>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>