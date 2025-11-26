<?php /* @var $vo InternalValidation */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Internal Validation</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>

	<script language="JavaScript">
		function save()
		{
			var myForm = document.forms[0];
			if(validateForm(myForm) == false)
			{
				return false;
			}

			myForm.submit();
		}

		function delete_record(internal_validation_id)
		{
			if(!confirm('This action cannot be undone, are you sure you want to delete this record?'))
				return;
			var client = ajaxRequest('do.php?_action=edit_learner_internal_validation&subaction=delete_record&ajax_request=true&internal_validation_id='+ encodeURIComponent(internal_validation_id));
			alert(client.responseText);
			window.history.back();
		}

		function iv_qualification_id_onchange(qualification_id, event)
		{

			var f = qualification_id.form;
			var q_id = qualification_id.value;
			var grid = document.getElementById('td_units');
			if(q_id != '')
			{
				$("#td_units").empty();
				var client = ajaxRequest('do.php?_action=edit_learner_internal_validation&subaction=load_units&ajax_request=true&qualification_id='+ encodeURIComponent(q_id)+'&tr_id='+encodeURIComponent(<?php echo $vo->tr_id; ?>));
				$("#td_units").html(client.responseText);
			}
			else
			{

			}
		}
	</script>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, $user_types_with_save_access)){?><button onclick="save();">Save</button><?php }?>
		<?php if(($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, $user_types_with_save_access)) && !is_null($vo->id) && $vo->id != '') {?><button onclick="delete_record(<?php echo $vo->id; ?>);">Delete</button><?php } ?>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
	<input type="hidden" name="_action" value="save_learner_internal_validation" />
	<table border="0" cellspacing="8" style="margin-left:10px">
		<col width="190"/>
		<col width="380"/>
		<tr>
			<td class="fieldLabel_compulsory">Qualification:</td>
			<td>
				<?php
				if(is_null($vo->id) && $vo->id == '')
					echo HTML::select('iv_qualification_id', $qualifications_ddl, $vo->iv_qualification_id, true);
				else
					echo HTML::select('iv_qualification_id', $qualifications_ddl, $vo->iv_qualification_id, false);
				?>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">Unit(s):</td>
			<td>
				<div id="td_units"><?php echo HTML::checkboxGrid('unit_references', $units_ddl, $selected_units, 2); ?></div>
				<span style="color:gray;margin-left:10px">(Units list appears based on selected qualification)</span>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">IV Type:</td>
			<td><?php echo HTML::select('iv_type', $iv_types, $vo->iv_type, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">Completion Date:</td>
			<td><?php echo HTML::datebox('iv_date', $vo->iv_date, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">Action Date:</td>
			<td><?php echo HTML::datebox('iv_action_date', $vo->iv_action_date); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">Internal Verifier:</td>
			<td><?php echo HTML::select('iv_user_id', $iv_ddl, $vo->iv_user_id, true); ?></td>
		</tr>
		<?php if($vo->evidence != ''){?>
		<tr>
			<td>Evidence File:</td>
			<td><?php if($evidence_link != '') echo  $evidence_link; else echo 'Not uploaded'; ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td id="lblEvidence" class="fieldLabel_optional">Upload Evidence File:</td>
			<td><input id="evidence" class="optional" type="file" name="evidence" /></td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Comments:</td>
			<td><textarea rows="10" cols="50" id="comments" name="comments"><?php echo $vo->comments; ?></textarea></td>
		</tr>
	</table>

</body>
</html>