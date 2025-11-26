<?php
class save_learner_eligibility implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_POST['tr_id']) ? $_POST['tr_id'] : '';
        if($tr_id == '')
            throw new Exception("Missing querystring argument: tr_id");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
            throw new Exception("Invalid tr_id");

        foreach($_POST AS $key => $value)
        {
            $tr->$key = $value;
        }

        $target_directory = 'OnboardingModule' . DIRECTORY_SEPARATOR . 'learners' . DIRECTORY_SEPARATOR . $tr->ob_learner_id . DIRECTORY_SEPARATOR . $tr->id . DIRECTORY_SEPARATOR .'onboarding';
        $valid_extensions = array('csv', 'doc', 'docx', 'pdf', 'jpg', 'png', 'jpeg', 'txt');

        $paths = Repository::processFileUploads('care_leaver_evidence_file', $target_directory, $valid_extensions);
        if(count($paths) > 0)
        {
            if(is_file(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->care_leaver_evidence_file))
                unlink(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->care_leaver_evidence_file);

                $tr->care_leaver_evidence_file = basename($paths[0]);
        }

        $paths = Repository::processFileUploads('ehc_evidence_file', $target_directory, $valid_extensions);
        if(count($paths) > 0)
        {
            if(is_file(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->ehc_evidence_file))
                unlink(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->ehc_evidence_file);

                $tr->ehc_evidence_file = basename($paths[0]);
        }

        $paths = Repository::processFileUploads('evidence_pp_file', $target_directory, $valid_extensions);
        if(count($paths) > 0)
        {
            if(is_file(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->evidence_pp_file))
                unlink(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->evidence_pp_file);

                $tr->evidence_pp_file = basename($paths[0]);
        }

        $paths = Repository::processFileUploads('evidence_ilr_file', $target_directory, $valid_extensions);
        if(count($paths) > 0)
        {
            if(is_file(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->evidence_ilr_file))
                unlink(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->evidence_ilr_file);

                $tr->evidence_ilr_file = basename($paths[0]);
        }

        $paths = Repository::processFileUploads('evidence_previous_uk_study_visa_file', $target_directory, $valid_extensions);
        if(count($paths) > 0)
        {
            if(is_file(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->evidence_previous_uk_study_visa_file))
                unlink(Repository::getRoot() . DIRECTORY_SEPARATOR . $target_directory . DIRECTORY_SEPARATOR . $tr->evidence_previous_uk_study_visa_file);

                $tr->evidence_previous_uk_study_visa_file = basename($paths[0]);
        }

	    if(!isset($_POST['EligibilityList']))
        {
            $tr->EligibilityList = [];
        }



        $tr->save($link);

        $this->saveExtraFields($link, $tr, $_POST);

        if(IS_AJAX)
        {
            echo "The information has been saved successfully.";
        }
        else
        {
            http_redirect("do.php?_action=read_training&id={$tr->id}");
        }

    }

    private function saveExtraFields(PDO $link, TrainingRecord $tr, $data)
    {
        $extra_info = DAO::getObject($link, "SELECT * FROM ob_learner_extra_details WHERE tr_id = '{$tr->id}'");
        if(!isset($extra_info->tr_id))
        {
            $extra_info = new stdClass();
            $ob_learner_extra_details_fields = DAO::getSingleColumn($link, "SHOW COLUMNS FROM ob_learner_extra_details");
            foreach($ob_learner_extra_details_fields AS $extra_info_key => $extra_info_value)
                $extra_info->$extra_info_value = null;
        }

        foreach($extra_info AS $key => $value)
        {
            $extra_info->$key = isset($data[$key]) ? $data[$key] : null;
        }
        $extra_info->tr_id = $tr->id;     
        DAO::saveObjectToTable($link, "ob_learner_extra_details", $extra_info);
    }
}