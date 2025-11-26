<?php
class ajax_get_sunesis_learner implements IAction
{
    public function execute(PDO $link)
    {
        header('Content-Type: text/xml; charset=iso-8859-1');

        $LearnRefNumber = isset($_REQUEST['LearnRefNumber'])?$_REQUEST['LearnRefNumber']:'';
        $ULN = isset($_REQUEST['ULN'])?$_REQUEST['ULN']:'';

        $st = DAO::query($link, "select tr.l03, users.`firstnames`, users.`surname`, users.`l45`, users.`dob` From users left join tr on tr.username = users.username where users.l45 = '$ULN' or users.ULN = '$ULN' or tr.l03 = '$LearnRefNumber' limit 0,1");
        if ($st)
        {
            $data = "";
            while ($row = $st->fetch())
            {
                $data .= $row['l03'] . "|";
                $data .= $row['firstnames'] . "|";
                $data .= $row['surname'] . "|";
                $data .= $row['l45'] . "|";
                $data .= $row['dob'];
            }
        }

        if($data!="")
        {
            echo $data;
        }
        else
        {
            echo "error";
        }
    }
}
?>