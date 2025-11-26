<?php
class ajax_evidence implements IAction
{
    public function execute( PDO $link )
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        if($subaction != '' && $subaction == 'get_evidence')
        {
            echo $this->getEvidence($link);
            exit;
        }

        if($subaction != '' && $subaction == 'get_project')
        {
            echo $this->getProject($link);
            exit;
        }

        if($subaction != '' && $subaction == 'delete_evidence')
        {
            $id = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
            DAO::execute($link, "delete from evidence_criteria where id = '$id'");
            exit;
        }

        if($subaction != '' && $subaction == 'delete_project')
        {
            $id = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
            DAO::execute($link, "delete from evidence_project where id = '$id'");
            exit;
        }

    }


    public function getEvidence(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
        $sql = <<<SQL
SELECT id, criteria as evidence_criteria, competency FROM evidence_criteria WHERE id = '{$id}'
SQL;
        return json_encode(DAO::getObject($link, $sql));

    }

    public function getProject(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id']: '';
        $sql = <<<SQL
SELECT id, project as project_criteria FROM evidence_project WHERE id = '{$id}'
SQL;
        return json_encode(DAO::getObject($link, $sql));

    }



}