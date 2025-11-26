<?php /* @var $course Course */ ?>
<?php /* @var $framework Framework */ ?>
<?php /* @var $provider Organisation */ ?>
<?php /* @var $provider_main_location Location */ ?>

<!DOCTYPE html>
<head xmlns="http://www.w3.org/1999/html">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Edit Course Tracking Template</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
	<link href="/assets/adminlte/plugins/toastr/toastr.min.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>

	</style>

</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Edit Course Tracking Template</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
			</div>
			<div class="ActionIconBar">

			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<?php $_SESSION['bc']->render($link); ?>
	</div>
</div>

<br>

<div class="container-fluid">

	<div class="row">
		<div class="col-sm-12">
			<table class="table table-bordered">
				<tr>
					<td>
						<span class="text-bold">Course Title:</span><br>
						<?php echo $course->title; ?>
						<span class="pull-right">
							<?php
							echo $course->active == '1' ? '<label class="label label-success">Active</label>' : '<label class="label label-danger">Not Active</label>';
							?>
						</span>
					</td>
					<td>
						<span class="text-bold">Provider:</span><br>
						<?php
						echo $provider->legal_name . ' &nbsp; ';
						echo $provider_main_location->address_line_1 != '' ? $provider_main_location->address_line_1 . ', ' : '';
						echo $provider_main_location->address_line_4 != '' ? $provider_main_location->address_line_4 . ', ' : '';
						echo $provider_main_location->postcode != '' ? '<i class="fa fa-map-marker"></i> ' . $provider_main_location->postcode . '<br>' : '';
						?>
					</td>
					<td><span class="text-bold">Duration:</span><br><?php echo Date::toShort($course->course_start_date) . ' - ' . Date::toShort($course->course_end_date); ?></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<div class="well well-sm well-white" style="padding-bottom: 1px;">
				<span class="btn btn-xs btn-success" onclick="window.location.replace('do.php?_action=edit_tracking_template&id=<?php echo $course->id; ?>');"><i class="fa fa-edit"></i> Save Template</span> &nbsp;
				<p></p>
			</div>
		</div>
		<div class="col-sm-2"></div>
	</div>

	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<h5 class="text-bold lead">Course Tracking Template</h5>
		</div>
		<div class="col-sm-2"></div>
	</div>
	<div class="row">
		<div class="col-sm-2"></div>
		<div class="col-sm-8">
			<div class="box box-solid">
				<div class="box-header with-border">
					<span class="box-title">Add Section</span>
				</div>
				<div class="box-body">
					<div class="alert alert-info">
						Enter the section title e.g. Knowledge, Skills, Behaviours etc. And press Save Section.
					</div>
					<form class="form-horizontal" name="frmAddSection" role="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
						<input type="hidden" name="_action" value="save_tracking_template" />
						<input type="hidden" name="subaction" value="add_section" />
						<input type="hidden" name="course_id" value="<?php echo $course->id; ?>" />
						<div class="row">
							<div class="col-sm-8">
								<div class="form-group">
									<label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Section Title:</label>
									<div class="col-sm-8">
										<input type="text" class="form-control compulsory" name="new_section_title" id="new_section_title" value="" maxlength="250" />
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<button type="button" class="btn btn-success btn-sm" id="btnAddSection">
									<i class="fa fa-save"></i> Add Section
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-2"></div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<?php
					$template_sections = DAO::getResultset($link, "SELECT id, title FROM tracking_template WHERE course_id = '{$course->id}' AND section_id IS NULL AND element_id IS NULL", DAO::FETCH_ASSOC);
					$first_section = true;
					foreach($template_sections AS $section)
					{
						echo $first_section ?
							'<li class="active"><a href="#tab_'.$section['id'].'" data-toggle="tab">'.$section['title'].'</a></li>' :
							'<li><a href="#tab_'.$section['id'].'" data-toggle="tab">'.$section['title'].'</a></li>';
						$first_section = false;
					}
					?>
				</ul>
				<div class="tab-content">
					<?php
					$first_section = true;
					foreach($template_sections AS $section)
					{
						echo $first_section ?
							'<div class="tab-pane active" id="tab_'.$section['id'].'">'.$this->editTrackingTemplateTab($link, $course->id, $section['id']).'</div>' :
							'<div class="tab-pane" id="tab_'.$section['id'].'">'.$this->editTrackingTemplateTab($link, $course->id, $section['id']).'</div>';

						$first_section = false;
					}
					?>
				</div>
			</div>
		</div>
	</div>


</div> <!--container-fluid-->

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>


<script type="text/javascript">

	$(function() {


	});

	$('#btnAddSection').on('click', function(){
		var myForm = document.forms['frmAddSection'];
		if(!validateForm(myForm))
		{
			return false;
		}

		myForm.submit();
	});
	$('#btnAddElement').on('click', function(){
		var myForm = document.forms['frmAddElement'];
		if(!validateForm(myForm))
		{
			return false;
		}

		myForm.submit();
	});

</script>

</body>
</html>
