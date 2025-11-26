<?php /* @var $vo User */  ?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>KPI Reports</title>
	<link rel="stylesheet" href="/common.css" type="text/css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>

</head>

<body>
	<div class="banner">
		<div class="Title">KPI Report Overview</div>
		<div class="ButtonBar">

		</div>
		<div class="ActionIconBar">
			<button onclick="window.location.reload(false);"
				title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif"
					width="16" height="16" style="vertical-align:text-bottom" /></button>
		</div>
	</div>

	<?php $_SESSION['bc']->render($link); ?>

	<h3>List of Reports for contract year <?php echo $contract_year; ?></h3>


	<form method="get" action="/do.php" style="display: inline;">
		<input type="hidden" name="_action" value="kpi_report_list" />
		<p>Filter by contract year: <select name="y">
				<option value="2025" <?php echo $contract_year == '2025' ? 'selected ' : '' ?>>2025-26</option>
				<option value="2024" <?php echo $contract_year == '2024' ? 'selected ' : '' ?>>2024-25</option>
				<option value="2023" <?php echo $contract_year == '2023' ? 'selected ' : '' ?>>2023-24</option>
				<option value="2022" <?php echo $contract_year == '2022' ? 'selected ' : '' ?>>2022-23</option>
				<option value="2021" <?php echo $contract_year == '2021' ? 'selected ' : '' ?>>2021-22</option>
				<option value="2020" <?php echo $contract_year == '2020' ? 'selected ' : '' ?>>2020-21</option>
				<option value="2019" <?php echo $contract_year == '2019' ? 'selected ' : '' ?>>2019-20</option>
				<option value="2018" <?php echo $contract_year == '2018' ? 'selected ' : '' ?>>2018-19</option>
				<option value="2017" <?php echo $contract_year == '2017' ? 'selected ' : '' ?>>2017-18</option>
				<option value="2016" <?php echo $contract_year == '2016' ? 'selected ' : '' ?>>2016-17</option>
				<option value="2015" <?php echo $contract_year == '2015' ? 'selected ' : '' ?>>2015-16</option>
				<option value="2014" <?php echo $contract_year == '2014' ? 'selected ' : '' ?>>2014-15</option>
				<option value="2013" <?php echo $contract_year == '2013' ? 'selected ' : '' ?>>2013-14</option>
				<option value="2012" <?php echo $contract_year == '2012' ? 'selected ' : '' ?>>2012-13</option>
				<option value="2011" <?php echo $contract_year == '2011' ? ' selected ' : '' ?>>2011-12</option>
				<option value="2010" <?php echo $contract_year == '2010' ? ' selected ' : '' ?>>2010-11</option>
				<option value="2009" <?php echo $contract_year == '2009' ? ' selected ' : '' ?>>2009-10</option>
				<option value="2008" <?php echo $contract_year == '2008' ? ' selected ' : '' ?>>2008-09</option>
			</select> <input type="submit" name="submit" value="Go" /></p>
		<p>Filter by programme type: <?php echo HTML::select('p', $programme_types, $programme_type, true, false); ?>

	</form>

	<ul class="formlist">


		<?php
		foreach ($reports as $reportName => $info) {
			echo '<li class="' . $info['cssClass'] . '"><a href="?_action=kpi_report_list&amp;r=' . $reportName . '&amp=output=HTML&y=' . $contract_year . '&p=' . $programme_type . '">' . $info['title'] . '</a>' . $info['description'];
		}
		?>
	</ul>

</body>

</html>