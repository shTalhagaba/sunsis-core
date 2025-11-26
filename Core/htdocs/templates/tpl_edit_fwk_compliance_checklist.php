<?php /* @var $framework Framework */ ?>

<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Edit Compliance Checklist</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
		.ui-datepicker .ui-datepicker-title select {
			color: #000;
		}
		.disabled{
			pointer-events:none;
			opacity:0.4;
		}
		input[type=checkbox] {
			transform: scale(1.4);
		}
	</style>
</head>
<body>

<div class="row">
	<div class="col-lg-12">
		<div class="banner">
			<div class="Title" style="margin-left: 6px;">Edit Compliance Checklist</div>
			<div class="ButtonBar">
				<span class="btn btn-sm btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
				<span class="btn btn-sm btn-default" onclick="saveCompliance();"><i class="fa fa-save"></i> Save</span>
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
	<div class="col-sm-6">
		<table class="table table-bordered small">
			<tr>
				<th class="bg-gray-light">Framework : </th><td colspan="5"><?php echo $framework->title; ?></td></tr>
			<tr>
				<th class="bg-gray-light">Programme Type: </th><td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(ProgType, ' ' , ProgTypeDesc) FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE ProgType = '{$framework->framework_type}'"); ?></td>
				<?php if($framework->framework_type == 25) { ?>
				<th class="bg-gray-light">Standard Code</th>
				<td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(StandardCode, ' ' , StandardName) FROM lars201718.Core_LARS_Standard WHERE StandardCode = '{$framework->StandardCode}'"); ?></td>
				<?php } else { ?>
				<th class="bg-gray-light">Framework Code</th>
				<td><?php echo DAO::getSingleValue($link, "SELECT CONCAT(FworkCode, ' ', IssuingAuthorityTitle) FROM lars201718.`Core_LARS_Framework` WHERE FworkCode = '{$framework->framework_code}'"); ?></td>
				<?php } ?>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<span class="lead text-bold">Compliance Checklist</span>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<form class="form-horizontal" name="frmCompliance" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<input type="hidden" name="_action" value="save_fwk_compliance_checklist" />
			<input type="hidden" name="framework_id" value="<?php echo $framework->id ?>" />

			<table class="table table-bordered" style="width: 70%;">
				<tr class="bg-gray-light">
					<th style="width: 70%;">Compliance Item / Event</th>
					<th style="width: 10%;">Order</th>
					<th style="width: 20%;">Action</th>
				</tr>
				<?php
				foreach($compliance_checklist AS $row)
				{
					$rowId  = 'r' . $row['id'];
					echo '<tr id="'.$rowId.'">';
					echo '<input type="hidden" name="id'.$rowId.'" id="id'.$rowId.'" value="'.$row['id'].'" />';
					echo '<td><input type="text" name="c_event'.$rowId.'" id="c_event'.$rowId.'" value="'.$row['c_event'].'" class="form-control" /></td>';
					echo '<td><input type="text" name="sorting'.$rowId.'" id="sorting'.$rowId.'" value="'.$row['sorting'].'" class="form-control" onkeypress="return numbersonly(this);" maxlength="3" /></td>';
					echo '<td>';
					echo '<span title="Save this entry" class="btn btn-success btn-sm" onclick="saveRow(\'r'.$row['id'].'\');"><i class="fa fa-save fa-lg"></i></span> &nbsp; ';
					if($row['related_entries'] == 0)
						echo '<span title="Delete this entry" class="btn btn-danger btn-sm" onclick="deleteRow(\'r'.$row['id'].'\');"><i class="fa fa-trash fa-lg"></i></span>';
					echo '</td>';
					echo '</tr>';
				}
				?>
				<tr id="newrow">
					<input type="hidden" name="idnewrow" id="idnewrow" value="newrow" />
					<td><input type="text" name="c_event_newrow" id="c_eventnewrow" class="form-control" /></td>
					<td><input type="text" name="sorting_newrow" id="sortingnewrow" class="form-control" /></td>
					<td><span title="Save this entry" class="btn btn-success btn-sm" onclick="saveRow('newrow');"><i class="fa fa-save fa-lg"></i></span> &nbsp; </td>
				</tr>
			</table>
		</form>
	</div>
</div>

<br>

<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
<script src="/assets/adminlte/dist/js/app.min.js"></script>
<script src="/common.js" type="text/javascript"></script>

<script language="JavaScript">

	$(function() {

		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});

	});

	function saveCompliance()
	{
		var frmCompliance = document.forms["frmCompliance"];
		if(validateForm(frmCompliance) == false)
		{
			return false;
		}
		frmCompliance.submit();
	}

	function saveRow(rowId)
	{
		var suffix = rowId;
		var id = $("#id"+suffix).val();
		var c_event = $("#c_event"+suffix).val();
		var sorting = $("#sorting"+suffix).val();

		var postData = "id="+encodeURIComponent(id)+"&c_event="+encodeURIComponent(c_event)+"&sorting="+encodeURIComponent(sorting)+"&framework_id="+encodeURIComponent('<?php echo $framework->id; ?>');
		var client = ajaxRequest("do.php?_action=edit_fwk_compliance_checklist&subaction=saveEntry", postData);
		if(client)
		{
			alert("Records saved.");
			window.location.reload();
		}
		else
		{
			window.location.reload();
		}
	}

	function deleteRow(rowId)
	{
		var id = $("#id"+rowId).val();

		var postData = "id="+encodeURIComponent(id);
		var client = ajaxRequest("do.php?_action=edit_fwk_compliance_checklist&subaction=deleteEntry", postData);
		if(client)
		{
			alert("Entry deleted.");
			window.location.reload();
		}
		else
		{
			window.location.reload();
		}
	}


</script>

</body>
</html>