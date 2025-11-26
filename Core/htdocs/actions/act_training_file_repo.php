<?php
class training_file_repo implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $folder = isset($_REQUEST['folder']) ? trim($_REQUEST['folder']) : '';
        $subaction = isset($_REQUEST['subaction']) ? trim($_REQUEST['subaction']) : '';

        if($tr_id == '')
        {
            throw new Exception("Missing querystring argument: tr_id");
        }
        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        if(is_null($tr))
        {
            throw new Exception("Invalid tr_id");
        }
        if($subaction == 'create_section')
        {
            $section = isset($_REQUEST['new_folder_name']) ? trim($_REQUEST['new_folder_name']) : '';
            if (!$section) 
            {
                return;
            }
            $path = Repository::getRoot() . '/' . trim($tr->username) . '/' . basename($section);
            
            if (!file_exists($path)) 
            {
                mkdir($path, 0777, true);
            }
        }
	if($subaction == 'delete_section')
        {
            $section = isset($_REQUEST['section']) ? trim($_REQUEST['section']) : '';
            if (!$section) 
            {
                return;
            }
            if (!$section) {
                return;
            }
    
            $path = Repository::getRoot() . '/' . trim($tr->username) . '/' . basename($section);
            if (!is_dir($path)) 
            {
                return;
            }
    
            $files = Repository::readDirectory($path);
            if (count($files) > 0) 
            {
                return;
            }
    
            rmdir($path);
            return;
        }

        $learner_dir = Repository::getRoot().'/'.trim($tr->username);

        $existing_folder_names = [];
        $existing_folders = Repository::readDirectory($learner_dir);
        foreach ($existing_folders as $_folder) 
        {
            if ($_folder->isDir()) 
            {
                $existing_folder_names[] = $_folder->getName();
            }
        }
        sort($existing_folder_names);

        if($folder == '' || $folder == trim($tr->username))
        {
            $files = Repository::readDirectory($learner_dir);
        }
	elseif($folder == 'SunesisOnboardingModule')
        {
            $files = $this->getOnboardingFiles($link, $tr);
        }
        else
        {
            if(!is_dir($learner_dir . '/' . $folder))
            {
                throw new Exception("Invalid path");
            }
            $files = Repository::readDirectory($learner_dir . '/' . $folder);
        }

	$_SESSION['bc']->add($link, "do.php?_action=training_file_repo&tr_id={$tr->id}", "Learner File Repo");
        
        
        include('tpl_training_file_repo.php');
    }

    public function isAbleToDelete()
    {
        if($_SESSION['user']->isAdmin())
            return true;

	if( DB_NAME == "am_superdrug" && in_array($_SESSION['user']->type, [User::TYPE_ASSESSOR]) )
            return true;

        return false;
    }

    private function getMaximumFileSizeToUploadForTrainingRecord()
    {
        $max_file_upload = 6291456;
        return $max_file_upload;
    }

    private function getOnboardingFiles(PDO $link, TrainingRecord $tr)
    {
        $files = [];
        $ob_ids = DAO::getObject($link, "SELECT ob_tr.id, ob_tr.ob_learner_id FROM ob_tr WHERE ob_tr.sunesis_tr_id = '{$tr->id}'");
        if(!isset($ob_ids->id))
            return $files;
        
        if(is_file(Repository::getRoot() . "/OnboardingModule/learners/{$ob_ids->ob_learner_id}/{$ob_ids->id}/OTJT Sheet.pdf"))
        {
            $_otj_file = new RepositoryFile(Repository::getRoot() . "/OnboardingModule/learners/{$ob_ids->ob_learner_id}/{$ob_ids->id}/OTJT Sheet.pdf");
            $files[] = $_otj_file;
        }
        foreach (["schedule1", "skills_analysis", "onboarding"] as $section) 
        {
            $s_files = Repository::readDirectory(Repository::getRoot() . "/OnboardingModule/learners/{$ob_ids->ob_learner_id}/{$ob_ids->id}/{$section}");
            foreach ($s_files as $s_file) 
            {
                if ($s_file->getExtension() != 'pdf')
                    continue;

                $files[] = $s_file;
            }       
        }

        return $files;
    }
}