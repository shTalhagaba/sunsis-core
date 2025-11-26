<?php
class GetCRMContracts extends View
{

    public static function getInstance($link, $crm_id)
    {
        $key = 'view'.__CLASS__;
        if(true)
        {
            // Create new view object
            $sql = <<<HEREDOC
select * From contracts where contract_year = 2013 ORDER BY title;
HEREDOC;


            $view = $_SESSION[$key] = new GetCRMContracts();
            $view->setSQL($sql);

        }

        return $_SESSION[$key];
    }


    public function render(PDO $link,$crm_id)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th><input id="global" type="checkbox" title = "top" onclick="checkAll(this);" /></a></th><th>Title</th></tr></thead>';
            $counter=1;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                $contract_id = $row['id'];
                $members = DAO::getSingleColumn($link, "select contract_id from crm_subjects_contracts where crm_subject_id = $crm_id");
                $disabled= '';
                if(in_array($contract_id, $members))
                    echo '<td><input ' . $disabled . ' id="button'.$counter++.'" type="checkbox" checked title="' . $row['title'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
                else
                    echo '<td><input ' . $disabled . ' id="button'.$counter++.'" type="checkbox" title="' . $row['title'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
                echo '<td>' . $row['title'] . '</td>';
                echo '</tr>';

                $qid = $row['id'];

            }
            echo '</tbody></table></div align="left">';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}
?>