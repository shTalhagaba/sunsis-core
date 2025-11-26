<?php
class edit_allocation implements IAction
{
    public function execute(PDO $link)
    {
        // Validate data entry
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_allocation&id=" . $id, "Add/ Edit Allocation");

        if($id !== '' && !is_numeric($id))
        {
            throw new Exception("You must specify a numeric id in the querystring");
        }

        if($id == '')
        {
            // New record
            $vo = new Allocation();
        }
        else
        {
            $vo = Allocation::loadFromDatabase($link, $id);
        }

        include('tpl_edit_allocation.php');
    }
}
?>