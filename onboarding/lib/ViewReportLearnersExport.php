<?php
class ViewReportLearnersExport extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            // Create new view object
            $sql = <<<SQL
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
	(SELECT country_name FROM lookup_countries WHERE id = tr.country_of_birth) AS country_of_birth,
	(SELECT country_name FROM lookup_countries WHERE id = tr.country_of_perm_residence) AS country_of_perm_residence,
	(SELECT description FROM lookup_nationalities WHERE `id` = tr.nationality) AS nationality,
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
	IF(employer_agreement_schedules.`tp_sign` IS NOT NULL, 'Yes', 'No') AS schedule1_signed_by_provider,
    IF(employer_agreement_schedules.`emp_sign` IS NOT NULL, 'Yes', 'No') AS schedule1_signed_by_employer,
    IF(ob_learner_skills_analysis.`signed_by_learner` = 1, 'Yes', 'No') AS assessment_signed_by_learner,
    IF(ob_learner_skills_analysis.`signed_by_provider` = 1, 'Yes', 'No') AS assessment_signed_by_provider,
    IF(ob_learner_skills_analysis.`is_eligible_after_ss` = 'Y', 'Yes', 'No') AS is_eligible_after_ss,
    IF(tr.learner_sign IS NOT NULL, 'Yes', 'No') AS ob_form_signed_by_learner,
    IF(tr.emp_sign IS NOT NULL, 'Yes', 'No') AS ob_form_signed_by_employer,
    IF(tr.tp_sign IS NOT NULL, 'Yes', 'No') AS ob_form_signed_by_provider,
    IF(tr.personality_test IS NOT NULL, 'Yes', 'No') AS first_learning_activity_done,
    tr.id AS tr_id
FROM
	tr
	LEFT JOIN ob_learners ON tr.`ob_learner_id` = ob_learners.`id`
	LEFT JOIN ob_learner_care_leaver_details ON tr.id = ob_learner_care_leaver_details.`tr_id`
	LEFT JOIN frameworks ON tr.framework_id = frameworks.`id`
	LEFT JOIN ob_learner_skills_analysis ON tr.id = ob_learner_skills_analysis.`tr_id`
	LEFT JOIN employer_agreement_schedules ON (employer_agreement_schedules.tr_id = tr.`id` AND employer_agreement_schedules.`employer_id` = tr.`employer_id`)
 ;
SQL;

            $view = $_SESSION[$key] = new ViewReportLearnersExport();
            $view->setSQL($sql);

            // Add view filters
            $options = array(
                0=>array('SHOW_ALL', ' Show all', null, 'WHERE true'),
                1=>array('1', ' Schedule 1 signed by Provider ', null, 'HAVING schedule1_signed_by_provider = "Yes"'),
                2=>array('2', ' Schedule 1 signed by Employer ', null, 'HAVING schedule1_signed_by_employer = "Yes"'),
                3=>array('3', ' Assessment signed by Learner ', null, 'HAVING assessment_signed_by_learner = "Yes"'),
                4=>array('4', ' Assessment signed by Provider ', null, 'HAVING assessment_signed_by_provider = "Yes"'),
                5=>array('5', ' Onboarding signed by Learner ', null, 'HAVING ob_form_signed_by_learner = "Yes"'),
                6=>array('6', ' Onboarding signed by Employer', null, 'HAVING ob_form_signed_by_employer = "Yes"'),
                7=>array('7', ' Onboarding signed by Provider', null, 'HAVING ob_form_signed_by_provider = "Yes"'),
                8=>array('8', ' 1st learning activity done by Learner', null, 'HAVING first_learning_activity_done = "Yes"'));
            $f = new CheckboxViewFilter('filter_record_status', $options, array(8));
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);
            $f = new TextboxViewFilter('filter_firstnames', "WHERE ob_learners.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE ob_learners.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
            $f->setDescriptionFormat("TR IDs: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Learner Firstname', null, 'ORDER BY ob_learners.`firstnames` ASC')
            );
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 0, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);
        }

        return $_SESSION[$key];
    }

    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());

        if($st)
        {
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }

            echo $this->getViewNavigator();
            echo '<div class="center"><table class="table table-bordered">';
            echo '<thead><tr>';
            foreach($columns as $column)
            {
                echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . '</th>';
            }
            echo '</tr></thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
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
                echo '<tr>';
                foreach($columns as $column)
                {
                    echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table></div>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }


}
?>