<?php
class save_framework implements IAction
{
    public function execute(PDO $link)
    {
        $framework = new Framework();

        $framework->populate($_POST);
        $framework->parent_org = $_SESSION['user']->employer_id;
        $framework->clients = $_SESSION['user']->username;

        $framework->active = 0;
        if ( isset($_POST['active']) )
        {
            $framework->active = 1;
        }

        $framework->track = 0;
        if ( isset($_POST['track']) )
        {
            $framework->track = 1;
        }

        $tnp1 = [];
        if(isset($_POST['total_tnp']) && intval($_POST['total_tnp']) > 0)
        {
            for($i = 1; $i <= $_POST['total_tnp']; $i++)
            {
                if(trim($_POST['price_description_'.$i]) != '')
                {
                    $tnp1[] = [
                        'description' => trim($_POST['price_description_'.$i]),
                        'cost' => $_POST['price_cost_'.$i],
                        'reduce' => isset($_POST['price_include_'.$i]) ? 1 : 0 ,
                    ];
                }
            }
        }
        // if there is only one tnp price item
        if(count($tnp1) == 0)
        {
            if(trim($_POST['price_description_1']) != '')
            {
                $tnp1[] = [
                    'description' => trim($_POST['price_description_1']),
                    'cost' => $_POST['price_cost_1'],
                    'reduce' => isset($_POST['price_include_1']) ? 1 : 0 ,
                ];
            }
        }
        
        $additional_prices = [];
        if(isset($_POST['total_additional_prices']) && intval($_POST['total_additional_prices']) > 0)
        {
            for($i = 1; $i <= $_POST['total_additional_prices']; $i++)
            {
                if(trim($_POST['additional_prices_description_'.$i]) != '')
                {
                    $additional_prices[] = [
                        'description' => trim($_POST['additional_prices_description_'.$i]),
                        'cost' => $_POST['additional_prices_cost_'.$i],
                    ];
                }
            }
        }
        // if there is only one additional price item
        if(count($additional_prices) == 0)
        {
            if(trim($_POST['additional_prices_description_1']) != '')
            {
                $additional_prices[] = [
                    'description' => trim($_POST['additional_prices_description_1']),
                    'cost' => $_POST['additional_prices_cost_1'],
                ];
            }
        }

        $framework->tnp1 = json_encode($tnp1);
        $framework->additional_prices = json_encode($additional_prices);

        if($_POST['id'] != '')
        {
            $existing_record = Framework::loadFromDatabase($link, $_POST['id']);
            $log_string = $existing_record->buildAuditLogString($link, $framework);
            if($log_string != '')
            {
                $note = new Note();
                $note->subject = "Framework record edited";
                $note->note = $log_string;
            }
        }
        else
        {
            $note = new Note();
            $note->subject = "New framework added";
            $note->note = json_encode($_POST);
        }

        $rpl_percentages = [];
        for($i = 1; $i <= 5; $i++)
        {
            $score = "score_{$i}";
            $rpl_percentages[$score] = isset($_POST[$score]) ? $_POST[$score] : 0;
        }
        $framework->rpl_percentages = json_encode($rpl_percentages);

        DAO::transaction_start($link);
        try
        {
            $framework->save($link);

            if(isset($note) && !is_null($note))
            {
                $note->is_audit_note = true;
                $note->parent_table = 'frameworks';
                $note->parent_id = $framework->id;
                $note->created = date('Y-m-d H:i:s');
                $note->save($link);
            }

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }


        $_SESSION['bc']->index = $_SESSION['bc']->index-1;
        http_redirect('do.php?_action=view_framework_qualifications&id='.$framework->id);
    }
}
?>