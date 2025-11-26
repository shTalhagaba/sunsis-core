<?php /* @var $acl ACL */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>School</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<style type="text/css">

p.aclEntry
{
	font-family: monospace;
	margin-left: 10px;
}

</style>

</head>

<body>
<div class="banner">
	<div class="Title">Application-Wide Access Control</div>
	<div class="ButtonBar">
		<button	onclick="window.location.replace('do.php?_action=edit_application_acl');">Edit</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<h3>Application Administrators</h3>
<p class="sectionDescription">Administrators are above the ACL and may view, edit and
create any document. There must always be at least one application-wide administrator.</p>
<?php //echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('administrator')).'</p>'; ?>
<?php 
	if(is_array($acl_list))
	{
		echo "<table class='resultset' cellpadding='6'>";
		echo "<tr><th>System Username</th><th>Full Name</th></tr>";
		foreach($acl_list AS $_username)
		{
			echo "<tr>";
			echo "<td>{$_username}</td>";
			echo "<td>" . DAO::getSingleValue($link, "SELECT CONCAT(surname, ', ', firstnames) FROM users WHERE users.username = '{$_username}'") . "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
?>

<?php
// RE - I've commented these two ACL controls out until we find out if it is usable by the client.
/*<h3>Organisation Creators</h3>
<p class="sectionDescription">Organisation creators can create new organisations but
are not above the ACL and they may only view documents and organisations for which they
have authorisation.</p>
	*/
?>

<?php // echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('org creator')).'</p>'; ?>

<?php

/*
<h3>People Creators</h3>
<p class="sectionDescription">People creators can create new people but
are not above the ACL and they may only view documents and people for which they
have authorisation.</p>
*/
	?>
<?php // echo '<p class="aclEntry">'.implode('</p><p class="aclEntry">', $acl->getIdentities('people creator')).'</p>'; ?>

</body>
</html>