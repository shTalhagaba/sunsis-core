<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Add Learners to Register</title>
	<link rel="stylesheet" href="/styles/common.css" type="text/css"/>
	<link rel="stylesheet" href="/styles/dynamicStyles.php" type="text/css"/>
	<link rel="stylesheet" href="/styles/jquery-ui/jquery-ui-1.8.11.custom.css" type="text/css"/>

	<script language="JavaScript" src="/scripts/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/scripts/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/scripts/json.js"></script>
	<script language="JavaScript" src="/scripts/common.js"></script>

	<script language="JavaScript">
		var attendanceModuleId = <?php echo $attendance_module_id ?>;
		var lessonId = <?php echo $lesson_id ?>;
	</script>

	<script language="JavaScript" src="/js/add_new_lesson_learner.js&n=<?php echo time(); ?>"></script>

	<style type="text/css">
		div.RightMenu
		{
			margin-top: 108px;
		}

		#divLeft, #divRight
		{
			width:275px;
			height:400px;
			border-width:1px;
			border-color:#668FEB;
			border-style:solid;
			margin-right: 10px;
			overflow:scroll;
			background-position: center;
			background-repeat: no-repeat;
		}

		#filter_school
		{
			width: 275px;
		}

		select.filter
		{
			width: 260px;
		}

		td.columnHeading
		{
			font-weight:bold;
		}

		div.learner
		{
			height: 60px;
			padding: 2px;
			cursor: pointer;
			border-bottom: #DDDDDD 1px solid;
		}

		div.enrolledLearner
		{
			height: 60px;
			padding: 2px;
			background-color: orange;
			cursor: default;
			border-bottom: #ffd07a 1px solid;
		}

		div.learner:hover
		{
			background-color: #FDF1E2;
		}

		div.learnerDetails
		{
			margin-left:5px;
			font-size: 80%;
			color: #333333;
		}
	</style>



</head>




<body onload="body_onload()">

<h3 id="sectionLearners">3. Select new learners</h3>
<p class="sectionDescription">Click on learners in the list on the left to
	add them to the list on the right.
</p>
<div style="margin-left:10px;">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<input type="hidden" name="_action" value="add_new_lesson_learner" />
		<input type="hidden" name="attendance_module_id" value="<?php echo $attendance_module_id; ?>" />
		<input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>" />

		<table width="580" style="margin-left:10px;" >
			<col width="120"/>
			<tr>
				<td id="headingLeft" class="columnHeading" colspan="2">Training Provider</td>
				<td id="headingRight" class="columnHeading" colspan="2">Learners to add (0)</td>
			</tr>

			<tr>
				<td colspan="2"><?php echo HTML::select("filter_provider", $providers_dropdown, null, false); ?></td>
				<td>Sort by:</td>
				<td><?php echo HTML::select("filter_sort", $sort_dropdown, 0, false); ?></td>
			</tr>

			<tr>
				<td></td>
				<td>Surname: <input type="text" size="9" name="surname"/></td>
				<td colspan="2"><?php echo HTML::button("Enrol", "addLearners();"); ?>
					&nbsp;<?php echo HTML::button("Clear", "removeAllLearners();"); ?>
					&nbsp;<?php echo HTML::button("Show/Hide existing", "showHideExistingLearners();"); ?></td>
			</tr>
			<tr>
				<td colspan="2"><div id="divLeft" ></div></td>
				<td colspan="2"><div id="divRight" ></div></td>
			</tr>

		</table>
	</form>

</div>

<div id="dialogLearnerAlreadyEnrolled" title="Adding Learner to this Register" style="display:none">
	The learner you have selected has already been part of this register.
</div>

</body>
</html>