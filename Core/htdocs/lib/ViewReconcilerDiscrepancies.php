<?php
class ViewReconcilerDiscrepancies extends View
{

	public static function getInstance($import_id)
	{
		$key = 'view_'.__CLASS__;


			$sql = <<<HEREDOC
SELECT
	srt.id as sunesis_id,
	srt.name,
	srt.A09 as sunesis_a09,
	srt.L03 as sunesis_l03,
	prt.id as pfr_id,
	prt.A09 as pfr_a09,
	prt.L03 as pfr_l03,
	prt.contract_id as pfr_contract_id,
	srt.contract_id as contract_id,
	SUM(srt.P1_OPP-prt.P1_OPP) AS P1_OPP,
	SUM(srt.P1_bal-prt.P1_bal) AS P1_bal,
	SUM(srt.P1_ach-prt.P1_ach) AS P1_ach,
	SUM(srt.P2_OPP-prt.P2_OPP) AS P2_OPP,
	SUM(srt.P2_bal-prt.P2_bal) AS P2_bal,
	SUM(srt.P2_ach-prt.P2_ach) AS P2_ach,
	SUM(srt.P3_OPP-prt.P3_OPP) AS P3_OPP,
	SUM(srt.P3_bal-prt.P3_bal) AS P3_bal,
	SUM(srt.P3_ach-prt.P3_ach) AS P3_ach,
	SUM(srt.P4_OPP-prt.P4_OPP) AS P4_OPP,
	SUM(srt.P4_bal-prt.P4_bal) AS P4_bal,
	SUM(srt.P4_ach-prt.P4_ach) AS P4_ach,
	SUM(srt.P5_OPP-prt.P5_OPP) AS P5_OPP,
	SUM(srt.P5_bal-prt.P5_bal) AS P5_bal,
	SUM(srt.P5_ach-prt.P5_ach) AS P5_ach,
	SUM(srt.P6_OPP-prt.P6_OPP) AS P6_OPP,
	SUM(srt.P6_bal-prt.P6_bal) AS P6_bal,
	SUM(srt.P6_ach-prt.P6_ach) AS P6_ach,
	SUM(srt.P7_OPP-prt.P7_OPP) AS P7_OPP,
	SUM(srt.P7_bal-prt.P7_bal) AS P7_bal,
	SUM(srt.P7_ach-prt.P7_ach) AS P7_ach,
	SUM(srt.P8_OPP-prt.P8_OPP) AS P8_OPP,
	SUM(srt.P8_bal-prt.P8_bal) AS P8_bal,
	SUM(srt.P8_ach-prt.P8_ach) AS P8_ach,
	SUM(srt.P9_OPP-prt.P9_OPP) AS P9_OPP,
	SUM(srt.P9_bal-prt.P9_bal) AS P9_bal,
	SUM(srt.P9_ach-prt.P9_ach) AS P9_ach,
	SUM(srt.P10_OPP-prt.P10_OPP) AS P10_OPP,
	SUM(srt.P10_bal-prt.P10_bal) AS P10_bal,
	SUM(srt.P10_ach-prt.P10_ach) AS P10_ach,
	SUM(srt.P11_OPP-prt.P11_OPP) AS P11_OPP,
	SUM(srt.P11_bal-prt.P11_bal) AS P11_bal,
	SUM(srt.P11_ach-prt.P11_ach) AS P11_ach,
	SUM(srt.P12_OPP-prt.P12_OPP) AS P12_OPP,
	SUM(srt.P12_bal-prt.P12_bal) AS P12_bal,
	SUM(srt.P12_ach-prt.P12_ach) AS P12_ach,
	SUM(srt.grand_total-prt.grand_total) AS grand_total,
	srt.grand_total as sunesis_grand_total,
	prt.grand_total as pfr_grand_total,
	srt.record_issue as sunesis_issue,
	prt.record_issue as pfr_issue,
	(prt.P1_OPP+prt.P2_OPP+prt.P3_OPP+prt.P4_OPP+prt.P5_OPP+prt.P6_OPP+prt.P7_OPP+prt.P8_OPP+prt.P9_OPP+prt.P10_OPP+prt.P11_OPP+prt.P12_OPP) AS pfr_on_payment,
	(prt.P1_bal+prt.P2_bal+prt.P3_bal+prt.P4_bal+prt.P5_bal+prt.P6_bal+prt.P7_bal+prt.P8_bal+prt.P9_bal+prt.P10_bal+prt.P11_bal+prt.P12_bal) AS pfr_balancing,
	(prt.P1_ach+prt.P2_ach+prt.P3_ach+prt.P4_ach+prt.P5_ach+prt.P6_ach+prt.P7_ach+prt.P8_ach+prt.P9_ach+prt.P10_ach+prt.P11_ach+prt.P12_ach) AS pfr_achieved,
	(srt.P1_OPP+srt.P2_OPP+srt.P3_OPP+srt.P4_OPP+srt.P5_OPP+srt.P6_OPP+srt.P7_OPP+srt.P8_OPP+srt.P9_OPP+srt.P10_OPP+srt.P11_OPP+srt.P12_OPP) AS sunesis_on_payment,
	(srt.P1_bal+srt.P2_bal+srt.P3_bal+srt.P4_bal+srt.P5_bal+srt.P6_bal+srt.P7_bal+srt.P8_bal+srt.P9_bal+srt.P10_bal+srt.P11_bal+srt.P12_bal) AS sunesis_balancing,
	(srt.P1_ach+srt.P2_ach+srt.P3_ach+srt.P4_ach+srt.P5_ach+srt.P6_ach+srt.P7_ach+srt.P8_ach+srt.P9_ach+srt.P10_ach+srt.P11_ach+srt.P12_ach) AS sunesis_achieved
FROM
	rpt_reconciler_sunesis AS srt,
	rpt_reconciler_pfr AS prt
WHERE
	(
		prt.A09 = srt.A09
		# AND prt.L03 = srt.L03
		AND prt.ULN = srt.ULN
		AND prt.import_id = srt.import_id
		AND srt.import_id = {$import_id}
		AND srt.record_status = 1
		AND prt.record_status = 1
	)
GROUP BY
	# srt.A09, srt.L03;
	srt.A09, srt.ULN;
HEREDOC;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$view = $_SESSION[$key] = new ViewReconcilerDiscrepancies();
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

		}
		else {
			$view = $_SESSION[$key];
		}

		$options = "SELECT contracts.id, contracts.title, null, CONCAT('WHERE srt.contract_id=', contracts.id) FROM contracts, rpt_reconciler_sunesis where contracts.id = rpt_reconciler_sunesis.contract_id AND rpt_reconciler_sunesis.import_id = {$import_id} group by contracts.id";
		$f = new DropDownViewFilter('filter_contracts', $options, null, true);
		$f->setDescriptionFormat("Contract: %s");
		$view->addFilter($f);

		$view->setSQL($sql);

		return $_SESSION[$key];
	}

	public function render(PDO $link) {

		$reconciler = new Reconciler();

		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());

		if( $st ) {

			$missing_totaliser = 0;

			echo $this->getViewNavigator();

			echo "<table style='resultset' cellpadding='6' ><thead><tr>";
			echo "<th>Learner</th>";
			echo "<th>Learning Aim</th>";
			echo "<th>Contract</th>";
			echo "<th style='text-align:right;'>Sunesis Value</th>";
			echo "<th style='text-align:right;'>PFR Value</th>";
			echo "<th style='text-align:right;'>Discrepancy</th>";
			echo "</tr></thead>";
			echo "<tbody>";

			// remove any zero lined records
			while( $row = $st->fetch() ) {

				echo '<tr class="data-display" id="rid'.$row['sunesis_id'].'" onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};" title="" ';
				echo ' onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};"</tr>';

				echo "<td>".$row['name'].'<br/>';
				echo $row['sunesis_l03'].'</td>';
				echo '<td>'.$row['sunesis_a09'].'</td>';
				$contract_name = 'unmatched contract';
				if ( $row['contract_id'] != '' ) {
					$get_missing_contract_query = 'SELECT title FROM contracts where id = '.$row['contract_id'];
					$contract_name = DAO::getSingleValue($link, $get_missing_contract_query);
				}
				echo '<td>'.$contract_name.'</td>';
				echo '<td style="text-align:right; color: #4572A7;">'.Reconciler::formatCash($row['sunesis_grand_total'])."</td>";
				echo '<td style="text-align:right;" >'.Reconciler::formatCash($row['pfr_grand_total']).'</td>';
				echo "<td style='text-align:right; color: #4572A7;'>".Reconciler::formatCash($row['sunesis_grand_total']-$row['pfr_grand_total'])."</td></tr>";
				$missing_totaliser += $row['sunesis_grand_total'];
			}

			echo  "</tbody>";
			echo  "</table>";
		}
	}
}
?>