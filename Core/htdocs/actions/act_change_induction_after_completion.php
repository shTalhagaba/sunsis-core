<?php
class change_induction_after_completion implements IAction
{
	public function execute(PDO $link)
	{
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $inductee_id = isset($_REQUEST['inductee_id']) ? $_REQUEST['inductee_id'] : '';
        $induction_id = isset($_REQUEST['induction_id']) ? $_REQUEST['induction_id'] : '';

        if($tr_id == '' || $inductee_id == '' || $induction_id == '')
        {
            throw new Exception("Missing querystring arguments: tr_id, inductee_id, induction_id");
        }

        if($subaction == 'save')
        {
            $this->saveInformation($link, $_REQUEST);            
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $inductee = Inductee::loadFromDatabase($link, $inductee_id);
        $induction = Induction::loadFromDatabase($link, $induction_id);

        $_SESSION['bc']->add($link, "do.php?_action=change_induction_after_completion&tr_id={$tr_id}&inductee_id={$inductee_id}&induction_id={$induction_id}", "Update Induction Information");

        include('tpl_change_induction_after_completion.php');
    }

    public function saveInformation(PDO $link, $data)
    {
        $induction = Induction::loadFromDatabase($link, $_REQUEST['induction_id']);

        foreach([
            'iag_numeracy',
            'iag_literacy',
            'math_cert',
            'eng_cert',
            'wfd_assessment',
            'maths_gcse_elig_met',
            'maths_gcse_grade',
            'eng_gcse_grade',
            'sci_gcse_grade',
            'it_gcse_grade',
        ] AS $field)
        {
            $induction->$field = isset($_REQUEST[$field]) ? $_REQUEST[$field] : $induction->$field;
        }

        $induction->save($link);

        http_redirect('do.php?_action=read_training_record&id='.$data['tr_id']);
    }
}