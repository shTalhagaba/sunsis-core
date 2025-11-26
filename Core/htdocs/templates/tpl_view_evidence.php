<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Qualification</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script language="JavaScript">
<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
<?php } ?>
</script>
	
<script language="JavaScript">
function edit(qualification_id)
{
	var myForm = document.forms[0];
	var buttons = myForm.elements['evidenceradio'];
	var selected = 0;
	for(var i = 0; i<buttons.length; i++)
	{
		if(buttons[i].checked)
		{
			selected = 1;	
			evidence_id =  buttons[i].value;
		}
	}

	if(selected == 0)
	{
		alert("Please select an evidence ");
	}
	else
	{
		window.location.replace('do.php?_action=addEvidenceToTemplate&qualification_id=<?php echo rawurlencode($qualification_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle);?>&framework_id=<?php echo rawurlencode($framework_id);?>&evidence_id=' + evidence_id +'&tr_id=<?php echo rawurlencode($tr_id);?>');
	}
}	
	
function delete_evidence()
{
	var myForm = document.forms[0];
	var buttons = myForm.elements['evidenceradio'];
	var selected = 0;
	for(var i = 0; i<buttons.length; i++)
	{
		if(buttons[i].checked)
		{
			selected = 1;	
			evidence_id =  buttons[i].value;
		}
	}

	if(selected == 0)
	{
		alert("Please select an evidence ");
	}
	else
	{
		window.location.replace('do.php?_action=delete_evidence&qualification_id=<?php echo rawurlencode($qualification_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle);?>&framework_id=<?php echo rawurlencode($framework_id);?>&evidence_id=' + evidence_id +'&tr_id=<?php echo rawurlencode($tr_id);?>');
	}
}	

</script>

<style type="text/css">
	div.evidence
	{
		margin: 3px 10px 3px 20px;
		padding: 1px 1px 10px 1px;
		background-color: white;
	}

</style>


</head>
<body>
<div class="banner">
	<div class="Title">Evidence Database</div>
	<div class="ButtonBar">
		<?php if($group_id!=''){ ?>
			<button onclick="self.close();">Close</button>
			<!-- <button onclick="opener.location='do.php?_action=edit_matrix&qualification_id=<?php //echo $qualification_id; ?>&internaltitle=<?php //echo $internaltitle;?>&framework_id=<?php //echo rawurlencode($framework_id);?>&group_id=<?php //echo rawurlencode($group_id);?>';"> Close </button> -->
		<?php } else { ?>
			<button onclick="self.close();">Close</button>
			<!-- <button onclick="window.location.replace('do.php?_action=edit_tr_matrix&qualification_id=<?php //echo rawurlencode($qualification_id); ?>&internaltitle=<?php //echo rawurlencode($internaltitle);?>&framework_id=<?php //echo rawurlencode($framework_id);?>&tr_id=<?php //echo rawurlencode($tr_id);?>&target=<?php //echo rawurlencode($target);?>&achieved=<?php //echo rawurlencode($achieved);?>');"> Close </button> -->
		<?php } ?>				 
		<button onclick="edit();"> Edit </button>
		<button onclick="delete_evidence();">Delete</button>
		<button onclick="window.location.replace('do.php?_action=addEvidenceToTemplate&qualification_id=<?php echo rawurlencode($qualification_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle);?>&framework_id=<?php echo rawurlencode($framework_id);?>&tr_id=<?php echo rawurlencode($tr_id);?>&target=<?php echo rawurlencode($target);?>&achieved=<?php echo rawurlencode($achieved);?>&group_id=<?php echo rawurlencode($group_id);?>');"> Add New </button>
		<button onclick="window.location.replace('do.php?_action=import_evidences&qualification_id=<?php echo rawurlencode($qualification_id); ?>&internaltitle=<?php echo rawurlencode($internaltitle);?>&framework_id=<?php echo rawurlencode($framework_id);?>&tr_id=<?php echo rawurlencode($tr_id);?>&target=<?php echo rawurlencode($target);?>&achieved=<?php echo rawurlencode($achieved);?>&group_id=<?php echo rawurlencode($group_id);?>');"> Import </button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<h3> Learner: <?php echo $learner?>  <br> Qualification: <?php echo $internaltitle; ?> <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<br>
<?php echo $evidence_view->render($link, $evidence_title); ?> 

<input type="hidden" id="qualification_id" value="<?php rawurlencode($qualification_id); ?>" />

<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</form>
</body>
</html>