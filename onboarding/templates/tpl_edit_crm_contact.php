<?php /* @var $vo OrganisationContact */ ?>
<?php /* @var $organisation Organisation */ ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $vo->contact_id == ''?'Create Organisation Contact':'Edit Organisation Contact'; ?></title>
	<link rel="stylesheet" href="css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/font-awesome/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


	<style>
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;"><?php echo $vo->contact_id == ''?'Create Organisation Contact':'Edit Organisation Contact'; ?></div>
			<div class="ButtonBar">
				<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-xs btn-default" onclick="save();"><i class="fa fa-save"></i> Save</span>
				<?php if($vo->contact_id != "" && $_SESSION['user']->isAdmin()) { ?>
				<span class="btn btn-xs btn-danger" onclick="deleteNote();"><i class="fa fa-remove"></i> Delete</span>
				<?php } ?>
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

<div class="row">

</div>

<div class="row">
<div class="col-sm-8">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h2 class="box-title"><?php echo $vo->contact_id == ''?'Add':'Edit'; ?> Details</h2>
		</div>
		<form autocomplete="off" class="form-horizontal" name="frmOrgCRMContact" id="frmOrgCRMContact" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="contact_id" value="<?php echo $vo->contact_id; ?>" />
			<input type="hidden" name="org_id" value="<?php echo $vo->org_id; ?>" />
			<input type="hidden" name="_action" value="save_crm_contact" />
			<div class="box-body with-border">
				<div class="form-group">
					<label for="contact_title" class="col-sm-4 control-label fieldLabel_optional">Title:</label>
					<div class="col-sm-8">
                        <?php echo HTML::selectChosen('contact_title', LookupHelper::getDDLTitles($link), $vo->contact_title, true, false); ?>
					</div>
				</div>
				<div class="form-group">
					<label for="contact_name" class="col-sm-4 control-label fieldLabel_compulsory">Full Name:</label>
					<div class="col-sm-8">
						<input class="form-control compulsory" type="text" name="contact_name" id="contact_name" value="<?php echo $vo->contact_name; ?>" />
					</div>
				</div>
<!--				<div class="form-group">-->
<!--					<label for="contact_department" class="col-sm-4 control-label fieldLabel_optional">Department:</label>-->
<!--					<div class="col-sm-8">-->
<!--                        --><?php //echo HTML::selectChosen('contact_department', $departments_ddl, $vo->contact_department, true); ?>
<!--                        <span class="btn btn-xs btn-info" id="btnNewDepartment" title="Add new department" onclick="$('#btnNewDepartment').hide();$('#divNewDepartment').show();">&nbsp;+&nbsp;</span>-->
<!--					</div>-->
<!--				</div>-->
<!--                <div class="form-group" id="divNewDepartment" style="display: none;">-->
<!--                    <label for="txtNewDepartment" class="col-sm-4 control-label fieldLabel_optional">Enter New Contact Type:</label>-->
<!--                    <div class="col-sm-8">-->
<!--                        <div class="callout">-->
<!--                            <input class="form-control optional" type="text" id="txtNewDepartment" name="txtNewDepartment" value="" size="50" maxlength="50" />-->
<!--                            <p class="small"> 50 characters max.</p>-->
<!--                            <span class="btn btn-xs btn-info" onclick="$('#btnNewDepartment').show();$('#divNewDepartment').hide();">&nbsp;Cancel&nbsp;</span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
				<div class="form-group">
					<label for="job_role" class="col-sm-4 control-label fieldLabel_optional">Contact Type:</label>
					<div class="col-sm-8">
						<?php echo HTML::selectChosen('job_role', $job_roles_ddl, $vo->job_role, true); ?>
					</div>
				</div>
				<div class="form-group">
					<label for="job_title" class="col-sm-4 control-label fieldLabel_optional">Job Title / Position:</label>
					<div class="col-sm-8">
						<input class="form-control optional" type="text" name="job_title" id="job_title" value="<?php echo $vo->job_title; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="contact_telephone" class="col-sm-4 control-label fieldLabel_optional">Telephone:</label>
					<div class="col-sm-8">
						<input class="form-control optional" type="text" name="contact_telephone" id="contact_telephone" value="<?php echo $vo->contact_telephone; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="contact_mobile" class="col-sm-4 control-label fieldLabel_optional">Mobile:</label>
					<div class="col-sm-8">
						<input class="form-control optional" type="text" name="contact_mobile" id="contact_mobile" value="<?php echo $vo->contact_mobile; ?>" />
					</div>
				</div>
				<div class="form-group">
					<label for="contact_email" class="col-sm-4 control-label fieldLabel_optional">Email:</label>
					<div class="col-sm-8">
						<input class="form-control optional" type="text" name="contact_email" id="contact_email" value="<?php echo $vo->contact_email; ?>" />
					</div>
				</div>
				<div class="callout">
					<div class="form-group">
						<label for="left_employer" class="col-sm-4 control-label fieldLabel_optional">Left Organisation:<br>(<small>No longer working for this organisation</small>):</label>
						<div class="col-sm-8">
                            <?php echo HTML::selectChosen('left_employer', $yes_no_options, $vo->left_employer, false); ?>
						</div>
					</div>
					<div class="form-group">
						<label for="left_employer_notes" class="col-sm-4 control-label fieldLabel_optional">Comments:<br>(<small>Comments if CRM contact has left this organisation</small>)</label>
						<div class="col-sm-8">
							<textarea name="left_employer_notes" id="left_employer_notes" rows="5" style="width: 100%;"><?php echo $vo->left_employer_notes; ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<span class="btn btn-sm btn-primary btn-block" onclick="save();"><i class="fa fa-save"></i> Save Information</span>
			</div>
		</form>
	</div>
</div>

<div class="col-sm-4">
	<div class="callout callout-default">
		<?php
		echo '<span class="lead text-bold">' . $organisation->legal_name . '</span><br>';
		echo $org_main_location->address_line_1 != '' ? $org_main_location->address_line_1 . '<br>' : '';
		echo $org_main_location->address_line_2 != '' ? $org_main_location->address_line_2 . '<br>' : '';
		echo $org_main_location->address_line_3 != '' ? $org_main_location->address_line_3 . '<br>' : '';
		echo $org_main_location->address_line_4 != '' ? $org_main_location->address_line_4 . '<br>' : '';
		echo $org_main_location->postcode != '' ? $org_main_location->postcode . '<br>' : '';
		$result = DAO::getResultset($link, "SELECT * FROM organisation_contacts WHERE org_id = '{$organisation->id}' AND contact_id != '{$vo->contact_id}'", DAO::FETCH_ASSOC);
		if(count($result) > 0)
		{
			echo '<ul class="products-list product-list-in-box">';
			foreach($result AS $row)
			{
				$text_color = $row['left_employer'] == 1 ? 'text-red' : 'text-green';
				echo '<li class="item ' . ($row['left_employer'] == 1 ? 'text-red' : 'text-green') . '">';
				echo '<div class="product-img"><i class="fa fa-user fa-3x"></i></div>';
				echo '<div class="product-info">';
				echo '<a href="do.php?_action=edit_crm_contact&contact_id='.$row['contact_id'].'&org_id='.$row['org_id'].'&push=0" class="product-title '.$text_color.'">' . $row['contact_title'] . ' ' . $row['contact_name'] . '</a>';
				echo '<span class="product-description small">';
				echo $row['contact_department'] != '' ? $row['contact_department'] . '<br>' : '';
				echo $row['contact_telephone'] != '' ? '<i class="fa fa-phone"></i> ' . $row['contact_telephone'] . ' | ' : '';
				echo $row['contact_mobile'] != '' ? '<i class="fa fa-mobile"></i> ' . $row['contact_mobile'] . ' | ' : '';
				echo $row['contact_email'] != '' ? '<i class="fa fa-envelope"></i> ' . $row['contact_email'] . ' | ' : '';
				echo '</span>';
				echo '</div>';
				echo '</li>';
			}
			echo '</ul>';
		}
		?>
	</div>
</div>
</div>
<br>


<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>

<script language="JavaScript">

	$(function() {

	});

	function save()
	{
		var myForm = document.forms['frmOrgCRMContact'];
		if(validateForm(myForm) == false)
		{
			return false;
		}
		if(myForm.contact_email.value != '' && !validateEmail(myForm.contact_email.value))
		{
			alert('Please enter valid email address.');
			myForm.contact_email.focus();
			return;
		}

		myForm.submit();
	}

	function deleteNote()
	{
		if(window.confirm("Do you really want to delete this Contact?"))
		{
			window.location.replace('do.php?_action=edit_crm_contact&subaction=delete_learner_crm_contact&contact_id=<?php echo $vo->contact_id; ?>&org_id=<?php echo $vo->org_id; ?>');
		}
	}

</script>

</body>
</html>