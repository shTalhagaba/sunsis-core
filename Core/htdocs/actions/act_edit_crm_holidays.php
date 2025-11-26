<?php
class edit_crm_holidays implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $contact_id = isset($_REQUEST['contact_id']) ? $_REQUEST['contact_id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_crm_holidays&id=" . $id , "Edit CRM holidays");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring");
        }

        include('tpl_edit_crm_holidays.php');
    }

    private function getPreviousHolidays($link, $id)
    {

        $html = "";
        $st = $link->query("select * from crm_holidays where contact_id = '$id'");
        if($st)
        {
            $html .= <<<HEREDOC
<div class="Directory">
<table class="resultset" border="0" cellspacing="0" cellpadding="6">
<col width="100"/><col width="100"/><col width="170"/>
<tr>
	<th>Holiday Start Date</th><th>Holiday End Date</th><th>Comments</th>
</tr>
HEREDOC;
        while($row = $st->fetch())
        {
            $html .= "<tr>";
            $html .= '<td align="right" style="font-family:monospace" width="30">'.$row['holiday_start_date'].'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="30">'.$row['holiday_end_date'].'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="170">'.$row['comments'].'</td>';
            $html .= "</tr>";
        }
        $html .= "</table>\r\n";
        $html .= "</div>\r\n";

        return $html;
       }
    }
}
?>