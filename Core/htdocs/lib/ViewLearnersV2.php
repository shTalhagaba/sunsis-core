<?php
class ViewLearnersV2 extends View
{
    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $sql = new SQLStatement("
SELECT
  learners.id AS learner_id,
  learners.username AS learner_username,
  learners.firstnames,
  learners.surname,
  learners.imi_redeem_code,
  DATE_FORMAT(learners.dob, '%d/%m/%Y') AS date_of_birth,
  learners.home_address_line_1,
  learners.home_address_line_2,
  learners.home_address_line_3,
  learners.home_address_line_4,
  learners.home_postcode,
  learners.home_email AS learner_email,
  learners.home_mobile AS learner_mobile,
  learners.home_telephone AS learner_telephone,
  employers.legal_name AS employer,
  CONCAT(
    COALESCE(locations.`address_line_1`, ''),
    ' ',
    COALESCE(locations.`address_line_2`, ''),
    ' ',
    COALESCE(locations.`address_line_3`, ''),
    ' ',
    COALESCE(locations.`address_line_4`, ''),
    ' ',
    COALESCE(REPLACE(locations.`postcode`, ' ', '&nbsp;'), '')
  ) AS work_address,
  learners.duplex_status,
  (SELECT COUNT(*) FROM lookup_wmca_postcode WHERE lookup_wmca_postcode.postcode = learners.home_postcode) AS postcode_lookup_entries,
  (SELECT COUNT(*) FROM crm_learner_hs_form WHERE crm_learner_hs_form.learner_id = learners.id AND crm_learner_hs_form.learner_sign IS NOT NULL ) AS completed_hs_form_entries,
  (SELECT IF(training.`status` = 2, 'Completed', 'Booked') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L1' AND training.`learner_id` = learners.`id` LIMIT 1) AS l1_status,
  (SELECT IF(training.`status` = 2, 'Completed', 'Booked') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L2' AND training.`learner_id` = learners.`id` LIMIT 1) AS l2_status,
  (SELECT DATE_FORMAT(crm_training_schedule.`training_date`, '%d/%m/%Y') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = learners.`id` LIMIT 1) AS l3_date,
  (SELECT DATE_FORMAT(crm_training_schedule.`training_date`, '%d/%m/%Y') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = learners.`id` LIMIT 1) AS l4_date,
  (SELECT IF(training.`status` = 2, 'Completed', 'Booked') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = learners.`id` LIMIT 1) AS l3_status,
  (SELECT IF(training.`status` = 2, 'Completed', 'Booked') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = learners.`id` LIMIT 1) AS l4_status,
  (SELECT COUNT(*) FROM emails WHERE emails.entity_type = 'sunesis_learner' AND emails.entity_id = learners.id AND emails.email_type = 2) AS l3_joining_emails,
  (SELECT COUNT(*) FROM emails WHERE emails.entity_type = 'sunesis_learner' AND emails.entity_id = learners.id AND emails.email_type = 1) AS l4_joining_emails,
  (SELECT COUNT(*) FROM training WHERE training.learner_id = learners.id) AS allocated_dates,
  (SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L3' AND training.learner_id = learners.id AND crm_training_schedule.training_date >= CURDATE()) AS future_allocated_dates_l3,
  (SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L4' AND training.learner_id = learners.id AND crm_training_schedule.training_date >= CURDATE()) AS future_allocated_dates_l4,
  (SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`venue` = 'Ruddington' AND training.learner_id = learners.id) AS east_midland,
  (SELECT COUNT(*) FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`venue` = 'Peterborough Skills Academy' AND training.learner_id = learners.id) AS psa_learner,
  learners.l24,
  learners.l41a,
  learners.ni,
  learners.who_created

FROM
  users AS learners
  LEFT JOIN organisations AS employers
    ON learners.`employer_id` = employers.`id`
  LEFT JOIN locations
    ON (
      locations.`organisations_id` = employers.`id`
      AND locations.`is_legal_address` = 1
    )
			");

            $sql->setClause("WHERE learners.`type` = '" . User::TYPE_LEARNER . "'");

            $view = $_SESSION[$key] = new ViewLearnersV2();
            $view->setSQL($sql->__toString());

            $f = new TextboxViewFilter('filter_learner_firstnames', "WHERE learners.firstnames LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Learner First Name: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_learner_surname', "WHERE learners.surname LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Learner Surname: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_learner_mobile', "WHERE REPLACE(learners.home_mobile, ' ', '') LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Learner Mobile: %s");
            $view->addFilter($f);

//            $options = 'SELECT DISTINCT home_address_line_3, home_address_line_3, null, CONCAT("HAVING home_address_line_3=",CHAR(39),home_address_line_3,CHAR(39)) FROM users WHERE users.type = 5 ORDER BY home_address_line_3';
//            $f = new DropDownViewFilter('filter_address_line_3', $options, null, true);
//            $f->setDescriptionFormat("Address line 3: %s");
//            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_postcode', "WHERE learners.home_postcode LIKE '%%%s%%'", null);
            $f->setDescriptionFormat("Postcode: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'With IMI Redeem Code', null, 'WHERE learners.imi_redeem_code IS NOT NULL'),
                2=>array(2, 'Without IMI Redeem Code', null, 'WHERE learners.imi_redeem_code IS NULL'));
            $f = new DropDownViewFilter('filter_imi_redeem_code', $options, 0, false);
            $f->setDescriptionFormat("IMI Redeem Code: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'With NI Number', null, 'WHERE learners.ni IS NOT NULL'),
                2=>array(2, 'Without NI Number', null, 'WHERE learners.ni IS NULL'));
            $f = new DropDownViewFilter('filter_ni', $options, 0, false);
            $f->setDescriptionFormat("NI Number: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'New', null, 'HAVING allocated_dates = 0  AND l24 IS NULL AND l41a IS NULL '),
                2=>array(2, 'Booked', null, 'HAVING allocated_dates > 0  AND l24 IS NULL AND l41a IS NULL '),
                3=>array(3, 'Level 3 Completed', null, 'HAVING l3_status = "Completed"'),
                4=>array(4, 'Level 4 Completed', null, 'HAVING l4_status = "Completed"'),
                5=>array(5, 'Level 3 Completed and not Level 4', null, 'HAVING l3_status = "Completed" AND l4_date IS NULL'),
		        6=>array(6, 'Booked Level 3', null, 'HAVING l3_date != ""'),
                7=>array(7, 'Booked Level 4', null, 'HAVING l4_date != ""'),
                8=>array(8, 'Booked (Future)', null, 'HAVING (future_allocated_dates_l3 > 0 OR future_allocated_dates_l4 > 0) AND (l3_status != "Completed" OR l3_status IS NULL) AND (l4_status != "Completed" OR l4_status IS NULL)'),
		        9=>array(9, 'Level 1', null, 'WHERE FIND_IN_SET("L1", learners.l24)'),
                10=>array(10, 'Level 2', null, 'WHERE FIND_IN_SET("L2", learners.l24)'),
                11=>array(11, 'Needs rebooking for Level 3', null, 'WHERE FIND_IN_SET("L3", learners.l41a)'),
                12=>array(12, 'Needs rebooking for Level 4', null, 'WHERE FIND_IN_SET("L4", learners.l41a)'),
            );
            $f = new DropDownViewFilter('filter_learner_status', $options, 0, false);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Incomplete HS Form', null, 'HAVING completed_hs_form_entries = 0'),
                2=>array(2, 'Completed HS Form', null, 'HAVING completed_hs_form_entries > 0'));
            $f = new DropDownViewFilter('filter_hs_form_status', $options, 0, false);
            $f->setDescriptionFormat("HS Form Status: %s");
            $view->addFilter($f);

	        $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Exclude Archived Learners', null, 'WHERE learners.crb = 0'),
                2=>array(2, 'Only Archived Learners', null, 'WHERE learners.crb = 1'));
            $f = new DropDownViewFilter('filter_archive_status', $options, 1, false);
            $f->setDescriptionFormat("Archive Status: %s");
            $view->addFilter($f);

	        $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Exclude Outstanding Payment Learners', null, 'WHERE learners.ifl = 0'),
                2=>array(2, 'Only Outstanding Payment Learners', null, 'WHERE learners.ifl = 1'));
            $f = new DropDownViewFilter('filter_outstanding_payment', $options, 0, false);
            $f->setDescriptionFormat("Outstanding Payment: %s");
            $view->addFilter($f);	

	        $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Exclude East Midland', null, 'HAVING east_midland = 0'),
                2=>array(2, 'Only East Midland', null, 'HAVING east_midland > 0'));
            $f = new DropDownViewFilter('filter_east_midland', $options, 0, false);
            $f->setDescriptionFormat("East Midland: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'Exclude PSA', null, 'HAVING psa_learner = 0'),
                2=>array(2, 'Only PSA', null, 'HAVING psa_learner > 0 OR who_created IN (SELECT username FROM users WHERE users.employer_id = 3278) '));
            $f = $_SESSION['user']->employer_id == 3278 ? new DropDownViewFilter('filter_psa_learner', $options, 2, false) : new DropDownViewFilter('filter_psa_learner', $options, 0, false);
            $f->setDescriptionFormat("PSA Leanrers: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, 'With valid postcode', null, 'WHERE learners.bypass_postcode = "1" OR (learners.home_postcode IS NOT NULL AND learners.home_postcode IN (SELECT postcode FROM lookup_wmca_postcode))'),
                2=>array(2, 'With invalid postcode', null, 'WHERE learners.bypass_postcode = "0" AND learners.home_postcode IS NOT NULL AND learners.home_postcode NOT IN (SELECT postcode FROM lookup_wmca_postcode)'));
            $f = new DropDownViewFilter('filter_postcode_status', $options, 0, false);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

            $format = "WHERE learners.created >= '%s'";
            $f = new DateViewFilter('filter_from_creation_date', $format, '');
            $f->setDescriptionFormat("From Creation Date: %s");
            $view->addFilter($f);

            $format = "WHERE learners.created <= '%s'";
            $f = new DateViewFilter('filter_to_creation_date', $format, '');
            $f->setDescriptionFormat("To Creation Date: %s");
            $view->addFilter($f);

	    $format = "HAVING STR_TO_DATE(l3_date, '%d/%m/%Y') >= '%s'";
            $f = new DateViewFilter('filter_from_l3_course_date', $format, '');
            $f->setDescriptionFormat("From L3 Course Date: %s");
            $view->addFilter($f);

            $format = "HAVING STR_TO_DATE(l3_date, '%d/%m/%Y') <= '%s'";
            $f = new DateViewFilter('filter_to_l3_course_date', $format, '');
            $f->setDescriptionFormat("To L3 Course Date: %s");
            $view->addFilter($f);

            $format = "HAVING STR_TO_DATE(l4_date, '%d/%m/%Y') >= '%s'";
            $f = new DateViewFilter('filter_from_l4_course_date', $format, '');
            $f->setDescriptionFormat("From L4 Course Date: %s");
            $view->addFilter($f);

            $format = "HAVING STR_TO_DATE(l4_date, '%d/%m/%Y') <= '%s'";
            $f = new DateViewFilter('filter_to_l4_course_date', $format, '');
            $f->setDescriptionFormat("To L4 Course Date: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Firstname', null, 'ORDER BY learners.firstnames'),
                1=>array(2, 'Surname', null, 'ORDER BY learners.surname'));
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(300,300,null,null),
                5=>array(400,400,null,null),
                6=>array(500,500,null,null),
                7=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

        }

        return $_SESSION[$key];
    }


    public function render(PDO $link, $columns)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div align="center" ><table class="table table-bordered table-condensed" id="tblLearners">';
            echo '<thead class="bg-gray-active"><tr><th>&nbsp;</th>';
            foreach($columns as $column)
            {
                echo '<th>' . ucwords(str_replace("_"," ",$column)) . '</th>';
            }
            echo '</tr></thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("/do.php?_action=read_learner&username={$row['learner_username']}&id={$row['learner_id']}");

                echo '<td>';
                if($row['home_postcode'] != '' && $row['postcode_lookup_entries'] == 0)
                    echo ' &nbsp; <label class="label label-danger">Invalid postcode</label>';
                if($row['home_postcode'] != '' && $row['postcode_lookup_entries'] > 0)
                    echo ' &nbsp; <label class="label label-success">Valid postcode</label>';
                echo '</td>';

                foreach($columns as $column)
                {
                    echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                }

                echo '</tr>';
            }

            echo '</tbody></table></div><p><br></p>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

}
?>