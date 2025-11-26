<?php /* @var $vo Interview */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Interview</title>
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
		function entry_onclick(radio)
		{
			var td = radio.parentNode;
			var tr = td.parentNode;

			var inputs = tr.getElementsByTagName("td");

			for(var i = 0; i < 3; i++)
			{
				if(inputs[i].tagName == 'TD')
				{
					if(inputs[i].className=='redd')
						inputs[i].className='redl';

					if(inputs[i].className=='greend')
						inputs[i].className='greenl';

					if(inputs[i].className=='yellowd')
						inputs[i].className='yellowl';
				}
			}

			if(td.className=='redl')
				td.className='redd';

			if(td.className=='greenl')
				td.className='greend';

			if(td.className=='yellowl')
				td.className='yellowd';
		}
	</script>

	<style type="text/css">
		td.greenl
		{
			background-image:url('/images/trafficlight-green.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.redl
		{
			background-image:url('/images/trafficlight-red.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.yellowl
		{
			background-image:url('/images/trafficlight-yellow.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 0.2;
			filter: alpha(opacity=20);
		}

		td.greend
		{
			background-image:url('/images/trafficlight-green.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

		td.redd
		{
			background-image:url('/images/trafficlight-red.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

		td.yellowd
		{
			background-image:url('/images/trafficlight-yellow.jpg');
			background-color:white;
			background-repeat: no-repeat;
			background-position: center;
			opacity: 1;
			filter: alpha(opacity=100);
		}

	</style>

</head>
<body>
<div class="banner">
	<div class="Title"><?php echo $page_title; ?></div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER){?><button onclick="save();">Save</button><?php }?>
		<button onclick="<?php echo $js_cancel; ?>">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="tr_id" value="<?php echo $vo->tr_id ?>" />
	<input type="hidden" name="_action" value="save_learner_interview" />
	<table border="0" cellspacing="8" style="margin-left:10px">
		<col width="190"/>
		<col width="380"/>
		<tr>
			<td class="fieldLabel_compulsory">Date:</td>
			<td><?php echo HTML::datebox('interview_date', $vo->interview_date, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">Start Time:</td>
			<td><?php echo HTML::timebox('interview_start_time', $vo->interview_start_time, true); ?><span style="color:gray">(24 hour, HH:MM)</span></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory" valign="top">End Time:</td>
			<td><?php echo HTML::timebox('interview_end_time', $vo->interview_end_time, true); ?><span style="color:gray">(24 hour, HH:MM)</span></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Type:</td>
			<td><?php echo HTML::select('interview_type', $interview_types, $vo->interview_type, true, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Assessor/Interviewer:</td>
			<td><?php echo HTML::select('interviewer', $assessors, $vo->interviewer, true, true); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Status:</td>
			<td><?php echo HTML::select('interview_status', $interview_statuses, $vo->interview_status, true, true); ?></td>
		</tr>
		<?php if($vo->id != '') {?>
		<tr>
			<td class="fieldLabel_compulsory">GYR:</td>
			<td>
				<table>
					<tr>
						<?php if($vo->interview_rgb_status == 'green') {?>
							<td align="center" class="greend" width="32" height="40"><input type="radio" checked="checked" value="green" name="interview_rgb_status" title="Satisfactory" onclick="entry_onclick(this);" /></td>
						<?php }else{ ?>
							<td align="center" class="greenl" width="32" height="40"><input type="radio" value="green" name="interview_rgb_status" title="Satisfactory" onclick="entry_onclick(this);" /></td>
						<?php } ?>
						<?php if($vo->interview_rgb_status == 'yellow') {?>
							<td align="center" class="yellowd" width="32" height="40"><input type="radio" checked="checked" value="yellow" name="" title="Average" onclick="entry_onclick(this);"/></td>
						<?php } else { ?>
							<td align="center" class="yellowl" width="32" height="40"><input type="radio" value="yellow" name="interview_rgb_status" title="Average" onclick="entry_onclick(this);"/></td>
						<?php } ?>
						<?php if($vo->interview_rgb_status == 'red') {?>
							<td align="center" class="redd" width="32" height="40"><input type="radio" checked="checked" value="red" name="interview_rgb_status" title="Dis-satisfactory" onclick="entry_onclick(this);"/></td>
						<?php } else {?>
							<td align="center" class="redl" width="32" height="40"><input type="radio" value="red" name="interview_rgb_status" title="Dis-satisfactory" onclick="entry_onclick(this);"/></td>
						<?php } ?>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="fieldLabel_optional">Paperwork:</td>
			<td><?php echo HTML::select('interview_paperwork', $interview_paperworks, $vo->interview_paperwork, true, false); ?></td>
		</tr>
		<?php } ?>
		<!--<tr>
			<td class="fieldLabel_compulsory">Module:</td>
			<td><?php /*echo HTML::select('interview_module', $modules, $vo->interview_module, true, true); */?></td>
		</tr>-->
		<tr>
			<td class="fieldLabel_optional">Comments:</td>
			<td><textarea rows="10" cols="50" id="interview_comments" name="interview_comments"><?php echo $vo->interview_comments; ?></textarea></td>
		</tr>
	</table>

</body>
</html>