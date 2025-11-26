<?php
class ViewLearnerReviews extends View
{

    public static function getInstance(PDO $link, $id)
    {
        if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
        {
            $where = '';
        }
        else
        {
            $where = " AND (reviews.interviewer = " . $_SESSION['user']->id . ") " ;
        }

        // Create new view object
        $sql = <<<HEREDOC
SELECT
	reviews.id AS review_id,
	reviews.review_date,
	reviews.review_start_time,
	reviews.review_end_time,
	(SELECT description FROM lookup_review_types WHERE id = reviews.review_type) AS review_type,
	(SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = reviews.interviewer) AS interviewer,
	(SELECT description FROM lookup_review_status WHERE id = reviews.review_status) AS review_status,
	reviews.review_rgb_status,
	#(SELECT description FROM lookup_appointment_paperwork WHERE id = appointments.appointment_paperwork) AS appointment_paperwork,
	#(SELECT title FROM modules WHERE id = appointments.appointment_module) AS appointment_module,
	reviews.review_comments,
	reviews.tr_id,
	reviews.created
FROM
	reviews
WHERE tr_id='$id' $where
ORDER BY reviews.review_date DESC,
	reviews.review_start_time DESC;
HEREDOC;

        $view = new ViewLearnerReviews();
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
                echo '<td rowspan = "2" align="center">&nbsp;&nbsp;&nbsp;<span class="button" onclick="window.location.href=\'do.php?_action=print_appointment&tr_id=' . $row['tr_id'] . '&review_id=' . $row['review_id'] . '\';">PDF</span>';
                echo '<span class="button" onclick="window.location.href=\'do.php?_action=send_appointment_email&tr_id=' . $row['tr_id'] . '&review_id=' . $row['review_id'] . '\';">Email</span>&nbsp;&nbsp;&nbsp;';
                //echo '<span onclick="fetchAndOpenLog(' . $row['review_id'] . ');" class="button">Form</span></td>';
                echo '<span class="button" onclick="window.location.href=\'do.php?_action=assessor_review_formv2&source=1&tr_id='.$row['tr_id'] . '&review_id='. $row['review_id'] . '\';">Form</span></td>';
                echo '</tr>';

                echo HTML::viewrow_opening_tag('/do.php?_action=edit_generic_review&review_id=' . $row['review_id'] . '&tr_id=' . $row['tr_id']);
                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/interview-icon.png\" border=\"0\" alt=\"\" /></td>";
                echo '<td align="left">' . HTML::cell(Date::toShort($row['review_date'])) . '</td>';
                echo '<td align="left">' . HTML::cell($row['review_start_time']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['review_end_time']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['review_type']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['interviewer']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['review_status']) . '</td>';
                switch($row['review_rgb_status'])
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
                echo '<td align="center">' . HTML::cell($row['created']) . '</td>';
                echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['review_comments']) . '</td>';
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