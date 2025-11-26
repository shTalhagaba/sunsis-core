<?php
class edit_learner_crm_note implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
        $subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

        if($subaction == 'new_contact_type')
        {
            $this->new_contact_type($link);
            exit;
        }
        if($subaction == 'load_contact_type')
        {
            $this->load_contact_type($link);
            exit;
        }
        if($subaction == 'new_subject')
        {
            $this->new_subject($link);
            exit;
        }

        $_SESSION['bc']->add($link, "do.php?_action=edit_learner_crm_note&id=" . $id . "&tr_id=" . $tr_id, "Edit Learner CRM Note");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring");
        }

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);

        $html2 = $this->getFileDownloads($tr,$id);

        if($id == '')
        {
            // New record
            $vo = new LearnerCrmNote();
            $vo->name_of_person = DAO::getSingleValue($link, "select concat(firstnames,' ',surname) from tr where id = '$tr_id'");
            $vo->by_whom = $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname;
        }
        else
        {
            $vo = LearnerCrmNote::loadFromDatabase($link, $id);
        }

        // Dropdown arrays
        $sql = "SELECT id, description, null FROM lookup_crm_contact_initiator;";
        $contact_initiator = DAO::getResultSet($link, $sql);

        $sql = "SELECT id, description, null FROM lookup_crm_contact_type;";
        $contact_type = DAO::getResultSet($link, $sql);

        if(DB_NAME=='am_reed_demo' || DB_NAME=='ams' || DB_NAME=='am_reed')
            $subject = DAO::getResultSet($link, "SELECT DISTINCT lookup_crm_subject.`id`, lookup_crm_subject.description, NULL
FROM lookup_crm_subject
INNER JOIN crm_subjects_contracts ON crm_subjects_contracts.crm_subject_id = lookup_crm_subject.`id`
INNER JOIN contracts ON contracts.id = crm_subjects_contracts.contract_id
WHERE lookup_crm_subject.description != '' AND contracts.id = {$tr->contract_id}  ORDER BY description;");
        else
            $subject = DAO::getResultSet($link, "SELECT id, description, null FROM lookup_crm_subject WHERE description != '' ORDER BY description;");

        if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]) && $id == '')
        {
            $subject = DAO::getResultSet($link, "SELECT id, description, null FROM lookup_crm_subject WHERE description != '' AND candidate = '1' ORDER BY description;");
        }

        $by_whom_ddl = DAO::getResultset($link, "SELECT users.id, CONCAT(users.`firstnames`, ' ', users.`surname`, ' [', lookup_user_types.`description`, ']'), CONCAT(organisations.`legal_name`, ' - ', REPLACE(TRIM(TRAILING 's' FROM lookup_org_type.`org_type`), 'Client', 'System Owner'))
FROM users INNER JOIN lookup_user_types ON users.`type` = lookup_user_types.`id` INNER JOIN organisations ON users.`employer_id` = organisations.`id` INNER JOIN lookup_org_type ON organisations.`organisation_type` = lookup_org_type.id
WHERE users.type NOT IN (5, 26) ORDER BY organisations.`legal_name`, users.`firstnames`;");

        if(SystemConfig::getEntityValue($link, "module_eportfolio") || in_array(DB_NAME, ["am_baltic_demo", "am_baltic"]))
            include('tpl_edit_learner_crm_note_v2.php');
        else
            include('tpl_edit_learner_crm_note.php');
    }

    private function getFileDownloads(TrainingRecord $pot_vo, $id, $section = null)
    {
        $learner_dir = Repository::getRoot().'/'.trim($pot_vo->username) . '/crm/'.$id;
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

    public function new_contact_type(PDO $link)
    {
        $value = isset($_REQUEST['value'])?$_REQUEST['value']:'';
        $lookup_value = new stdClass();
        $lookup_value->id = null;
        $lookup_value->description = $value;
        DAO::saveObjectToTable($link, 'lookup_crm_contact_type', $lookup_value);
        unset($lookup_value);
    }

    public function load_contact_type(PDO $link)
    {
        header('Content-Type: text/xml');
        $sql = "SELECT id, description, null FROM lookup_crm_contact_type ORDER BY description";
        $st = $link->query($sql);
        if($st)
        {
            echo "<?xml version=\"1.0\" ?>\r\n";
            echo "<select>\r\n";
            // First entry is empty
            echo "<option value=\"\"></option>\r\n";
            while($row = $st->fetch())
            {
                echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
            }
            echo '</select>';
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public function new_subject(PDO $link)
    {
        $value = isset($_REQUEST['value'])?$_REQUEST['value']:'';
        $lookup_value = new stdClass();
        $lookup_value->id = null;
        $lookup_value->description = $value;
        DAO::saveObjectToTable($link, 'lookup_crm_subject', $lookup_value);
        unset($lookup_value);
    }
}
?>