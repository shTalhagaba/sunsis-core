<?php
class TrainingRecord extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)
    {
        $pot = null;

        //$query = "SELECT tr.*, literacy.`description` AS literacy, numeracy.`description` AS numeracy, lookup_reasons_for_leaving.description as reason_for_leaving, providers.id as provider_id, provider_location.full_name as provider_full_name, provider_location.contact_email as provider_email, organisations.legal_name, locations.full_name FROM tr   LEFT JOIN users ON users.`username` = tr.`username` LEFT JOIN lookup_pre_assessment AS literacy ON literacy.`id` = users.`literacy` LEFT JOIN lookup_pre_assessment AS numeracy ON numeracy.`id` = users.`numeracy` LEFT JOIN lookup_reasons_for_leaving on lookup_reasons_for_leaving.id = tr.reasons_for_leaving LEFT JOIN organisations as providers on providers.id = tr.provider_id LEFT JOIN locations as provider_location on provider_location.id = tr.provider_location_id LEFT JOIN organisations ON tr.employer_id = organisations.id LEFT JOIN locations on tr.employer_location_id = locations.id WHERE tr.id=" . addslashes((string)$id) . ";";
        $query = "SELECT tr.*, literacy.`description` AS literacy, numeracy.`description` AS numeracy, lookup_reasons_for_leaving.description as reason_for_leaving, providers.id as provider_id, provider_location.full_name as provider_full_name, organisations.legal_name, locations.full_name FROM tr   LEFT JOIN users ON users.`username` = tr.`username` LEFT JOIN lookup_pre_assessment AS literacy ON literacy.`id` = users.`literacy` LEFT JOIN lookup_pre_assessment AS numeracy ON numeracy.`id` = users.`numeracy` LEFT JOIN lookup_reasons_for_leaving on lookup_reasons_for_leaving.id = tr.reasons_for_leaving LEFT JOIN organisations as providers on providers.id = tr.provider_id LEFT JOIN locations as provider_location on provider_location.id = tr.provider_location_id LEFT JOIN organisations ON tr.employer_id = organisations.id LEFT JOIN locations on tr.employer_location_id = locations.id WHERE tr.id=" . addslashes((string)$id) . ";";
        $obj = DAO::getObject($link, $query);
        if($obj){
            $pot = new TrainingRecord();
            $pot->populate($obj);
        }

        if(!isset($pot->college_start_date))
            $pot->college_start_date = $pot->start_date;
        return $pot;
    }


    /**
     * This method doesn't actually load data into a TrainingRecord object but instead
     * returns a basic XML representation of a TrainingRecord.
     * @param PDO $link
     * @param int $id
     * @return string XML string
     */
    public function loadData(PDO $link, $id)
    {
        $que = "select title from student_frameworks where tr_id='$id'";
        $framework_title = trim(DAO::getSingleValue($link, $que));

        $que = "select courses.title from courses LEFT JOIN courses_tr on courses_tr.course_id = courses.id where tr_id='$id'";
        $course_title = trim(DAO::getSingleValue($link, $que));

        $tr = TrainingRecord::loadFromDatabase($link, $id);

        $que = "select legal_name from organisations where id=$tr->employer_id";
        $legal_name = trim(DAO::getSingleValue($link, $que));


        $data = "<?xml version=\"1.0\" ?>\r\n";
        $data .= "<TrainingRecord><FrameworkTitle>" . htmlspecialchars((string)$framework_title) . "</FrameworkTitle>";
        $data .= "<CourseTitle>" . htmlspecialchars((string)$course_title) . "</CourseTitle>";
        $data .= "<FirstNames>" . htmlspecialchars((string)$tr->firstnames) . "</FirstNames>";
        $data .= "<Surname>" . htmlspecialchars((string)$tr->surname) . "</Surname>";
        $data .= "<EmployerName>" . htmlspecialchars((string)$legal_name) . "</EmployerName>";

        $sql = <<<HEREDOC
SELECT
	*
FROM
	student_qualifications
Where tr_id = $id and aptitude != 1 and framework_id != 0
HEREDOC;

        $st = $link->query($sql);

        if($st)
        {

            $data .= "<Qualifications>";

            while($row = $st->fetch())
            {

                $data .= "<Qualification><QualificationTitle>" . htmlspecialchars((string)$row['internaltitle']) . "</QualificationTitle>";

                $data .= "<CompletedUnits>";
                $xml = $row['evidences'];
                $xml = mb_convert_encoding($xml,'UTF-8');
                //$pageDom = new DomDocument();
                //$pageDom->loadXML($xml);
                $pageDom = XML::loadXmlDom($xml);
                $e = $pageDom->getElementsByTagName('unit');
                foreach($e as $node)
                {
                    if($node->getAttribute('percentage')==100)
                    {
                        $data .= "<Unit percentage='" . htmlspecialchars((string)$node->getAttribute('percentage')) . "'>" . htmlspecialchars((string)$node->getAttribute("title")) . "";

                        $el = $node->getElementsByTagName('element');
                        foreach($el as $ele)
                        {
                            $data .= "<Element percentage='" . htmlspecialchars((string)$ele->getAttribute('percentage')) . "'>" . htmlspecialchars((string)$ele->getAttribute('title')) . "</Element>";
                        }
                        $data .= "</Unit>";
                    }
                }

                $data .= "</CompletedUnits>";

                $data .= "<ToBeCompletedUnits>";
                $xml = $row['evidences'];
                $xml = mb_convert_encoding($xml,'UTF-8');
                //$pageDom = new DomDocument();
                //$pageDom->loadXML($xml);
                $pageDom = XML::loadXmlDom($xml);
                $e = $pageDom->getElementsByTagName('unit');
                foreach($e as $node)
                {
                    if($node->getAttribute('percentage')!=100)
                    {
                        $data .= "<Unit percentage='" . htmlspecialchars((string)$node->getAttribute('percentage')) . "'>" . htmlspecialchars((string)$node->getAttribute("title")) . "</Unit>";
                    }
                }

                $data .= "</ToBeCompletedUnits>";

                $data .= "</Qualification>";

                //	if($row['id']=='100/5575/7')
                //		throw new Exception($data);

            }

            $data .= "</Qualifications>";
        }

        $data .= "</TrainingRecord>";


        return $data;

    }


    public function save(PDO $link)
    {
	if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
        {
            $this->arm_comments = substr($this->arm_comments, 0, 199);
        }
        return DAO::saveObjectToTable($link, 'tr', $this);
    }


    public function delete(PDO $link)
    {
        if(!$this->isSafeToDelete($link))
        {
            throw new Exception("PeriodOfTraining #{$this->id} cannot be deleted");
        }

        $query = "DELETE FROM tr WHERE id={$this->id};";
        DAO::execute($link, $query);

        return true;
    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }


    /**
     * Overridden method
     * @param pdo $link
     * @param ValueObject $new_object
     * @param array $exclude_fields
     */
    public function buildAuditLogString(PDO $link, Entity $new_vo, array $exclude_fields = array())
    {
        if(count($exclude_fields) == 0)
        {
            // These fields use lookup codes
            $exclude_fields = array('ethnicity', 'gender', 'status_code', 'employer_id', 'employer_location_id');
        }

        $changes_list = parent::buildAuditLogString($link, $new_vo, $exclude_fields);

        // Test each of the exceptions separately
        if($this->ethnicity != $new_vo->ethnicity)
        {
            $lookup = DAO::getLookupTable($link, "SELECT value, description from dropdown0708 where code='L12' order by value");
            $from = isset($lookup[$this->ethnicity]) ? $lookup[$this->ethnicity] : $this->ethnicity;
            $to = isset($lookup[$new_vo->ethnicity]) ? $lookup[$new_vo->ethnicity] : $new_vo->ethnicity;
            $changes_list .= "[ethnicity] changed from '$from' to '$to'\n";
        }
        if($this->gender != $new_vo->gender)
        {
            //$lookup = DAO::getLookupTable($link, "SELECT id, description FROM lookup_gender ORDER BY id");
            //$changes_list .= "[gender] changed from '{$lookup[$this->gender]}' to '{$lookup[$new_vo->gender]}'\n";
        }
        if($this->status_code != $new_vo->status_code)
        {
            $lookup = DAO::getLookupTable($link, "SELECT code, description FROM lookup_pot_status ORDER BY code");
            $changes_list .= "[status] changed from '{$lookup[$this->status_code]}' to '{$lookup[$new_vo->status_code]}'\n";
        }
        if($this->assessor != $new_vo->assessor)
        {
            $from_assessor = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$this->assessor}'");
            $to_assessor = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$new_vo->assessor}'");
            $changes_list .= "[Assessor] changed from '{$from_assessor}' to '{$to_assessor}'\n";
        }
        if($this->crm_contact_id != $new_vo->crm_contact_id)
        {
            $from_assessor = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$this->crm_contact_id}'");
            $to_assessor = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$new_vo->crm_contact_id}'");
            $changes_list .= "[Line Manager] changed from '{$from_assessor}' to '{$to_assessor}'\n";
        }
	    if($this->contract_id != $new_vo->contract_id)
        {
            $from_contract = DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '{$this->contract_id}'");
            $to_contract = DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '{$new_vo->contract_id}'");
            $changes_list .= "[Contract] changed from '{$from_contract}' to '{$to_contract}'\n";
        }
        if( ($this->employer_id != $new_vo->employer_id) && ($new_vo->employer_id != '') )
        {
            $from_contract = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$this->employer_id}'");
            $to_contract = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$new_vo->employer_id}'");
            $changes_list .= "[Employer] changed from '{$from_contract}' to '{$to_contract}'\n";
        }
	if( ($this->employer_location_id != $new_vo->employer_location_id) && ($new_vo->employer_location_id != '') )
        {
            $from_location = DAO::getSingleValue($link, "SELECT postcode FROM locations WHERE id = '{$this->employer_location_id}'");
            $to_location = DAO::getSingleValue($link, "SELECT postcode FROM locations WHERE id = '{$new_vo->employer_location_id}'");
            $changes_list .= "[Employer Location] changed from '{$from_location}' to '{$to_location}'\n";
        }

        return $changes_list;
    }


    /**
     * @param mixed $id A numeric id, an array of numeric ids or a SQL
     * query that produces a list of numeric ids
     */
    public function updateAttendanceStatistics()
    {

    }

    public static function getPercentageAchieved(PDO $link, $tr_id)
    {
        $sql = <<<SQL
SELECT
  SUM(
    IF(
      aptitude = 1,
      100,
      IF(
        unitsUnderAssessment > 100,
        100,
        unitsUnderAssessment
      )
    ) /
    (SELECT
      SUM(proportion)
    FROM
      student_qualifications AS sq
    WHERE sq.tr_id = student_qualifications.tr_id
      AND aptitude != 1
    GROUP BY student_qualifications.tr_id) * proportion
  )
FROM
  student_qualifications
WHERE tr_id = '$tr_id'
  AND aptitude != 1
;
SQL;
        return DAO::getSingleValue($link, $sql);
    }

    public static function updateProgressStatistics($link, $tr_id)
    {
        $sql = "UPDATE tr
	LEFT OUTER JOIN (
		SELECT
			student_qualifications.tr_id,
			SUM(IF(unitsUnderAssessment>100,100,unitsUnderAssessment)/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) AS `percentage_completed`
		FROM
			student_qualifications
		WHERE aptitude!=1 and student_qualifications.tr_id = $tr_id
		GROUP BY
			student_qualifications.tr_id
	) AS `student qualifications subquery`
		ON `student qualifications subquery`.tr_id = tr.id
SET l36 = `student qualifications subquery`.percentage_completed
WHERE tr.id = $tr_id;
		";
        DAO::execute($link, $sql);
    }

    public static function getFrameworkTarget($link, $id)
    {
        $target = DAO::getSingleValue($link, "SELECT SUM(`sub`.target*proportion/(SELECT SUM(proportion) FROM student_qualifications WHERE tr_id = tr.id AND aptitude!=1))
FROM tr
LEFT OUTER JOIN (SELECT
student_milestones.tr_id,
student_qualifications.proportion,
CASE timestampdiff(MONTH, student_qualifications.start_date, CURDATE())
WHEN -1 THEN 0
WHEN -2 THEN 0
WHEN -3 THEN 0
WHEN -4 THEN 0
WHEN -5 THEN 0
WHEN -6 THEN 0
WHEN -7 THEN 0
WHEN -8 THEN 0
WHEN -9 THEN 0
WHEN -10 THEN 0
WHEN 0 THEN 0
WHEN 1 THEN AVG(student_milestones.month_1)
WHEN 2 THEN AVG(student_milestones.month_2)
WHEN 3 THEN AVG(student_milestones.month_3)
WHEN 4 THEN AVG(student_milestones.month_4)
WHEN 5 THEN AVG(student_milestones.month_5)
WHEN 6 THEN AVG(student_milestones.month_6)
WHEN 7 THEN AVG(student_milestones.month_7)
WHEN 8 THEN AVG(student_milestones.month_8)
WHEN 9 THEN AVG(student_milestones.month_9)
WHEN 10 THEN AVG(student_milestones.month_10)
WHEN 11 THEN AVG(student_milestones.month_11)
WHEN 12 THEN AVG(student_milestones.month_12)
WHEN 13 THEN AVG(student_milestones.month_13)
WHEN 14 THEN AVG(student_milestones.month_14)
WHEN 15 THEN AVG(student_milestones.month_15)
WHEN 16 THEN AVG(student_milestones.month_16)
WHEN 17 THEN AVG(student_milestones.month_17)
WHEN 18 THEN AVG(student_milestones.month_18)
WHEN 19 THEN AVG(student_milestones.month_19)
WHEN 20 THEN AVG(student_milestones.month_20)
WHEN 21 THEN AVG(student_milestones.month_21)
WHEN 22 THEN AVG(student_milestones.month_22)
WHEN 23 THEN AVG(student_milestones.month_23)
WHEN 24 THEN AVG(student_milestones.month_24)
WHEN 25 THEN AVG(student_milestones.month_25)
WHEN 26 THEN AVG(student_milestones.month_26)
WHEN 27 THEN AVG(student_milestones.month_27)
WHEN 28 THEN AVG(student_milestones.month_28)
WHEN 29 THEN AVG(student_milestones.month_29)
WHEN 30 THEN AVG(student_milestones.month_30)
WHEN 31 THEN AVG(student_milestones.month_31)
WHEN 32 THEN AVG(student_milestones.month_32)
WHEN 33 THEN AVG(student_milestones.month_33)
WHEN 34 THEN AVG(student_milestones.month_34)
WHEN 35 THEN AVG(student_milestones.month_35)
WHEN 36 THEN AVG(student_milestones.month_36)
ELSE 100
END AS target
FROM
student_milestones
INNER JOIN student_qualifications ON student_qualifications.id = student_milestones.`qualification_id` AND student_milestones.tr_id = student_qualifications.`tr_id` AND student_milestones.`tr_id` = $id and student_qualifications.aptitude!=1
GROUP BY student_milestones.`qualification_id`) AS `sub` ON `sub`.tr_id = tr.id WHERE tr.id = $id;
");
        return $target;
    }

    public function isGrouped($link)
    {
        $group_id = DAO::getSingleValue($link, "select groups_id from group_members where tr_id = $this->id");
        return $group_id;
    }

    /**
     * @static
     * @param string $uln
     * @return bool
     */
    public static function validateULN($uln)
    {
        $uln = trim($uln);
        if($uln)
        {
            // Validate the entered ULN
            $valid_pattern = "/^[1-9]{1}[0-9]{9}$/";
            $valid_pattern = preg_match($valid_pattern, $uln);
            if($valid_pattern)
            {
                $remainder = ((10 * $uln[0])
                        + (9 * $uln[1])
                        + (8 * $uln[2])
                        + (7 * $uln[3])
                        + (6 * $uln[4])
                        + (5 * $uln[5])
                        + (4 * $uln[6])
                        + (3 * $uln[7])
                        + (2 * $uln[8])) % 11;

                if($remainder == 0)
                {
                    return false;
                }

                $check_digit = 10 - $remainder;
                if($check_digit != $uln[9])
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @static
     * @param string $ni
     * @return bool
     */
    public static function validateNI($ni)
    {
        if($ni)
        {
            // Check if is valid NI pattern
            $valid_pattern = "/^[A-CEGHJ-NOPR-TW-Z]{1}[A-CEGHJ-NPR-TW-Z]{1}[0-9]{6}[ABCD\s]{0,1}$/";
            $valid_pattern = preg_match($valid_pattern, $ni);
            $invalid_pattern = "/(^GB)|(^BG)|(^NK)|(^KN)|(^TN)|(^NT)|(^ZZ).+/";
            $invalid_pattern = preg_match($invalid_pattern, $ni);
            if($valid_pattern == false || $invalid_pattern == true)
            {
                return false;
            }
        }

        return true;
    }

    public function getWorkbookNotifications(PDO $link)
    {
        $result = new stdClass();
        $result->unread_notifications = 0;
        $result->total_notifications = 0;
        $result->notifications = array();
        $notifications = array();
        // check for workbooks
        $results = DAO::getResultset($link, "SELECT * FROM user_notifications WHERE `user_id` = '{$this->id}' AND `type` = 'WORKBOOK' ORDER BY created DESC ", DAO::FETCH_ASSOC);
        foreach($results AS $row)
        {
            $result->unread_notifications += $row['checked'] == 0 ? 1 : 0;

            $item = '';
            $item .= $row['checked'] == 0 ? '<li class="bg-gray">' : '<li>';
            $item .= '<a class="clsNotificationsMenuItem" id="' . $row['id'] . '" href="' . $row['link'] . '">' . $row['detail'] . '<br><span class="fa fa-clock-o"></span> ' . Date::to($row['created'], Date::DATETIME) . '</a>';
            $item .= '</li>';
            $notifications[] = $item;
        }
        $result->total_notifications = count($notifications);
        $result->notifications = $notifications;

        return $result;
    }

    public function getUpcomingEvents(PDO $link, $start_date = '', $end_date = '')
    {
        $end_date = new Date(date('Y-m-d'));
        $end_date->addDays(15);

        $result = new stdClass();
        $result->actions = 0;
        $result->next_review_in = "";
        $result->next_appointment_in = "";
        $result->next_crm_action_in = "";

        $review = DAO::getSingleValue($link, "SELECT DATEDIFF(due_date, CURDATE()) AS diff FROM assessor_review WHERE assessor_review.tr_id = '{$this->id}' AND assessor_review.due_date BETWEEN CURDATE() AND '" . $end_date->formatMySQL() . "' ORDER BY due_date ASC LIMIT 1");
        if($review != '')
        {
            $result->actions++;
            $result->next_review_in = "Your next review in " . $review . " days";
        }
        $appointment = DAO::getSingleValue($link, "SELECT DATEDIFF(appointment_date, CURDATE()) AS diff FROM appointments WHERE tr_id = '{$this->id}' AND appointment_date BETWEEN CURDATE() AND '" . $end_date->formatMySQL() . "' ORDER BY appointment_date ASC LIMIT 1");
        if($appointment != '')
        {
            $result->actions++;
            $result->next_appointment_in = "Your next appointment in " . $appointment . " days";
        }
        $crm_action = DAO::getSingleValue($link, "SELECT DATEDIFF(next_action_date, CURDATE()) AS diff FROM crm_notes_learner WHERE tr_id = '{$this->id}' AND next_action_date BETWEEN CURDATE() AND '" . $end_date->formatMySQL() . "' ORDER BY next_action_date ASC LIMIT 1");
        if($crm_action != '')
        {
            $result->actions++;
            $result->next_crm_action_in = "Your next crm action in " . $crm_action . " days";
        }
        return $result;
    }

    public function synchOnboardingChanges(PDO $link)
    {
        $learner = User::loadFromDatabase($link, $this->username);
        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE ob_learners.user_id = '{$learner->id}'");
        if(is_null($ob_learner))
            return;

        if(is_null($ob_learner->learner_signature) || is_null($ob_learner->employer_signature))
            return;

        DAO::transaction_start($link);
        try
        {
            /*
                        $learner = User::loadFromDatabase($link, $this->username);
                        $ob_learner = DAO::getObject($link, "SELECT * FROM ob_learners WHERE ob_learners.user_id = '{$learner->id}'");
                        if(is_null($ob_learner))
                            return;
            */
            $fields = array(
                'firstnames', 'surname', 'dob', 'home_postcode', 'home_email', 'ethnicity', 'gender', 'ni'
            , 'home_address_line_1', 'home_address_line_2', 'home_address_line_3', 'home_address_line_4'
            ,'home_telephone', 'home_mobile'
            );
            //update learner and training records
            foreach($fields AS $f)
            {
                $learner->$f = $ob_learner->$f;
                $this->$f = $ob_learner->$f;
            }
            $learner->save($link);
            $this->save($link);

            $current_submission = 'W' . DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date;");
            //$current_submission = 'W01';
            $objILR = DAO::getObject($link, "SELECT ilr.* FROM ilr WHERE ilr.tr_id = '{$this->id}' AND ilr.contract_id = '{$this->contract_id}' AND ilr.submission = '{$current_submission}'");
            $ilr = XML::loadSimpleXML($objILR->ilr);
            $ilr->FamilyName = $this->surname;
            $ilr->GivenNames = addslashes((string)$this->firstnames);
            $ilr->DateOfBirth = $this->dob;
            $ilr->Ethnicity = $this->ethnicity;
            if($this->gender == 'M' || $this->gender == 'F')
                $ilr->Sex = $this->gender;
            $ilr->LLDDHealthProb = $ob_learner->LLDD;
            if($ob_learner->LLDD == 'Y')
            {
                //if no LLDDHealthProb then create else update
                if(!isset($ilr->LLDDHealthProb))
                {
                    $LLDDHealthProb = $ilr->addChild('LLDDHealthProb', '1');
                }
                else
                {
                    $ilr->LLDDHealthProb = '1';
                }
                $ob_learner_lldd_cat = explode(',', $ob_learner->llddcat);
                // first remove and then add the updated
                unset($ilr->LLDDandHealthProblem);
                foreach($ob_learner_lldd_cat AS $cat)
                {
                    $LLDDandHealthProblem = $ilr->addChild('LLDDandHealthProblem');
                    $LLDDandHealthProblem->LLDDCat = $cat;
                    if($cat == $ob_learner->primary_lldd)
                        $LLDDandHealthProblem->PrimaryLLDD = '1';
                }
            }
            elseif($ob_learner->LLDD == 'N')
            {
                //if no LLDDHealthProb then create else update
                if(!isset($ilr->LLDDHealthProb))
                {
                    $ilr->addChild('LLDDHealthProb', '2');
                }
                else
                {
                    $ilr->LLDDHealthProb = '2';
                }
            }
            elseif($ob_learner->LLDD == 'P')
            {
                //if no LLDDHealthProb then create else update
                if(!isset($ilr->LLDDHealthProb))
                {
                    $ilr->addChild('LLDDHealthProb', '9');
                }
                else
                {
                    $ilr->LLDDHealthProb = '9';
                }
            }
            $ilr->NINumber = $ob_learner->ni;
            $prior_attain = DAO::getSingleValue($link, "SELECT `level` FROM ob_learners_pa WHERE ob_learner_id = '{$ob_learner->id}' AND q_type = 'h' LIMIT 1");
            if($prior_attain != '')
                $ilr->PriorAttain = $prior_attain;

            unset($ilr->LearnerContact);

            $LearnerContact1 = $ilr->addChild('LearnerContact');
            $LearnerContact1->LocType = '2';
            $LearnerContact1->ContType = '1';
            $LearnerContact1->PostCode = $this->home_postcode;

            $LearnerContact2 = $ilr->addChild('LearnerContact');
            $LearnerContact2->LocType = '2';
            $LearnerContact2->ContType = '2';
            $LearnerContact2->PostCode = $this->home_postcode;

            $LearnerContact3 = $ilr->addChild('LearnerContact');
            $LearnerContact3->LocType = '3';
            $LearnerContact3->ContType = '2';
            $LearnerContact3->TelNumber = $this->home_telephone;

            $LearnerContact4 = $ilr->addChild('LearnerContact');
            $LearnerContact4->LocType = '4';
            $LearnerContact4->ContType = '2';
            $LearnerContact4->Email = $this->home_email;

            $LearnerContact5 = $ilr->addChild('LearnerContact');
            $LearnerContact5->LocType = '1';
            $LearnerContact5->ContType = '2';
            $PostAdd = $LearnerContact5->addChild('PostAdd');
            $PostAdd->AddLine1 = $this->home_address_line_1;
            $PostAdd->AddLine2 = $this->home_address_line_2;
            $PostAdd->AddLine3 = $this->home_address_line_3;
            $PostAdd->AddLine4 = $this->home_address_line_4;

            unset($ilr->ContactPreference);
            $ob_learners_RUI = explode(',', $ob_learner->RUI);
            foreach($ob_learners_RUI AS $v)
            {
                $ContactPreference = $ilr->addChild('ContactPreference');
                $ContactPreference->ContPrefType = 'RUI';
                $ContactPreference->ContPrefCode = $v;
            }
            $ob_learners_PMC = explode(',', $ob_learner->PMC);
            foreach($ob_learners_PMC AS $v)
            {
                $ContactPreference = $ilr->addChild('ContactPreference');
                $ContactPreference->ContPrefType = 'PMC';
                $ContactPreference->ContPrefCode = $v;
            }

            $prior_enrolment_emp_date = new Date($this->start_date);
            $prior_enrolment_emp_date->subtractDays(1);
            foreach($ilr->LearnerEmploymentStatus AS $emp_stat)
            {
                if($emp_stat->DateEmpStatApp->__toString() == $prior_enrolment_emp_date->formatMySQL())
                {
                    $dom = dom_import_simplexml($emp_stat);
                    $dom->parentNode->removeChild($dom);
                }
            }
            $LearnerEmploymentStatus = $ilr->addChild('LearnerEmploymentStatus');
            $LearnerEmploymentStatus->EmpStat = $ob_learner->EmploymentStatus;
            $LearnerEmploymentStatus->DateEmpStatApp = $prior_enrolment_emp_date->formatMySQL();
            if($ob_learner->EmploymentStatus == '10')
            {
                $SEI = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $SEI->addChild('ESMType', 'SEI');
                $SEI->addChild('ESMCode', $ob_learner->SEI);

                $SEM = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $SEM->addChild('ESMType', 'SEM');
                $SEM->addChild('ESMCode', $ob_learner->SEM);

                $LOE = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $LOE->addChild('ESMType', 'LOE');
                $LOE->addChild('ESMCode', $ob_learner->LOE);

                $EII = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $EII->addChild('ESMType', 'EII');
                $EII->addChild('ESMCode', $ob_learner->EII);
            }
            if($ob_learner->EmploymentStatus == '11' || $ob_learner->EmploymentStatus == '12')
            {
                $LOU = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $LOU->addChild('ESMType', 'LOU');
                $LOU->addChild('ESMCode', $ob_learner->LOU);

                $BSI = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $BSI->addChild('ESMType', 'BSI');
                $BSI->addChild('ESMCode', $ob_learner->BSI);

                $PEI = $LearnerEmploymentStatus->addChild('EmploymentStatusMonitoring');
                $PEI->addChild('ESMType', 'PEI');
                $PEI->addChild('ESMCode', $ob_learner->PEI);
            }
            $dom = new DOMDocument;
            $dom->preserveWhiteSpace = FALSE;
            @$dom->loadXML($ilr->saveXML());
            $dom->formatOutput = TRUE;
            $modified_ilr = $dom->saveXml();
            $modified_ilr = str_replace('<?xml version="1.0"?>', '', $modified_ilr);

            DAO::execute($link, "UPDATE ilr SET ilr.ilr = '{$modified_ilr}' WHERE ilr.tr_id = '{$this->id}' AND ilr.contract_id = '{$this->contract_id}' AND ilr.submission = '{$current_submission}'");

            DAO::execute($link, "UPDATE tr SET tr.ob_alert = '0' WHERE tr.id = '{$this->id}'");

            $log = new OnboardingLogger();
            $log->subject = 'DATA SYNCHRONIZED';
            $log->note = "Details from onboarding data capture form are updated in Learner, Training Record and ILR";
            $log->ob_learner_id = $ob_learner->id;
            $log->by_whom = $_SESSION['user']->id;
            $log->save($link);
            unset($log);

            if($ob_learner->is_finished == 'Y' && !is_null($ob_learner->learner_signature))
            {
                $signature = "";
                $signature_parts = explode('&', $ob_learner->learner_signature);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                }
                if($signature != "")
                {
                    if(!file_exists(Repository::getRoot().'/'.$this->username))
                        mkdir(Repository::getRoot().'/'.$this->username);
                    $learner_signature_file = Repository::getRoot(). '/'.$this->username.'/learner_signature.png';
                    imagepng($signature, $learner_signature_file, 0, NULL);
                }
            }
            if($ob_learner->is_finished == 'Y' && !is_null($ob_learner->employer_signature))
            {
                $signature = "";
                $signature_parts = explode('&', $ob_learner->employer_signature);
                if(isset($signature_parts[0]) && isset($signature_parts[1]) && isset($signature_parts[2]))
                {
                    $title = explode('=', $signature_parts[0]);
                    $font = explode('=', $signature_parts[1]);
                    $size = explode('=', $signature_parts[2]);
                    $signature = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
                }
                if($signature != "")
                {
                    if(!file_exists(Repository::getRoot().'/'.$this->username))
                        mkdir(Repository::getRoot().'/'.$this->username);
                    $employer_signature_file = Repository::getRoot(). '/'.$this->username.'/employer_signature.png';
                    imagepng($signature, $employer_signature_file, 0, NULL);
                }
            }

            DAO::transaction_commit($link);
        }
        catch(Exception $e)
        {
            DAO::transaction_rollback($link, $e);
            throw new WrappedException($e);
        }
    }

    public function getTrackingUnitsDetail(PDO $link)
    {
        $return = [];
        if(DB_NAME != "am_baltic" && DB_NAME != "am_baltic_demo")
            return $return;

        $status_options = InductionHelper::getListSchOptions();
        $sql = <<<SQL

SELECT m1.*
FROM op_tracker_unit_sch m1 LEFT JOIN op_tracker_unit_sch m2
 ON (m1.tr_id = m2.tr_id AND m1.unit_ref = m2.unit_ref AND m1.id < m2.id)
WHERE m2.id IS NULL AND m1.tr_id = '$this->id';

SQL;

        $results = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($results AS $row)
        {
            $u_ref = $row['unit_ref'];
            if(substr($u_ref, -5) === ' Test' || strtolower(substr($u_ref, -5)) === ' test')
            {
                $_sql = <<<SQL
SELECT
	IF(session_attendance.`attendance_date` IS NOT NULL, session_attendance.`attendance_date`, sessions.`start_date`) AS attendance_date
FROM
	sessions
	INNER JOIN session_entries ON sessions.id = session_entries.`entry_session_id`
	LEFT JOIN session_attendance ON session_entries.`entry_id` = session_attendance.`session_entry_id`
WHERE
	session_entries.`entry_tr_id` = '$this->id'
	AND session_entries.`entry_exam_name` = '$u_ref'
	AND sessions.`event_type` != 'SUP'
ORDER BY
	sessions.`created` DESC
LIMIT 1
;
SQL;
            }
            else
            {
                $_sql = <<<SQL
SELECT
	IF(session_attendance.`attendance_date` IS NOT NULL, MIN(session_attendance.`attendance_date`), sessions.`start_date`) AS attendance_date
FROM
	sessions
	INNER JOIN session_entries ON sessions.id = session_entries.`entry_session_id`
	LEFT JOIN session_attendance ON session_entries.`entry_id` = session_attendance.`session_entry_id`
WHERE
	(FIND_IN_SET('$u_ref', sessions.`unit_ref`)
	OR
	'$u_ref' = sessions.`unit_ref`)
	AND session_entries.`entry_tr_id` = '$this->id'
;
SQL;
            }
            $return[] = [
                'unit_ref' => $row['unit_ref'],
                'code' => isset($status_options[$row['code']]) ? $status_options[$row['code']] : $row['code'],
                'date' => DAO::getSingleValue($link, $_sql)
            ];
        }

        return $return;
    }


    public function getCourseTitle(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT courses.title FROM courses INNER JOIN courses_tr ON courses.id = courses_tr.course_id WHERE courses_tr.tr_id = '{$this->id}'");
    }

    public function getProgType(PDO $link)
    {
        return DAO::getSingleValue($link,"select framework_type from frameworks where id in (select id from student_frameworks where tr_id = '$this->id')");
    }

    public static function getAssessmentProgress(PDO $link, $tr_id)
    {

        $course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '$tr_id'");
        $total_units = DAO::getSingleValue($link, "SELECT MAX(aps) FROM ap_percentage WHERE course_id = '{$course_id}';");
        $passed_units = DAO::getSingleValue($link, "SELECT COUNT(*) FROM assessment_plan_log LEFT JOIN assessment_plan_log_submissions AS sub ON sub.assessment_plan_id = assessment_plan_log.id AND
		        sub.id = (SELECT MAX(id) FROM assessment_plan_log_submissions WHERE assessment_plan_log_submissions.assessment_plan_id = assessment_plan_log.id)
                WHERE tr_id = '{$tr_id}' AND completion_date is not null");
        if($total_units==0)
            return 0;
        else
            return round(($passed_units/$total_units) * 100);
    }

    public static function getDiscountedStartDate(PDO $link, $tr_id)
    {
        return DAO::getSingleValue($link, "SELECT DATE_SUB(start_date, INTERVAL
COALESCE((SELECT SUM(DATEDIFF(closure_date,start_date)+1) FROM tr AS trp
LEFT JOIN courses_tr AS trcp ON trcp.`tr_id` = trp.`id`
LEFT JOIN frameworks AS trfp ON trfp.id = trcp.`framework_id`
WHERE trp.status_code = 6 AND trp.start_date < tr.start_date AND trp.l03 = tr.l03
AND trp.closure_date IS NOT NULL
AND trf.framework_type = trfp.framework_type
AND ((trfp.framework_code IS NOT NULL AND trf.framework_code = trfp.framework_code)
OR (trfp.StandardCode IS NOT NULL AND trfp.StandardCode = trf.`StandardCode`))),0)
DAY)
FROM tr
LEFT JOIN courses_tr AS trc ON trc.`tr_id` = tr.`id`
LEFT JOIN frameworks AS trf ON trf.id = trc.`framework_id`
WHERE tr.id = $tr_id;
");
    }

    public static function getOriginalStartDate(PDO $link, $tr_id)
    {
        return DAO::getSingleValue($link, "SELECT
MIN(IF(tr.start_date < tr2.`start_date`, tr.`start_date`, tr2.`start_date`)) AS start_date
FROM tr
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN frameworks ON frameworks.id = courses_tr.`framework_id`
LEFT JOIN tr AS tr2 ON tr.l03 = tr2.l03
LEFT JOIN courses_tr AS courses_tr2 ON courses_tr2.tr_id = tr2.`id`
LEFT JOIN frameworks AS frameworks2 ON frameworks2.id = courses_tr2.`framework_id`
WHERE tr.id = '$tr_id' 
AND frameworks.`framework_type` = frameworks2.`framework_type`
AND (frameworks.`framework_code` = frameworks2.`framework_code` OR frameworks.StandardCode = frameworks2.StandardCode);
");
    }

    public static function getCurrentDiscountedTrainingMonth(PDO $link, $tr_id)
    {
        $dateOne = TrainingRecord::getDiscountedStartDate($link, $tr_id);
        $dateTwo = date('Y-m-d');
        $firstDate = new DateTime($dateOne);
        $secondDate = new DateTime($dateTwo);
        $differenceInDays = $firstDate->diff($secondDate)->days;
        $differenceInWeeks = $differenceInDays / 7;

        // if(DB_NAME=='am_baltic_demo' || DB_NAME=='am_baltic')
            // return floor($differenceInWeeks);
        // else
            return floor($differenceInWeeks);
        //return DAO::getSingleValue($link, "SELECT TIMESTAMPDIFF(MONTH, '$start_date', CURDATE())");
    }

    public static function getDiscountedDaysOnProgramme(PDO $link, $tr_id, $end_date = '')
    {
        $start_date = TrainingRecord::getDiscountedStartDate($link, $tr_id);
        if($end_date=='')
            $end_date = DAO::getSingleValue($link, "SELECT IF(closure_date IS NOT NULL, closure_date, CURDATE()) FROM tr WHERE id = '$tr_id';");

        $firstDate = new DateTime($start_date);
        $secondDate = new DateTime(Date::toMySQL($end_date));
        $differenceInDays = $firstDate->diff($secondDate)->days;

        return $differenceInDays+1;
    }

    public static function getFirstCourseDate(PDO $link, $tracker_id, $tr_id)
    {
        $tracker_units_list = DAO::getSingleColumn($link, "SELECT unit_ref  FROM op_tracker_units WHERE tracker_id = '{$tracker_id}'");
        $first_course_date = new Date('2050-01-01');
        foreach($tracker_units_list AS $_tracker_unit)
        {
            $_sql = "SELECT sessions.start_date FROM sessions INNER JOIN session_entries ON sessions.`id` = session_entries.`entry_session_id`
                                                    WHERE session_entries.`entry_tr_id` = '{$tr_id}' AND FIND_IN_SET('{$tracker_id}', sessions.`tracker_id`)  AND FIND_IN_SET('{$_tracker_unit}', unit_ref);";
            $course_date = DAO::getSingleValue($link, $_sql);
            if($course_date == '')
                continue;
            $course_date = new Date($course_date);
            if($course_date->before($first_course_date))
                $first_course_date = $course_date;
        }
        if($first_course_date->__toString() != '2050-01-01')
            return $first_course_date;

        return null;
    }


    public static function getEvidenceProgress(PDO $link, $tr_id, $course_id, $complete_iqa = 0, $current_training_month = 0)
    {
    	//if(DB_NAME == "am_baltic_demo") {return (object)['total' => 0, 'target' => 0, 'matrix' => 0];}
        if($complete_iqa==1)
        {
            $query = "SELECT
(SELECT COUNT(*) FROM evidence_criteria WHERE course_id = $course_id) AS total
,(SELECT COUNT(*) FROM evidence_criteria WHERE FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions WHERE project_submissions.completion_date IS NOT NULL AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0) AS matrix
,(SELECT comp FROM ap_percentage WHERE course_id = '$course_id' AND max_month < '$current_training_month' ORDER BY comp DESC LIMIT 1) AS target
,(select summative_raised_date_pct from courses where id = $course_id) as summative_raised_date_pct
";
        }
        else
        {
            $query = "SELECT
(SELECT COUNT(*) FROM evidence_criteria WHERE course_id = $course_id) AS total
,(SELECT COUNT(*) FROM evidence_criteria WHERE FIND_IN_SET(evidence_criteria.id,(SELECT GROUP_CONCAT(REPLACE(matrix,\" \",\"\")) FROM project_submissions
where ((project_submissions.completion_date IS NULL AND project_submissions.iqa_recheck_date is null  and COALESCE(project_submissions.iqa_status,0) != 2) and project_submissions.sent_iqa_date IS NOT NULL)
AND project_id IN (SELECT id FROM tr_projects WHERE tr_id = '$tr_id')))<>0)
+(SELECT COUNT(DISTINCT competency_id) FROM submissions_iqa WHERE tr_id = '$tr_id' AND iqa_accept = 1 and submission_id IN (SELECT id FROM project_submissions WHERE completion_date IS NOT NULL)) as matrix
,(SELECT comp FROM ap_percentage WHERE course_id = '$course_id' AND max_month < '$current_training_month' ORDER BY comp DESC LIMIT 1) AS target";
        }

        $obj = DAO::getObject($link, $query);
        return $obj;
    }

    public static function getProjects(PDO $link, $tr_id)
    {
        $query = "SELECT
evidence_project.project
,IF(sub.completion_date IS NOT NULL,\"Complete\",IF(sub.iqa_status=2,\"Rework required\",IF(sub.sent_iqa_date IS NOT NULL AND (sub.iqa_status IS NULL OR sub.iqa_status!=2),\"IQA\",IF(sub.submission_date IS NOT NULL,\"Awaiting marking\",IF(sub.due_date<CURDATE() AND submission_date IS NULL,\"Overdue\",\"In progress\"))))) AS `status`
FROM tr_projects
LEFT JOIN project_submissions AS sub ON sub.`project_id` = tr_projects.`id` AND sub.id = (SELECT MAX(id) FROM project_submissions AS s2 WHERE s2.`project_id` = tr_projects.id)
LEFT JOIN evidence_project ON evidence_project.id = tr_projects.project
WHERE tr_projects.project IS NOT NULL AND tr_projects.tr_id= '$tr_id'";
        $obj = DAO::getResultSet($link, $query);
        return $obj;
    }

    public function updateOperationsStatus(PDO $link)
    {
        $op_details = DAO::getObject($link, "SELECT * FROM tr_operations WHERE tr_id = '{$this->id}'");
        if(!isset($op_details->tr_id))
        {
            return;
        }

        if($op_details->bil_details == '')
        {
            $bil_details = XML::loadSimpleXML('<Note><Type></Type><Date></Date><Note></Note><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $bil_details = XML::loadSimpleXML($op_details->bil_details);
            $bil_details = $bil_details->Note;
            $max = count($bil_details);
            $bil_details = $bil_details[$max-1];
        }

        if($op_details->lar_details == '')
        {
            $lar_details = XML::loadSimpleXML('<Note><Type></Type><Date></Date><Note></Note><RAG></RAG><NextActionDate></NextActionDate><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $lar_details = XML::loadSimpleXML($op_details->lar_details);
            $lar_details = $lar_details->Note;
            $max = count($lar_details);
            $lar_details = $lar_details[$max-1];
        }

	if(in_array($lar_details->Type->__toString(), ["O", "S", "D"]))
        {
            return;
        }
        
        $system_auto_learner_status = "";
        if(in_array($this->status_code, ["1", "2", "3"])  && !in_array($op_details->learner_status, ["LRA", "A", "F", "PL", "LB", "PNDL"]) )
        {
            $today = new Date(date('Y-m-d'));
            $gateway_forecast_actual_date = DAO::getSingleValue($link, "SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = '{$this->id}' AND op_epa.`task` = '12' ORDER BY id DESC LIMIT 1");
            $summative_actual_date = DAO::getSingleValue($link, "SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = '{$this->id}' AND op_epa.`task` = '3' ORDER BY id DESC LIMIT 1");
            $passed_to_ss = DAO::getSingleValue($link, "SELECT op_epa.task_applicable FROM op_epa WHERE op_epa.`tr_id` = '{$this->id}' AND op_epa.`task` = '5' ORDER BY id DESC LIMIT 1");
            $passed_to_ss_date = DAO::getSingleValue($link, "SELECT op_epa.task_actual_date FROM op_epa WHERE op_epa.`tr_id` = '{$this->id}' AND op_epa.`task` = '5' ORDER BY id DESC LIMIT 1");
            
            if($gateway_forecast_actual_date != '' && $summative_actual_date != '' && $passed_to_ss_date == '')
            {
                $gateway_forecast_actual_date = new Date($gateway_forecast_actual_date);
                if($gateway_forecast_actual_date->before($today))
                {
                    $system_auto_learner_status = "PC";
                }
            }
            if($system_auto_learner_status == "" && $gateway_forecast_actual_date != '' && $summative_actual_date == '')
            {
                $gateway_forecast_actual_date = new Date($gateway_forecast_actual_date);
                if($gateway_forecast_actual_date->before($today))
                {
                    $system_auto_learner_status = "PA";
                }
            }
            if($system_auto_learner_status == "" && $bil_details->Type->__toString() == "F")
            {
                $system_auto_learner_status = "FBIL";
            }
            if($system_auto_learner_status == "" && $bil_details->Type->__toString() == "O")
            {
                $system_auto_learner_status = "OBIL";
            }            
            // if($system_auto_learner_status == "" && $gateway_forecast_actual_date != "")// && !in_array($op_details->learner_status, ["PC", "PA", "FBIL", "OBIL"]))
            // {
            //     $gateway_forecast_actual_date = new Date($gateway_forecast_actual_date);
            //     if( $gateway_forecast_actual_date->after($today) && in_array($lar_details->Type->__toString(), ["O", "S"]) )
            //     {
            //         $system_auto_learner_status = "LAR";
            //     }
            // }
            if($gateway_forecast_actual_date != '' && $summative_actual_date != '' && $passed_to_ss_date != '')
            {
                $system_auto_learner_status = "GR";
            }

            if($system_auto_learner_status == '' && !in_array($op_details->learner_status, ["LRA", "A", "F"]))
            {
                $op_details->learner_status = "OP";
            }
            else
            {
                $op_details->learner_status = $system_auto_learner_status;
            }

            DAO::execute($link, "UPDATE tr_operations SET tr_operations.learner_status = '{$op_details->learner_status}' WHERE tr_operations.tr_id = '{$this->id}'");
        }

        if( 
            (isset($gateway_forecast_actual_date) && $gateway_forecast_actual_date != '') && 
            (isset($summative_actual_date) && $summative_actual_date != '') && 
            (isset($passed_to_ss_date) && $passed_to_ss_date != '') 
            )
        {
	    $op_details->learner_status = "GR";
            DAO::execute($link, "UPDATE tr_operations SET tr_operations.learner_status = 'GR' WHERE tr_operations.tr_id = '{$this->id}'");
        }

        // extra rules given which can be triggered if status is not OP
        if($op_details->learner_status == 'LAR' && $lar_details->Type->__toString() == 'N')
        {
            $op_details->learner_status = "OP";
            DAO::execute($link, "UPDATE tr_operations SET tr_operations.learner_status = '{$op_details->learner_status}' WHERE tr_operations.tr_id = '{$this->id}'");
        }
        
    }

    public function createReviews(PDO $link)
    {
        $frequency = DAO::getObject($link, "SELECT first_review, review_frequency FROM frameworks WHERE id IN (SELECT framework_id FROM courses_tr WHERE tr_id = '{$this->id}');");
        $review = array();

        // First review 
        $review['tr_id'] = $this->id;
        $first_review = $frequency->first_review;
        $due_date = DAO::getSingleValue($link, "select DATE_ADD(start_date, INTERVAL '$first_review' WEEK) from tr where id = '{$this->id}'");
        $review['due_date'] = $due_date;
        $row = array();
        $row[] = $review;
        $index=1;
        while(true)
        {
            $interval = $first_review+($frequency->review_frequency*$index);
            $due_date = DAO::getSingleValue($link, "select DATE_ADD(start_date, INTERVAL '$interval' WEEK) from tr where id = '{$this->id}' and target_date > DATE_ADD(start_date, INTERVAL '$interval' WEEK)");
            if($due_date!="")
            {
                $review = array();
                $review['tr_id'] = $this->id;
                $review['due_date'] = $due_date;
                $row[] = $review;
            }
            else
            {
                DAO::multipleRowInsert($link, "assessor_review", $row);
                return true;
            }
            $index++;
        }
    }

    public function dueToRestart($link)
    {
        $tr_id = $this->id;
        $LearnRefNumber = $this->l03;
        $last_id = DAO::getSingleValue($link, "SELECT MAX(id) FROM tr WHERE l03 = '$LearnRefNumber' AND status_code = 6");
        if($last_id == $tr_id)
            return true;
        else
            return false;
    }

    public function daysToComplete()
    {
        $earlier = new DateTime($this->closure_date);
        $later = new DateTime($this->target_date);
        return $later->diff($earlier)->format("%a");
    }

    public $id = NULL;
    public $username = NULL;
    public $programme = NULL;
    public $cohort = NULL;
    public $start_date = NULL;
    public $target_date = NULL;
    public $closure_date = NULL;
    public $marked_date = NULL;
    public $status_code = NULL;


    // SNAPSHOT FROM LEARNER RECORD
    public $school_id = NULL;
    public $firstnames = NULL;
    public $surname = NULL;
    public $gender = NULL;
    public $ethnicity = NULL;
    public $dob = NULL;
    public $uln = NULL;
    public $upi = NULL;
    public $upn = NULL;
    public $ni = NULL;
    public $numeracy = NULL;
    public $literacy = NULL;

    /*	public $home_paon_start_number = NULL;
        public $home_paon_start_suffix = NULL;
        public $home_paon_end_number = NULL;
        public $home_paon_end_suffix = NULL;
        public $home_paon_description = NULL;
        public $home_saon_start_number = NULL;
        public $home_saon_start_suffix = NULL;
        public $home_saon_end_number = NULL;
        public $home_saon_end_suffix = NULL;
        public $home_saon_description = NULL;
        public $home_street_description = NULL;
        public $home_locality = NULL;
        public $home_town = NULL;
        public $home_county = NULL;*/
    public $home_address_line_1 = NULL;
    public $home_address_line_2 = NULL;
    public $home_address_line_3 = NULL;
    public $home_address_line_4 = NULL;
    public $home_postcode = NULL;
    public $home_email = NULL;
    public $home_telephone = NULL;
    public $home_mobile = NULL;

    // Khushnood stuff for ILR
    public $learning_difficulties = NULL;
    public $disability = NULL;
    public $learning_difficulty = NULL;
    public $current_postcode = NULL;
    public $country_of_domicile = NULL;
    public $prior_attainment_level = NULL;
    public $contract_id = NULL;
    public $l03 = NULL;
    public $l28a = NULL;
    public $l28b = NULL;
    public $l34a = NULL;
    public $l34b = NULL;
    public $l34c = NULL;
    public $l34d = NULL;
    public $l36 = NULL;
    public $l37 = NULL;
    public $l39 = NULL;
    public $l40a = NULL;
    public $l40b = NULL;
    public $l41a = NULL;
    public $l41b = NULL;
    public $l45 = NULL;
    public $l47 = NULL;

    public $employer_id = NULL;
    public $legal_name = NULL;
    public $full_name = NULL;
    public $employer_location_id = NULL;
    /*	public $work_paon_start_number = NULL;
        public $work_paon_start_suffix = NULL;
        public $work_paon_end_number = NULL;
        public $work_paon_end_suffix = NULL;
        public $work_paon_description = NULL;
        public $work_saon_start_number = NULL;
        public $work_saon_start_suffix = NULL;
        public $work_saon_end_number = NULL;
        public $work_saon_end_suffix = NULL;
        public $work_saon_description = NULL;
        public $work_street_description = NULL;
        public $work_locality = NULL;
        public $work_town = NULL;
        public $work_county = NULL;*/
    public $work_address_line_1 = NULL;
    public $work_address_line_2 = NULL;
    public $work_address_line_3 = NULL;
    public $work_address_line_4 = NULL;
    public $work_postcode = NULL;
    public $work_email = NULL;
    public $work_telephone = NULL;
    public $work_mobile = NULL;

    public $provider_id = NULL;
    public $provider_full_name = NULL;
    public $provider_location_id = NULL;
    /*	public $provider_paon_start_number = NULL;
        public $provider_paon_start_suffix = NULL;
        public $provider_paon_end_number = NULL;
        public $provider_paon_end_suffix = NULL;
        public $provider_paon_description = NULL;
        public $provider_saon_start_number = NULL;
        public $provider_saon_start_suffix = NULL;
        public $provider_saon_end_number = NULL;
        public $provider_saon_end_suffix = NULL;
        public $provider_saon_description = NULL;
        public $provider_street_description = NULL;
        public $provider_locality = NULL;
        public $provider_town = NULL;
        public $provider_county = NULL;*/
    public $provider_address_line_1 = NULL;
    public $provider_address_line_2 = NULL;
    public $provider_address_line_3 = NULL;
    public $provider_address_line_4 = NULL;
    public $provider_postcode = NULL;
    public $provider_email = NULL;
    public $provider_telephone = NULL;



    // ATTENDANCE STATISTICS
    public $scheduled_lessons = null;
    public $registered_lessons = null;
    public $attendances = null;
    public $lates = null;
    public $very_lates = null;
    public $authorised_absences = null;
    public $unexplained_absences = null;
    public $unauthorised_absences = null;
    public $dismissals_uniform = null;
    public $dismissals_discipline = null;

    public $units_total = null;
    public $units_not_started = null;
    public $units_behind = null;
    public $units_on_track = null;
    public $units_under_assessment = null;
    public $units_completed = null;
    public $uploadedfile = NULL;
    public $work_experience = NULL;

    public $assessor = NULL;
    public $tutor = NULL;
    public $verifier = NULL;
    public $wbcoordinator = NULL;
    public $reason_for_leaving = NULL;
    public $reasons_for_leaving = NULL;
    public $ilr_status = NULL;
    public $l42a = NULL;
    public $l42b = NULL;
    public $archive_box = NULL;
    public $destruction_date = NULL;
    public $reason_unfunded = NULL;
    public $revised_planned = NULL;
    public $portfolio_in_date = NULL;
    public $portfolio_iv_date = NULL;
    public $ace_sign_date = NULL;
    public $tr_01_received = NULL;//added for Edudo
    public $added_to_issues = NULL;//added for Edudo
    public $cost_code = NULL;//added for Edudo

    public $acm = NULL; //for pathway
    public $iv_line_manager = NULL; //for pathway
    public $notification_status = NULL; //for pathway

    public $tdf1 = NULL; //for lead
    public $tdf2 = NULL; //for lead
    public $achievement_date = NULL; //for lead

    public $learner_access_key = NULL;
    public $ecordia_id = NULL;

    public $college_id  = NULL; // for siemens
    public $at_risk  = NULL; // for liga uk
    public $crm_contact_id = NULL;
    public $learner_work_email = NULL;
    public $ob_alert = NULL; // for Siemens
    public $created = NULL;

    public $cs_review1 = NULL;
    public $cs_review2 = NULL;
    public $cs_review3 = NULL;

    public $epa_organisation = NULL;
    public $epa_assessor_name = NULL;
    public $epa_prop_date1 = NULL;
    public $epa_prop_result1 = NULL;
    public $epa_prop_date2 = NULL;
    public $epa_prop_result2 = NULL;
    public $epa_prop_date3 = NULL;
    public $epa_prop_result3 = NULL;
    public $prior_record = NULL;

    public $operations_status = NULL;

    public $college_start_date = NULL;
    public $college_end_date = NULL;

    public $coordinator = NULL;

    public $ad_lldd = NULL;
    public $ad_arrangement_req = NULL;
    public $ad_arrangement_agr = NULL;
    public $ad_evidence = NULL;

    public $coach = NULL;
    public $tg_id = NULL;

    public $otj_hours = NULL;
    public $last_contact = NULL;
    public $learner_profile = NULL;
    public $progression_discussed = NULL;
    public $outcome = NULL;
    public $progression_status = NULL;
    public $reason_not_progressing = NULL;
    public $notified_arm = NULL;
    public $app_title = NULL;
    public $progression_comments = NULL;
    public $progression_last_date = NULL;
    public $start_date_inc_epa = NULL;
    public $end_date_inc_epa = NULL;
    public $progression_rating = NULL;
    public $portfolio_prediction = NULL;
    public $actual_progression = NULL;

    public $arm_prog_status = NULL;
    public $arm_reason_not_prog = NULL;
    public $arm_closed_date = NULL;
    public $arm_revisit_progression = NULL;
    public $arm_prog_rating = NULL;
    public $arm_chance_to_progress = NULL;
    public $arm_comments = NULL;
    public $employer_mentor = NULL;
    public $planned_induction_date = NULL;
    public $actual_induction_date = NULL;

    public $hc_processed_by = null;
    public $hc_reason = null;
    public $hc_additional_info_comments = null;
    public $hc_assigned_to = null;
    public $hc_contact_comment = null;
    public $hc_date_added = null;
    public $hc_date_removed = null;

    public $dm_reason = null;
    public $dm_additional_info_comments = null;
    public $dm_assigned_to = null;
    public $dm_contact_comment = null;
    public $dm_date_added = null;
    public $dm_date_removed = null;

    public $ldd_age_category = null;
    public $ldd_gender_ident = null;
    public $ldd_sex_orient = null;
    public $ldd_condition = null;
    public $ldd_condition_other = null;
    public $ldd_mental = null;
    public $ldd_mental_other = null;
    public $ldd_physical = null;
    public $ldd_physical_other = null;
    public $ldd_undiagnosed = null;
    public $ldd_survey_choice = null;	

    public $rpl = null;
    public $red_duration = null;
    public $red_price = null;
    public $gold_employer = null;
    public $gold_learner = null;
    public $passed_to_arm = null;
    public $summative_raised_date = NULL;
    public $inherited_date = null;

    public $trusted_contact_name = null;
    public $trusted_contact_mobile = null;
    public $trusted_contact_rel = null;
    public $details_checked_date = null;

    public $last_day_of_active_learning = null;
    public $first_day_of_active_learning = null;
    public $new_planned_end_date = null;
    public $training_plan_sent = null;
    public $training_plan_signed = null;
    public $reinstatement_notes = null;
    public $pp_end_date = null;
    public $planned_epa_date = null;

    public $hc_stage = null;
    public $dm_stage = null;
    
    public $reinstatement_nda = null;
    public $reinstatement_owner = null;
    public $reinstatement_type = null;

    public $onefile_id = null;
    public $onefile_username = null;

    public $iqa_lead = NULL;
    public $summative_date = NULL;
    public $summative_date_actioned = NULL;
    public $summative_status = NULL;
    public $iqa_summary = NULL;
    public $coach_comments = NULL;
    public $reinstatement_date_raised = null;
    public $reinstatement_date_closed = null;

    public $hc_funding_month = null;
    public $dm_funding_month = null;
    public $amount_transfer_learner = null;
    public $original_start_date = null;
    public $gateway_date = NULL;
    public $bootcamp_outcome = null;

	public $attendance = null;
	public $late_total = null;
	public $unauthorised_absence = null;
	public $sickness_total = null;
	public $why_are_you_doing_this_apps = null;
	public $most_recent_review_comment = null;
	public $most_recent_employer_comment = null;
	public $most_recent_city_skills_comments = null;
    public $review_targets = null;
	public $next_progress_review = null;
	public $line_manager = null;
	public $line_manager_email = null;
    public $training_plan_sent_date = null;
    public $training_plan_signed_date = null;
    public $parent_id = null;

    public $sales_lead = null;
    public $bil_withdrawal = null;

    protected $audit_fields = array(
        'surname'=>'Learner surname', 
        'firstnames' => 'First Name', 
        'dob' => 'Date of Birth',
        'start_date' => 'Start Date',
        'target_date' => 'Planned End Date',
        'closure_date' => 'Actual End Date'
    );
}
?>