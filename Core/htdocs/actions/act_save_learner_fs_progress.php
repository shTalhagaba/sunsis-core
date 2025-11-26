<?php
class save_learner_fs_progress implements IAction
{
    public function execute(PDO $link)
    {
        $vo = new FSProgress();
        $vo->populate($_POST);
        $vo->modified = date('Y-m-d H:i:s');

        if($vo->achieved_timestamp == "" and $vo->achieved == 1)
            $vo->achieved_timestamp = date('Y-m-d H:i:s');

        // Set progress_plan_set_date_maths field
        /*if($_POST['progress_plan_set_date_maths_old']=="" and $_POST['progress_plan_maths']!="")
        {
            $vo->progress_plan_set_date_maths = date('Y-m-d');
        }
        if($_POST['progress_plan_set_date_reading_old']=="" and $_POST['progress_plan_reading']!="")
        {
            $vo->progress_plan_set_date_reading = date('Y-m-d');
        }
        if($_POST['progress_plan_set_date_writing_old']=="" and $_POST['progress_plan_writing']!="")
        {
            $vo->progress_plan_set_date_writing = date('Y-m-d');
        }*/

        if($_POST['maths_mock_status_old']=="" and $_POST['maths_mock_status']!="")
        {
            $vo->maths_mock_status_changed = date('Y-m-d');
        }
        if($_POST['english_mock_status_old']=="" and $_POST['english_mock_status']!="")
        {
            $vo->english_mock_status_changed = date('Y-m-d');
        }

        $vo->save($link);

        $tr = TrainingRecord::loadFromDatabase($link, $_POST['tr_id']);
        $username = $tr->username;
        $fs_progress_id = $vo->id;
        $db=DB_NAME;
        $target_directory = "/$username/fs_progress/$fs_progress_id";
        $valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

        $r = Repository::processFileUploads('uploaded_file', $target_directory, $valid_extensions);

        if(IS_AJAX)
        {
            header("Content-Type: text/plain");
            echo $vo->id;
        }
        else
        {
            http_redirect('do.php?_action=read_training_record&webinars_tab=1&id=' . $vo->tr_id);
        }
    }
}
?>