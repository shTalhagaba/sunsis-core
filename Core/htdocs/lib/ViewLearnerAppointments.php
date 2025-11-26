<?php
class ViewLearnerAppointments extends View
{

    public static function getInstance(PDO $link, $id)
    {
        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
        {
            $where = '';
        }
		elseif($_SESSION['user']->type == User::TYPE_ASSESSOR)
		{
			$where = " AND (tr.assessor = '{$_SESSION['user']->id}') ";
		}
	elseif($_SESSION['user']->type == User::TYPE_LEARNER)
        {
            $where = " AND (tr.username = '{$_SESSION['user']->username}') ";
        }
        else
        {
            $where = " AND (appointments.interviewer = " . $_SESSION['user']->id . ") " ;
        }

        // Create new view object
        $sql = <<<HEREDOC
SELECT
	appointments.id AS appointment_id,
	appointments.appointment_date,
	appointments.appointment_start_time,
	appointments.appointment_end_time,
	appointments.interviewer AS interviewer_id,
	(SELECT description FROM lookup_appointment_types WHERE id = appointments.appointment_type) AS appointment_type,
	(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = appointments.interviewer) AS interviewer,
	(SELECT description FROM lookup_appointment_status WHERE id = appointments.appointment_status) AS appointment_status,
	appointments.appointment_rgb_status,
	#(SELECT description FROM lookup_appointment_paperwork WHERE id = appointments.appointment_paperwork) AS appointment_paperwork,
	#(SELECT title FROM modules WHERE id = appointments.appointment_module) AS appointment_module,
	appointments.appointment_comments,
	appointments.tr_id,
	appointments.created
FROM
	appointments
	LEFT JOIN tr ON appointments.tr_id = tr.id
WHERE tr_id='$id' $where
ORDER BY appointments.appointment_date DESC,
	appointments.appointment_start_time DESC;
HEREDOC;

        $view = new ViewLearnerAppointments();
        $view->setSQL($sql);


        return $view;
    }


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="2">';

            echo '<thead><tr>';
            echo '<th  class="topRow">Actions</th><th  class="topRow"></th><th  class="topRow">Date</th><th  class="topRow">Start Time</th><th  class="topRow">End Time</th><th  class="topRow">Type</th><th  class="topRow">Assessor/Interviewer</th><th  class="topRow">Status</th><th  class="topRow">G</th><th  class="topRow">Y</th><th  class="topRow">R</th><th  class="topRow">Created</th><th  class="topRow">Comments</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo '<tr>';
                echo '<td rowspan = "2" align="center">&nbsp;&nbsp;&nbsp;<span class="button" onclick="window.location.href=\'do.php?_action=print_appointment&tr_id=' . $row['tr_id'] . '&appointment_id=' . $row['appointment_id'] . '\';">Print</span><span class="button" onclick="window.location.href=\'do.php?_action=send_appointment_email&tr_id=' . $row['tr_id'] . '&appointment_id=' . $row['appointment_id'] . '\';">Email</span>&nbsp;&nbsp;&nbsp;<span onclick="fetchAndOpenLog(' . $row['appointment_id'] . ');" class="button">View Log</span></td>';
                echo '</tr>';
				if($_SESSION['user']->isAdmin())
                	echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_appointment&appointment_id=' . $row['appointment_id'] . '&tr_id=' . $row['tr_id']);
				else
				{
					if($_SESSION['user']->id == $row['interviewer_id'])
						echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_appointment&appointment_id=' . $row['appointment_id'] . '&tr_id=' . $row['tr_id']);
					else
						echo '<tr>';
				}
                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/interview-icon.png\" border=\"0\" alt=\"\" /></td>";
                echo '<td align="left">' . HTML::cell(Date::toShort($row['appointment_date'])) . '</td>';
                echo '<td align="left">' . HTML::cell($row['appointment_start_time']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['appointment_end_time']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['appointment_type']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['interviewer']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['appointment_status']) . '</td>';
                switch($row['appointment_rgb_status'])
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
                //echo '<td align="center">' . HTML::cell($row['appointment_paperwork']) . '</td>';
                //echo '<td align="center">' . HTML::cell($row['appointment_module']) . '</td>';
                echo '<td align="center">' . Date::to($row['created'], Date::DATETIME) . '</td>';
                echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['appointment_comments']) . '</td>';
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