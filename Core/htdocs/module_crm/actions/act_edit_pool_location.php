<?php
class edit_pool_location implements IAction
{
    public function execute(PDO $link)
    {
        $loc_id = isset($_GET['id']) ? $_GET['id'] : '';
        $pool_id = isset($_GET['pool_id']) ? $_GET['pool_id'] : '';

        $_SESSION['bc']->add($link, "do.php?_action=edit_pool_location&id={$loc_id}&pool_id={$pool_id}", "Add/Edit Location");

        if( ($pool_id == '') && ($loc_id == '') )
        {
            throw new Exception("Either querystring argument id or pool_id (or both) must be specified");
        }

        if($loc_id !== '' && !is_numeric($loc_id))
        {
            throw new Exception("Querystring argument id must be numeric");
        }

        if($pool_id !== '' && !is_numeric($pool_id))
        {
            throw new Exception("Querystring argument pool_id must be numeric");
        }

        $pool = DAO::getObject($link, "SELECT * FROM pool WHERE pool.id = '{$pool_id}'");
        if(!isset($pool->id))
        {
            throw new Exception("Invalid pool id");
        }

        if($loc_id == '')
        {
            $location = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM pool_locations");
            foreach($records AS $key => $value)
                $location->$value = null;

            $location->pool_id = $pool->id;

            $locations_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM pool_locations WHERE pool_locations.pool_id = '{$pool_id}'");
            if($locations_count == 0)
                $location->is_legal_address = 1;
        }
        else
        {
            $location = DAO::getObject($link, "SELECT * FROM pool_locations WHERE id = '{$loc_id}'");
            if(!isset($location->id))
                throw new Exception("Invalid pool location id {$loc_id}");
        }

        include('tpl_edit_pool_location.php');
    }

    private function renderOtherLocations(PDO $link, $location)
    {
        $records = DAO::getResultset($link, "SELECT * FROM pool_locations WHERE pool_locations.pool_id = '{$location->pool_id}' AND pool_locations.id != '{$location->id}' ORDER BY is_legal_address DESC , id", DAO::FETCH_ASSOC);
        if(count($records) == 0)
        {
            echo '<i class="fa fa-info-circle"></i> No other locations.';
        }
        else
        {
            foreach($records AS $loc)
            {
                $tick = $loc['is_legal_address'] == '1' ? '<i class="fa fa-check fa-lg" title="Main Location"></i> ' : '';
                echo $loc['full_name'] != '' ? '<span class="text-blue text-bold">' . $loc['full_name'] . '</span> ' . $tick . '<br>' : '';
                echo $loc['short_name'] != '' ? $loc['short_name'] . '<br>' : '';
                echo $loc['address_line_1'] != '' ? $loc['address_line_1'] . '<br>' : '';
                echo $loc['address_line_2'] != '' ? $loc['address_line_2'] . '<br>' : '';
                echo $loc['address_line_3'] != '' ? $loc['address_line_3'] . '<br>' : '';
                echo $loc['address_line_4'] != '' ? $loc['address_line_4'] . '<br>' : '';
                echo $loc['postcode'] != '' ? '<i class="fa fa-map-marker"></i> ' . $loc['postcode'] . '<br>' : '';
                echo $loc['telephone'] != '' ? '<i class="fa fa-phone"></i> ' . $loc['telephone'] . '<br>' : '';
                echo $loc['fax'] != '' ? '<i class="fa fa-fax"></i> ' . $loc['fax'] : '';
                echo '<hr> ';
            }
        }
    }
}
?>