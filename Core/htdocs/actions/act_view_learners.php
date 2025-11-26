<?php
class view_learners implements IAction
{
    public function execute(PDO $link)
    {

        $id = (int)isset($_REQUEST['id'])?$_REQUEST['id']:'';

        switch($id)
        {
            case 1:
                $a = "All learners";
                break;
            case 2:
                $a = "Learners in training";
                break;
            case 3:
                $a = "Learners not in training";
                break;
            case 4:
                $a = "Achievers";
                break;
            default:
                $a = "All learners";
                break;
        }

        $_SESSION['bc']->index=0;
        $_SESSION['bc']->add($link, "do.php?_action=view_learners&id=" . $id, "View " . $a);


        $view = ViewLearners::getInstance($link, $id);

        if(in_array(DB_NAME, ["am_duplex"]))
        {
            $view = ViewLearnersV2::getInstance($link);
            $view->refresh($link, $_REQUEST);
            require_once('tpl_view_learners_duplex.php');
        }
        else
        {
            $view = ViewLearners::getInstance($link, $id);
            $view->refresh($link, $_REQUEST);
            if(DB_NAME == "am_demo" || DB_NAME == "am_lead_demo")
                require_once('tpl_view_learners1.php');
            else
                require_once('tpl_view_learners.php');
        }
    }
}
?>