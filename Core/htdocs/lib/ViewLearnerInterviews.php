<?php
class ViewLearnerInterviews extends View
{

	public static function getInstance(PDO $link, $id)
	{
			// Create new view object
			$sql = <<<HEREDOC
SELECT
	interviews.id AS interview_id,
	interviews.interview_date,
	interviews.interview_start_time,
	interviews.interview_end_time,
	(SELECT description FROM lookup_interview_types WHERE id = interviews.interview_type) AS interview_type,
	(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = interviews.interviewer) AS interviewer,
	(SELECT description FROM lookup_interview_status WHERE id = interviews.interview_status) AS interview_status,
	interviews.interview_rgb_status,
	(SELECT description FROM lookup_interview_paperwork WHERE id = interviews.interview_paperwork) AS interview_paperwork,
	#(SELECT title FROM modules WHERE id = interviews.interview_module) AS interview_module,
	interviews.interview_comments,
	interviews.tr_id,
	interviews.created
FROM
	interviews
WHERE tr_id='$id';
HEREDOC;

		$view = new ViewLearnerInterviews();
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
			echo '<th>Actions</th><th></th><th>Date</th><th>Start Time</th><th>End Time</th><th>Type</th><th>Assessor/Interviewer</th><th>Status</th><th>G</th><th>Y</th><th>R</th><th>Paperwork</th><th>Created</th><th>Comments</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr>';
				echo '<td rowspan = "2" align="center">&nbsp;&nbsp;&nbsp;<span class="button" onclick="window.location.href=\'do.php?_action=send_interview_email&tr_id=' . $row['tr_id'] . '&interview_id=' . $row['interview_id'] . '\';">Email</span>&nbsp;&nbsp;&nbsp;<span onclick="fetchAndOpenLog(' . $row['interview_id'] . ');" class="button">View Log</span></td>';
				echo '</tr>';

				echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_interview&interview_id=' . $row['interview_id'] . '&tr_id=' . $row['tr_id']);
				echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/interview-icon.png\" border=\"0\" alt=\"\" /></td>";
				echo '<td align="left">' . HTML::cell(Date::toShort($row['interview_date'])) . '</td>';
				echo '<td align="left">' . HTML::cell($row['interview_start_time']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['interview_end_time']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['interview_type']) . '</td>';
				echo '<td align="left">' . HTML::cell($row['interviewer']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['interview_status']) . '</td>';
				switch($row['interview_rgb_status'])
				{
					case 'green':
						echo '<td align="center" class="greend" width="32"></td>';
						echo '<td align="center" class="yellowl" width="32"></td>';
						echo '<td align="center" class="redl" width="32"></td>';
						break;
					case 'yellow':
						echo '<td align="center" class="greenl" width="32"></td>';
						echo '<td align="center" class="yellowd" width="32"></td>';
						echo '<td align="center" class="redl" width="32"></td>';
						break;
					case 'red':
						echo '<td align="center" class="greenl" width="32"></td>';
						echo '<td align="center" class="yellowl" width="32"></td>';
						echo '<td align="center" class="redd" width="32"></td>';
						break;
					default:
						echo '<td align="center" class="greenl" width="32"></td>';
						echo '<td align="center" class="yellowl" width="32"></td>';
						echo '<td align="center" class="redl" width="32"></td>';
						break;
				}
				echo '<td align="center">' . HTML::cell($row['interview_paperwork']) . '</td>';
				//echo '<td align="center">' . HTML::cell($row['interview_module']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['created']) . '</td>';
				echo '<td align="center">' . HTML::cell($row['interview_comments']) . '</td>';
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