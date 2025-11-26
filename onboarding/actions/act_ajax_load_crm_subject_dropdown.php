<?php
class ajax_load_crm_subject_dropdown implements IAction
{
    public function execute(PDO $link)
    {
        $employer = isset($_REQUEST['employer']) ? $_REQUEST['employer'] : '';

        header('Content-Type: text/xml');

        $where_clause = $employer == '1' ? ' AND employer = "1" ' : '';

        $sql = <<<HEREDOC
SELECT
	id, description, null
FROM
	lookup_crm_subject
WHERE
	(1=1)
	$where_clause
ORDER BY
	description
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";

            // First entry is empty
            echo "<option value=\"\"></option>\r\n";

            while($row = $st->fetch())
            {
                echo '<option value="' . htmlspecialchars($row[0]) . '">' . htmlspecialchars($row[1]) . "</option>\r\n";
            }

            echo '</select>';

        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }
}
?>