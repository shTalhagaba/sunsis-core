<?php
class save_learner_employer_contact implements IAction
{
    public function execute(PDO $link)
    {
        $vo = new EmployerContact();
        $vo->populate($_POST);
	$vo->arm_attended = (isset($_POST['arm_attended']) && $_POST['arm_attended'] == 1) ? 1 : 0;	

        $vo->save($link);

        $tr = TrainingRecord::loadFromDatabase($link, $_POST['tr_id']);
        $username = $tr->username;
        $fs_progress_id = $vo->id;
        $db=DB_NAME;
        $target_directory = "/$username/employer_contact/$fs_progress_id";
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