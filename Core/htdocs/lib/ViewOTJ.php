<?php
class ViewOTJ extends View
{

    public static function getInstance(PDO $link, $id)
    {

        // Create new view object
        $sql = <<<HEREDOC
SELECT
	otj.*, lookup_otj_types.`description`,TIME_FORMAT(TIMEDIFF(time_to,time_from),"%H") as duration
FROM
	otj
left JOIN lookup_otj_types on lookup_otj_types.id = otj.type
WHERE tr_id='$id'
ORDER BY date
;
HEREDOC;

        $view = new ViewOTJ();
        $view->setSQL($sql);


        return $view;
    }


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left"><table style="width: 70%;" class="resultset" border="0" cellspacing="0" cellpadding="6">';

		$tr_id = '';

            echo '<thead><tr>';
            echo '<th class="topRow">&nbsp;</th><th  class="topRow">Date</th><th  class="topRow">Start Time</th><th  class="topRow">End Time</th><th  class="topRow">Duration</th><th  class="topRow">Type</th><th class="topRow" style="width: 40%;">Comments</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
		$tr_id = $row['tr_id'];
                if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_otj_hours&otj_id=' . $row['id'] . '&tr_id=' . $row['tr_id']);
                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/register/reg-late.png\" border=\"0\" alt=\"\" /></td>";
                echo '<td align="left">' . HTML::cell(Date::toShort($row['date'])) . '</td>';
                echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['time_from']) . '</td>';
                echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['time_to']) . '</td>';
				//$minutes = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(time_to, time_from)))*60) + (SUM(MINUTE(TIMEDIFF(time_to, time_from)))) FROM otj WHERE id = '{$row['id']}'");
				//echo '<td align="center" style="font-size: 11px;">' . ViewOTJ::convertToHoursMins($minutes, '%02d hours %02d minutes') . '</td>';
	            echo '<td align="center" style="font-size: 11px;">' . $row['duration_hours'] . ' hour(s) ' . $row['duration_minutes'] . ' minutes</td>';
                echo '<td align="left" style="font-size: 11px;">' . HTML::cell($row['description']) . '</td>';
                echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['comments']) . '</td>';
                echo '</tr>';

            }

	    if(in_array(DB_NAME, ["am_demo"]) && $tr_id != '')
            {
                    echo '<tr><td colspan="7"><hr></td></tr>';
                $sql = <<<SQL
SELECT lessons.*, 'register' AS description,  TIME_FORMAT(TIMEDIFF(end_time,start_time),"%H") AS duration
FROM lessons INNER JOIN register_entries ON lessons.`id` = register_entries.`lessons_id` 
WHERE register_entries.`pot_id` = '{$tr_id}' AND lessons.`set_as_otj` = 1 ORDER BY lessons.date;
SQL;
                $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
                foreach($records AS $row)
                {
                    echo '<tr>';
                    echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/register/reg-late.png\" border=\"0\" alt=\"\" /></td>";
                    echo '<td align="left">' . HTML::cell(Date::toShort($row['date'])) . '</td>';
                    echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['start_time']) . '</td>';
                    echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['end_time']) . '</td>';
                    $minutes = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(end_time, start_time)))*60) + (SUM(MINUTE(TIMEDIFF(end_time, start_time)))) FROM lessons WHERE id = '{$row['id']}'");
                    echo '<td align="center" style="font-size: 11px;">' . ViewOTJ::convertToHoursMins($minutes, '%02d hours %02d minutes') . '</td>';
                    //echo '<td align="center" style="font-size: 11px;">' . $row['duration_hours'] . ' hour(s) ' . $row['duration_minutes'] . ' minutes</td>';
                    echo '<td align="left" style="font-size: 11px;">' . HTML::cell('Register') . '</td>';
                    echo '<td align="center" style="font-size: 11px;"></td>';
                    echo '</tr>';
                }
            }	

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