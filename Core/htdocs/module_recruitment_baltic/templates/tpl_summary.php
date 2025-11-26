<?php /* @var $view ViewForecastVacanciesSummary */  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="stylesheet" href="/common.css" type="text/css"/>

	<link rel="stylesheet" href="/jquery-ui/css/jquery-ui-1.8.11.custom.css" type="text/css"/>
	<script language="JavaScript" src="/jquery-ui/js/jquery-1.5.2.min.js"></script>
	<script language="JavaScript" src="/jquery-ui/js/jquery-ui-1.8.11.custom.min.js"></script>
	<script language="JavaScript" src="/common.js"></script>


</head>

<body>
<div class="banner">
	<div class="Title">Forecast Vacancies Summary</div>
	<div class="ButtonBar">
	</div>
	<div class="ActionIconBar">

	</div>
</div>



<div align="left" >
	<form name="frm_summary" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
		<input type="hidden" name="_action" value="summary" />
		<?php
		echo 'Month:';
		echo HTML::select('month', $months, $month, false, false);
		echo 'Region:';
		echo HTML::select('region', $region_dropdown, $region, true, false, true);
		echo 'BRM:';
		echo HTML::select('brm', $brm_dropdown, $brm, true, false, true);
		echo 'Sector:';
		echo HTML::select('sector', $sector_dropdown, $sector, true, false, true);
		echo 'Employer:';
		echo HTML::select('employer', $employers_dropdown, $employer, true, false, true);
		?>
		<button type="submit"> Submit </button>
	</form>
</div>
<div align="left"style="margin-top:50px;">
	<?php /*echo $stat_graphs; */?>
	<?php echo $table;


	$report3 = new DataMatrix(array_keys($k[0]), $k, true);


	echo $report3->to('PieChart');?>



</div>


</body>
</html>