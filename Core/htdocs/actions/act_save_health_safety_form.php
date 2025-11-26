<?php
class save_health_safety_form implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {

        DAO::transaction_start($link);
        try
        {
            $form_learner = new HealthSafetyForm();
            $form_learner->populate($_POST);

            if(!isset($_POST['assessment1']))
                $form_learner->assessment1 = "off";
            else
                $form_learner->assessment1 = "on";

            if(!isset($_POST['assessment2']))
                $form_learner->assessment2 = "off";
            else
                $form_learner->assessment2 = "on";

            if(!isset($_POST['assessment3']))
                $form_learner->assessment3 = "off";
            else
                $form_learner->assessment3 = "on";

            if(!isset($_POST['assessment5']))
                $form_learner->assessment5 = "off";
            else
                $form_learner->assessment5 = "on";

            if(!isset($_POST['assessment6']))
                $form_learner->assessment6 = "off";
            else
                $form_learner->assessment6 = "on";

            if(!isset($_POST['assessment7']))
                $form_learner->assessment7 = "off";
            else
                $form_learner->assessment7 = "on";

            if(!isset($_POST['assessment8']))
                $form_learner->assessment8 = "off";
            else
                $form_learner->assessment8 = "on";

            if(!isset($_POST['assessment13']))
                $form_learner->assessment13 = "off";
            else
                $form_learner->assessment13 = "on";

            if(!isset($_POST['assessment14']))
                $form_learner->assessment14 = "off";
            else
                $form_learner->assessment14 = "on";

            if(!isset($_POST['assessment15']))
                $form_learner->assessment15 = "off";
            else
                $form_learner->assessment15 = "on";

            if(!isset($_POST['assessment16']))
                $form_learner->assessment16 = "off";
            else
                $form_learner->assessment16 = "on";

            if(!isset($_POST['assessment17']))
                $form_learner->assessment17 = "off";
            else
                $form_learner->assessment17 = "on";

            if(!isset($_POST['enforcement_actions']))
                $form_learner->enforcement_actions = "off";
            else
                $form_learner->enforcement_actions = "on";

            $form_learner->save($link);
            DAO::transaction_commit($link);

        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }

        if(IS_AJAX)
        {
            header("Content-Type: text/plain");
            echo $form_learner->id;
        }
        elseif(isset($_SESSION['user']->type))
        {
            //http_redirect('do.php?_action=read_training_record&id='.$vo->tr_id);
        }
        else
        {
            echo "Saved";
        }
    }

}
?>



