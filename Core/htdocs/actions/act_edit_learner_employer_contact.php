<?php
class edit_learner_employer_contact implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $employer_contact_id = isset($_REQUEST['employer_contact_id']) ? $_REQUEST['employer_contact_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['employer_contact_id']))
                echo $this->deleteLearnerEmployerContactRecord($link, $_REQUEST['employer_contact_id']);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_learner_employer_contact&tr_id=" . $tr_id, "Add/Edit Employer Contact");

        if($employer_contact_id == '')
        {
            // New record
            $vo = new EmployerContact();
            $vo->tr_id = $tr_id;
            $page_title = "Add Employer Contact Details";
            $exam_status = "";
        }
        else
        {
            $vo = EmployerContact::loadFromDatabase($link, $employer_contact_id);
            $page_title = "Edit Employer Contact Details";
            $today_date = new Date(date('Y-m-d'));
        }

        $contact_type_ddl = DAO::getResultset($link, "SELECT id, description FROM lookup_contact_type ORDER BY description");

        $enable_save = true;
        if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER)
            $enable_save = false;

        if($_SESSION['user']->id == 23428)
            $enable_save = true;

        // Cancel button URL
        $js_cancel = "window.location.replace('do.php?_action=read_training_record&webinars_tab=1&id=$tr_id');";

        $pot_vo = TrainingRecord::loadFromDatabase($link,$tr_id);
        $html2 = $this->getFileDownloads($pot_vo,$employer_contact_id);

        $progression_opportunities_ddl = array(
            array('1', 'Yes'),
            array('2', 'No'),
            array('3', 'Unsure at this time'),
        );


        include('tpl_edit_learner_employer_contact.php');
    }

    private function getFileDownloads(TrainingRecord $pot_vo, $employer_contact_id, $section = null)
    {
        $learner_dir = Repository::getRoot().'/'.trim($pot_vo->username) . '/employer_contact/'.$employer_contact_id;
        $files = Repository::readDirectory($learner_dir);
        if(count($files) == 0){
            return "";
        }

        $html = "";

        $html .= <<<HEREDOC
<div class="Directory">
<table cellspacing="0" style="table-layout:fixed; width:570">
<col width="310"/><col width="70"/><col width="170"/>
<tr>
	<th>Filename</th><th>Size</th><th>Upload Date</th><th>&nbsp;</th>
</tr>
HEREDOC;

        /* @var $f RepositoryFile */
        foreach($files as $f)
        {
            if($f->isDir()){
                continue;
            }
            $html .= "<tr>\r\n";
            $html .= '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$f->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$f->getName()).'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="70">'.Repository::formatFileSize($f->getSize()).'</td>';
            $html .= '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $f->getModifiedTime()).'</td>';
            if($_SESSION['user']->isAdmin())
            {
                $html .= '<td align="right" width="20"><img src="/images/trash_can.png" title="Delete file" onclick="deleteFile(\''.$f->getRelativePath().'\');" style="cursor:pointer"/></td>';
            }
            else
            {
                $html .= '<td align="right" width="20">&nbsp;</td>';
            }
            $html .= "\r\n</tr>\r\n";
        }

        $html .= "</table>\r\n";
        $html .= "</div>\r\n";

        return $html;
    }

    private function deleteLearnerEmployerContactRecord(PDO $link, $employer_contact_id)
    {
        $result = DAO::execute($link, "DELETE FROM employer_contact WHERE id = " . $employer_contact_id);
        if($result > 0)
            return 'The record has been successfully deleted.';
        else
            return 'Operation failed.';
    }
}
?>