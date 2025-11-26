<?php /* @var $contract Contract */ ?>
<?php /* @var $contract_holder ContractHolder */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Rules Violation Report</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script src="/common.js" type="text/javascript"></script>

	<script language="JavaScript">
		function exportToPDF()
		{

		}
	</script>

</head>
<body>
<div class="banner">
	<div class="Title">Rules Violation Report</div>
	<div class="ButtonBar">
		<!--<button onclick="exportToPDF();">Cancel</button>-->
	</div>
	<div class="ActionIconBar">

	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>
<div id="div_report">

<table border="0" cellspacing="4" cellpadding="4" style="margin-left:10px"><caption><h1>Rules Violations Report</h1></caption>
	<col width="150" />
	<tr>
		<td class="fieldLabel">Contract Title:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$contract->title ?: ''); ?></td>
		</tr>
	<tr>
		<td class="fieldLabel">Contract Holder:</td><td class="fieldValue"><?php echo isset($contract_holder->legal_name)?htmlspecialchars((string)$contract_holder->legal_name ?: ''):''; ?></td>
		<td class="fieldLabel">Funded:</td><td class="fieldValue"><?php echo htmlspecialchars((string) DAO::getSingleValue($link,"SELECT contract_type FROM lookup_contract_types WHERE id = '$contract->funded'") ?: ''); ?></td>
		</tr>
	<tr>
		<td class="fieldLabel">Location:</td><td class="fieldValue"><?php echo htmlspecialchars((string) $contract_location); ?></td>
		<td class="fieldLabel">Contract Year:</td><td class="fieldValue"><?php echo htmlspecialchars($contract->contract_year.'-'.str_pad((substr($contract->contract_year,2,2)+1),2,'0',0)); ?></td>
		</tr>
	<tr>
		<td class="fieldLabel">UPIN:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$contract->upin ?: ''); ?></td>
		<td class="fieldLabel">UKPRN:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$contract->ukprn ?: ''); ?></td>
		</tr>
</table>

<br>
<br>

<?php echo $report; ?>
</div>
</body>
</html>