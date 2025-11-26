<?php
class ViewScottishPayments extends View
{

	public static function getInstance(PDO $link, $training_id)
	{
		$training_record = TrainingRecord::loadFromDatabase($link, $training_id);
		$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = " . $training_id);
		$course = Course::loadFromDatabase($link, $course_id);
		$framework = Framework::loadFromDatabase($link, $course->framework_id);
		$age = substr(Date::dateDiff($training_record->start_date, $training_record->dob, 3),0,2);

		$where = "WHERE frameworks.`id` = " . $framework->id;

/*		if($age >= 16 && $age <= 19)
			$where .= " AND fwrk_scottish_funding.`description` LIKE '16_19%'";
		elseif($age >= 20 && $age <= 24)
			$where .= " AND fwrk_scottish_funding.`description` LIKE '20_24%'";
		elseif($age >= 25)
			$where .= " AND fwrk_scottish_funding.`description` LIKE '25_Plus%'";*/

		// Create new view object
		$sql = <<<HEREDOC
SELECT
  frameworks.id,
  frameworks.milestones
FROM
  frameworks

$where
;

HEREDOC;

		$view = new ViewScottishPayments();
		$view->setSQL($sql);


		return $view;
	}


	public function render(PDO $link, $tr_id)
	{
		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
		$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = " . $tr_id);
		$course = Course::loadFromDatabase($link, $course_id);
		$framework = Framework::loadFromDatabase($link, $course->framework_id);

		if($training_record->dob == '' || is_null($training_record->dob))
		{
			echo 'Learner\'s Date of Birth is blank, please enter the date of birth to populate this tab.';
			return;
		}
		$age = substr(Date::dateDiff($training_record->start_date, $training_record->dob, 3),0,2);
		if($age < 16)
		{
			echo 'Learner\'s age is less than 16.';
			return;
		}
		$start_payment = '';
		$outcome_payment = '';

		$st = $link->query($this->getSQL());
		if($st)
		{
			echo '<div align="center">';
			echo '<p><table class = "resultset" align="center"><caption><strong>Summary</strong></caption><thead><th width="100">Total to Claim &pound;</th><th width="100">Paid &pound; </th><th width="100">Due &pound;</th></thead>';
			echo '<tbody><tr><td class="summary_claimed" align="center"></td><td class="summary_paid" align="center"></td><td class="summary_due" align="center"></td></tr></tboby></table></p>';
			echo '</div>';
			echo '<div align="left">';
			echo '<form id="learner_scottish_funding_grid" name="learner_scottish_funding_grid" action="' . $_SERVER['PHP_SELF'] . '" method="post">';
			echo '<input type="hidden" name="fwrk_id" value="' . $framework->id . '" />';
			echo '<input type="hidden" name="tr_id" value="' . $tr_id . '" />';
			echo '<input type="hidden" name="_action" value="save_learner_scottish_funding"/>';

			echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr>';
			echo '<th></th><th>Type of Payment</th><th>Due Date</th><th>Milestones Completion Date</th><th>Claimed Amount</th><th>Claim Date</th><th>Date Paid</th><th>Paid Amount</th><th>Balance</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				$SP_stored = DAO::getResultset($link, "SELECT scottish_payments.* FROM scottish_payments WHERE scottish_payments.payment_type = 'SP' AND scottish_payments.fwrk_id = " . $row['id'] . " AND scottish_payments.tr_id = " . $tr_id, DAO::FETCH_ASSOC);
				if(isset($SP_stored) && count($SP_stored) > 0)
					$SP_stored = $SP_stored[0];
				else
				{
					$SP_stored['due_date'] = NULL;
					$SP_stored['milestones_completion_date'] = NULL;
					$SP_stored['doc_sent_date'] = NULL;
					$SP_stored['date_paid'] = NULL;
					$SP_stored['amount_received'] = NULL;
				}
				$OP_stored = DAO::getResultset($link, "SELECT scottish_payments.* FROM scottish_payments WHERE scottish_payments.payment_type = 'OP' AND scottish_payments.fwrk_id = " . $row['id'] . " AND scottish_payments.tr_id = " . $tr_id, DAO::FETCH_ASSOC);
				if(isset($OP_stored) && count($OP_stored) > 0)
					$OP_stored = $OP_stored[0];
				else
				{
					$OP_stored['due_date'] = NULL;
					$OP_stored['milestones_completion_date'] = NULL;
					$OP_stored['doc_sent_date'] = NULL;
					$OP_stored['date_paid'] = NULL;
					$OP_stored['amount_received'] = NULL;
				}
				if($age >= 16 && $age <= 19)
				{
					$start_payment = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_SP' AND fwrk_id = " . $row['id']);
					$outcome_payment = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_OP' AND fwrk_id = " . $row['id']);
				}
				elseif($age >= 20 && $age <= 24)
				{
					$start_payment = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_SP' AND fwrk_id = " . $row['id']);
					$outcome_payment = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_OP' AND fwrk_id = " . $row['id']);
				}
				elseif($age >= 25)
				{
					$start_payment = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus_SP' AND fwrk_id = " . $row['id']);
					$outcome_payment = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus_OP' AND fwrk_id = " . $row['id']);
				}

				if($age <= 19)
				{
					echo '<tr>';
					echo "<td align='center' style='border-right-style: solid;'> <img height='50' width='50' src=\"/images/pound-sign.png\" border=\"0\" alt=\"\" /></td>";
					echo '<td align="left">' . HTML::cell("Start Payment") . '</td>';
					echo '<td align="left">' . HTML::datebox('SP_due_date', $SP_stored['due_date'], false) . '</td>';
					//echo '<td align="left">' . HTML::datebox('milestones_completion_date', $SP_stored['milestones_completion_date'], true) . '</td>';
					echo '<td align="left">' . HTML::cell('') . '</td>';
					echo '<td class="clsSPClaimed" align="left">' . HTML::cell('£ ' . $start_payment) . '</td>';
					echo '<td align="left">' . HTML::datebox('SP_doc_sent_date', $SP_stored['doc_sent_date'], false) . '</td>';
					echo '<td class="clsSPPaidDate" align="left">' . HTML::datebox('SP_date_paid', $SP_stored['date_paid'], false) . '</td>';
					echo '<td align="left">' . HTML::textbox('SP_amount_received', $SP_stored['amount_received'], 'size="5"') . '</td>';
					echo '<td class="clsSPBalance" align="left">' . HTML::cell(floatval($start_payment) - floatval($SP_stored['amount_received'])) . '</td>';
					echo '</tr>';
				}
				$total_milestones_claimed = 0;
				$total_milestones_paid = 0;
				for($a=1; $a <= $row['milestones']; $a++)
				{
					$MP_stored = DAO::getResultset($link, "SELECT scottish_payments.* FROM scottish_payments WHERE scottish_payments.payment_type = 'MP" . $a . "' AND scottish_payments.fwrk_id = " . $row['id'] . " AND scottish_payments.tr_id = " . $tr_id, DAO::FETCH_ASSOC);
					if(isset($MP_stored) && count($MP_stored) > 0)
						$MP_stored = $MP_stored[0];
					else
					{
						$MP_stored['due_date'] = NULL;
						$MP_stored['milestones_completion_date'] = NULL;
						$MP_stored['doc_sent_date'] = NULL;
						$MP_stored['date_paid'] = NULL;
						$MP_stored['amount_received'] = NULL;
					}
					echo '<tr>';
					echo "<td align='center' style='border-right-style: solid;'> <img height='50' width='50' src=\"/images/pound-sign.png\" border=\"0\" alt=\"\" /></td>";
					echo '<td align="left">' . HTML::cell("Milestone " . $a) . '</td>';
					echo '<td align="left">' . HTML::datebox('MP_due_date_'.$a, $MP_stored['due_date'], false) . '</td>';
					echo '<td align="left">' . HTML::datebox('MP_milestones_completion_date_'.$a, $MP_stored['milestones_completion_date'], false) . '</td>';
					if($age >= 16 && $age <= 19)
						$sql = "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_MP_" . $a . "' AND fwrk_id = " . $row['id'];
					elseif($age >= 20 && $age <= 24)
						$sql = "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_MP_" . $a . "' AND fwrk_id = " . $row['id'];
					elseif($age >= 25)
						$sql = "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus_MP_" . $a . "' AND fwrk_id = " . $row['id'];
					$milestone_payment = DAO::getSingleValue($link, $sql);
					echo '<td class="clsMPClaimed" align="left">' . HTML::cell('£ ' . $milestone_payment) . '</td>';
					echo '<td align="left">' . HTML::datebox('MP_doc_sent_date_'.$a, $MP_stored['doc_sent_date'], false) . '</td>';
					echo '<td class="clsMPPaidDate" align="left">' . HTML::datebox('MP_date_paid_'.$a, $MP_stored['date_paid'], false) . '</td>';
					echo '<td align="left">' . HTML::textbox('MP_amount_received_'.$a, $MP_stored['amount_received'], 'size="5"') . '</td>';
					echo '<td class="clsMPBalance" align="left">&pound; ' . HTML::cell(floatval($milestone_payment) - floatval($MP_stored['amount_received'])) . '</td>';
					echo '</tr>';
					$total_milestones_claimed += $milestone_payment;
					$total_milestones_paid += $MP_stored['amount_received'];
				}
				echo '<tr>';
				echo "<td align='center' style='border-right-style: solid;'> <img height='50' width='50' src=\"/images/pound-sign.png\" border=\"0\" alt=\"\" /></td>";
				echo '<td align="left">' . HTML::cell("Outcome Payment") . '</td>';
				echo '<td align="left">' . HTML::datebox('OP_due_date', $OP_stored['due_date'], false) . '</td>';
				echo '<td align="left">' . HTML::cell('') . '</td>';
				echo '<td class="clsOPClaimed" align="left">' . HTML::cell('£ ' . $outcome_payment) . '</td>';
				echo '<td align="left">' . HTML::datebox('OP_doc_sent_date', $OP_stored['doc_sent_date'], false) . '</td>';
				echo '<td class="clsOPPaidDate" align="left">' . HTML::datebox('OP_date_paid', $OP_stored['date_paid'], false) . '</td>';
				echo '<td align="left">' . HTML::textbox('OP_amount_received', $OP_stored['amount_received'], 'size="5"') . '</td>';
				echo '<td class="clsOPBalance" align="left">' . HTML::cell(floatval($outcome_payment) - floatval($OP_stored['amount_received'])) . '</td>';
				echo '</tr>';
				$total_claimed = $start_payment + $outcome_payment + $total_milestones_claimed;
				$total_paid = $SP_stored['amount_received'] + $OP_stored['amount_received'] + $total_milestones_paid;
				$total_due = $total_claimed - $total_paid;
				echo '<tr>';
				echo "<td align='left'><input id= 'sum_claimed' type='hidden' value='" . $total_claimed . "' /></td>";
				echo '<td align="left"><input id= "sum_paid" type="hidden" value="' . $total_paid . '" /></td>';
				echo '<td align="left"><input id= "sum_due" type="hidden" value="' . $total_due . '" /></td>';
				echo '<td align="left"></td>';
				echo '<td align="left"></td>';
				echo '<td align="left"></td>';
				echo '<td align="left"></td>';
				echo '<td align="left"></td>';
				echo '<td align="left"></td>';
				echo '</tr>';
			}

			echo '</tbody></table>';
			echo '</form></div>';

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}

}
?>