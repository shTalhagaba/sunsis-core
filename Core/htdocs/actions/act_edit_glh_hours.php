<?php
class edit_glh_hours implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $glh_id = isset($_REQUEST['glh_id']) ? $_REQUEST['glh_id'] : '';
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

        if(isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'])
        {
            if(isset($_REQUEST['glh_id']))
                echo $this->deleteGLH($link, $_REQUEST['glh_id']);
            else
                echo 'Missing query string argument.';
            exit;
        }

        if($tr_id == '')
            throw new Exception('Missing Training Record ID.');

        $_SESSION['bc']->add($link, "do.php?_action=edit_glh_hours&tr_id=" . $tr_id, "Add/Edit GLH Hours");

        if($glh_id == '')
        {
            // New record
            $vo = new GLH();
            $vo->tr_id = $tr_id;
            $page_title = "Add GLH Details";
            $sql = "SELECT id, CONCAT(firstnames, ' ', surname), NULL FROM users WHERE id = " . $_SESSION['user']->id . " ORDER BY firstnames; "; // reed asked for a change this means that anyone can book an appointment for him/herself and this should be preset and not editable.
            $assessors = DAO::getResultSet($link, $sql);

//	        $vo->date = '10/06/2020';
//	        $vo->time_from = '10:00';
//	        $vo->time_to = '11:00';
        }
        else
        {
            $vo = GLH::loadFromDatabase($link, $glh_id);
            $page_title = "Edit GLH Details";
            $today_date = new Date(date('Y-m-d'));
            $otj_date = new Date($vo->date);
        }

        // Dropdown arrays
        $sql = "SELECT id, description, null FROM lookup_otj_types ORDER BY description; ";
        $types = DAO::getResultSet($link, $sql);

	    $enable_save = true;
	    if($_SESSION['user']->type == User::TYPE_LEARNER || $_SESSION['user']->type == User::TYPE_SYSTEM_VIEWER)
		    $enable_save = false;

	    $pot_vo = TrainingRecord::loadFromDatabase($link, $tr_id);

	    $other_records = $this->renderOtherRecords($link, $pot_vo, $vo->id);

        if(DB_NAME=='am_city_skills')
        {
            $ddlHours = [];
            for($i = 0; $i < 100; $i++)
                $ddlHours[] = $i <= 9 ? ["0{$i}", $i] : [$i, $i];
        }
        else
        {
            $ddlHours = [];
            for($i = 0; $i < 24; $i++)
                $ddlHours[] = $i <= 9 ? ["0{$i}", $i] : [$i, $i];
   
        }
	    $ddlMinutes = [];
	    for($i = 0; $i <= 60; $i++)
		    $ddlMinutes[] = $i <= 9 ? ["0{$i}", $i] : [$i, $i];


	$files_html = $this->getFileDownloads($pot_vo, $glh_id);

        include('tpl_edit_glh_hours.php');
    }

    private function deleteGLH(PDO $link, $glh_id)
    {
        $result = DAO::execute($link, "DELETE FROM otj WHERE id = " . $glh_id);
        if($result > 0)
            return 'The record has been successfully deleted.';
        else
            return 'Operation failed.';
    }

	private function renderOtherRecords(PDO $link, TrainingRecord $tr, $exclude_id = '')
	{
		if($exclude_id == '')
			$records = DAO::getResultset($link, "SELECT * FROM glh WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
		else
			$records = DAO::getResultset($link, "SELECT * FROM glh WHERE tr_id = '{$tr->id}' AND id != '{$exclude_id}' ORDER BY id", DAO::FETCH_ASSOC);

		$html = '';
		if(count($records) == 0)
			return $html;

		foreach($records AS $row)
		{
			$html .= '<div class="well well-sm">';
			$html .= '<div class="table-responsive">';
			$html .= '<table class="table">';
			$html .= '<tr><th>Date:</th><td>' . Date::toShort($row['date']) . '</td></tr>';
			$html .= '<tr><th>Time:</th><td>' . $row['time_from'] . ' - ' . $row['time_to'] . '</td></tr>';
			$html .= '<tr><th>Duration:</th><td>' . $row['duration_hours'] . ' hours(s) ' . $row['duration_minutes'] . ' minutes</td></tr>';
			$html .= '<tr><th>Type:</th><td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_otj_types WHERE id = '{$row['type']}'") . '</td></tr>';
			$html .= '<tr><th>Comments:</th><td>' . $row['comments'] . '</td> </tr>';
			$html .= '</table> ';
			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}

	private function getFileDownloads(TrainingRecord $pot_vo, $glh_id, $section = null)
    {
        $learner_dir = Repository::getRoot().'/'.trim($pot_vo->username) . '/GLH Diaries/'.$glh_id;
        $files = Repository::readDirectory($learner_dir);
        if(count($files) == 0){
            return "";
        }

        $html = "";

        $html .= <<<HEREDOC
<div class="Directory">
<table class="table table-bordered" style="table-layout:fixed; width:570">
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


}
?>