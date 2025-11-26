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


<script language="JavaScript">
function save()
{
	document.forms[0].submit();
}


</script>

</head>

<body>
<div class="banner">
	<div class="Title">Application-Wide Access Control</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->type!=12){?>			
		<button	onclick="save();">Save</button>
		<?php }?>
		<button	onclick="window.location.replace('do.php?_action=read_application_acl');">Cancel</button>
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<form name="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="_action" value="save_application_acl" />

<h3 class="introduction">Introduction</h3>
<p class="introduction">The Application Access Control List (ACL) manages the
granting of core privileges within the Sunesis application.</p>

<h3>Application Administrators</h3>
<p class="sectionDescription">The following users are not constrained by Sunesis security
and may create, view, edit and delete any document. There must always be at least one application-wide administrator.</p>
<div style="margin-left:10px"><?php $acl->renderList($link, 'acl_administrator', $acl->getIdentities('administrator'), ACL::USERS); ?></div>

<?php
	//TODO RE - I've commented these two ACL controls out until we find out if it is usable by the client.

/*<h3>Organisation Creators</h3>
<p class="sectionDescription">Organisation creators can create new organisations but
are not above the ACL and they may only view documents and organisations for which they
have authorisation.</p>
<div style="margin-left:10px">
*/
	?>
	<?php // $acl->renderList($link, 'acl_org_creator', $acl->getIdentities('org creator')); ?>
<?php
/*</div>

<h3>People Creators</h3>
<p class="sectionDescription">People creators can create new people but
are not above the ACL and they may only view documents and people for which they
have authorisation.</p>
<div style="margin-left:10px">
*/
?>
<?php // $acl->renderList($link, 'acl_people_creator', $acl->getIdentities('people creator')); ?>

<?php
// </div>
?>
</form>

<br/>
<br/>
</body>
</html>