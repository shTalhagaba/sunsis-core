
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis - Skills Forward Integration</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/iCheck/all.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		#home_postcode{text-transform:uppercase}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Sunesis - Skills Forward Integration</div>
			<div class="ButtonBar"></div>
			<div class="ActionIconBar"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<p></p>

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-4">
			<div class="box box-primary collapsed-box">
				<div class="box-header with-border">
					<span class="box-title">Get details of users</span>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body small">
					<form name="frmGetUserDetails" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
						<input type="hidden" name="_action" value="ajax_forskills">
						<input type="hidden" name="subaction" value="getUserDetails">
						<div class="form-group">
							<label for="username">Username:</label>
							<input type="text" class="form-control" name="username" placeholder="Enter forskills username">
						</div>
						<div class="form-group">
							<label for="studentRef">Student Ref:</label>
							<input type="text" class="form-control" name="studentRef" placeholder="Enter forskills student ref">
						</div>
						<div class="form-group">
							<label for="studentRef">Email:</label>
							<input type="text" class="form-control" name="email" placeholder="Enter forskills email">
						</div>
						<div class="form-group">
							<label for="studentRef">NI Number:</label>
							<input type="text" class="form-control" name="ninumber" placeholder="Enter ni number">
						</div>
						<div class="form-group">
							<span id="btnGetUserDetails" class="btn btn-sm btn-primary" onclick="getUserDetails(this.id);">Get Results</span>
						</div>
					</form>
				</div>
			</div>

			<div class="box box-primary collapsed-box">
				<div class="box-header with-border">
					<span class="box-title">Get list of user assessments</span>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body small">
					<form name="frmGetUserAssessments" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
						<input type="hidden" name="_action" value="ajax_forskills">
						<input type="hidden" name="subaction" value="getUserAssessments">
						<div class="form-group">
							<label for="username">Username:</label>
							<input type="text" class="form-control" name="username" placeholder="Enter forskills username">
						</div>
						<div class="form-group">
							<label for="studentRef">Student Ref:</label>
							<input type="text" class="form-control" name="studentRef" placeholder="Enter forskills student ref">
						</div>
						<div class="form-group">
							<label for="studentRef">Email:</label>
							<input type="text" class="form-control" name="email" placeholder="Enter forskills email">
						</div>
						<div class="form-group">
							<label for="studentRef">NI Number:</label>
							<input type="text" class="form-control" name="ninumber" placeholder="Enter ni number">
						</div>
						<div class="form-group">
							<span id="btnGetUserAssessments" class="btn btn-sm btn-primary" onclick="getUserAssessments(this.id);">Get Results</span>
						</div>
					</form>
				</div>
			</div>

			<div class="box box-primary collapsed-box">
				<div class="box-header with-border">
					<span class="box-title">Get all results for a user by their linked Inst. ID</span>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div class="box-body small">
					<form name="frmGetAllResultsForUser" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
						<input type="hidden" name="_action" value="ajax_forskills">
						<input type="hidden" name="subaction" value="getAllResultsForUser">
						<div class="form-group">
							<label for="idUserInstitution">idUserInstitution:</label>
							<input type="text" class="form-control" name="idUserInstitution" placeholder="Enter id of the user linked to your institution within ForSkills">
						</div>
						<div class="form-group">
							<span id="btnGetAllResultsForUser" class="btn btn-sm btn-primary" onclick="getAllResultsForUser(this.id);">Get Results</span>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-8">

			<div class="box box-primary">
				<div class="box-header with-border">
					<span class="box-title">Result</span>
				</div>
				<div class="box-body small">
					<div class="table-responsive" id="divResult">
						<i>Use left side panel to bring results</i>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/iCheck/icheck.min.js"></script>

<script language="JavaScript">
	$(function(){
		$('#input_Birthdate').attr('class', 'datepicker optional form-control');
	});

	function getUserDetails(btn_id)
	{
		$('#'+btn_id).prop('disabled', true);

		$('#divResult').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');

		var myForm = document.forms["frmGetUserDetails"];

		var getUserDetailsCallback = function(req, error){
			if(!error)
			{
				$('#divResult').html(req.responseText);
			}
			else
			{
				alert('Operation failed, please raise a support request with the details of your action.');
			}
			$('#'+btn_id).prop('disabled', false);
		};

		var client = ajaxPostForm(myForm, getUserDetailsCallback);
	}

	function getUserAssessments(btn_id)
	{
		$('#'+btn_id).prop('disabled', true);

		$('#divResult').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');

		var myForm = document.forms["frmGetUserAssessments"];

		var getUserAssessmentsCallback = function(req, error){
			if(!error)
			{
				$('#divResult').html(req.responseText);
			}
			else
			{
				alert('Operation failed, please raise a support request with the details of your action.');
			}
			$('#'+btn_id).prop('disabled', false);
		};

		var client = ajaxPostForm(myForm, getUserAssessmentsCallback);
	}

	function getAllResultsForUser(btn_id)
	{
		$('#'+btn_id).prop('disabled', true);

		$('#divResult').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i> <img id="loading" src="images/loading.gif" alt="Loading" /></div>');

		var myForm = document.forms["frmGetAllResultsForUser"];

		var getAllResultsForUserCallback = function(req, error){
			if(!error)
			{
				$('#divResult').html(req.responseText);
			}
			else
			{
				alert('Operation failed, please raise a support request with the details of your action.');
			}
			$('#'+btn_id).prop('disabled', false);
		};

		var client = ajaxPostForm(myForm, getAllResultsForUserCallback);
	}

	function getAssessmentByUsername(username)
	{
		var myForm = document.forms["frmGetUserAssessments"];
		myForm.elements['username'].value = username;
		getUserAssessments('btnGetUserAssessments');
	}




</script>

</body>
</html>