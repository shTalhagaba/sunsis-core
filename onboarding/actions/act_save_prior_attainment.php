<?php
class save_prior_attainment implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '')
            throw new Exception("Missing querystring argument: tr_id");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            throw new Exception("Invalid tr_id");

        DAO::transaction_start($link);
        try
        {
            DAO::execute($link, "DELETE FROM ob_learners_pa WHERE tr_id = '{$tr->id}'");

            $english = new stdClass();
            $english->tr_id = $tr->id;
            $english->level = isset($_POST['gcse_english_level'])?$_POST['gcse_english_level']:'';
            $english->subject = isset($_POST['gcse_english_subject'])?$_POST['gcse_english_subject']:'';
            $english->p_grade = isset($_POST['gcse_english_grade_predicted'])?$_POST['gcse_english_grade_predicted']:'';
            $english->a_grade = isset($_POST['gcse_english_grade_actual'])?$_POST['gcse_english_grade_actual']:'';
            $english->date_completed = isset($_POST['gcse_english_date_completed'])?$_POST['gcse_english_date_completed']:'';
            $english->q_type = 'g';
            if($english->p_grade != '' || $english->a_grade != '')
                DAO::saveObjectToTable($link, 'ob_learners_pa', $english);
            unset($english);
            $maths = new stdClass();
            $maths->tr_id = $tr->id;
            $maths->level = isset($_POST['gcse_maths_level'])?$_POST['gcse_maths_level']:'';
            $maths->subject = isset($_POST['gcse_maths_subject'])?$_POST['gcse_maths_subject']:'';
            $maths->p_grade = isset($_POST['gcse_maths_grade_predicted'])?$_POST['gcse_maths_grade_predicted']:'';
            $maths->a_grade = isset($_POST['gcse_maths_grade_actual'])?$_POST['gcse_maths_grade_actual']:'';
            $maths->date_completed = isset($_POST['gcse_maths_date_completed'])?$_POST['gcse_maths_date_completed']:'';
            $maths->q_type = 'g';
            if($maths->p_grade != '' || $maths->a_grade != '')
                DAO::saveObjectToTable($link, 'ob_learners_pa', $maths);
            unset($maths);
            $ict = new stdClass();
            $ict->tr_id = $tr->id;
            $ict->level = isset($_POST['gcse_ict_level'])?$_POST['gcse_ict_level']:'';
            $ict->subject = isset($_POST['gcse_ict_subject'])?$_POST['gcse_ict_subject']:'';
            $ict->p_grade = isset($_POST['gcse_ict_grade_predicted'])?$_POST['gcse_ict_grade_predicted']:'';
            $ict->a_grade = isset($_POST['gcse_ict_grade_actual'])?$_POST['gcse_ict_grade_actual']:'';
            $ict->date_completed = isset($_POST['gcse_ict_date_completed'])?$_POST['gcse_ict_date_completed']:'';
            $ict->q_type = 'g';
            if($ict->p_grade != '' || $ict->a_grade != '')
                DAO::saveObjectToTable($link, 'ob_learners_pa', $ict);
            unset($ict);
            for($i = 1; $i <= 15; $i++)
            {
                $objPA = new stdClass();
                $objPA->tr_id = $tr->id;
                $objPA->level = isset($_POST['level'.$i])?$_POST['level'.$i]:'';
                $objPA->subject = isset($_POST['subject'.$i])?substr($_POST['subject'.$i], 0, 79):'';
                $objPA->p_grade= isset($_POST['predicted_grade'.$i])?$_POST['predicted_grade'.$i]:'';
                $objPA->a_grade = isset($_POST['actual_grade'.$i])?$_POST['actual_grade'.$i]:'';
                $objPA->date_completed = isset($_POST['date_completed'.$i])?$_POST['date_completed'.$i]:'';
                $objPA->q_type = isset($_POST['q_type'.$i]) ? substr($_POST['q_type'.$i], 0, 3):'';
                if(trim($objPA->level) != '' && trim($objPA->subject) != '')
                    DAO::saveObjectToTable($link, 'ob_learners_pa', $objPA);
                unset($objPA);
            }
            $high_level = new stdClass();
            $high_level->tr_id = $tr->id;
            $high_level->level = isset($_POST['high_level'])?$_POST['high_level']:'';
            $high_level->subject = isset($_POST['high_subject'])?$_POST['high_subject']:'h';
            $high_level->q_type = 'h';
            DAO::saveObjectToTable($link, 'ob_learners_pa', $high_level);

	    foreach([
                'numeracy', 'literacy', 'literacy_other', 'numeracy_other', 'numeracy_diagnostic', 'literacy_diagnostic', 
                'literacy_diagnostic_other', 'numeracy_diagnostic_other',
                'ict', 'ict_other',
                'esol', 'esol_other',
            ] AS $ia_field)
            {
                $tr->$ia_field = isset($_POST[$ia_field]) ? $_POST[$ia_field] : $tr->$ia_field;    
            }
	    $tr->prior_edu_checked = isset($_POST['prior_edu_checked']) ? $_POST['prior_edu_checked'] : 0;
	    $tr->fs_eng_opt_in = isset($_POST['fs_eng_opt_in']) ? $_POST['fs_eng_opt_in'] : '';
	    $tr->fs_maths_opt_in = isset($_POST['fs_maths_opt_in']) ? $_POST['fs_maths_opt_in'] : '';
	    $tr->save($link);

            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        if(IS_AJAX)
        {
            echo "Prior attainment has been updated successfully";
        }
        else
        {
            http_redirect("do.php?_action=read_training&id={$tr->id}");
        }

    }
}