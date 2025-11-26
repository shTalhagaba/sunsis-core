<?php
/**
 * Created By: Perspective Ltd.
 * User: Richard Elmes
 * Date: 26/06/12
 * Time: 14:46
 */

class ajax_get_discrepancy_detail implements IAction
{
	/**
	 * @param PDO $link
	 */
	public function execute(PDO $link) {

		if ((!isset($_REQUEST['rid']) && !isset($_REQUEST['mid'])) || !isset($_REQUEST['impid'])  ) {
			echo '<div style="font-size: 1.1em;" >Cannot Identify the record you are looking for!</div>';
			exit;
		}

		$location_name = 'Sunesis';

		// default to a sunesis record to pull out
		$sql = <<<HEREDOC

		SELECT

	srt.course_name as 'Header Course Name',
	srt.provider_name as 'Header Provider Name',
	srt.name as 'Header Learner Name' ,
	srt.A09 as 'Header sunesis_a09',
	srt.L03 as 'Header sunesis_l03',
	prt.A09 as 'Header pfr_a09' ,
	prt.L03 as 'Header pfr_l03',

	IF( srt.learner_start_date != prt.learner_start_date, CONCAT('+',srt.learner_start_date,'|+',prt.learner_start_date,'|'), 'matched') as 'Learning Start Date',
	IF(srt.learner_target_end_date!=prt.learner_target_end_date, CONCAT('+',srt.learner_target_end_date,'|+',prt.learner_target_end_date,'|'), 'matched')  as 'Learning Planned End Date',
	IF(srt.learner_end_date!=prt.learner_end_date, CONCAT('+',srt.learner_end_date,'|+',	prt.learner_end_date,'|'), 'matched') as 'Learning Actual End Date',
	IF(srt.entry_end_date!=prt.entry_end_date, CONCAT('+',srt.entry_end_date,'|+',prt.entry_end_date,'|'), 'matched') as 'Achievement Date',
	IF(srt.P1_OPP!=prt.P1_OPP, CONCAT('+&pound;',srt.P1_OPP,'|+&pound;',prt.P1_OPP,'|'), 'matched') as 'August On Programme',
	IF(srt.P1_bal!=prt.P1_bal, CONCAT('+&pound;',srt.P1_bal,'|+&pound;',prt.P1_bal,'|'), 'matched') as 'August Balancing Payment',
	IF(srt.P1_ach!=prt.P1_ach, CONCAT('+&pound;',srt.P1_ach,'|+&pound;',prt.P1_ach,'|'), 'matched') as 'August Achievement',
	IF(srt.P2_OPP!=prt.P2_OPP, CONCAT('+&pound;',srt.P2_OPP,'|+&pound;',prt.P2_OPP,'|'), 'matched') as 'September On Programme',
	IF(srt.P2_bal!=prt.P2_bal, CONCAT('+&pound;',srt.P2_bal,'|+&pound;',prt.P2_bal,'|'), 'matched') as 'September Balancing Payment',
	IF(srt.P2_ach!=prt.P2_ach, CONCAT('+&pound;',srt.P2_ach,'|+&pound;',prt.P2_ach,'|'), 'matched') as 'September Achievement',
	IF(srt.P3_OPP!=prt.P3_OPP, CONCAT('+&pound;',srt.P3_OPP,'|+&pound;',prt.P3_OPP,'|'), 'matched') as 'October On Programme',
	IF(srt.P3_bal!=prt.P3_bal, CONCAT('+&pound;',srt.P3_bal,'|+&pound;',prt.P3_bal,'|'), 'matched') as 'October Balancing Payment',
	IF(srt.P3_ach!=prt.P3_ach, CONCAT('+&pound;',srt.P3_ach,'|+&pound;',prt.P3_ach,'|'), 'matched') as 'October Achievement',
	IF(srt.P4_OPP!=prt.P4_OPP, CONCAT('+&pound;',srt.P4_OPP,'|+&pound;',prt.P4_OPP,'|'), 'matched') as 'November On Programme',
	IF(srt.P4_bal!=prt.P4_bal, CONCAT('+&pound;',srt.P4_bal,'|+&pound;',prt.P4_bal,'|'), 'matched') as 'November Balancing Payment',
	IF(srt.P4_ach!=prt.P4_ach, CONCAT('+&pound;',srt.P4_ach,'|+&pound;',prt.P4_ach,'|'), 'matched') as 'November Achievement',
	IF(srt.P5_OPP!=prt.P5_OPP, CONCAT('+&pound;',srt.P5_OPP,'|+&pound;',prt.P5_OPP,'|'), 'matched') as 'December On Programme',
	IF(srt.P5_bal!=prt.P5_bal, CONCAT('+&pound;',srt.P5_bal,'|+&pound;',prt.P5_bal,'|'), 'matched') as 'December Balancing Payment',
	IF(srt.P5_ach!=prt.P5_ach, CONCAT('+&pound;',srt.P5_ach,'|+&pound;',prt.P5_ach,'|'), 'matched') as 'December Achievement',
	IF(srt.P6_OPP!=prt.P6_OPP, CONCAT('+&pound;',srt.P6_OPP,'|+&pound;',prt.P6_OPP,'|'), 'matched') as 'January On Programme',
	IF(srt.P6_bal!=prt.P6_bal, CONCAT('+&pound;',srt.P6_bal,'|+&pound;',prt.P6_bal,'|'), 'matched') as 'January Balancing Payment',
	IF(srt.P6_ach!=prt.P6_ach, CONCAT('+&pound;',srt.P6_ach,'|+&pound;',prt.P6_ach,'|'), 'matched') as 'January Achievement',
	IF(srt.P7_OPP!=prt.P7_OPP, CONCAT('+&pound;',srt.P7_OPP,'|+&pound;',prt.P7_OPP,'|'), 'matched') as 'February On Programme',
	IF(srt.P7_bal!=prt.P7_bal, CONCAT('+&pound;',srt.P7_bal,'|+&pound;',prt.P7_bal,'|'), 'matched') as 'February Balancing Payment',
	IF(srt.P7_ach!=prt.P7_ach, CONCAT('+&pound;',srt.P7_ach,'|+&pound;',prt.P7_ach,'|'), 'matched') as 'February Achievement',
	IF(srt.P8_OPP!=prt.P8_OPP, CONCAT('+&pound;',srt.P8_OPP,'|+&pound;',prt.P8_OPP,'|'), 'matched') as 'March On Programme',
	IF(srt.P8_bal!=prt.P8_bal, CONCAT('+&pound;',srt.P8_bal,'|+&pound;',prt.P8_bal,'|'), 'matched') as 'March Balancing Payment',
	IF(srt.P8_ach!=prt.P8_ach, CONCAT('+&pound;',srt.P8_ach,'|+&pound;',prt.P8_ach,'|'), 'matched') as 'March Achievement',
	IF(srt.P9_OPP!=prt.P9_OPP, CONCAT('+&pound;',srt.P9_OPP,'|+&pound;',prt.P9_OPP,'|'), 'matched') as 'April On Programme',
	IF(srt.P9_bal!=prt.P9_bal, CONCAT('+&pound;',srt.P9_bal,'|+&pound;',prt.P9_bal,'|'), 'matched') as 'April Balancing Payment',
	IF(srt.P9_ach!=prt.P9_ach, CONCAT('+&pound;',srt.P9_ach,'|+&pound;',prt.P9_ach,'|'), 'matched') as 'April Achievement',
	IF(srt.P10_OPP!=prt.P10_OPP, CONCAT('+&pound;',srt.P10_OPP,'|+&pound;',prt.P10_OPP,'|'), 'matched') as 'May On Programme',
	IF(srt.P10_bal!=prt.P10_bal, CONCAT('+&pound;',srt.P10_bal,'|+&pound;',prt.P10_bal,'|'), 'matched') as 'May Balancing Payment',
	IF(srt.P10_ach!=prt.P10_ach, CONCAT('+&pound;',srt.P10_ach,'|+&pound;',prt.P10_ach,'|'), 'matched') as 'May Achievement',
	IF(srt.P11_OPP!=prt.P11_OPP, CONCAT('+&pound;',srt.P11_OPP,'|+&pound;',prt.P11_OPP,'|'), 'matched') as 'June On Programme',
	IF(srt.P11_bal!=prt.P11_bal, CONCAT('+&pound;',srt.P11_bal,'|+&pound;',prt.P11_bal,'|'), 'matched') as 'June Balancing Payment',
	IF(srt.P11_ach!=prt.P11_ach, CONCAT('+&pound;',srt.P11_ach,'|+&pound;',prt.P11_ach,'|'), 'matched') as 'June Achievement',
	IF(srt.P12_OPP!=prt.P12_OPP, CONCAT('+&pound;',srt.P12_OPP,'|+&pound;',prt.P12_OPP,'|'), 'matched') as 'July On Programme',
	IF(srt.P12_bal!=prt.P12_bal, CONCAT('+&pound;',srt.P12_bal,'|+&pound;',prt.P12_bal,'|'), 'matched') as 'July Balancing Payment',
	IF(srt.P12_ach!=prt.P12_ach, CONCAT('+&pound;',srt.P12_ach,'|+&pound;',prt.P12_ach,'|'), 'matched') as 'July Achievement',
	IF(srt.grand_total!=prt.grand_total, CONCAT('+&pound;',srt.grand_total,'|+&pound;',prt.grand_total,'|'), 'matched') as 'Grand Total'

FROM
	rpt_reconciler_sunesis AS srt
	LEFT JOIN rpt_reconciler_pfr AS prt
	ON (prt.A09 = srt.A09
		# AND prt.L03 = srt.L03
		AND prt.ULN = srt.ULN
			AND prt.import_id = srt.import_id)
WHERE
	srt.import_id = {$_REQUEST['impid']}

HEREDOC;


		if (isset($_REQUEST['rid']) ) {
			$sql .= ' AND srt.id = '.$_REQUEST['rid'];
		}

		if(isset($_REQUEST['mid']) && $_REQUEST['mid'] != '' && isset($_REQUEST['type']) ) {
			//sunesis
			if($_REQUEST['type'] == 's') {
				// build sql for appearing only in pfr or sunesis
				$sql = <<<HEREDOC

		SELECT

	srt.course_name as 'Header Course Name',
	srt.provider_name as 'Header Provider Name',
	srt.name as 'Header Learner Name' ,
	srt.A09 as 'Header sunesis_a09',
	srt.L03 as 'Header sunesis_l03',
	IF( srt.learner_start_date != '', CONCAT('+',srt.learner_start_date,'|+&nbsp;|'), 'matched') as 'Learning Start Date',
	IF(srt.learner_target_end_date!='', CONCAT('+',srt.learner_target_end_date,'|+&nbsp;|'), 'matched')  as 'Learning Planned End Date',
	IF(srt.learner_end_date!='', CONCAT('+',srt.learner_end_date,'|+&nbsp;|'), 'matched') as 'Learning Actual End Date',
	IF(srt.entry_end_date!='', CONCAT('+',srt.entry_end_date,'|+&nbsp;|'), 'matched') as 'Achievement Date',
	IF(srt.P1_OPP>0, CONCAT('+&pound;',srt.P1_OPP,'|+&nbsp;|'), 'matched') as 'August On Programme',
	IF(srt.P1_bal>0, CONCAT('+&pound;',srt.P1_bal,'|+&nbsp;|'), 'matched') as 'August Balancing Payment',
	IF(srt.P1_ach>0, CONCAT('+&pound;',srt.P1_ach,'|+&nbsp;|'), 'matched') as 'August Achievement',
	IF(srt.P2_OPP>0, CONCAT('+&pound;',srt.P2_OPP,'|+&nbsp;|'), 'matched') as 'September On Programme',
	IF(srt.P2_bal>0, CONCAT('+&pound;',srt.P2_bal,'|+&nbsp;|'), 'matched') as 'September Balancing Payment',
	IF(srt.P2_ach>0, CONCAT('+&pound;',srt.P2_ach,'|+&nbsp;|'), 'matched') as 'September Achievement',
	IF(srt.P3_OPP>0, CONCAT('+&pound;',srt.P3_OPP,'|+&nbsp;|'), 'matched') as 'October On Programme',
	IF(srt.P3_bal>0, CONCAT('+&pound;',srt.P3_bal,'|+&nbsp;|'), 'matched') as 'October Balancing Payment',
	IF(srt.P3_ach>0, CONCAT('+&pound;',srt.P3_ach,'|+&nbsp;|'), 'matched') as 'October Achievement',
	IF(srt.P4_OPP>0, CONCAT('+&pound;',srt.P4_OPP,'|+&nbsp;|'), 'matched') as 'November On Programme',
	IF(srt.P4_bal>0, CONCAT('+&pound;',srt.P4_bal,'|+&nbsp;|'), 'matched') as 'November Balancing Payment',
	IF(srt.P4_ach>0, CONCAT('+&pound;',srt.P4_ach,'|+&nbsp;|'), 'matched') as 'November Achievement',
	IF(srt.P5_OPP>0, CONCAT('+&pound;',srt.P5_OPP,'|+&nbsp;|'), 'matched') as 'December On Programme',
	IF(srt.P5_bal>0, CONCAT('+&pound;',srt.P5_bal,'|+&nbsp;|'), 'matched') as 'December Balancing Payment',
	IF(srt.P5_ach>0, CONCAT('+&pound;',srt.P5_ach,'|+&nbsp;|'), 'matched') as 'December Achievement',
	IF(srt.P6_OPP>0, CONCAT('+&pound;',srt.P6_OPP,'|+&nbsp;|'), 'matched') as 'January On Programme',
	IF(srt.P6_bal>0, CONCAT('+&pound;',srt.P6_bal,'|+&nbsp;|'), 'matched') as 'January Balancing Payment',
	IF(srt.P6_ach>0, CONCAT('+&pound;',srt.P6_ach,'|+&nbsp;|'), 'matched') as 'January Achievement',
	IF(srt.P7_OPP>0, CONCAT('+&pound;',srt.P7_OPP,'|+&nbsp;|'), 'matched') as 'February On Programme',
	IF(srt.P7_bal>0, CONCAT('+&pound;',srt.P7_bal,'|+&nbsp;|'), 'matched') as 'February Balancing Payment',
	IF(srt.P7_ach>0, CONCAT('+&pound;',srt.P7_ach,'|+&nbsp;|'), 'matched') as 'February Achievement',
	IF(srt.P8_OPP>0, CONCAT('+&pound;',srt.P8_OPP,'|+&nbsp;|'), 'matched') as 'March On Programme',
	IF(srt.P8_bal>0, CONCAT('+&pound;',srt.P8_bal,'|+&nbsp;|'), 'matched') as 'March Balancing Payment',
	IF(srt.P8_ach>0, CONCAT('+&pound;',srt.P8_ach,'|+&nbsp;|'), 'matched') as 'March Achievement',
	IF(srt.P9_OPP>0, CONCAT('+&pound;',srt.P9_OPP,'|+&nbsp;|'), 'matched') as 'April On Programme',
	IF(srt.P9_bal>0, CONCAT('+&pound;',srt.P9_bal,'|+&nbsp;|'), 'matched') as 'April Balancing Payment',
	IF(srt.P9_ach>0, CONCAT('+&pound;',srt.P9_ach,'|+&nbsp;|'), 'matched') as 'April Achievement',
	IF(srt.P10_OPP>0, CONCAT('+&pound;',srt.P10_OPP,'|+&nbsp;|'), 'matched') as 'May On Programme',
	IF(srt.P10_bal>0, CONCAT('+&pound;',srt.P10_bal,'|+&nbsp;|'), 'matched') as 'May Balancing Payment',
	IF(srt.P10_ach>0, CONCAT('+&pound;',srt.P10_ach,'|+&nbsp;|'), 'matched') as 'May Achievement',
	IF(srt.P11_OPP>0, CONCAT('+&pound;',srt.P11_OPP,'|+&nbsp;|'), 'matched') as 'June On Programme',
	IF(srt.P11_bal>0, CONCAT('+&pound;',srt.P11_bal,'|+&nbsp;|'), 'matched') as 'June Balancing Payment',
	IF(srt.P11_ach>0, CONCAT('+&pound;',srt.P11_ach,'|+&nbsp;|'), 'matched') as 'June Achievement',
	IF(srt.P12_OPP>0, CONCAT('+&pound;',srt.P12_OPP,'|+&nbsp;|'), 'matched') as 'July On Programme',
	IF(srt.P12_bal>0, CONCAT('+&pound;',srt.P12_bal,'|+&nbsp;|'), 'matched') as 'July Balancing Payment',
	IF(srt.P12_ach>0, CONCAT('+&pound;',srt.P12_ach,'|+&nbsp;|'), 'matched') as 'July Achievement',
	IF(srt.grand_total>0, CONCAT('+&pound;',srt.grand_total,'|+&nbsp;|'), 'matched') as 'Grand Total'

FROM
	rpt_reconciler_sunesis AS srt
WHERE
	srt.import_id = {$_REQUEST['impid']}
	AND srt.id = {$_REQUEST['mid']}
HEREDOC;
			}
			//pfr
			elseif($_REQUEST['type'] == 'p') {
				$sql = <<<HEREDOC

		SELECT

	srt.course_name as 'Header Course Name',
	srt.provider_name as 'Header Provider Name',
	srt.name as 'Header Learner Name' ,
	srt.A09 as 'Header sunesis_a09',
	srt.L03 as 'Header sunesis_l03',
	IF( srt.learner_start_date != '', CONCAT('+&nbsp;|+',srt.learner_start_date,'|'), 'matched') as 'Learning Start Date',
	IF(srt.learner_target_end_date!='', CONCAT('+&nbsp;|+',srt.learner_target_end_date,'|'), 'matched')  as 'Learning Planned End Date',
	IF(srt.learner_end_date!='', CONCAT('+&nbsp;|+',srt.learner_end_date,'|'), 'matched') as 'Learning Actual End Date',
	IF(srt.entry_end_date!='', CONCAT('+&nbsp;|+',srt.entry_end_date,'|'), 'matched') as 'Achievement Date',
	IF(srt.P1_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P1_OPP,'|'), 'matched') as 'August On Programme',
	IF(srt.P1_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P1_bal,'|'), 'matched') as 'August Balancing Payment',
	IF(srt.P1_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P1_ach,'|'), 'matched') as 'August Achievement',
	IF(srt.P2_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P2_OPP,'|'), 'matched') as 'September On Programme',
	IF(srt.P2_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P2_bal,'|'), 'matched') as 'September Balancing Payment',
	IF(srt.P2_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P2_ach,'|'), 'matched') as 'September Achievement',
	IF(srt.P3_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P3_OPP,'|'), 'matched') as 'October On Programme',
	IF(srt.P3_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P3_bal,'|'), 'matched') as 'October Balancing Payment',
	IF(srt.P3_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P3_ach,'|'), 'matched') as 'October Achievement',
	IF(srt.P4_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P4_OPP,'|'), 'matched') as 'November On Programme',
	IF(srt.P4_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P4_bal,'|'), 'matched') as 'November Balancing Payment',
	IF(srt.P4_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P4_ach,'|'), 'matched') as 'November Achievement',
	IF(srt.P5_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P5_OPP,'|'), 'matched') as 'December On Programme',
	IF(srt.P5_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P5_bal,'|'), 'matched') as 'December Balancing Payment',
	IF(srt.P5_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P5_ach,'|'), 'matched') as 'December Achievement',
	IF(srt.P6_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P6_OPP,'|'), 'matched') as 'January On Programme',
	IF(srt.P6_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P6_bal,'|'), 'matched') as 'January Balancing Payment',
	IF(srt.P6_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P6_ach,'|'), 'matched') as 'January Achievement',
	IF(srt.P7_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P7_OPP,'|'), 'matched') as 'February On Programme',
	IF(srt.P7_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P7_bal,'|'), 'matched') as 'February Balancing Payment',
	IF(srt.P7_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P7_ach,'|'), 'matched') as 'February Achievement',
	IF(srt.P8_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P8_OPP,'|'), 'matched') as 'March On Programme',
	IF(srt.P8_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P8_bal,'|'), 'matched') as 'March Balancing Payment',
	IF(srt.P8_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P8_ach,'|'), 'matched') as 'March Achievement',
	IF(srt.P9_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P9_OPP,'|'), 'matched') as 'April On Programme',
	IF(srt.P9_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P9_bal,'|'), 'matched') as 'April Balancing Payment',
	IF(srt.P9_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P9_ach,'|'), 'matched') as 'April Achievement',
	IF(srt.P10_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P10_OPP,'|&nbsp;|'), 'matched') as 'May On Programme',
	IF(srt.P10_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P10_bal,'|&nbsp;|'), 'matched') as 'May Balancing Payment',
	IF(srt.P10_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P10_ach,'|&nbsp;|'), 'matched') as 'May Achievement',
	IF(srt.P11_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P11_OPP,'|&nbsp;|'), 'matched') as 'June On Programme',
	IF(srt.P11_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P11_bal,'|&nbsp;|'), 'matched') as 'June Balancing Payment',
	IF(srt.P11_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P11_ach,'|&nbsp;|'), 'matched') as 'June Achievement',
	IF(srt.P12_OPP>0, CONCAT('+&nbsp;|+&pound;',srt.P12_OPP,'|&nbsp;|'), 'matched') as 'July On Programme',
	IF(srt.P12_bal>0, CONCAT('+&nbsp;|+&pound;',srt.P12_bal,'|&nbsp;|'), 'matched') as 'July Balancing Payment',
	IF(srt.P12_ach>0, CONCAT('+&nbsp;|+&pound;',srt.P12_ach,'|&nbsp;|'), 'matched') as 'July Achievement',
	IF(srt.grand_total>0, CONCAT('+&nbsp;|+&pound;',srt.grand_total,'|'), 'matched') as 'Grand Total'

FROM
	rpt_reconciler_pfr AS srt
WHERE
	srt.import_id = {$_REQUEST['impid']}
	AND srt.id = {$_REQUEST['mid']}
HEREDOC;
				$location_name = 'PFR File';
			}
		}

		// $sql .= " GROUP BY srt.A09, srt.L03";

		$reconciler_record_detail = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);



		echo '<div style="font-size: 1.1em;" >'.$reconciler_record_detail[0]['Header Learner Name'].'<br/>'.$reconciler_record_detail[0]['Header sunesis_l03'].'</div>';
		echo '<div style="text-align:right; color: #9e9e9e; font-size: 0.9em;" >'.$reconciler_record_detail[0]['Header Provider Name'].'<br/>'.$reconciler_record_detail[0]['Header Course Name'].'</div>';
		echo '<br/>';
		echo '<table class="resultset" cellpadding="6" >';
		echo '<tr><th>Data Column</th><th>Sunesis Value</th><th>PFR Value</th></tr>';
		foreach ( $reconciler_record_detail[0] as $title => $data ) {

			if ( !preg_match('/^Header(.*)/', $title ) && $data != 'matched' ) {
				if ( $title == 'Grand Total' ) {
					$data = preg_replace('/\+/', '<th style="text-align: right">', $data);
					$data = preg_replace('/\|/', '</th>', $data);
					echo '<tfoot><tr><th>'.$title.'</th>'.$data.'</tr></tfoot>';
				}
				else {
					$data = preg_replace('/\+/', '<td>', $data);
					$data = preg_replace('/\|/', '</td>', $data);
					echo '<tr><td style="text-align: left" >'.$title.'</td>'.$data.'</tr>';
				}
			}
		}
		echo '</table>';
		// echo '<table class="resultset" cellpadding="6" >';
		// echo $reconciler_record_detail[0]['record_issue'];
		// echo '</table>';
		exit;
	}
}
