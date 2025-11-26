<?php
class save_glh_comments implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : ''; 
        $lesson_id = isset($_REQUEST['lesson_id']) ? $_REQUEST['lesson_id'] : ''; 

        $tr_username = DAO::getSingleValue($link, "SELECT username FROM tr WHERE tr.id = '{$tr_id}'");
        $valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

        
        $entry = DAO::getObject($link, "SELECT * FROM register_entries WHERE pot_id = '{$tr_id}' AND lessons_id = '{$lesson_id}'");
        if(! isset($entry->id) )
        {
            $entry = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM register_entries");
            foreach($records AS $key => $value)
                $entry->$value = null;
            $entry->pot_id = $tr_id;
            $entry->lessons_id = $lesson_id;

        }

        $entry->reflective_comments_learner = isset($_REQUEST['reflective_comments_learner']) ? $_REQUEST['reflective_comments_learner'] : '';

        DAO::saveObjectToTable($link, "register_entries", $entry);

        if( isset($_REQUEST['date']) && $_REQUEST['date'] != '' )
        {
            $glh = new stdClass();
            $glh->id = null;
            $glh->tr_id = $tr_id;
            $glh->date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
            $glh->duration_hours = isset($_REQUEST['duration_hours']) ? $_REQUEST['duration_hours'] : '';
            $glh->duration_minutes = isset($_REQUEST['duration_minutes']) ? $_REQUEST['duration_minutes'] : '';
            $glh->type = 18;
            $glh->comments = isset($_REQUEST['comments']) ? $_REQUEST['comments'] : '';
            $glh->created = date('Y-m-d H:i:s');
            $glh->modified = date('Y-m-d H:i:s');
    
            DAO::saveObjectToTable($link, "glh", $glh);
    
            if(isset($glh->id) && $glh->id != '')
            {
                $target_directory = "/{$tr_username}/GLH Diaries/{$glh->id}";
                $r = Repository::processFileUploads('uploaded_file', $target_directory, $valid_extensions);
            }
        }

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        echo <<<HTML
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sunesis</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">
<body>


<div class="jumbotron text-center">
  <h1 class="display-3">Thank You!</h1>
  <p class="lead"><strong>Your information has been saved successfully.</strong></p>
  <hr>
  <p class="lead">
    <img height="50px" class="headerlogo" src="$header_image1" />
  </p>
</div>

HTML;

    }
}