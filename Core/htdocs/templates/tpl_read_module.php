<?php /* @var $vo Contract */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Contract</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

	<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
	<script language="JavaScript" src="/calendarPopup/CalendarPopup.js"></script>

	<!-- Initialise calendar popup -->
	<script language="JavaScript">
		<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
		var calPop = new CalendarPopup();
		calPop.showNavigationDropdowns();
			<?php } else { ?>
		var calPop = new CalendarPopup("calPop1");
		calPop.showNavigationDropdowns();
		document.write(getCalendarStyles());
			<?php } ?>
	</script>

	<script language="JavaScript">
		function deleteRecord()
		{
			var module_attached_to_lesson = <?php echo $lessonsAttached; ?>;
			if(window.confirm("Delete this module?"))
			{
				if(module_attached_to_lesson != 0)
					alert("There are lessons attached to this module, it cannot be deleted.");
				else
					window.location.replace('do.php?_action=delete_module&id=<?php echo $vo->id; ?>');
			}
		}

	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Module</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12 && $_SESSION['user']->isAdmin()){?>
		<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_module&edit=1');">Edit</button>
		<button onclick="deleteRecord();">Delete</button>
		<?php }?>
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Details</h3>
<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="id" value="<?php echo $vo->id ?>" />
	<input type="hidden" name="_action" value="save_module" />
	<table border="0" cellspacing="4" style="margin-left:10px">
		<col width="170" />
		<tr>
			<td class="fieldLabel_compulsory">Title: (Ref)</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->title); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Training Provider:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$provider); ?></td>
		</tr>
		<tr>
			<td class="fieldLabel_compulsory">Learning Hours:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->learning_hours); ?></td>
		</tr>
	</table>

	<!-- Popup calendar -->
	<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>

</body>
</html>