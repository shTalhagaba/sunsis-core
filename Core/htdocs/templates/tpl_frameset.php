<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sunesis - Perspective</title>
</head>

<frameset cols="174, *" border="0" frameborder="0" framespacing="0" id="sunesis-frameset">
	<frame name="left" src="do.php?_action=left_hand_menu" border="0" framespacing="0" frameborder="0" marginwidth="0" marginheight="0" noresize="noresize"/>
	 <frame name="right" src="do.php?_action=home_page" border="0" framespacing="0" frameborder="0" marginwidth="0" marginheight="0"/>
<?php 

/*
if($_SESSION['user']->isAdmin())
	echo '<frame name="right" src="do.php?_action=home_page" border="0" framespacing="0" frameborder="0" marginwidth="0" marginheight="0"/>';
elseif($_SESSION['user']->isOrgAdmin()) 
	echo '<frame name="right" src="do.php?_action=view_training_records" border="0" framespacing="0" frameborder="0" marginwidth="0" marginheight="0"/>';
elseif($_SESSION['user']->type==2 || $_SESSION['user']->type==3 || $_SESSION['user']->type==4)
	echo '<frame name="right" src="do.php?_action=view_learner_groups" border="0" framespacing="0" frameborder="0" marginwidth="0" marginheight="0"/>';
elseif($_SESSION['user']->type==5)
{	
	$username = $_SESSION['user']->username;
	$que = "select id from tr where username='$username'";
	$tr_id = trim(DAO::getSingleValue($link, $que));
	echo '<frame name="right" src="do.php?_action=read_training_record&id=' . $tr_id .'" border="0" framespacing="0" frameborder="0" marginwidth="0" marginheight="0"/>';
}
*/
?>
</frameset>