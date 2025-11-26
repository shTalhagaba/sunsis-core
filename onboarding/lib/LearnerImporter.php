<?php


class LearnerImporter
{
    public static function import(PDO $link, $absolute_file_path, $delimiter = ',')
    {
        if(!is_file($absolute_file_path))
        {
            throw new Exception('File not found');
        }

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

        $importer = new CsvImporter($absolute_file_path, $delimiter, true);

        $result_status = [
            'status' => null,
            'description' => null,
            'header' => $importer->header,
        ];

        foreach(["firstnames", "surname", "edrs", "ebs_id", "dob"] AS $h)
        {
            if(!$importer->headerExists($learner_mapping[$h]))
            {
                $result_status['status'] = 'error';
                $result_status['description'] = 'Missing header: ' . $learner_mapping[$h];
                return $result_status;
            }
        }

        $rows = $importer->get();

        $learners_created = 0;

        DAO::transaction_start($link);
        try
        {
            foreach($rows AS $row)
            {
                if(!is_array($row))
                    continue;

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

                $new_learner = $ob_learner->id == '' ? true : false;
                $ob_learner->save($link);
                if($new_learner)
                    $learners_created++;

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

                self::prepareKSB($link, $tr, $framework);

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
        $result_status['description'] = count($rows) . " learner records processed. " .
            "{$learners_created} new learners created";

        return $result_status;
    }

    private static function prepareKSB(PDO $link, TrainingRecord $tr, $framework)
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
                            'evidence_title' => $evidence_title,
                            'del_hours' => trim($delhours) != '' ? $delhours : 0,
                        ];
                        DAO::saveObjectToTable($link, 'ob_learner_ksb', $save_entry);
                    }
                }
            }
        }
    }


}