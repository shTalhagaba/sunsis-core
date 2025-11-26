<?php
class complete_glh_comments implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : ''; 
        $lesson_id = isset($_REQUEST['l_id']) ? $_REQUEST['l_id'] : ''; 
        $key = isset($_REQUEST['key'])?$_REQUEST['key']:'';

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $lesson = DAO::getObject($link, "SELECT * FROM lessons WHERE id = '{$lesson_id}'");
        $group = DAO::getObject($link, "SELECT * FROM groups WHERE groups.id = '{$lesson->groups_id}'");

        $ob_header_image1 = SystemConfig::getEntityValue($link, 'ob_header_image1');
        $ob_header_image2 = SystemConfig::getEntityValue($link, 'ob_header_image2');

        $scroll_logic = 1;

        $header_image1 = SystemConfig::getEntityValue($link, "ob_header_image1");

        $provider = Organisation::loadFromDatabase($link, $tr->provider_id);

        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);

        $ddlHours = [];
	    for($i = 0; $i < 24; $i++)
		    $ddlHours[] = $i <= 9 ? ["0{$i}", $i] : [$i, $i];
	    $ddlMinutes = [];
	    for($i = 0; $i <= 60; $i++)
		    $ddlMinutes[] = $i <= 9 ? ["0{$i}", $i] : [$i, $i];;

        $courseId = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$tr->id}'");
        $course = Course::loadFromDatabase($link, $courseId);
        
        include_once('tpl_complete_glh_comments.php');
    }
}