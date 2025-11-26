<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Location</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
</head>

<body>
<div class="banner">
	<div class="Title">ILR Audit Trail Entry Details</div>
	<div class="ButtonBar">
		<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<h3> Details </h3>

<p><span class="fieldLabel">Timestamp: </span><br><span><?php echo Date::to($ilr_audit->date, Date::DATETIME); ?></span></p>
<?php
$user_details = DAO::getObject($link, "SELECT firstnames, surname, work_email, username, legal_name FROM users INNER JOIN organisations ON users.employer_id = organisations.id WHERE users.username = '{$ilr_audit->username}'");
if(isset($user_details->username))
{
	echo '<p><span class="fieldLabel">User: </span><br>';
	echo '<span>' . $user_details->surname . ', ' . $user_details->firstnames . '</span><br>';
	echo '<span>' . $user_details->work_email . '</span><br>';
	echo '<span>' . $user_details->legal_name . '</span><br>';

}
else
{
	echo '<p><span class="fieldLabel">User\'s username: </span><span>' . $ilr_audit->username . '</span><br></p>';
}
?>

<?php echo $resultText; ?>

</body>
</html>