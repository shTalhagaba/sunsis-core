<?php
class ViewLearnerExamResults extends View
{

	public static function getInstance(PDO $link, $id)
	{
		$where = '';

		// Create new view object
		$sql = <<<HEREDOC
SELECT DISTINCT
	exam_results.*
FROM
	exam_results
WHERE tr_id='$id' $where
;
HEREDOC;

		$view = new ViewLearnerExamResults();
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
			echo '<th class="topRow">&nbsp;</th><th  class="topRow">Qualification</th><th  class="topRow">Unit Reference</th><th  class="topRow">Unit Title</th><th  class="topRow">Exam Taken</th><th  class="topRow">Exam Type</th><th  class="topRow">Exam Booked Date</th><th  class="topRow">Exam Date</th><th  class="topRow">Attempt No.</th><th  class="topRow">Exam Result</th><th  class="topRow">Result Date</th><th  class="topRow">Comments</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				$exam_subtype = "";
				if($row['exam_subtype'] != '')
					$exam_subtype = $row['exam_subtype'] == '1'?'Paper Based':'Web Based';
				if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
					echo '<tr>';
				else
					echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_exam_result&exam_result_id=' . $row['id'] . '&tr_id=' . $row['tr_id']);
				echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/exam.png\" border=\"0\" alt=\"\" /></td>";
				echo '<td align="left">' . HTML::cell($row['qualification_title']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['unit_reference']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['unit_title']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['exam_type']==1?'Actual Exam':'Mock Exam') . '</td>';
				echo '<td align="left">' . HTML::cell($exam_subtype) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toShort($row['exam_booked_date'])) . '</td>';
				echo '<td align="left">' . HTML::cell(Date::toShort($row['exam_date'])) . '</td>';
				echo '<td align="center">' . HTML::cell($row['attempt_no']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['exam_result']) . '</td>';
				echo '<td align="center">' . HTML::cell(Date::toShort($row['result_date'])) . '</td>';
				echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['comments']) . '</td>';
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