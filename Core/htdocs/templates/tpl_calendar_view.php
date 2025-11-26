<?php /* @var $vo User */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>My Calendar</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script type="text/javascript" src="/assets/js/jquery/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/assets/js/jquery/jquery-ui-1.7.2.custom.min.js"></script>
</head>

<body>
<div class="banner">
	<div class="Title">Assessor Calendar</div>
	<div class="ButtonBar">
		<button onclick="window.location.replace('do.php?v=<?php echo $_REQUEST['v']; ?>&_action=calendar_view');">Today</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>


<?php
$_SESSION['bc']->render($link);
echo '<h1>View: <a href="?_action=calendar_view&amp;v=3&amp;y=' . intval($_REQUEST['y']) . '&amp;m=' . intval($_REQUEST['m']) . '&amp;d=' . intval($_REQUEST['d']) . '">Daily</a> | <a href="?_action=calendar_view&amp;v=2&amp;y=' . intval($_REQUEST['y']) . '&amp;m=' . intval($_REQUEST['m']) . '&amp;d=' . intval($_REQUEST['d']) . '">Weekly</a> | <a href="?_action=calendar_view&amp;v=1&amp;y=' . intval($_REQUEST['y']) . '&amp;m=' . intval($_REQUEST['m']) . '&amp;d=' . intval($_REQUEST['d']) . '">Monthly</a></h1>';

// echo '<p><a href="?_action=calendar_addevent">Add Event</a>';
echo $dataHTML; 

?>

</body>
</html>