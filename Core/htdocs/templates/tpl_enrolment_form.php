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

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>


	<script language="JavaScript">

course_id = null;
group_id = null;

function edit(qualification_id)
{



	if(course_id == null || course_id=='')
	{
		alert("Please select a course");
	}
	else if(group_id==null || group_id=='')
	{
		alert("Please select a group")	
	}
	else
	{
			window.location.replace('do.php?_action=attach_course_to_training_record&course_id=' + evidence_id + '&group_id=' + group_id +'&tr_id=<?php echo rawurlencode($tr_id);?>');
	}
}	


function populateQualifications(id)
{
	// To find which course is selected
	myForm = document.forms[0];
	buttons = myForm.elements['evidenceradio'];
	
	if(buttons=="[object NodeList]")
	{
		selected = 0;
		for(var i = 0; i<buttons.length; i++)
		{
			if(buttons[i].checked)
			{
				selected = 1;	
				evidence_id =  buttons[i].value;
			}
		}
	}
	else
	{
		selected = 0;
		if(buttons.checked)
			selected = 1;
			evidence_id = buttons.value;				
	}


	course_id = evidence_id;	
	director = document.getElementById('director');
	var url = 'do.php?_action=ajax_load_group_dropdown&course_id=' + evidence_id;
	ajaxPopulateSelect(director, url);
}

function director_onchange()
{


	group = document.getElementById('director');
	group_id = group[group.selectedIndex].value;

		// Call ajax to get all the lessons
		var request = ajaxBuildRequestObject();
		request.open("GET", expandURI('do.php?_action=ajax_load_lessons&course_id=' + course_id + '&group_id=' + group_id), false);
		
		request.setRequestHeader("x-ajax", "1"); // marker for server code
		request.send(null);

		if(request.status == 200)
		{
			var lessons = request.responseText;
			lesson = document.getElementById("lessons");
			document.getElementById("placeholder").removeChild(document.getElementById("placeholder").firstChild); 

			newdiv = document.createElement("div");
			newdiv.setAttribute("id","lessons");
			newdiv.innerHTML = lessons;
			document.getElementById("placeholder").appendChild(newdiv);		
		}
		else
		{
			ajaxErrorHandler(request);
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
<div class="banner">
	<div class="Title">Courses</div>
	<div class="ButtonBar">
		<button onclick="window.location.replace('do.php?_action=read_training_record&id=<?php echo rawurlencode($tr_id);?>');">Close</button>
		<button onclick="edit();">Enrol</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<h3> Courses  <img id="globe1" src="/images/rotatingGlobe.gif" style="vertical-align:text-bottom;visibility:hidden;" /></h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="_action" value="save_course_structure" />
<br>
<?php echo $evidence_view->render($link); ?> 

<table style="margin-top: 20px;" >
	<tr>
		<td class="fieldLabel_optional">Learning/ Teaching groups:</td>
		<td><?php echo HTML::select('director', $director_select, $director, true); ?></td>
	</tr>
</table>

<div style="margin-top: 20px;" id='placeholder'>
</div>

<input type="hidden" id="tr_id" value="<?php rawurlencode($tr_id); ?>" />

<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</form>
</body>
</html>