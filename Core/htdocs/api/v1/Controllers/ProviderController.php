<?php
namespace Controllers;

use DAO;
use Helpers\Database;
use Helpers\Response;
use HttpRequest;
use SQLStatement;

class ProviderController
{
    private $link = null;

    public function __construct() 
    {
        $this->link = Database::getInstance()->getConnection();
    }

    public function show(HttpRequest $request, $id)
    {
        return $this->index($request, $id);
    }

    public function index(HttpRequest $request, $providerId = null)
    {
        $sql = "
        SELECT
  organisations.id AS provider_id,
  organisations.legal_name
FROM
  organisations
ORDER BY organisations.`legal_name`
        ";

        $sql = new SQLStatement($sql);
        $sql->setClause("WHERE organisations.organisation_type = 3");
        $sql->setClause("WHERE organisations.active = 1");
        if(!is_null($providerId))
        {
            $sql->setClause("WHERE organisations.id = {$providerId}");
        }

        $result = DAO::getResultset($this->link, $sql->__toString(), DAO::FETCH_ASSOC);
        $providers = [];

        foreach($result AS $row)
        {
            $provider = [
                'ProviderID' => $row['provider_id'],
                'ProviderName' => $row['legal_name'],
                'Locations' => [],
            ];

            $locationsSql = "
            SELECT
    locations.id AS location_id,
    locations.`full_name`,
    locations.`address_line_1`,
    locations.`address_line_2`,
    locations.`address_line_3`,
    locations.`address_line_4`,
    locations.`postcode`
    FROM
    locations
    WHERE locations.`organisations_id` = '{$row['provider_id']}'
            ";

            $locationsResult = DAO::getResultset($this->link, $locationsSql, DAO::FETCH_ASSOC);
            foreach($locationsResult AS $locationRow)
            {
                $provider['Locations'][] = [
                    'LocationID' => $locationRow['location_id'],
                    'LocationTitle' => $locationRow['full_name'],
                    'AddressLine1' => $locationRow['address_line_1'],
                    'AddressLine2' => $locationRow['address_line_2'],
                    'AddressLine3' => $locationRow['address_line_3'],
                    'AddressLine4' => $locationRow['address_line_4'],
                    'Postcode' => $locationRow['postcode'],
                ];
            }

            $providers[] = $provider;
        }

        Response::success($providers);
    }
}