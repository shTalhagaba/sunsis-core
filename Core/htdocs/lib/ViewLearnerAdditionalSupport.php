<?php
class ViewLearnerAdditionalSupport extends View
{

    public static function getInstance(PDO $link, $id)
    {
        $where = '';

        // Create new view object
        $sql = <<<HEREDOC
SELECT
additional_support.*,
((HOUR(TIMEDIFF(time_to, time_from))*60) + (MINUTE(TIMEDIFF(time_to, time_from)))) AS minutes
,concat(users.firstnames, ' ', users.surname) as assessor_name
FROM
	additional_support
left join users on users.id = additional_support.assessor
WHERE tr_id='$id' $where
order by actual_date;
HEREDOC;

        $view = new ViewLearnerAdditionalSupport();
        $view->setSQL($sql);


        return $view;
    }


    public function render(PDO $link,$tr_id)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            $index = 1;
            echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

            echo '<thead><tr>';
            echo '<th class="topRow">&nbsp;</th><th  class="topRow">Time since<br>last session</th><th>Due Date</th><th>Actual Date</th><th>From</th><th>To</th><th>Total Hours</th><th>Subject Area</th><th>Contact Type</th><th>Manager Attendance</th><th>Assessor Name</th><th>Comments</th><th>Adobe Link</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            // $subject_areas = Array("Assessment Plans","Reflective Hours","Functional Skills","Others");
            $subject_areas = InductionHelper::getListSupportSessionsSubjects();
            $contact_types = Array("OLL","Workplace","Telephone");


            while($row = $st->fetch())
            {
                $tr_id = $row['tr_id'];
                if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_additional_support&id=' . $row['id'] . '&tr_id=' . $row['tr_id']);

                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/exam.png\" border=\"0\" alt=\"\" /></td>";

                $actual_date = $row['actual_date'];
                if($index==1)
                    $diff = strtotime($actual_date) - strtotime(DAO::getSingleValue($link, "select start_date from tr where id = '$tr_id'"));
                else // find the difference with subsequent actual date
                    $diff = strtotime($actual_date) - strtotime($prevActualDate);
                if(isset($actual_date) AND $actual_date != "" AND $actual_date != "0000-00-00")
                {
                    $weeks = floor(floor($diff/(60*60*24)) / 7);
                    $days = floor($diff/(60*60*24)) % 7;
                    echo ($days != 0)? "<td>" . HTML::textbox("diff_".$index, $weeks . "w " . $days . "d ", "disabled  size='5'") . "</td>": "<td>" . HTML::textbox("diff_".$index, $weeks . "w", "disabled  size='5'") . "</td>";
                    $prevActualDate = $actual_date;
                }
                else
                {
                    $add_extra = false;
                    echo "<td>" . HTML::textbox("diff_".$index, "", "disabled  size='5'") . "</td>";
                    $prevActualDate = $row['actual_date'];
                }

                $index++;

                echo '<td align="left">' . HTML::cell(Date::toShort($row['due_date'])) . '</td>';
                echo '<td align="left">' . HTML::cell(Date::toShort($row['actual_date'])) . '</td>';
                echo '<td align="center">' . HTML::cell($row['time_from']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['time_to']) . '</td>';
                echo '<td align="center">' . ViewLearnerAdditionalSupport::convertToHoursMins($row['minutes'], '%02d hours %02d minutes') . '</th>';
                echo '<td align="center">' . HTML::cell(isset($subject_areas[$row['subject_area']])?$subject_areas[$row['subject_area']]:"") . '</td>';
                echo '<td align="center">' . HTML::cell(isset($contact_types[$row['contact_type']])?$contact_types[$row['contact_type']]:"") . '</td>';
                echo '<td align="center">' . HTML::cell(($row['manager_attendance']=='true')?"Yes":"No") . '</td>';
                echo '<td align="center">' . HTML::cell($row['assessor_name']) . '</td>';
                echo '<td style="font-size: 11px;">' . HTML::cell($row['comments']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['adobe']) . '</td>';
                echo '</tr>';
            }
            //echo '<tr><td><b>Total Hours</b></td><td><b>'. DAO::getSingleValue($link, "SELECT CONCAT(SUM(HOUR(TIMEDIFF(time_to, time_from))), ' hours and ', SUM(MINUTE(TIMEDIFF(time_to, time_from))),' minutes')  FROM additional_support where tr_id = '$tr_id'")  .'</b></td></tr>';

			$minutes = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(time_to, time_from)))*60) + (SUM(MINUTE(TIMEDIFF(time_to, time_from)))) FROM additional_support WHERE tr_id = '{$tr_id}'");
	        echo '<tr><th colspan="7" align="center">Total Time: ' . $this->convertToHoursMins($minutes, '%02d hours %02d minutes') . '</th></tr>';

            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

	public static function convertToHoursMins($time, $format = '%02d:%02d')
	{
		if ($time < 1)
		{
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		return sprintf($format, $hours, $minutes);
	}

}
?>