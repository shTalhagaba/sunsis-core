<?php
class save_pool_organisation implements IAction
{
    public function execute(PDO $link)
    {
        $pool = new stdClass();
        $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM pool");
        foreach($records AS $key => $value)
            $pool->$value = isset($_POST[$value]) ? $_POST[$value] : null;
        $pool->organisation_type = Organisation::TYPE_EMPLOYER;

        if($pool->id == '')
        {
            $mainLocation = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM pool_locations");
            foreach($records AS $key => $value)
                $mainLocation->$value = null;
        }
        else
        {
            $mainLocation = DAO::getObject($link, "SELECT * FROM pool_locations WHERE pool_id = '{$pool->id}' AND is_legal_address = '1'");
            if(is_null($mainLocation))
            {
                $mainLocation = new stdClass();
                $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM pool_locations");
                foreach($records AS $key => $value)
                    $mainLocation->$value = null;
                $mainLocation->is_legal_address = 1;
            }
        }

        $mainLocation->full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
        $mainLocation->address_line_1 = isset($_POST['address_line_1']) ? $_POST['address_line_1'] : '';
        $mainLocation->address_line_2 = isset($_POST['address_line_2']) ? $_POST['address_line_2'] : '';
        $mainLocation->address_line_3 = isset($_POST['address_line_3']) ? $_POST['address_line_3'] : '';
        $mainLocation->address_line_4 = isset($_POST['address_line_4']) ? $_POST['address_line_4'] : '';
        $mainLocation->postcode = isset($_POST['postcode']) ? $_POST['postcode'] : '';
        $mainLocation->telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
        $mainLocation->fax = isset($_POST['fax']) ? $_POST['fax'] : '';

        $mainLocation->contact_title = isset($_POST['contact_title']) ? $_POST['contact_title'] : '';
        $mainLocation->contact_name = isset($_POST['contact_name']) ? $_POST['contact_name'] : '';
        $mainLocation->contact_mobile = isset($_POST['contact_mobile']) ? $_POST['contact_mobile'] : '';
        $mainLocation->contact_telephone = isset($_POST['contact_telephone']) ? $_POST['contact_telephone'] : '';
        $mainLocation->contact_email = isset($_POST['contact_email']) ? $_POST['contact_email'] : '';

        DAO::transaction_start($link);
        try{
            DAO::saveObjectToTable($link, 'pool', $pool);

            $mainLocation->pool_id = $pool->id;
            DAO::saveObjectToTable($link, 'pool_locations', $mainLocation);

            DAO::transaction_commit($link);
        }
        catch (Exception $ex){
            DAO::transaction_rollback($link);
            throw new Exception($ex->getMessage());
        }

        http_redirect("do.php?_action=read_pool_organisation&id={$pool->id}");
    }
}