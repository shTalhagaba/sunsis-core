<?php
class save_evidence_matrix implements IAction
{
    public function execute(PDO $link)
    {

        $course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';
        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $evidence_criteria = isset($_REQUEST['evidence_criteria']) ? $_REQUEST['evidence_criteria'] : '';
        $project_criteria = isset($_REQUEST['project_criteria']) ? $_REQUEST['project_criteria'] : '';
        $competency = isset($_REQUEST['competency']) ? $_REQUEST['competency'] : '';
        if($course_id == '')
            throw new Exception('Missing querystring argument: tr_id');

        if(isset($_REQUEST['formName']) && $_REQUEST['formName'] == 'frmEvidence') // this is just uploading the files for the op learner
        {
            $vo = new stdClass();
            $vo->id = $id;
            $vo->course_id = $course_id;
            $vo->criteria = $evidence_criteria;
            $vo->competency = $competency;
            DAO::saveObjectToTable($link, 'evidence_criteria', $vo);
            http_redirect("do.php?_action=edit_evidence_matrix&course_id=" . $course_id);
        }

        if(isset($_REQUEST['formName']) && $_REQUEST['formName'] == 'frmProject') // this is just uploading the files for the op learner
        {
            $vo = new stdClass();
            $vo->id = $id;
            $vo->course_id = $course_id;
            $vo->project = $project_criteria;
            DAO::saveObjectToTable($link, 'evidence_project', $vo);
            http_redirect("do.php?_action=edit_evidence_matrix&course_id=" . $course_id);
        }


    }
}
?>
