<?php
class api_download_files implements IUnauthenticatedAction
{
    public function execute(PDO $link)
    {
        if(!SOURCE_LOCAL && !SOURCE_BLYTHE_VALLEY)
        {
            if(isset($_SERVER['HTTP_USER_AGENT']) && isset($_SERVER['REMOTE_ADDR']))
            {
                $_details = [
                    'a' => $_POST,
                    'b' => substr($_SERVER['HTTP_USER_AGENT'], 0, 450) . " " . $_SERVER['REMOTE_ADDR']
                ];
            }
            else
            {
                $_details = [
                    'a' => $_POST
                ];
            }
            //Emailer::notification_email("inaam.azmat@perspective-uk.com", "no-reply@perspective-uk.com", "no-reply@perspective-uk.com", "Download API Accessed", '', json_encode($_details));
        }

        $accessKey = isset($_POST['accessKey']) ? $_POST['accessKey'] : '';
        $secret = isset($_POST['secret']) ? $_POST['secret'] : '';

        if($accessKey == '') {
            echo json_encode(['status' => 'error', 'description' => 'Missing access key']);
            return;
        };
        if($secret == '') {
            echo json_encode(['status' => 'error', 'description' => 'Missing secret key']);
            return;
        };

        $key = SystemConfig::getEntityValue($link, "barnsley_api_key");
        if(md5("barnsley_export{$accessKey}{$secret}") != $key){
            echo json_encode(['status' => 'error', 'description' => 'Invalid credentials']);
            return;
        }

        $returnType = isset($_POST['returnType']) ? $_POST['returnType'] : '';
        $f = isset($_POST['file']) ? strtolower(trim($_POST['file'])) : '';

        if(strtolower(trim($returnType)) == 'csv')
        {
            if($f == 'b')
                $this->generateBaseData($link);
            elseif($f == 'l')
                $this->generateLearningDifficulty($link);
            elseif($f == 'a')
                $this->generateAls($link);
            elseif($f == 'p')
                $this->generateProgrammeAims($link);
            else
                echo json_encode(['status' => 'error', 'description' => 'Please specify which file is required. Possible values [b, l, a, p]']);
        }
        else
        {
            $this->generateDataInJson($link);
        }
    }

    private function generateBaseData(PDO $link)
    {
        $file = 'Base Data.csv';
        header( "Content-Type: ;charset=utf-8" );
        header( "Content-Disposition: attachment;filename=\"$file\"" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        $fp = fopen('php://output', 'w');

        $sql = <<<HEREDOC
SELECT DISTINCT
	ob_learners.uln AS unique_learner_number,
	ob_learners.`ebs_id`,
	IF(FIND_IN_SET(1, tr.`RUI`), 'Yes', 'No') AS RUI1,
	IF(FIND_IN_SET(2, tr.`RUI`), 'Yes', 'No') AS RUI2,
	IF(FIND_IN_SET(1, tr.`PMC`), 'Yes', 'No') AS PMC1,
	IF(FIND_IN_SET(2, tr.`PMC`), 'Yes', 'No') AS PMC2,
	IF(FIND_IN_SET(3, tr.`PMC`), 'Yes', 'No') AS PMC3,
	ob_learners.`learner_title` AS title,
	ob_learners.`firstnames`,
	ob_learners.`surname`,
	DATE_FORMAT(ob_learners.`dob`, '%d/%m/%Y') AS dob,
	tr.`ethnicity`,
	ob_learners.`gender`,
	tr.`ni` AS ni_number,
	tr.`hhs`,
	tr.`home_address_line1` AS address_1,
	tr.`home_address_line2` AS address_2,
	tr.`home_address_line3` AS city,
	tr.`home_address_line4` AS county,
	tr.`home_postcode` AS postcode,
	tr.`home_telephone` AS telephone,
	tr.`home_mobile` AS mobile,
	'' AS emergency_contact_title_1,
	'' AS emergency_contact_name_1,
	'' AS emergency_contact_relationship_1,
	'' AS emergency_contact_telephone_1,
	'' AS emergency_contact_mobile_1,
	'' AS emergency_contact_title_2,
	'' AS emergency_contact_name_2,
	'' AS emergency_contact_relationship_2,
	'' AS emergency_contact_telephone_2,
	'' AS emergency_contact_mobile_2,
	IF(FIND_IN_SET(1, tr.EligibilityList), 'Yes', 'No') AS lived_uk_last_3_years,
	IF(FIND_IN_SET(2, tr.EligibilityList), 'Yes', 'No') AS current_enrolled_elsewhere,
	tr.currently_enrolled_in_other AS details_of_other_enrolment,
	(SELECT country_code FROM lookup_countries WHERE id = tr.country_of_birth) AS country_of_birth,
	(SELECT country_code FROM lookup_countries WHERE id = tr.country_of_perm_residence) AS country_of_perm_residence,
	(SELECT country_code FROM lookup_countries WHERE `id` = tr.nationality) AS nationality,
	IF(FIND_IN_SET(3, tr.EligibilityList), 'Yes', 'No') AS valid_ni_number,
	IF(FIND_IN_SET(4, tr.EligibilityList), 'Yes', 'No') AS attending_other_training,
	IF(FIND_IN_SET(5, tr.EligibilityList), 'Yes', 'No') AS non_eu_citizen_residency,
	DATE_FORMAT(tr.`date_of_first_uk_entry`, '%d/%m/%Y') AS date_of_first_entry,
	DATE_FORMAT(tr.`date_of_most_recent_uk_entry`, '%d/%m/%Y') AS date_of_most_recent_entry,
	IF(FIND_IN_SET(6, tr.EligibilityList), 'Yes', 'No') AS visa_needed,
	tr.passport_number,
	tr.immigration_category,
	tr.EmploymentStatus AS employment_status,
	IF(tr.work_curr_emp = 1, 'Yes', 'No') AS employed_by_current_employer,
	IF(tr.SEI = 1, 'Yes', 'No') AS self_employed,
	tr.empStatusEmployer AS employer_name,
	tr.LOE AS length_of_employment,
	tr.EII AS number_of_hours_per_week,
	tr.LOU AS length_of_unemployed,
	tr.BSI AS benefits,
	IF(tr.PEI = 1, 'Yes', 'No') AS full_time_education,
	IF(tr.ehc_plan = 1, 'Yes', 'No') AS ehc_plan,
	IF(tr.care_leaver = 1, 'Yes', 'No') AS care_leaver,
	IF(ob_learner_care_leaver_details.in_care_of_local_authority = 1, 'Yes', 'No') AS uk_care_authority,
	IF(ob_learner_care_leaver_details.eligible_for_bursary_payment = 1, 'Yes', 'No') AS bursary_access,
	IF(ob_learner_care_leaver_details.give_consent_to_inform_employer = 1, 'Yes', 'No') AS give_consent_to_inform_employer,
	ob_learner_care_leaver_details.in_care_evidence,
	ob_learner_care_leaver_details.`care_leaver_bank_name`,
	ob_learner_care_leaver_details.`care_leaver_account_name`,
	ob_learner_care_leaver_details.`care_leaver_sort_code`,
	ob_learner_care_leaver_details.`care_leaver_account_number`,
	frameworks.`programme_code` AS standard_programme_code,
	tr.contracted_hours_per_week,
	tr.total_contracted_hours_per_year,
	tr.total_contracted_hours_full_apprenticeship,
	ob_learner_skills_analysis.`total_training_price`,
	ob_learner_skills_analysis.`epa_price`,
	ob_learner_skills_analysis.`total_nego_price_fa` AS total_negotiated_price_following_assessment,
	(SELECT IF(have_criminal_conviction = 'Y', 'Yes', 'No') FROM ob_learner_criminal_convictions WHERE tr_id = tr.id) AS have_criminal_conviction,
	ob_learners.home_email AS personal_email,
	ob_learners.work_email AS work_email,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE tr.trainers = users.id) AS trainer,
	DATE_FORMAT(apprenticeship_start_date, '%d/%m/%Y') AS date_employement_status_apply,
	tr.id AS tr_id
FROM
	tr
	LEFT JOIN ob_learners ON tr.`ob_learner_id` = ob_learners.`id`
	LEFT JOIN ob_learner_care_leaver_details ON tr.id = ob_learner_care_leaver_details.`tr_id`
	LEFT JOIN frameworks ON tr.framework_id = frameworks.`id`
	LEFT JOIN ob_learner_skills_analysis ON tr.id = ob_learner_skills_analysis.`tr_id`
WHERE
    tr.learner_sign IS NOT NULL AND tr.emp_sign IS NOT NULL AND tr.tp_sign IS NOT NULL	AND ob_learners.`archive` != 'Y'
 ;
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            $columns = [];
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            $refined_columns = [];

            foreach($columns as $column)
            {
                $refined_columns[] = ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column)));
            }

            fputcsv( $fp, $refined_columns );

            while($row = $st->fetch())
            {
                $values = [];
                $emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$row['tr_id']}'", DAO::FETCH_ASSOC);
                for($i = 1; $i <= 2; $i++)
                {
                    $j = $i-1;
                    if(isset($emergency_contacts_result[$j]))
                    {
                        $row["emergency_contact_title_$i"] = $emergency_contacts_result[$j]['em_con_title'];
                        $row["emergency_contact_name_$i"] = $emergency_contacts_result[$j]['em_con_name'];
                        $row["emergency_contact_relationship_$i"] = $emergency_contacts_result[$j]['em_con_rel'];
                        $row["emergency_contact_telephone_$i"] = $emergency_contacts_result[$j]['em_con_tel'];
                        $row["emergency_contact_mobile_$i"] = $emergency_contacts_result[$j]['em_con_mob'];
                    }
                }

                foreach($columns as $column)
                {
                    $values[] = $row[$column];
                }

                fputcsv($fp, $values);
            }
        }

        fclose($fp);
        exit();
    }

    private function generateLearningDifficulty(PDO $link)
    {
        $file = 'Learning Difficulty.csv';
        header( "Content-Type: ;charset=utf-8" );
        header( "Content-Disposition: attachment;filename=\"$file\"" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        $fp = fopen('php://output', 'w');

        $sql = <<<HEREDOC
SELECT DISTINCT
	ob_learners.uln AS unique_learner_number,
	ob_learners.`ebs_id`,
	tr.llddcat AS `category`,
	tr.primary_lldd AS `primary`
FROM
	tr
	LEFT JOIN ob_learners ON tr.`ob_learner_id` = ob_learners.`id`
WHERE
    tr.learner_sign IS NOT NULL AND 
    tr.emp_sign IS NOT NULL AND 
    tr.tp_sign IS NOT NULL AND 
    tr.llddcat IS NOT NULL AND
    ob_learners.`archive` != 'Y'
 ;
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            $llddcats_lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_lldd_categories");

            $columns = [];
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            $refined_columns = [];
            foreach($columns as $column)
            {
                $refined_columns[] = ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . ",";
            }
            fputcsv( $fp, $refined_columns );

            while($row = $st->fetch())
            {
                $lldd_cats = $row['category'] != '' ? explode(",", $row['category']) : [];
                foreach($lldd_cats AS $cat)
                {
                    $values = [];
                    $values[] = "{$row['unique_learner_number']},";
                    $values[] = "{$row['ebs_id']},";
                    $values[] = isset($llddcats_lookup[$cat]) ? "{$llddcats_lookup[$cat]}," : "{$cat},";
                    $values[] = $cat == $row['primary'] ? 'Yes' : 'No';

                    fputcsv($fp, $values);
                }
            }
        }

        fclose($fp);
        exit();

    }

    private function generateAls(PDO $link)
    {
        $file = 'ALS.csv';
        header( "Content-Type: ;charset=utf-8" );
        header( "Content-Disposition: attachment;filename=\"$file\"" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        $fp = fopen('php://output', 'w');

        $sql = <<<HEREDOC
SELECT DISTINCT
	ob_learners.`uln`,
  ob_learners.`ebs_id`,
  DATE_FORMAT(ob_learner_als.`date_discussed`, '%d/%m/%Y') AS date_discussed,
  IF(ob_learner_als.`support_required` = 'Y', 'Yes', 'No') AS support_required,
  ob_learner_als.`details`,
  DATE_FORMAT(ob_learner_als.`date_claimed_from`, '%d/%m/%Y') AS date_claimed_from,
  ob_learner_als.`additional_info`
FROM
  tr
  INNER JOIN ob_learners ON tr.ob_learner_id = ob_learners.id
  INNER JOIN ob_learner_als ON tr.id = ob_learner_als.tr_id
WHERE
    tr.learner_sign IS NOT NULL AND 
    tr.emp_sign IS NOT NULL AND 
    tr.tp_sign IS NOT NULL  AND
    ob_learners.`archive` != 'Y'
 ;
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            $columns = [];
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            $refined_columns = [];
            foreach($columns as $column)
            {
                $refined_columns[] = ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . ",";
            }
            fputcsv( $fp, $refined_columns );

            while($row = $st->fetch())
            {
                $values = [];

                foreach($columns as $column)
                {
                    $values[] = $row[$column];
                }

                fputcsv($fp, $values);
            }
        }

        fclose($fp);
        exit();

    }

    private function generateProgrammeAims(PDO $link)
    {
        $file = 'Programme Aims.csv';
        header( "Content-Type: ;charset=utf-8" );
        header( "Content-Disposition: attachment;filename=\"$file\"" );
        header( "Pragma: no-cache" );
        header( "Expires: 0" );
        $fp = fopen('php://output', 'w');

        $sql = <<<HEREDOC
SELECT DISTINCT
  ob_learners.`uln`,
  ob_learners.`ebs_id`,
  ob_learner_quals.`qual_id` AS ilr_aim,
  DATE_FORMAT(ob_learner_quals.`qual_start_date`, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(ob_learner_quals.`qual_end_date`, '%d/%m/%Y') AS planned_end_date,
  CASE ob_learner_quals.`qual_dl`
	WHEN 'NA' THEN 'N/A'
	WHEN 'C' THEN 'College'
	WHEN 'W' THEN 'Workplace'
	ELSE ''
  END AS delivery_location,
  CASE ob_learner_quals.`qual_ma`
	WHEN 'NA' THEN 'N/A'
	WHEN 'WDR' THEN 'Weekly D-Rel.'
	WHEN 'FDR' THEN 'Fortnightly D.Rel.'
	WHEN 'PMA' THEN 'Planned Mock Assessment'
	WHEN 'MON' THEN 'Monthly'
	WHEN '3W' THEN '3 Weekly'
	WHEN '6W' THEN '6 Weekly'
	ELSE ''
  END AS mode_of_attendance,
  ob_learner_quals.`qual_dow` AS day_of_week,
  ob_learner_quals.`qual_dh` AS delivery_hours
FROM
  tr
  INNER JOIN ob_learners ON tr.ob_learner_id = ob_learners.id
  INNER JOIN ob_learner_quals ON tr.id = ob_learner_quals.tr_id
WHERE
    tr.learner_sign IS NOT NULL AND 
    tr.emp_sign IS NOT NULL AND 
    tr.tp_sign IS NOT NULL  AND
    ob_learners.`archive` != 'Y'
;
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            $columns = [];
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            $refined_columns = [];
            foreach($columns as $column)
            {
                $refined_columns[] = ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . ",";
            }
            fputcsv( $fp, $refined_columns );

            while($row = $st->fetch())
            {
                $values = [];

                foreach($columns as $column)
                {
                    $values[] = $row[$column];
                }

                fputcsv($fp, $values);
            }
        }

        fclose($fp);
        exit();

    }

    private function generateDataInJson(PDO $link)
    {
        $training_ids = [];
        $sql = <<<HEREDOC
SELECT DISTINCT
	ob_learners.uln AS unique_learner_number,
	ob_learners.`ebs_id`,
	IF(FIND_IN_SET(1, tr.`RUI`), 'Yes', 'No') AS RUI1,
	IF(FIND_IN_SET(2, tr.`RUI`), 'Yes', 'No') AS RUI2,
	IF(FIND_IN_SET(1, tr.`PMC`), 'Yes', 'No') AS PMC1,
	IF(FIND_IN_SET(2, tr.`PMC`), 'Yes', 'No') AS PMC2,
	IF(FIND_IN_SET(3, tr.`PMC`), 'Yes', 'No') AS PMC3,
	ob_learners.`learner_title` AS title,
	ob_learners.`firstnames`,
	ob_learners.`surname`,
	DATE_FORMAT(ob_learners.`dob`, '%d/%m/%Y') AS dob,
	tr.`ethnicity`,
	ob_learners.`gender`,
	tr.`ni` AS ni_number,
	tr.`hhs`,
	tr.`home_address_line1` AS address_1,
	tr.`home_address_line2` AS address_2,
	tr.`home_address_line3` AS city,
	tr.`home_address_line4` AS county,
	tr.`home_postcode` AS postcode,
	tr.`home_telephone` AS telephone,
	tr.`home_mobile` AS mobile,
	'' AS emergency_contact_title_1,
	'' AS emergency_contact_name_1,
	'' AS emergency_contact_relationship_1,
	'' AS emergency_contact_telephone_1,
	'' AS emergency_contact_mobile_1,
	'' AS emergency_contact_title_2,
	'' AS emergency_contact_name_2,
	'' AS emergency_contact_relationship_2,
	'' AS emergency_contact_telephone_2,
	'' AS emergency_contact_mobile_2,
	IF(FIND_IN_SET(1, tr.EligibilityList), 'Yes', 'No') AS lived_uk_last_3_years,
	IF(FIND_IN_SET(2, tr.EligibilityList), 'Yes', 'No') AS current_enrolled_elsewhere,
	tr.currently_enrolled_in_other AS details_of_other_enrolment,
	(SELECT country_code FROM lookup_countries WHERE id = tr.country_of_birth) AS country_of_birth,
	(SELECT country_code FROM lookup_countries WHERE id = tr.country_of_perm_residence) AS country_of_perm_residence,
	(SELECT country_code FROM lookup_countries WHERE `id` = tr.nationality) AS nationality,
	IF(FIND_IN_SET(3, tr.EligibilityList), 'Yes', 'No') AS valid_ni_number,
	IF(FIND_IN_SET(4, tr.EligibilityList), 'Yes', 'No') AS attending_other_training,
	IF(FIND_IN_SET(5, tr.EligibilityList), 'Yes', 'No') AS non_eu_citizen_residency,
	DATE_FORMAT(tr.`date_of_first_uk_entry`, '%d/%m/%Y') AS date_of_first_entry,
	DATE_FORMAT(tr.`date_of_most_recent_uk_entry`, '%d/%m/%Y') AS date_of_most_recent_entry,
	IF(FIND_IN_SET(6, tr.EligibilityList), 'Yes', 'No') AS visa_needed,
	tr.passport_number,
	tr.immigration_category,
	tr.EmploymentStatus AS employment_status,
	IF(tr.work_curr_emp = 1, 'Yes', 'No') AS employed_by_current_employer,
	IF(tr.SEI = 1, 'Yes', 'No') AS self_employed,
	tr.empStatusEmployer AS employer_name,
	tr.LOE AS length_of_employment,
	tr.EII AS number_of_hours_per_week,
	tr.LOU AS length_of_unemployed,
	tr.BSI AS benefits,
	IF(tr.PEI = 1, 'Yes', 'No') AS full_time_education,
	IF(tr.ehc_plan = 1, 'Yes', 'No') AS ehc_plan,
	IF(tr.care_leaver = 1, 'Yes', 'No') AS care_leaver,
	IF(ob_learner_care_leaver_details.in_care_of_local_authority = 1, 'Yes', 'No') AS uk_care_authority,
	IF(ob_learner_care_leaver_details.eligible_for_bursary_payment = 1, 'Yes', 'No') AS bursary_access,
	IF(ob_learner_care_leaver_details.give_consent_to_inform_employer = 1, 'Yes', 'No') AS give_consent_to_inform_employer,
	ob_learner_care_leaver_details.in_care_evidence,
	ob_learner_care_leaver_details.`care_leaver_bank_name`,
	ob_learner_care_leaver_details.`care_leaver_account_name`,
	ob_learner_care_leaver_details.`care_leaver_sort_code`,
	ob_learner_care_leaver_details.`care_leaver_account_number`,
	frameworks.`programme_code` AS standard_programme_code,
	tr.contracted_hours_per_week,
	tr.total_contracted_hours_per_year,
	tr.total_contracted_hours_full_apprenticeship,
	ob_learner_skills_analysis.`total_training_price`,
	ob_learner_skills_analysis.`epa_price`,
	ob_learner_skills_analysis.`total_nego_price_fa` AS total_negotiated_price_following_assessment,
	(SELECT IF(have_criminal_conviction = 'Y', 'Yes', 'No') FROM ob_learner_criminal_convictions WHERE tr_id = tr.id) AS have_criminal_conviction,
	ob_learners.home_email AS personal_email,
	ob_learners.work_email AS work_email,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE tr.trainers = users.id) AS trainer,
	DATE_FORMAT(apprenticeship_start_date, '%d/%m/%Y') AS date_employement_status_apply,
	tr.id AS tr_id
FROM
	tr
	LEFT JOIN ob_learners ON tr.`ob_learner_id` = ob_learners.`id`
	LEFT JOIN ob_learner_care_leaver_details ON tr.id = ob_learner_care_leaver_details.`tr_id`
	LEFT JOIN frameworks ON tr.framework_id = frameworks.`id`
	LEFT JOIN ob_learner_skills_analysis ON tr.id = ob_learner_skills_analysis.`tr_id`
WHERE
    tr.learner_sign IS NOT NULL AND tr.emp_sign IS NOT NULL AND tr.tp_sign IS NOT NULL	AND ob_learners.archive != 'Y'
 ;
HEREDOC;

        $result = [
            'BaseData' => [],
            'LearningDifficulty' => [],
            'ALS' => [],
            'ProgrammeAims' => [],
        ];
        $st = $link->query($sql);
        if ($st)
        {
            $columns = [];
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            while($row = $st->fetch())
            {
                if(!in_array($row['tr_id'], $training_ids))
                    $training_ids[] = $row['tr_id'];

                $emergency_contacts_result = DAO::getResultset($link, "SELECT * FROM ob_learner_emergency_contacts WHERE tr_id = '{$row['tr_id']}'", DAO::FETCH_ASSOC);
                for($i = 1; $i <= 2; $i++)
                {
                    $j = $i-1;
                    if(isset($emergency_contacts_result[$j]))
                    {
                        $row["emergency_contact_title_$i"] = $emergency_contacts_result[$j]['em_con_title'];
                        $row["emergency_contact_name_$i"] = $emergency_contacts_result[$j]['em_con_name'];
                        $row["emergency_contact_relationship_$i"] = $emergency_contacts_result[$j]['em_con_rel'];
                        $row["emergency_contact_telephone_$i"] = $emergency_contacts_result[$j]['em_con_tel'];
                        $row["emergency_contact_mobile_$i"] = $emergency_contacts_result[$j]['em_con_mob'];
                    }
                }

                $learner = [];
                foreach($columns as $column)
                {
                    $learner[$column] = $row[$column];
                }

                $result['BaseData'][] = $learner;
            }
        }

        $training_ids = implode(",", $training_ids);

        $sql = <<<HEREDOC
SELECT DISTINCT
	ob_learners.uln AS unique_learner_number,
	ob_learners.`ebs_id`,
	tr.llddcat AS `category`,
	tr.primary_lldd AS `primary`
FROM
	tr
	LEFT JOIN ob_learners ON tr.`ob_learner_id` = ob_learners.`id`
WHERE
    tr.id IN ($training_ids) AND
    tr.llddcat IS NOT NULL
 ;
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            $llddcats_lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_lldd_categories");

            while($row = $st->fetch())
            {
                $lldd_cats = $row['category'] != '' ? explode(",", $row['category']) : [];
                foreach($lldd_cats AS $cat)
                {
                    $learning_difficulty = [];
                    $learning_difficulty['unique_learner_number'] = $row['unique_learner_number'];
                    $learning_difficulty['ebs_id'] = $row['ebs_id'];
                    $learning_difficulty['category'] = isset($llddcats_lookup[$cat]) ? $llddcats_lookup[$cat] : $cat;
                    $learning_difficulty['primary'] = $cat == $row['primary'] ? 'Yes' : 'No';

                    $result['LearningDifficulty'][] = $learning_difficulty;
                }
            }
        }

        $sql = <<<HEREDOC
SELECT DISTINCT
	ob_learners.`uln`,
  ob_learners.`ebs_id`,
  DATE_FORMAT(ob_learner_als.`date_discussed`, '%d/%m/%Y') AS date_discussed,
  IF(ob_learner_als.`support_required` = 'Y', 'Yes', 'No') AS support_required,
  ob_learner_als.`details`,
  DATE_FORMAT(ob_learner_als.`date_claimed_from`, '%d/%m/%Y') AS date_claimed_from,
  ob_learner_als.`additional_info`
FROM
  tr
  INNER JOIN ob_learners ON tr.ob_learner_id = ob_learners.id
  INNER JOIN ob_learner_als ON tr.id = ob_learner_als.tr_id
WHERE
    tr.id IN ($training_ids)
 ;
HEREDOC;

        $st = $link->query($sql);
        if ($st)
        {
            $columns = [];
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }
            while($row = $st->fetch())
            {
                $als = [];
                foreach($columns as $column)
                {
                    $als[$column] = $row[$column];
                }

                $result['ALS'][] = $als;
            }
        }

        $sql = <<<HEREDOC
SELECT DISTINCT
  ob_learners.`uln`,
  ob_learners.`ebs_id`,
  ob_learner_quals.`qual_id` AS ilr_aim,
  DATE_FORMAT(ob_learner_quals.`qual_start_date`, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(ob_learner_quals.`qual_end_date`, '%d/%m/%Y') AS planned_end_date,
  CASE ob_learner_quals.`qual_dl`
	WHEN 'NA' THEN 'N/A'
	WHEN 'C' THEN 'College'
	WHEN 'W' THEN 'Workplace'
	ELSE ''
  END AS delivery_location,
  CASE ob_learner_quals.`qual_ma`
	WHEN 'NA' THEN 'N/A'
	WHEN 'WDR' THEN 'Weekly D-Rel.'
	WHEN 'FDR' THEN 'Fortnightly D.Rel.'
	WHEN 'PMA' THEN 'Planned Mock Assessment'
	WHEN 'MON' THEN 'Monthly'
	WHEN '3W' THEN '3 Weekly'
	WHEN '6W' THEN '6 Weekly'
	ELSE ''
  END AS mode_of_attendance,
  ob_learner_quals.`qual_dow` AS day_of_week,
  ob_learner_quals.`qual_dh` AS delivery_hours
FROM
  tr
  INNER JOIN ob_learners ON tr.ob_learner_id = ob_learners.id
  INNER JOIN ob_learner_quals ON tr.id = ob_learner_quals.tr_id
WHERE
    tr.id IN ($training_ids)
 ;
HEREDOC;
        $st = $link->query($sql);
        if ($st)
        {
            $columns = [];
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }
            while($row = $st->fetch())
            {
                $program_aim = [];
                foreach($columns as $column)
                {
                    $program_aim[$column] = $row[$column];
                }
                $result['ProgrammeAims'][] = $program_aim;
            }
        }

        header('Content-type: application/json');
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

}