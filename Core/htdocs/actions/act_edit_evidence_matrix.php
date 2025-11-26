<?php
class edit_evidence_matrix implements IAction
{
    public function execute(PDO $link)
    {
        $course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';

        if($course_id == '')
        {
            throw new Exception('Missing querystring argument: tr_id');
        }
        $_SESSION['bc']->add($link, "do.php?_action=edit_evidence_matrix&course_id=".$course_id, "Edit Evidence Matrix");

        include_once('tpl_edit_evidence_matrix.php');
    }
}