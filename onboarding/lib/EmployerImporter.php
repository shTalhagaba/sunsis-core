<?php


class EmployerImporter
{
    public static function import(PDO $link, $absolute_file_path, $delimiter = ',')
    {
        if(!is_file($absolute_file_path))
        {
            throw new Exception('File not found');
        }

        $organisation_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'organisations'");
        $location_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'locations'");
        $primary_contact_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'primary_contact'");
        $finance_contact_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'finance_contact'");
        $levy_contact_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'levy_contact'");
        $location_contact_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'location_contact'");

        $importer = new CsvImporter($absolute_file_path, $delimiter, true);

        $result_status = [
            'status' => null,
            'description' => null,
            'header' => $importer->header,
        ];

        foreach(["employer_code", "edrs", "funding_type", "employer_type", "need_admin_service", "code"] AS $h)
        {
            if(!$importer->headerExists($organisation_mapping[$h]))
            {
                $result_status['status'] = 'error';
                $result_status['description'] = 'Missing header: ' . $organisation_mapping[$h];
                return $result_status;
            }
        }

        $rows = $importer->get();

        $codes = DAO::getLookupTable($link, "SELECT description, code FROM lookup_employer_size");

        $employers_created = 0;
        $employers_updated = 0;

        DAO::transaction_start($link);
        try
        {
            foreach($rows AS $row)
            {
                if(!is_array($row))
                    continue;

                $employer_code = $row[$organisation_mapping['employer_code']];
                $edrs = $row[$organisation_mapping['edrs']];

                foreach(["legal_name", "edrs",] AS $v)
                {
                    if(is_null(trim($organisation_mapping[$v])) || trim($organisation_mapping[$v]) == '')
                        continue 2;
                }

                $employer_id = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE employer_code = '{$employer_code}' AND organisation_type = '2'");
                if($employer_id == '')
                {
                    $employer_id = DAO::getSingleValue($link, "SELECT id FROM organisations WHERE edrs = '{$edrs}' AND organisation_type = '2'");
                }
                if($employer_id != '')
                {
                    $employer = Employer::loadFromDatabase($link, $employer_id);
                }
                else
                {
                    $employer = new Employer();
                }
                if($employer->id == '')
                {
                    $location = new Location();
                    $location->is_legal_address = 1;
                }
                else
                {
                    $location = $employer->getMainLocation($link);
                }

                $primary_contact_saved = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE org_id = '{$employer->id}' AND job_role = '" . OrganisationContact::TYPE_PRIMARY . "'");
                $primary_contact = new OrganisationContact();
                if(isset($primary_contact_saved->contact_id))
                {
                    $primary_contact = OrganisationContact::loadFromDatabase($link, $primary_contact_saved->contact_id);
                }

                $finance_contact_saved = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE org_id = '{$employer->id}' AND job_role = '" . OrganisationContact::TYPE_FINANCE . "'");
                $finance_contact = new OrganisationContact();
                if(isset($finance_contact_saved->contact_id))
                {
                    $finance_contact = OrganisationContact::loadFromDatabase($link, $finance_contact_saved->contact_id);
                }

                $levy_contact_saved = DAO::getObject($link, "SELECT * FROM organisation_contacts WHERE org_id = '{$employer->id}' AND job_role = '" . OrganisationContact::TYPE_LEVY . "'");
                $levy_contact = new OrganisationContact();
                if(isset($levy_contact_saved->contact_id))
                {
                    $levy_contact = OrganisationContact::loadFromDatabase($link, $levy_contact_saved->contact_id);
                }

                foreach($organisation_mapping AS $field => $value)
                {
                    if($field == 'code')
                    {
                        $employer->$field = isset($codes[$row[$value]]) ? $codes[$row[$value]] : null;
                    }
                    elseif(in_array($field, ["agreement_expiry"]))
                    {
                        $employer->$field = isset($row[$value]) ? Date::toMySQL($row[$value]) : null;
                    }
                    elseif(in_array($field, ["need_admin_service"]))
                    {
                        if(strtolower($row[$value]) == 'yes')
                            $employer->$field = "Y";
                        elseif(strtolower($row[$value]) == 'no')
                            $employer->$field = "N";
                    }
                    elseif($field == "employer_type")
                    {
                        if($row[$value] == 'Existing (if previous opportunities)')
                            $employer->$field = "EE";
                        elseif($row[$value] == 'New (if no history)')
                            $employer->$field = "NE";
                    }
                    elseif($field == "funding_type")
                    {
                        if($row[$value] == 'Co-Investor')
                            $employer->$field = "CO";
                        elseif($row[$value] == 'Levy')
                            $employer->$field = "L";
                    }
                    else
                    {
                        $employer->$field = isset($row[$value]) ? $row[$value] : null;
                    }
                }
                $employer->trading_name = $employer->trading_name == '' ? $employer->legal_name : $employer->trading_name;
                $employer->short_name = substr(strtoupper(str_replace(' ', '', $employer->legal_name)), 0, 11);

                foreach($location_mapping AS $field => $value)
                {
                    $location->$field = isset($row[$value]) ? $row[$value] : null;
                }
                $location->full_name = $location->full_name == '' ? 'Main Site' : $location->full_name;
                $location->short_name = substr(strtoupper(str_replace(' ', '', $employer->legal_name)), 0, 11);

                foreach($primary_contact_mapping AS $field => $value)
                {
                    $primary_contact->$field = isset($row[$value]) ? $row[$value] : null;
                }
                $primary_contact->job_role = OrganisationContact::TYPE_PRIMARY;
                $location->contact_name = $primary_contact->contact_name;
                $location->contact_telephone = $primary_contact->contact_telephone;
                $location->contact_email = $primary_contact->contact_email;
                $location->contact_mobile = $primary_contact->contact_mobile;

                foreach($finance_contact_mapping AS $field => $value)
                {
                    $finance_contact->$field = isset($row[$value]) ? $row[$value] : null;
                }
                $finance_contact->job_role = OrganisationContact::TYPE_FINANCE;

                foreach($levy_contact_mapping AS $field => $value)
                {
                    $levy_contact->$field = isset($row[$value]) ? $row[$value] : null;
                }
                $levy_contact->job_role = OrganisationContact::TYPE_LEVY;

                foreach(["edrs", "legal_name"] AS $v)
                {
                    if(is_null($employer->$v) || trim($employer->$v) == '')
                        continue 2;
                }

                $new_employer = $employer->id == '' ? true : false;

                $employer->save($link);

                if($new_employer)
                    $employers_created++;
                else
                    $employers_updated++;

                $location->organisations_id = $employer->id;
                $location->save($link);

                if($primary_contact->contact_name != '')
                {
                    $primary_contact->org_id = $employer->id;
                    $primary_contact->save($link);
                }

                if($finance_contact->contact_name != '')
                {
                    $finance_contact->org_id = $employer->id;
                    $finance_contact->save($link);
                }

                if($levy_contact->contact_name != '')
                {
                    $levy_contact->org_id = $employer->id;
                    $levy_contact->save($link);
                }

            }

            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            $result_status['status'] = 'error';
            $result_status['description'] = json_encode($row) . '<br>' . $ex->getMessage();
            return $result_status;
        }

        $result_status['status'] = 'success';
        $result_status['description'] = count($rows) . " employer records processed. " .
            "{$employers_created} created, and {$employers_updated} updated";

        return $result_status;
    }

}