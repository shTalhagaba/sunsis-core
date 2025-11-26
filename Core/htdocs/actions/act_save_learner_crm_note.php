<?php
class save_learner_crm_note implements IAction
{
    public function execute(PDO $link)
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        $vo = new LearnerCrmNote();
        $vo->populate($_REQUEST);
        $vo->notify_assessor = isset($_REQUEST['notify_assessor'])?1:0;
        $vo->concerns = isset($_REQUEST['concerns'])?1:0;
        $vo->save($link);

        $tr = TrainingRecord::loadFromDatabase($link, $_REQUEST['tr_id']);
        $username = $tr->username;
        $fs_progress_id = $vo->id;
        $db=DB_NAME;
        $target_directory = "/$username/crm/$id";
        $valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

        $r = Repository::processFileUploads('uploaded_file', $target_directory, $valid_extensions);


        http_redirect($_SESSION['bc']->getPrevious());
    }
}
?>