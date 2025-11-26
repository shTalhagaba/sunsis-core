<?php
$groups_select = DAO::getResultset($link, "SELECT id, title, null FROM groups WHERE courses_id = '{$course->id}' ORDER BY title");
$contracts_select = DAO::getResultset($link, "SELECT id, title, NULL FROM contracts WHERE contract_year = YEAR(CURDATE()) OR contract_year = YEAR(CURDATE())-1 OR contract_year = YEAR(CURDATE())+1 ORDER BY contract_year DESC, title;");
?>
<div class="row">
	<div class="col-sm-2"></div>
	<div class="col-sm-8 well well-sm">
		<span class="pull-left btn btn-xs btn-success" onclick="enrolLearners();"><i class="fa fa-save"></i> Save Enrollment</span>
	</div>
	<div class="col-sm-2"></div>
</div>
<p></p>

<div class="col-sm-12">
	<span class="lead text-bold">Enrol Learners</span>
</div>

<div class="col-sm-12">
	<div class="box box-info box-solid collapsed-box">
		<div class="box-header with-border small">
			<span><i class="fa fa-search"></i> Filters</span>
			<div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button></div>
		</div>
		<div class="box-body">
			<form name="filters" method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="applyFilter">
				<input type="hidden" name="_action" value="read_course_v2" />
				<input type="hidden" name="subview" value="enrol_learners" />
				<input type="hidden" name="id" value="<?php echo $course->id; ?>" />
				<div class="row">
					<div class="col-sm-3">Learner Ref (l03)</div>
					<div class="col-sm-3">Learner Surname</div>
					<div class="col-sm-3">Employer</div>
					<div class="col-sm-3">Status</div>
					<div class="col-sm-3"></div>
				</div>
				<div class="row">
					<div class="col-sm-3"><?php echo $viewSubview->getFilterHTML('filter_learner_l03'); ?></div>
					<div class="col-sm-3"><?php echo $viewSubview->getFilterHTML('filter_surname'); ?></div>
					<div class="col-sm-3"><?php echo $viewSubview->getFilterHTML('filter_organisation'); ?></div>
					<div class="col-sm-3"><?php echo $viewSubview->getFilterHTML('filter_learners_type'); ?></div>
					<div class="col-sm-3"></div>
				</div>
				<div class="row">
					<div class="col-sm-12"><hr style="margin: 2px;"></div>
					<div class="col-sm-6"><button class="btn btn-xs btn-block btn-info" type="submit"><i class="fa fa-check"></i> Apply</button></div>
					<div class="col-sm-6"><button class="btn btn-xs btn-block btn-default" type="button" onclick="resetFilters();"><i class="fa fa-refresh"></i> Reset</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="col-sm-12">
	<div class="box-body with-header">
		<form class="form-horizontal" name="frmEnrolLearners" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="_action" value="save_course_group" />
			<input type="hidden" name="course_id" value="<?php echo $course->id; ?>" />

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="group_id" class="col-sm-4 control-label fieldLabel_optional">Select Cohort:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('group_id', $groups_select, '', true); ?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="contract_id" class="col-sm-4 control-label fieldLabel_compulsory">Select Contract:</label>
						<div class="col-sm-8">
							<?php echo HTML::selectChosen('contract_id', $contracts_select, '', true, true); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="input_start_date" class="col-sm-4 control-label fieldLabel_compulsory">Start Date:</label>
						<div class="col-sm-8">
							<?php echo HTML::datebox('start_date', '', true); ?>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="input_target_date" class="col-sm-4 control-label fieldLabel_compulsory">Planned End Date:</label>
						<div class="col-sm-8">
							<?php echo HTML::datebox('target_date', '', true); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="title" class="col-sm-12 pull-left fieldLabel_compulsory">Select Learners:</label>
						<div class="col-sm-12">
							<?php echo $viewSubview->render($link); ?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">

	function learnersToEnrol_onclick(element)
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

	function enrolLearners()
	{
		var myForm = document.forms['frmEnrolLearners'];
		if(myForm.elements['contract_id'].value == '')
		{
			return alert('Please select the contract.');
		}
		if(myForm.elements['start_date'].value == '')
		{
			return alert('Please enter start date');
		}
		if(myForm.elements['target_date'].value == '')
		{
			return alert('Please enter planned end date');
		}
		if($('input[type=checkbox][class=chkEnrolLearnersSelection]:checked').length == 0)
		{
			return alert('Please select at least one learner.');
		}

		var usernames = myForm.elements['learnersToEnrol'];
		for(var i = 0; i < usernames.length; i++)
		{
			if(usernames[i].checked)
			{
				$.ajax({
					url:'do.php?_action=save_start_training',
					type:'post',
					data:{
						username: usernames[i].value,
						course_id: myForm.elements['course_id'].value,
						group_id: myForm.elements['group_id'].value,
						start_date: myForm.elements['start_date'].value,
						end_date: myForm.elements['target_date'].value,
						contract_id: myForm.elements['contract_id'].value
					}
				}).done(function (response, textStatus) {

					}).fail(function (jqXHR, textStatus, errorThrown) {
						alert(textStatus + ': ' + errorThrown);
					});
			}
		}
		window.location.replace('<?php echo $_SESSION['bc']->getPrevious(); ?>');
	}

</script>