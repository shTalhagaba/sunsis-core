<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Learners Progress Report</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>


	<!--[if IE]>
	<link rel="stylesheet" href="/common-ie.css" type="text/css"/>
	<![endif]-->
	<script type="text/javascript">
		var GB_ROOT_DIR = "/assets/js/greybox/";
	</script>
	<script type="text/javascript" src="/assets/js/greybox/AJS.js"></script>
	<script type="text/javascript" src="/assets/js/greybox/AJS_fx.js"></script>
	<script type="text/javascript" src="/assets/js/greybox/gb_scripts.js"></script>
	<link href="/assets/js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div class="banner">
	<div class="Title">Learners Progress Report</div>

	<div class="ActionIconBar">
		<button onclick="window.location.href = 'do.php?_action=read_xml&export=excel';" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" alt="" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div align="center" style="margin-top:50px;">
	<?php echo $text_html; ?>
	<?php
	/*ini_set('memory_limit','2048M');
	$StatusList= array();
	$StatusList[0] = "";
	$StatusList[1]=" [Not Started]";
	$StatusList[2]=" [Behind]";
	$StatusList[3]=" [On Track]";
	$StatusList[4]=" [Completed]";

	$info_required = array();
	$info_required[] = "reference";
	$info_required[] = "title";
	$info_required[] = "owner_reference";
	$info_required[] = "proportion";
	$info_required[] = "mandatory";
	$info_required[] = "percentage";

	
	echo "<table border='1' class='resultset'>";
	echo "<thead>";
	echo "<th>Firstname(s)</th><th>Surname</th><th>L03</th><th>Qualification Number</th><th>Training Record ID</th>";
	foreach($info_required AS $column_header)
		echo "<th>" . $column_header . "</th>";
	echo "</thead>";
	echo "<tbody>";

	$student_qualifications = DAO::getResultset($link, "SELECT student_qualifications.id, tr_id, tr.l03, tr.firstnames, tr.surname, evidences FROM student_qualifications INNER JOIN tr ON student_qualifications.tr_id = tr.id LIMIT 1500;# WHERE tr_id = 4172;# AND student_qualifications.id = '100/5570/8';", DAO::FETCH_ASSOC);
	foreach($student_qualifications AS $qualification)
	{
		$evidence = XML::loadSimpleXML($qualification['evidences']);//pre(count($evidence->xpath('//root/units/units')));

		if(count($evidence->xpath('//root/units/units')) == 0)
		{
			if(count($evidence->xpath('//root/units')) == 0)
			{
				foreach($evidence AS $individual_unit)
				{
					echo "<tr>";
					echo "<td>" . $qualification['firstnames'] ."</td>";
					echo "<td>" . $qualification['surname'] ."</td>";
					echo "<td>" . $qualification['l03'] ."</td>";
					echo "<td>" . $qualification['id'] ."</td>";
					echo "<td>" . $qualification['tr_id'] ."</td>";

					$temp = array();
					$temp = (array) $individual_unit->attributes();
					$temp = $temp['@attributes'];

					$temp = $this->sortArrayByArray($temp, $info_required);
					//pre($temp);
					foreach($temp AS $key => $value)
					{
						if(in_array($key, $info_required))
							echo "<td>" . $value . "</td>";
					}
					echo "</tr>";
				}
			}
			elseif(count($evidence->xpath('//root/units')) > 0)
			{
				foreach($evidence->children() AS $main_unit_group)
				{
					foreach($main_unit_group AS $individual_unit)
					{
						echo "<tr>";
						echo "<td>" . $qualification['firstnames'] ."</td>";
						echo "<td>" . $qualification['surname'] ."</td>";
						echo "<td>" . $qualification['l03'] ."</td>";
						echo "<td>" . $qualification['id'] ."</td>";
						echo "<td>" . $qualification['tr_id'] ."</td>";

						$temp = array();
						$temp = (array) $individual_unit->attributes();
						$temp = $temp['@attributes'];

						$temp = $this->sortArrayByArray($temp, $info_required);
	//pre($temp);
						foreach($temp AS $key => $value)
						{
							if(in_array($key, $info_required))
								echo "<td>" . $value . "</td>";
						}
						echo "</tr>";
					}
				}
			}
		}
		elseif(count($evidence->xpath('//root/units/units')) > 0)
		{
			foreach($evidence->children() AS $main_unit_group) // foreach main unit group
			{
				if(count($main_unit_group->xpath('units')) == 0)
				{
					foreach($main_unit_group AS $individual_unit)
					{
						//pre($individual_unit);
						echo "<tr>";
						echo "<td>" . $qualification['firstnames'] ."</td>";
						echo "<td>" . $qualification['surname'] ."</td>";
						echo "<td>" . $qualification['l03'] ."</td>";
						echo "<td>" . $qualification['id'] ."</td>";
						echo "<td>" . $qualification['tr_id'] ."</td>";

						$temp = array();
						$temp = (array) $individual_unit->attributes();
						$temp = $temp['@attributes'];

						$temp = $this->sortArrayByArray($temp, $info_required);

						foreach($temp AS $key => $value)
						{
							if(in_array($key, $info_required))
								echo "<td>" . $value . "</td>";
						}
						echo "</tr>";
					}
				}
				elseif(count($main_unit_group->xpath('units')) > 0)
				{
					foreach($main_unit_group AS $sub_unit_group)
					{
						foreach($sub_unit_group AS $individual_unit)
						{
							//pre($individual_unit);
							echo "<tr>";
							echo "<td>" . $qualification['firstnames'] ."</td>";
							echo "<td>" . $qualification['surname'] ."</td>";
							echo "<td>" . $qualification['l03'] ."</td>";
							echo "<td>" . $qualification['id'] ."</td>";
							echo "<td>" . $qualification['tr_id'] ."</td>";

							$temp = array();
							$temp = (array) $individual_unit->attributes();
							$temp = $temp['@attributes'];

							$temp = $this->sortArrayByArray($temp, $info_required);

							foreach($temp AS $key => $value)
							{
								if(in_array($key, $info_required))
									echo "<td>" . $value . "</td>";
							}
							echo "</tr>";
						}
					}
				}
			}
		}
	}



	echo "</tbody>";
	echo "</table>";*/
?>
</div>


</body>
</html>