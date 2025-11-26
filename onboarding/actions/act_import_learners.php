<?php
class import_learners extends ActionController
{
    public function indexAction(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=import_learners", "Import Learners");


        require_once('tpl_import_learners.php');
    }

    public function importLearnersFromDirectoryAction(PDO $link)
    {
        $employer_type = Organisation::TYPE_EMPLOYER;
        $employers = [];
        $employers_lookup_sql = <<<SQL
SELECT employers.edrs, employers.id AS employer_id, locations.id AS location_id 
FROM organisations AS employers INNER JOIN locations ON employers.id = locations.organisations_id 
WHERE employers.organisation_type = '$employer_type'
SQL;
        $employers_results = DAO::getResultset($link, $employers_lookup_sql, DAO::FETCH_ASSOC);
        foreach($employers_results AS $employer_row)
        {
            $employers[$employer_row['edrs']] = (object)[
                'employer_id' => $employer_row['employer_id'],
                'location_id' => $employer_row['location_id'],
            ];
        }

        $provider_type = Organisation::TYPE_TRAINING_PROVIDER;
        $providers_lookup_sql = <<<SQL
SELECT providers.id AS provider_id, locations.id AS location_id 
FROM organisations AS providers INNER JOIN locations ON providers.id = locations.organisations_id 
WHERE providers.organisation_type = '$provider_type'
SQL;
        $providers = DAO::getLookupTable($link, $providers_lookup_sql);

        $frameworks = [];
        $frameworks_sql = <<<SQL
SELECT
  frameworks.*,
  (SELECT
    framework_qualifications.`evidences`
  FROM
    framework_qualifications
  WHERE framework_qualifications.`framework_id` = frameworks.`id`
    AND framework_qualifications.`main_aim` = 1) AS main_aim_evidences,
  (SELECT
    ROUND(MaxEmployerLevyCap)
  FROM
    lars201718.`Core_LARS_ApprenticeshipFunding`
  WHERE ApprenticeshipType = 'STD'
    AND ApprenticeshipCode = frameworks.`StandardCode`
  ORDER BY EffectiveFrom DESC
  LIMIT 0, 1) AS funding_band_maximum,
  (SELECT
    ROUND(Duration)
  FROM
    lars201718.`Core_LARS_ApprenticeshipFunding`
  WHERE ApprenticeshipType = 'STD'
    AND ApprenticeshipCode = frameworks.`StandardCode`
  ORDER BY EffectiveFrom DESC
  LIMIT 0, 1) AS recommended_duration
FROM
  frameworks
WHERE programme_code IS NOT NULL
SQL;

        $frameworks_results = DAO::getResultset($link, $frameworks_sql, DAO::FETCH_ASSOC);
        foreach($frameworks_results AS $framework_row)
        {
            $frameworks[$framework_row['programme_code']] = (object)$framework_row;
        }

        $learner_mapping = DAO::getLookupTable($link, "SELECT sunesis_cell_header AS id, file_cell_header AS description FROM learner_import_file_header WHERE sunesis_table_name = 'ob_learners'");

//        $directory = Repository::getRoot() . "/employers/260";
//        if(SOURCE_LOCAL)
//            $directory = "C:/Users/ianss/Downloads/Barnsley College/learners";

        $directory = Repository::getRoot() . "/DataImports/learners";

        $directory_files = Repository::readDirectory($directory);
        if(count($directory_files) == 0)
        {
            return;
        }

        $directory_files = Repository::readDirectory($directory);
        if(count($directory_files) == 0)
        {
            return;
        }

        // readDirectory returns array of files sort by latest file
        $input_file = $directory_files[0];

        // check the latest successful import file timestamp
        $latest_successful_file_timestamp = DAO::getSingleValue($link, "SELECT import_file_modified_time FROM data_imports WHERE import_entity = 'learner' AND import_successful = '1' ORDER BY import_timestamp DESC LIMIT 1");

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
            "import_entity" => "learner",
        ];

        DAO::saveObjectToTable($link, "data_imports", $import);

        DAO::transaction_start($link);
        try
        {
            while (!feof($file_read))
            {
                $row = fgetcsv($file_read);
                if(is_array($row))
                {
                    $row = array_combine($header_line, $row);

                    $edrs = $row[$learner_mapping['edrs']];
                    if(!isset($employers[$edrs]))
                    {
                        // cannot create the learner as employer does not exist in the system.
                        continue;
                    }

                    $ebs_id = $row[$learner_mapping['ebs_id']];
                    $ob_learner_id = DAO::getSingleValue($link, "SELECT id FROM ob_learners WHERE ebs_id = '{$ebs_id}' ");
                    if($ob_learner_id != '')
                    {
                        $ob_learner = OnboardingLearner::loadFromDatabase($link, $ob_learner_id);
                    }
                    else
                    {
                        $ob_learner = new OnboardingLearner();
                    }

                    foreach($learner_mapping AS $field => $value)
                    {
                        if(!in_array($field, ["legal_name", "edrs"]))
                        {
                            $ob_learner->$field = $row[$value];
                        }
                    }
                    $ob_learner->dob = Date::toMySQL($ob_learner->dob);
                    $ob_learner->employer_id = $employers[$edrs]->employer_id;
                    $ob_learner->employer_location_id = $employers[$edrs]->location_id;
                    $ob_learner->save($link);

                    // now check other things for tr and other related entities
                    //if there is a tr entry for the given programme code then discard
                    //if no tr found for that programme code then create the tr and other things
                    $programme_code = $row[$learner_mapping['programme_code']];
                    if($programme_code == '')
                        continue;

                    if(!isset($frameworks[$programme_code]))
                        continue;

                    $framework = $frameworks[$programme_code];

                    $tr_id = DAO::getSingleValue($link, "SELECT tr.id FROM tr WHERE tr.ob_learner_id = '{$ob_learner->id}' AND tr.framework_id = '{$framework->id}'");
                    if($tr_id != '')
                        continue;

                    if($framework->program_manager == '')
                        continue;

                    $provider_id = DAO::getSingleValue($link, "SELECT users.employer_id FROM users WHERE users.id = '{$framework->program_manager}'");
                    if(!isset($providers[$provider_id]))
                        continue;

                    $provider_location_id = $providers[$provider_id];

                    // create a training record
                    $tr = new TrainingRecord();
                    $tr->id = null;
                    $tr->ob_learner_id = $ob_learner->id;
                    $tr->employer_id = $ob_learner->employer_id;
                    $tr->employer_location_id = $ob_learner->employer_location_id;
                    $tr->provider_id = $provider_id;
                    $tr->provider_location_id = $provider_location_id;
                    $tr->framework_id = $framework->id;
                    $tr->epa_organisation = $framework->epa_org_id;
                    $tr->epa_price = $framework->epa_price;
                    $tr->status_code = TrainingRecord::STATUS_IN_PROGRESS;
                    $tr->save($link);

                    $this->prepareKSB($link, $tr, $framework);

                    $skills_analysis_entry = new stdClass();
                    $skills_analysis_entry->tr_id = $tr->id;
                    $skills_analysis_entry->funding_band_maximum = $framework->funding_band_maximum;
                    $skills_analysis_entry->recommended_duration = $framework->recommended_duration;
                    $skills_analysis_entry->contracted_hours_per_week = $tr->contracted_hours_per_week;
                    $skills_analysis_entry->weeks_to_be_worked_per_year = $tr->weeks_to_be_worked_per_year;
                    $skills_analysis_entry->total_contracted_hours_per_year = $tr->total_contracted_hours_per_year;
                    $skills_analysis_entry->epa_org = $tr->epa_organisation;
                    $skills_analysis_entry->epa_price = $tr->epa_price;
                    DAO::saveObjectToTable($link, 'ob_learner_skills_analysis', $skills_analysis_entry);


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

//        pre('Import Successful');
        http_redirect('do.php?_action=import_learners');

    }

    public function prepareKSB(PDO $link, TrainingRecord $tr, $framework)
    {
        $evidences = XML::loadSimpleXML($framework->main_aim_evidences);
        foreach($evidences->units AS $unit_group)
        {
            $unit_group_title = $unit_group->attributes()->title->__toString();
            foreach($unit_group->unit AS $unit)
            {
                $unit_title = $unit->attributes()->title->__toString();
                foreach($unit->element AS $element)
                {
                    $element_title = $element->attributes()->title->__toString();
                    foreach($element->evidence AS $evidence)
                    {
                        $attributes = $evidence->attributes();
                        $delhours = 0;
                        if(isset($attributes['delhours']))
                        {
                            $delhours = $evidence->attributes()->delhours->__toString();
                        }
                        if($delhours == 'null')
                        {
                            $delhours = 0;
                        }
                        $evidence_title = $evidence->attributes()->title->__toString();
                        $save_entry = (object)[
                            'id' => null,
                            'tr_id' => $tr->id,
                            'unit_group' => $unit_group_title,
                            'unit_title' => $unit_title,
                            'evidence_title' => str_replace('  ', ' ', $evidence_title),
                            'del_hours' => trim($delhours) != '' ? $delhours : 0,
                        ];
                        DAO::saveObjectToTable($link, 'ob_learner_ksb', $save_entry);
                    }
                }
            }
        }
    }

    public function importAction(PDO $link)
    {
        if(!isset($_FILES['file_learners']))
        {
            throw new Exception("No file chosen");
        }

        $target_directory = 'DataImports/learners';
        $valid_extensions = array('csv');
        $r = Repository::processFileUploads('file_learners', $target_directory, $valid_extensions);
        if(!isset($r[0]))
            throw new Exception("File not uploaded, please try again.");


        $this->importLearnersFromDirectoryAction($link);
    }

    public function removeEntryAction(PDO $link)
    {
        $import_id = isset($_REQUEST['import_id']) ? $_REQUEST['import_id'] : '';
        if($import_id == '')
            return;

        $import = DAO::getObject($link, "SELECT * FROM data_imports WHERE import_id = '{$import_id}'");
        if(!isset($import->import_id))
            return;

        if(is_file(Repository::getRoot() . "/DataImports/learners/" . $import->import_file))
        {
            unlink(Repository::getRoot() . "/DataImports/learners/" . $import->import_file);
            DAO::execute($link, "DELETE FROM data_imports WHERE import_id = '{$import->import_id}'");
        }
    }

}
?>