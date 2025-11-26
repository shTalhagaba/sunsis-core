<?php
class save_learner_writing_assessment implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';
     
        if(trim($id) != '' && trim($key) != '')
        {
            if(!OnboardingHelper::isValidFreeWritingAssessmentUrl($link, $id, $key))
            {
                http_redirect('do.php?_action=error_page');
            }
        }
        else
        {
            http_redirect('do.php?_action=error_page');
        }

        $tr = TrainingRecord::loadFromDatabase($link, $id);
        if(is_null($tr))
        {
            http_redirect('do.php?_action=error_page');
            exit;
        }

        $ob_learner = $tr->getObLearnerRecord($link);

	$framework = Framework::loadFromDatabase($link, $tr->framework_id);
        $min_required_words = ( isset($framework->writing_assessment_chars) && $framework->writing_assessment_chars != '' ) ? $framework->writing_assessment_chars : 300;

        DAO::transaction_start($link);
        try
        {
            $_POST = Helpers::utf8_sanitize_recursive($_POST);

	    if(isset($_POST['learner_comments']) && (str_word_count($_POST['learner_comments']) + 5) < $min_required_words )
            {
		$_res = preg_split('/\s+/', $_POST['learner_comments']);
		$words_count = count($_res);
		if($words_count < $min_required_words)
                	throw new Exception($min_required_words . " minimum words required for this assessment. ");
            }

            $save_object = (object) [
                'tr_id' => $tr->id,
                'form_data' => json_encode($_POST),
                'learner_comments' => preg_replace('/[^\x00-\x7F]/', '', $_POST['learner_comments']),
                'learner_sign' => $_POST['learner_sign'],
                'learner_sign_name' => $ob_learner->firstnames . ' ' . $ob_learner->surname,
                'learner_sign_date' => date('Y-m-d'),
            ];
            DAO::saveObjectToTable($link, "ob_learner_writing_assessment", $save_object);

            $employer_signatures_log = (object)[
                'entity_id' => $save_object->tr_id,
                'entity_type' => 'ob_learner_writing_assessment',
                'user_sign' => $_POST['learner_sign'],
                'user_sign_date' => date('Y-m-d'),
                'user_sign_name' => $ob_learner->firstnames . ' ' . $ob_learner->surname,
                'user_type' => 'LEARNER',
            ];

            DAO::saveObjectToTable($link, "documents_signatures", $employer_signatures_log);
    
            DAO::transaction_commit($link);
        }
        catch(Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }



        http_redirect('do.php?_action=cs_completed');
    }

}