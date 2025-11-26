<?php
class detach_tag implements IAction
{
	public function execute(PDO $link)
	{
        if(isset($_REQUEST['tag_id']) && $_REQUEST['taggable_id'] && $_REQUEST['taggable_type'])
        {
            DAO::execute($link, "DELETE FROM taggables WHERE tag_id = '{$_REQUEST['tag_id']}' AND taggable_type = '{$_REQUEST['taggable_type']}' AND taggable_id = '{$_REQUEST['taggable_id']}' ");
        }

        echo true;
    }
}