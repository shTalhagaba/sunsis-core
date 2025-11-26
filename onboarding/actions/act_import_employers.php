<?php
class import_employers extends ActionController
{
    public function indexAction(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=import_employers", "Import Employers");


        require_once('tpl_import_employers.php');
    }

    public function importFromDirectoryAction(PDO $link)
    {
        $organisation_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'organisations'");
        $location_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'locations'");
        $primary_contact_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'primary_contact'");
        $finance_contact_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'finance_contact'");
        $levy_contact_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'levy_contact'");
        $location_contact_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM employer_import_file_header WHERE sunesis_table_name = 'location_contact'");

//        $directory = Repository::getRoot() . "/employers/260";
//        if(SOURCE_LOCAL)
//            $directory = "C:/Users/ianss/Downloads/Barnsley College/employers";

        $directory = Repository::getRoot() . "/DataImports/employers";

        $directory_files = Repository::readDirectory($directory);
        if(count($directory_files) == 0)
        {
            return;
        }

        // readDirectory returns array of files sort by latest file
        $input_file = $directory_files[0];

        // check the latest successful import file timestamp
        $latest_successful_file_timestamp = DAO::getSingleValue($link, "SELECT import_file_modified_time FROM data_imports WHERE import_entity = 'employer' AND import_successful = '1' ORDER BY import_timestamp DESC LIMIT 1");
        if($latest_successful_file_timestamp >= $input_file->getModifiedTime())
        {
            echo 'Last Successful File Timestamp: ' . date('d/m/Y H:i:s.', $latest_successful_file_timestamp) . '<br>';
            echo 'This File Timestamp: ' . date('d/m/Y H:i:s.', $input_file->getModifiedTime()) . '<br>';
            pre('So, import aborted');
        }

        $file_read = fopen($input_file->getAbsolutePath(),"r");
        $header_line = fgetcsv($file_read);

        //save in data_imports
        $import = (object)[
            "import_id" => null,
            "import_file" => $input_file->getName(),
            "import_file_modified_time" => $input_file->getModifiedTime(),
            "import_file_extension" => $input_file->getExtension(),
            "import_file_size" => $input_file->getSize(),
            "import_file_header" => json_encode($header_line),
            "import_successful" => null,
            "import_entity" => "employer",
        ];

        DAO::saveObjectToTable($link, "data_imports", $import);

        $codes = DAO::getLookupTable($link, "SELECT description, code FROM lookup_employer_size");

        DAO::transaction_start($link);
        try
        {
            while (!feof($file_read))
            {
                $row = fgetcsv($file_read);
                if(is_array($row))
                {
                    $row = array_combine($header_line, $row);

                    $employer_code = $row[$organisation_mapping['employer_code']];
                    $edrs = $row[$organisation_mapping['edrs']];
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
                            $employer->$field = isset($codes[$row[$value]]) ? $codes[$row[$value]] : '';
                        }
                        elseif(in_array($field, ["agreement_expiry"]))
                        {
                            $employer->$field = Date::toMySQL($row[$value]);
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
                            $employer->$field = $row[$value];
                        }
                    }
                    $employer->trading_name = $employer->trading_name == '' ? $employer->legal_name : $employer->trading_name;
                    $employer->short_name = substr(strtoupper(str_replace(' ', '', $employer->legal_name ?? '')), 0, 11);

                    foreach($location_mapping AS $field => $value)
                    {
                        $location->$field = $row[$value];
                    }
                    $location->full_name = $location->full_name == '' ? 'Main Site' : $location->full_name;
                    $location->short_name = substr(strtoupper(str_replace(' ', '', $employer->legal_name ?? '')), 0, 11);

                    foreach($primary_contact_mapping AS $field => $value)
                    {
                        $primary_contact->$field = $row[$value];
                    }
                    $primary_contact->job_role = OrganisationContact::TYPE_PRIMARY;
                    $location->contact_name = $primary_contact->contact_name;
                    $location->contact_telephone = $primary_contact->contact_telephone;
                    $location->contact_email = $primary_contact->contact_email;
                    $location->contact_mobile = $primary_contact->contact_mobile;

                    foreach($finance_contact_mapping AS $field => $value)
                    {
                        $finance_contact->$field = $row[$value];
                    }
                    $finance_contact->job_role = OrganisationContact::TYPE_FINANCE;

                    foreach($levy_contact_mapping AS $field => $value)
                    {
                        $levy_contact->$field = $row[$value];
                    }
                    $levy_contact->job_role = OrganisationContact::TYPE_LEVY;


                    $employer->save($link);

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
            }

            $import->import_successful = 1;
            DAO::saveObjectToTable($link, "data_imports", $import);

            DAO::transaction_commit($link);
        }
        catch (Exception $ex)
        {
            DAO::transaction_rollback($link);
            throw new Exception(json_encode($row) . '<br>' . $ex->getMessage());
        }
        fclose($file_read);

        //pre('Import Successful');
        http_redirect('do.php?_action=import_employers');
    }

    public function importAction(PDO $link)
    {
        if(!isset($_FILES['file_employers']))
        {
            throw new Exception("No file chosen");
        }

        $target_directory = 'DataImports/employers';
        $valid_extensions = array('csv');
        $r = Repository::processFileUploads('file_employers', $target_directory, $valid_extensions);
        if(!isset($r[0]))
            throw new Exception("File not uploaded, please try again.");


        $this->importFromDirectoryAction($link);
    }

    public function removeEntryAction(PDO $link)
    {
        $import_id = isset($_REQUEST['import_id']) ? $_REQUEST['import_id'] : '';
        if($import_id == '')
            return;

        $import = DAO::getObject($link, "SELECT * FROM data_imports WHERE import_id = '{$import_id}'");
        if(!isset($import->import_id))
            return;

        if(is_file(Repository::getRoot() . "/DataImports/employers/" . $import->import_file))
        {
            unlink(Repository::getRoot() . "/DataImports/employers/" . $import->import_file);
            DAO::execute($link, "DELETE FROM data_imports WHERE import_id = '{$import->import_id}'");
        }
    }

}
?>