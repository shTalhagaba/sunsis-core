<?php
class ViewEmployerReference extends View
{

    public static function getInstance(PDO $link, $id)
    {
        $where = '';

        // Create new view object
        $sql = <<<HEREDOC
SELECT
	*
FROM
	employer_reference
left join employer_reference_emails on employer_reference.id = employer_reference_emails.form_id
WHERE tr_id='$id' $where
;
HEREDOC;

        $view = new ViewEmployerReference();
        $view->setSQL($sql);


        return $view;
    }


    public function render(PDO $link,$tr_id)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

            echo '<thead><tr>';
            echo '<th class="topRow">&nbsp;</th><th  class="topRow">Event</th><th>Sent Date</th><th  class="topRow">Due Date</th><th  class="topRow">Returned Date</th><th  class="topRow">Status</th><th  class="topRow">% Complete</th><th  class="topRow">Call Employer</th><th  class="topRow">Comments</th><th  class="topRow">Form</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag('/do.php?_action=edit_employer_reference&id=' . $row['id'] . '&tr_id=' . $row['tr_id']);
                echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/exam.png\" border=\"0\" alt=\"\" /></td>";
                echo '<td align="left">' . HTML::cell(Date::toShort($row['date'])) . '</td>';
                echo '<td align="left">' . HTML::cell(Date::toShort($row['due_date'])) . '</td>';
                echo '<td align="left">' . HTML::cell(Date::toShort($row['meeting_date'])) . '</td>';
                echo '<td align="center">' . HTML::cell($row['paperwork_received']) . '</td>';
                echo '<td align="center">' . HTML::cell('%Complete') . '</td>';
                echo '<td align="center">' . HTML::cell('call employer') . '</td>';
                echo '<td style="font-size: 11px;">' . HTML::cell($row['comments']) . '</td>';
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