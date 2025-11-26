<?php
class ViewALS extends View
{

	public static function getInstance(PDO $link, $id)
	{
		$where = '';

		// Create new view object
		$sql = <<<HEREDOC
SELECT DISTINCT
	als.id as id,
	als.tr_id as tr_id,
	als.outcome_date as outcome_date,
	case outcome when 1 then 'Completed ALS Support Plan' when 2 then 'ALS Plan Completed - No funding required' when 3 then 'No ALS support required' end as outcome,
	case referred_by when 1 then 'Learner' when 2 then 'Assessor' when 3 then 'Employer' end as referred_by,
	als.referral_date as referral_date,
	reason as reason
FROM
	als
WHERE tr_id='$id' $where
;
HEREDOC;

		$view = new ViewALS();
		$view->setSQL($sql);


		return $view;
	}


	public function render(PDO $link)
	{
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

			echo '<thead><tr>';
			echo '<th class="topRow">&nbsp;</th><th  class="topRow">Referral Date</th><th  class="topRow">Referred By</th><th  class="topRow">Outcome Date</th><th  class="topRow">Outcome</th><th  class="topRow">Reason</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				//$exam_subtype = "";
				//if($row['exam_subtype'] != '')
				//	$exam_subtype = $row['exam_subtype'] == '1'?'Paper Based':'Web Based';
				if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
					echo '<tr>';
				else
					echo HTML::viewrow_opening_tag('/do.php?_action=edit_als&als_id=' . $row['id'] . '&tr_id=' . $row['tr_id']);
				echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/exam.png\" border=\"0\" alt=\"\" /></td>";
				echo '<td align="left">' . HTML::cell($row['referral_date']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['referred_by']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['outcome_date']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['outcome']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['reason']) . '</td>';
				echo '</tr>';

			}

			echo '</tbody></table></div>';

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}

}
?>