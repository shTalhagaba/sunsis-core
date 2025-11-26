<?php
class ViewLearnerEmployerContact extends View
{

    public static function getInstance(PDO $link, $id)
    {
        $where = '';

        // Create new view object
        $sql = <<<HEREDOC
SELECT
	employer_contact.*, lookup_contact_type.`description`
FROM
	employer_contact
left JOIN lookup_contact_type on lookup_contact_type.id = employer_contact.contact_type
WHERE tr_id='$id' $where
;
HEREDOC;

        $view = new ViewLearnerEmployerContact();
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
            echo '<th class="topRow">&nbsp;</th><th  class="topRow">Contact Type</th><th  class="topRow">Forecast Date</th><th  class="topRow">Contact Date</th><th  class="topRow">Comments</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_REVIEWER)
                    echo '<tr>';
                else
                    echo HTML::viewrow_opening_tag('/do.php?_action=edit_learner_employer_contact&employer_contact_id=' . $row['id'] . '&tr_id=' . $row['tr_id']);
                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/exam.png\" border=\"0\" alt=\"\" /></td>";
                echo '<td align="center" style="font-size: 11px;">' . HTML::cell($row['description']) . '</td>';
                echo '<td align="left">' . HTML::cell(Date::toShort($row['forecast_date'])) . '</td>';
                echo '<td align="left">' . HTML::cell(Date::toShort($row['contact_date'])) . '</td>';
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