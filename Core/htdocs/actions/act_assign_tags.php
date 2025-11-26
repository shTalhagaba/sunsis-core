<?php
class assign_tags implements IAction
{
	public function execute(PDO $link)
	{
        $taggable_type = isset($_REQUEST['taggable_type']) ? $_REQUEST['taggable_type'] : '';
        $taggable_id = isset($_REQUEST['taggable_id']) ? $_REQUEST['taggable_id'] : '';
        $tag_id = isset($_REQUEST['tag']) ? $_REQUEST['tag'] : '';
        $new_tag_name = isset($_REQUEST['new_tag']) ? $_REQUEST['new_tag'] : '';

        if($tag_id == '' && $new_tag_name == '')
        {
            http_redirect($_SESSION['bc']->getCurrent());    
        }

        $taggable = new stdClass();
        $taggable->taggable_type = $taggable_type;
        $taggable->taggable_id = $taggable_id;

        if($tag_id != '')
        {
            $taggable->tag_id = $tag_id;
        }
        else
        {
            $new_tag = new Tag();
            $new_tag->name = $new_tag_name;
            $new_tag->type = $taggable_type;
            $new_tag->save($link);

            $taggable->tag_id = $new_tag->id;
        }

        DAO::saveObjectToTable($link, "taggables", $taggable);

        http_redirect($_SESSION['bc']->getCurrent());
    }
}