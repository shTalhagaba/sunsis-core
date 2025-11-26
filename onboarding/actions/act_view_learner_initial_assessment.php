<?php

class view_learner_initial_assessment implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if ($id == '') {
            throw new Exception('Missing querystring argument: id');
        }

        $assessment = DAO::getObject($link, "SELECT * FROM ob_tr_assessments WHERE id = '{$id}'");
        if (!isset($assessment->tr_id)) {
            throw new Exception("Invalid ID");
        }

        $previousAssessments = DAO::getResultset($link, "SELECT * FROM ob_tr_assessments WHERE id != {$assessment->id} AND tr_id = '{$assessment->tr_id}' AND subject = '{$assessment->subject}' ORDER BY id DESC", DAO::FETCH_ASSOC);
        // $previousAssessments = DAO::getResultset($link, "SELECT * FROM ob_tr_assessments WHERE tr_id = '{$assessment->tr_id}' AND subject = '{$assessment->subject}' ORDER BY id DESC",DAO::FETCH_ASSOC);

        $questions = $this->getQuestions($link,$id);


        $tr = TrainingRecord::loadFromDatabase($link, $assessment->tr_id);
        $ob_learner = $tr->getObLearnerRecord($link);

        $trainer = $tr->trainers != '' ? User::loadFromDatabaseById($link, $tr->trainers) : new User();

        $_SESSION['bc']->add($link, "do.php?_action=view_learner_initial_assessment&id={$id}", "View Initial Assessment");


        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);
        $providerLogo = "images/logos/" . SystemConfig::getEntityValue($link, 'logo');
        if (!is_null($provider->provider_logo)) {
            $providerLogo = $provider->provider_logo;
        }

        require_once('tpl_view_learner_initial_assessment.php');
    }

    private function getQuestions($link, $id)
    {

        $sql = "SELECT qs.*, ans.answer as givin_answer, ans.correct FROM `ob_tr_assessment_answers` as ans";
        $sql .= " LEFT JOIN `ob_tr_questions` as qs on qs.id = ans.question_id";
        $sql .= " WHERE as_id = '{$id}'";
        $sql .= " ORDER BY qs.stage, qs.stage_level";

        return DAO::getResultset($link, $sql,DAO::FETCH_ASSOC);
    }
}

?>
