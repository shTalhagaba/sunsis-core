<?php
class save_skills_scan implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';

        $st = $link->query("select * from skills_scan where tr_id = '$tr_id'");
        while($row = $st->fetch())
        {
            $grade = $_POST["ss|".$row['id']];
            $grade2 = $_POST["ss2|".$row['id']];
            $grade3 = $_POST["ss3|".$row['id']];
            $grade4 = $_POST["ss4|".$row['id']];
            DAO::execute($link, "update skills_scan set grade = '$grade', grade2 = '$grade2', grade3 = '$grade3', grade4 = '$grade4' where id = {$row['id']} and tr_id = '$tr_id'");
        }

        $st = $link->query("select * from technical_knowledge where tr_id = '$tr_id'");
        while($row = $st->fetch())
        {
            $grade = $_POST["tk|".$row['id']];
            $grade2 = $_POST["tk2|".$row['id']];
            $grade3 = $_POST["tk3|".$row['id']];
            $grade4 = $_POST["tk4|".$row['id']];
            DAO::execute($link, "update technical_knowledge set grade = '$grade', grade2 = '$grade2', grade3 = '$grade3', grade4 = '$grade4' where id = {$row['id']} and tr_id = '$tr_id'");
        }

        $st = $link->query("select * from attitudes_behaviours where tr_id = '$tr_id'");
        while($row = $st->fetch())
        {
            $grade = $_POST["ab|".$row['id']];
            $grade2 = $_POST["ab2|".$row['id']];
            $grade3 = $_POST["ab3|".$row['id']];
            $grade4 = $_POST["ab4|".$row['id']];
            DAO::execute($link, "update attitudes_behaviours set grade = '$grade', grade2 = '$grade2', grade3 = '$grade3', grade4 = '$grade4' where id = {$row['id']} and tr_id = '$tr_id'");
        }

        http_redirect('do.php?_action=read_training_record&id=' . $tr_id);
    }
}
?>