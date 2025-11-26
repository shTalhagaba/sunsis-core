<?php
class ajax_load_pwaycode implements IAction
{
    public function execute(PDO $link)
    {
        header('Content-Type: text/xml');

        $framework_code = array_key_exists('framework_code', $_REQUEST)?$_REQUEST['framework_code']:'';
        $programme_type = array_key_exists('programme_type', $_REQUEST)?$_REQUEST['programme_type']:'';

        if($framework_code == '')
        {
            throw new Exception("Missing querystring argument");
        }


        $sql = <<<HEREDOC
SELECT PwayCode, LEFT(CONCAT(PwayCode, ' ' , PathwayName),40), NULL
FROM lars201718.Core_LARS_Framework
WHERE
	FworkCode=$framework_code and ProgType = '$programme_type'

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
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
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