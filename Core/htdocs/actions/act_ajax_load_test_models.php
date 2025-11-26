<?php
class ajax_load_test_models implements IAction
{
    public function execute(PDO $link)
    {
        header('Content-Type: text/xml');

        $make = array_key_exists('make', $_REQUEST)?$_REQUEST['make']:'';

        if($make == '')
        {
            throw new Exception("Missing querystring argument 'org_id'");
        }

        $sql = <<<HEREDOC
SELECT
* from car_models where make_id = '$make';
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
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[2]) . "</option>\r\n";
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