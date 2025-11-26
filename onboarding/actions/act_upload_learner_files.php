<?php
class upload_learner_files implements IAction
{
    public function execute(PDO $link)
    {
        $ob_learner_id = isset($_REQUEST['ob_learner_id']) ? $_REQUEST['ob_learner_id']:'';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id']:'';
        $dir = isset($_REQUEST['dir']) ? $_REQUEST['dir']:'';
        if(!$ob_learner_id == '' && $tr_id == '')
        {
            throw new Exception("Missing querystring arguments, provide either learner id or training id");
        }

        $target_dir = "";
        if($ob_learner_id != '')
        {
            $target_dir = "/OnboardingModule/learners/{$ob_learner_id}/";
        }
        if($tr_id != '')
        {
            $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
            if(is_null($tr))
            {
                throw new Exception("Invalid tr_id");
            }
            if($ob_learner_id != '')
            {
                if($tr->ob_learner_id != $ob_learner_id)
                {
                    throw new Exception("Invalid ids given.");
                }
            }
            $target_dir = "/OnboardingModule/learners/{$tr->ob_learner_id}/{$tr->id}/other";

            if($dir == 'delivery_plan')
            {
                $target_dir = "/OnboardingModule/learners/{$tr->ob_learner_id}/{$tr->id}/delivery_plan";
            }
        }

        $valid_extensions = array('jpeg', 'jpg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z', 'png');

        if($dir == 'delivery_plan')
        {
            $r = Repository::processFileUploads('input_uploaded_learner_dp_file', $target_dir, $valid_extensions);
        }
        else
        {
            $r = Repository::processFileUploads('input_uploaded_learner_file', $target_dir, $valid_extensions);
        }
        if(count($r) == 0)
        {
            throw new Exception("File is not uploaded, please try again.");
        }

        http_redirect($_SESSION['bc']->getCurrent());
    }

}
?>