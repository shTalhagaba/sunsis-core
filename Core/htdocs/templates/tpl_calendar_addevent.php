<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Add Event</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" href="/assets/css/ui-lightness/jquery-ui-1.7.2.custom.css" type="text/css" />
	<script type="text/javascript" src="/assets/js/jquery/jquery-1.3.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery-ui-1.7.2.custom.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery.maskedinput-1.2.2.min.js"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker-1.0.0.js"></script>
	<script type="text/javascript" src="/assets/js/js_calendar_addevent.js"></script>
	
</head>

<body>



<?php $_SESSION['bc']->render($link); ?>

<h3>Add Event</h3>

<form id="addevent" method="post" action="/do.php?_action=calendar_addevent">
<input type="hidden" name="do" value="doadd" />
<table class="formtable">
	<tbody>
		<?php if(sizeof($errors) > 0) { ?>
		<tr>
			<td colspan="2" class="errors">Errors:
				<ul>
					<?php 
					foreach($errors AS $key => $error)
					{
						echo '<li>' . $error . '</li>';	
					}
					?>
				</ul>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td width="100">Event Title:</td>
			<td><?php echo HTML::textbox('title', $_REQUEST['title'], ' class="width1"') ?><span class="required">Required</span></td>
		</tr>	
		<tr>
			<td>Dates:</td>
			<td><input type="text" name="datefrom" value="<?php echo isset($_REQUEST['datefrom']) ? $_REQUEST['datefrom'] : ''; ?>" class="width2" id="datefrom" /> <input type="text" id="datefromtime" name="datefromtime" value="<?php echo isset($_REQUEST['datefromtime']) ? $_REQUEST['datefromtime'] : ''; ?>" class="width2" /> to <input type="text" id="datetotime" name="datetotime" value="<?php echo isset($_REQUEST['datetotime']) ? $_REQUEST['datetotime'] : ''; ?>" class="width2" /> <input type="text" name="dateto" value="<?php echo isset($_REQUEST['dateto']) ? $_REQUEST['dateto'] : ''; ?>" class="width2" id="dateto" /></td>
		</tr>
		<tr>
			<td>All day?</td>
			<td><?php echo HTML::radioChoice('allday', array('Yes' => 1, 'No' => 0), $_REQUEST['allday']); ?></td>
		</tr>
		<tr>
			<td>Location:</td>
			<td><?php echo HTML::textbox('location', $_REQUEST['location'], ' class="width1"') ?></td>
		</tr>	
		<tr>
			<td>Calendar:</td>
			<td><?php echo $this->renderCalendarList($_REQUEST['calendar_id']); ?></td>
		</tr>
		<tr valign="top">
			<td>Description:</td>
			<td><textarea name="description" rows="4" cols="45" class="width1"><?php echo $_REQUEST['description']; ?></textarea></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="submit" value="Submit" /></td>
		</tr>
	</tbody>
</table>
</form>

</body>
</html>