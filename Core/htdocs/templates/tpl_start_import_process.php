<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sunesis Import Process</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">

		function fn_on_page_load()
		{

			$('#import_validation').attr('disabled',<?php echo $validate_button;?>);
			$('#import_employers').attr('disabled',<?php echo $imp_employers;?>);
			$('#import_frameworks').attr('disabled',<?php echo $imp_frameworks;?>);
			$('#import_courses').attr('disabled',<?php echo $imp_courses;?>);
			$('#import_framework_quals').attr('disabled',<?php echo $imp_framework_quals;?>);
			$('#import_course_quals').attr('disabled',<?php echo $imp_course_quals;?>);
			$('#import_learners').attr('disabled',<?php echo $imp_learners;?>);
			$('#import_training_records').attr('disabled',<?php echo $imp_training_records;?>);
			$('#import_student_quals').attr('disabled',<?php echo $imp_student_quals;?>);
			$('#import_ilrs').attr('disabled',<?php echo $imp_ilrs;?>);

		}

		function fn_validate()
		{
			var request = ajaxBuildRequestObject();
			if(request != null)
			{
				var postData = 'entity=validation';
				request.open("POST", expandURI('do.php?_action=start_import_process&time=<?php echo $time; ?>'), false);
				request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				request.setRequestHeader("x-ajax", "1"); // marker for server code
				request.send(postData);
				if(request.status == 200)
				{
					$("#div_result").show();
					$("#div_result").html(request.responseText);
				}
				else
				{
					alert(request.responseText);
				}
			}
			else
			{
				alert("Could not create XMLHttpRequest object");
			}
		}

		function fn_import_validation()
		{
			$( "#div_result" ).empty();

			fn_validate();

			var request = ajaxBuildRequestObject();
			if(request != null)
			{
				var postData = 'entity=special';
				request.open("POST", expandURI('do.php?_action=start_import_process&time=<?php echo $time; ?>'), false);
				request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				request.setRequestHeader("x-ajax", "1"); // marker for server code
				request.send(postData);
				if(request.status == 200)
				{   //alert(request.responseText);
					if(parseInt(request.responseText) > 0)
						$('#import_employers').attr('disabled',true);
					else
					{
						$('#import_validation').attr('disabled',true);
						$('#import_employers').attr('disabled',false);
					}
				}
				else
				{
					alert(request.responseText);
				}
			}
			else
			{
				alert("Could not create XMLHttpRequest object");
			}
		}

		function fn_import(entity)
		{
			var button_to_hide = "";
			var button_to_show = "";
			switch (entity)
			{
				case 'employers':
					button_to_hide = '#import_employers';
					button_to_show = '#import_frameworks';
					break;
				case 'frameworks':
					button_to_hide = '#import_frameworks';
					button_to_show = '#import_courses';
					break;
				case 'courses':
					button_to_hide = '#import_courses';
					button_to_show = '#import_framework_quals';
					break;
				case 'framework_quals':
					button_to_hide = '#import_framework_quals';
					button_to_show = '#import_course_quals';
					break;
				case 'course_quals':
					button_to_hide = '#import_course_quals';
					button_to_show = '#import_learners';
					break;
				case 'learners':
					button_to_hide = '#import_learners';
					button_to_show = '#import_training_records';
					break;
				case 'training_records':
					button_to_hide = '#import_training_records';
					button_to_show = '#import_student_quals';
					break;
				case 'student_quals':
					button_to_hide = '#import_student_quals';
					button_to_show = '#import_ilrs';
					break;
				case 'ilrs':
					button_to_hide = '#import_ilrs';
					break;
			}
			$("#div_result").hide();
			var temp = document.getElementById(button_to_hide.substring(1));

			$('#div_'+button_to_hide.substring(1)).css("pointer-events", "none");
			$('#div_'+button_to_hide.substring(1)).html('<img src="img/loader.gif" /> ');

			var request = ajaxBuildRequestObject();
			if(request == null)
			{
				alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
			}
			var url = expandURI('do.php?_action=start_import_process&entity='+ entity + '&time=<?php echo $time; ?>');
			request.open("GET", url, true); // (method, uri, synchronous)
			request.onreadystatechange = function(e)
			{
				if(request.readyState == 4)
				{
					if(request.status == 200)
					{
						$("#div_result").show();
						$("#div_result").html(request.responseText);
						$('#div_'+button_to_hide.substring(1)).css("pointer-events", "none");
						$('#div_'+button_to_hide.substring(1)).html(temp);
						$(button_to_hide).attr('disabled',true);
						$(button_to_show).attr('disabled',false);
					}
					else
					{
						ajaxErrorHandler(request);
					}
				}
			}
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(null); // post data
		}

	</script>

</head>
<body onload="fn_on_page_load();">
<div class="banner">
	<div class="Title">Sunesis Data Import Process</div>
	<div class="ButtonBar">
		<?php /*if($_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER){*/?><!--<button onclick="save();">Save</button>--><?php //}?>

	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div>
	<h3 class="introduction">Import</h3>
	<div class="Newspaper">
		<p class="introduction">This tool allows you to import data from CSV file upload.</p>
	</div>
</div>

<iframe id="loadarea" style="float: left;display: none;"></iframe><br />


<table border="0" style="float: left; border-radius: 5px;" cellpadding="6" cellspacing="6">
	<thead><th class="topRow">Actions</th> </thead>
	<tr><td><div id = "div_import_validation"><input type="button" id="import_validation" onclick="fn_import_validation();" value="Validate" /></div></td></tr>
	<tr><td><div id = "div_import_employers"><input type = "button" id="import_employers" onclick="fn_import('employers');" value="Import Employers" /></div></td></tr>
	<tr><td><div id = "div_import_frameworks"><input type = "button" id="import_frameworks" onclick="fn_import('frameworks');" value="Import Frameworks" /></div></div></td></tr>
	<tr><td><div id = "div_import_courses"><input type = "button" id="import_courses" onclick="fn_import('courses');" value="Import Courses" /></div></td></tr>
	<tr><td><div id = "div_import_framework_quals"><input type = "button" id="import_framework_quals" onclick="fn_import('framework_quals');" value="Import Framework Qualifications" /></div></td></tr>
	<tr><td><div id = "div_import_course_quals"><input type = "button" id="import_course_quals" onclick="fn_import('course_quals');" value="Import Course Qualifications" /></div></td></tr>
	<tr><td><div id = "div_import_learners"><input type = "button" id="import_learners" onclick="fn_import('learners');" value="Import Learners" /></div></td></tr>
	<tr><td><div id = "div_import_training_records"><input type = "button" id="import_training_records" onclick="fn_import('training_records');" value="Import Training Records" /></div></td></tr>
	<tr><td><div id = "div_import_student_quals"><input type = "button" id="import_student_quals" onclick="fn_import('student_quals');" value="Import Student Qualifications" /></div></td></tr>
	<tr><td><div id = "div_import_ilrs"><input type = "button" id="import_ilrs" onclick="fn_import('ilrs');" value="Import ILRs" /></div></td></tr>
</table>

<div id="div_result" style="float: right; width:1200px;height:400px;overflow:auto; margin:0 auto;"></div>

</body>
</html>