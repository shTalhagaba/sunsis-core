<?php
class save_pool_location implements IAction
{
    public function execute(PDO $link)
    {
        $location = new stdClass();
        $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM pool_locations");
        foreach($records AS $key => $value)
        {
            $location->$value = null;
            if(isset($_POST[$value]))
            {
                $location->$value = $_POST[$value];
            }
        }

        $count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM pool_locations WHERE pool_id={$location->pool_id} AND is_legal_address = 1;");
        if($count == 0)
        {
            $location->is_legal_address = 1;
        }

        if(isset($_POST['is_legal_address']))
            $location->is_legal_address = 1;

        if($location->is_legal_address == 1)
        {
            DAO::execute($link, "UPDATE pool_locations SET is_legal_address = 0 WHERE pool_id = '{$location->pool_id}'");
        }

        $location->short_name = strtolower($location->short_name);

        $loc = new GeoLocation();
        $loc->setPostcode($location->postcode, $link);
        $location->longitude = $loc->getLongitude();
        $location->latitude = $loc->getLatitude();
        $location->easting = $loc->getEasting();
        $location->northing = $loc->getNorthing();

        DAO::saveObjectToTable($link, "pool_locations", $location);

        http_redirect('do.php?_action=read_pool_organisation&id='.$location->pool_id);

    }
}
?>