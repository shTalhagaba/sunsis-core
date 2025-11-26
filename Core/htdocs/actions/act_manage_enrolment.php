<?php
class manage_enrolment implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$is_participant = isset($_REQUEST['is_participant'])?$_REQUEST['is_participant']:false;
		$ajax_request = isset($_REQUEST['ajax_request'])?$_REQUEST['ajax_request']:false;
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:false;

		$participant_id = '';
		$tr_id = '';

		if(!$ajax_request)
		{
			$participant_id = isset($_REQUEST['participant_id'])?$_REQUEST['participant_id']:'';
			$_SESSION['bc']->add($link, "do.php?_action=manage_enrolment&participant_id=".$participant_id, "Enrol Learner/Participant");
		}
		else
		{
			if($subaction == 'load_courses')
			{
				$this->updateCourseList($link);
				return;
			}
			if($subaction == 'load_course_groups')
			{
				$this->load_course_groups($link);
				return;
			}
			if($subaction == 'get_qualifications')
			{
				$this->get_qualifications($link);
				return;
			}
			if($subaction == 'get_open_quals_from_previous_episode')
			{
                $this->get_open_quals_from_previous_episode($link);
				return;
			}
			if($subaction == 'enrol_learner')
			{
				$this->enrol_learner($link);
				return;
			}
			if($subaction == 'get_contract_year')
			{
				echo $this->getContractYear($link);
				return;
			}
			//exit;
		}


		if($participant_id == '')
			throw new Exception('Missing mandatory information.');

		$participant = User::loadFromDatabaseById($link, $participant_id);
		$contract = $participant->is_participant?Contract::loadFromDatabase($link, $participant->contract):'';
		$contractType = $participant->is_participant?ContractType::loadFromDatabase($link, $participant->getContractTypeFromContract($link)):'';

		$sort_dropdown = array(
			array(0, "Qualification Title", null),
			array(1, "Qualification ID", null));

		$quals = DAO::getResultset($link, "SELECT qualification_id, internaltitle FROM course_qualifications_dates WHERE course_id = 2");

		$provider = '';
		if($participant->is_participant)
			$provider = DAO::getSingleValue($link, "SELECT employer_id FROM users WHERE users.id = '{$participant->adviser}'");
		if(DB_NAME == "am_siemens" || DB_NAME == "am_siemens_demo")
			$colleges_ddl = DAO::getResultset($link, "SELECT id, legal_name, LEFT(legal_name, 1) FROM organisations WHERE organisations.`organisation_type` = 7 ORDER BY legal_name;"); // organisation type 7 = Colleges


		include('tpl_manage_enrolment.php');
	}

	private function getContractYear(PDO $link)
	{
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		if($contract_id == '')
			throw new Exception('Missing querystring argument: contract_id');

		echo DAO::getSingleValue($link, "SELECT contract_year FROM contracts WHERE id = '{$contract_id}'");

	}

	private function updateCourseList(PDO $link)
	{
		header('Content-Type: text/xml');

		$provider_location = array_key_exists('provider_location', $_REQUEST)?$_REQUEST['provider_location']:'';

		if($provider_location == '')
		{
			throw new Exception("Missing querystring argument 'provider_location'");
		}

		$provider_id = DAO::getSingleValue($link, "SELECT locations.organisations_id FROM locations WHERE locations.id = '{$provider_location}'");
		if($provider_id == '')
			throw new Exception('Invalid proviedr location');

		$sql = <<<HEREDOC
SELECT
  courses.id,
  courses.`title`,
  NULL
FROM
  courses
WHERE courses.`organisations_id` = '$provider_id'
;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_course_groups(PDO $link)
	{
		header('Content-Type: text/xml');

		$course_id = array_key_exists('course_id', $_REQUEST)?$_REQUEST['course_id']:'';

		if($course_id == '')
		{
			throw new Exception("Missing querystring argument 'course_id'");
		}

		$sql = <<<HEREDOC
SELECT
  groups.id,
  groups.title,
  NULL
FROM
  groups
WHERE courses_id = '$course_id'
ORDER BY groups.title;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";

			// First entry is empty
			echo "<option value=\"\"></option>\r\n";

			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}

			echo '</select>';

		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function get_qualifications(PDO $link)
	{
		$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';

		if( ($course_id == '' || !is_numeric($course_id)) )
		{
			throw new Exception("Missing or empty querystring argument 'course_id'");
		}

		$sql = <<<HEREDOC
SELECT DISTINCT
	#REPLACE(qualification_id, '/', '') AS qualification_id,
	CONCAT(REPLACE(qualification_id, '/', ''), '_',internaltitle) AS qualification_id,
	internaltitle
FROM
	course_qualifications_dates

WHERE
	course_id=$course_id
ORDER BY
	qualification_id
HEREDOC;

		if($rs = $link->query($sql))
		{

			header("Content-Type: text/xml");
			echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
			echo '<qualifications>';

			while($row = $rs->fetch())
			{
				echo '<qualification qualification_id="'.$row['qualification_id'].'" '
					.' internaltitle="'.htmlspecialchars((string)$row['internaltitle']).'" />';
			}

			echo "</qualifications>";

		}
		else
		{

		}
	}

	private function get_open_quals_from_previous_episode(PDO $link)
	{
        $participant = User::loadFromDatabaseById($link, $_REQUEST['participant_id']);
        $username = $participant->username;
        $previous_tr_id = DAO::getSingleValue($link, "select id from tr where username = '$username' order by id desc limit 0,1");

		$sql = <<<HEREDOC
SELECT DISTINCT
	CONCAT(REPLACE(id, '/', ''), '_',internaltitle,'_P') AS qualification_id,
	internaltitle
FROM
	student_qualifications
WHERE tr_id = '$previous_tr_id' AND actual_end_date IS NULL
ORDER BY
	qualification_id
HEREDOC;

		if($rs = $link->query($sql))
		{

			header("Content-Type: text/xml");
			echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
			echo '<qualifications>';

			while($row = $rs->fetch())
			{
				echo '<qualification qualification_id="'.$row['qualification_id'].'" '
					.' internaltitle="'.htmlspecialchars((string)$row['internaltitle']).'" />';
			}

			echo "</qualifications>";

		}
		else
		{

		}
	}

	private function enrol_learner(PDO $link)
	{
		$required_inputs = array(
			'participant_id'
		,'start_date'
		,'planned_end_date'
		,'provider_location'
		,'course_id'
			//,'add_to_ilr'
		,'qualification_id'
		,'aims_to_populate_partner_ukprn'
		);

		foreach($required_inputs AS $input)
		{
			if(!isset($_REQUEST[$input]) || $_REQUEST[$input] == '')
				throw new Exception('missing mandatory querystring argument - ' . $input);
		}

		$ids = isset($_REQUEST['qualification_id']) ? $_REQUEST['qualification_id'] : array();

		if(count($ids) == 0){
			return;
		}

		$selected_qualification_ids = $ids;

		$participant = User::loadFromDatabaseById($link, $_REQUEST['participant_id']);
		if(is_null($participant))
			throw new Exception('Participant record not found');

		if($participant->is_participant && (is_null($participant->contract) || $participant->contract == ''))
			throw new Exception('Participant is not attached to any contract');

		$contract = $participant->is_participant?Contract::loadFromDatabase($link, $participant->contract):Contract::loadFromDatabase($link, $_REQUEST['selected_contract']);

		$course = Course::loadFromDatabase($link, $_REQUEST['course_id']);

		$framework = Framework::loadFromDatabase($link, $course->framework_id);

		$assessor = $participant->is_participant?'':$_REQUEST['selected_assessor'];

		$tutor = $participant->is_participant?'':$_REQUEST['selected_tutor'];

		$college = $participant->is_participant?'':$_REQUEST['selected_college'];

		$group = $participant->is_participant?'':$_REQUEST['selected_group'];

		$aims_to_populate_partner_ukprn = $participant->is_participant?'':$_REQUEST['aims_to_populate_partner_ukprn'];

		$aims_to_populate_partner_ukprn = explode(',', $aims_to_populate_partner_ukprn);

        $username = $participant->username;
        $previous_tr_id = DAO::getSingleValue($link, "select id from tr where username = '$username' order by id desc limit 0,1");

        $s = '';
		// Start transaction
		try
		{
			DAO::transaction_start($link);

			$start_date = (isset($_REQUEST['start_date']))?$_REQUEST['start_date']:'';
			$planned_end_date = (isset($_REQUEST['planned_end_date']))?$_REQUEST['planned_end_date']:'';

			$provider_location_id = (isset($_REQUEST['provider_location']))?$_REQUEST['provider_location']:'';
			if($provider_location_id != '')
				$provider_location = Location::loadFromDatabase($link, $provider_location_id);

			if($planned_end_date == '')
				$planned_end_date = $start_date;

			$sd = Date::toMySQL($start_date);
			$ed = Date::toMySQL($planned_end_date);

			$tr = new TrainingRecord();
			$tr->populate($participant, true);
			if($participant->is_participant)
				$tr->contract_id = $participant->contract;
			else
				$tr->contract_id = $contract->id;
			$tr->start_date = $sd;
			$tr->target_date = $ed;
			$tr->status_code = 1;
			$tr->ethnicity = $participant->ethnicity;
			$tr->work_experience = 0;
			$tr->l36 = 0;
			$tr->assessor = $assessor;
			$tr->tutor = $tutor;
			$tr->college_id = $college;

			if(!is_null($provider_location))
			{
				$tr->provider_id = $provider_location->organisations_id;
				$tr->provider_location_id = $provider_location->id;
				$tr->provider_address_line_1 = $provider_location->address_line_1;
				$tr->provider_address_line_2 = $provider_location->address_line_2;
				$tr->provider_address_line_3 = $provider_location->address_line_3;
				$tr->provider_address_line_4 = $provider_location->address_line_4;
				$tr->provider_postcode = $provider_location->postcode;
				$tr->provider_telephone = $provider_location->telephone;
			}
			// Make it null so it does not uses users id and creates its own id
			$tr->id = NULL;
			$l03 = DAO::getSingleValue($link, "SELECT l03 FROM tr WHERE username = '{$participant->username}' LIMIT 0,1");
			if($l03 == '')
			{
				$l03 = DAO::getSingleValue($link, "SELECT MAX(l03) FROM tr WHERE l03 + 0 <> 0 AND LENGTH(RTRIM(l03))=12");
				$l03 += 1;
				$tr->l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
			}
			else
			{
				//$l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
				$tr->l03 = $l03;
			}


			$tr->save($link);

			//$sql = "INSERT INTO courses_tr (course_id, tr_id, qualification_id, framework_id) VALUES ('" . $course->id . "', '" . $tr->id . "', '', '" . $framework->id . "')";
			$sql = <<<SQL
INSERT INTO
	courses_tr
SET
	course_id = '$course->id',
	tr_id = '$tr->id',
	qualification_id = '',
	framework_id = '$framework->id'
;
SQL;
			DAO::execute($link, $sql);

			$f_title = addslashes((string)$framework->title);
			$f_code = !is_null($framework->framework_code)?$framework->framework_code:'0';

//			$sql = "INSERT INTO student_frameworks (title, id, tr_id, sector, comments, duration_in_months) VALUES ('" . $f_title . "', '" . $framework->id . "', '" . $tr->id . "', '" . $framework->id . "')";
			$sql = <<<SQL
INSERT INTO
	student_frameworks
SET
	title = '$f_title',
	id = '$framework->id',
	tr_id = '$tr->id',
	sector = '$f_code',
	comments = '$framework->comments',
	duration_in_months = '$framework->duration_in_months'
;
SQL;
			DAO::execute($link, $sql);

			if($group != '')
			{
				$sql = <<<SQL
INSERT INTO
	group_members
SET
	groups_id = '$group',
	tr_id = '$tr->id',
	member = NULL,
	updated = NULL
;
SQL;
				DAO::execute($link, $sql);
			}

			foreach($selected_qualification_ids AS $id)
			{
				$id_parts = explode('_', $id);
                // First check if the qualification is from the previous record
                $qual_id = $id_parts[0];
                $internaltitle = $id_parts[1];
                if($previous_tr_id)
                {
                    $auto_id = DAO::getSingleValue($link,"select auto_id from student_qualifications where tr_id = '$previous_tr_id' and replace(id,'/','') = '$qual_id' and internaltitle = '$internaltitle'");
                    if($auto_id)
                    {
                        $tr_id = $tr->id;
                        DAO::execute($link,"update student_qualifications set tr_id = '$tr_id' where auto_id = '$auto_id'");


                        // Now remove the learning aim from the previouos ILR
                        $objILR = DAO::getObject($link, "SELECT ilr.* FROM ilr WHERE tr_id = '$previous_tr_id' order by contract_id desc , submission desc  limit 0,1");
                        $ilr = XML::loadSimpleXML($objILR->ilr);
                        $submission = $objILR->submission;
                        $contract_id = $objILR->contract_id;
                        foreach($ilr->LearningDelivery AS $LearningDelivery)
                        {
                            if($LearningDelivery->LearnAimRef=='$qual_id')
                            {
                                unset($LearningDelivery);
                            }
                        }
                        $dom = new DOMDocument;
                        $dom->preserveWhiteSpace = FALSE;
                        @$dom->loadXML($ilr->saveXML());
                        $dom->formatOutput = TRUE;
                        $modified_ilr = $dom->saveXml();
                        $modified_ilr = str_replace('<?xml version="1.0"?>', '', $modified_ilr);
                        DAO::execute($link, "UPDATE ilr SET ilr.ilr = '{$modified_ilr}' WHERE ilr.tr_id = '$previous_tr_id' AND ilr.contract_id = '$contract_id' AND ilr.submission = '$submission'");
                        //

                    }
                    else
                    {
                        $framework_qualification = FrameworkQualification::loadFromDatabaseByQualificationFrameworkCourse($link, $id_parts[0], $framework->id, $course->id, $id_parts[1]);
                        if(is_null($framework_qualification))
                            throw new  Exception('Data Error: Framework: ' . $framework->id . ', Course: ' . $course->id . ', Qualification: ' . $id);
                        $student_qualification = new StudentQualification();
                        $student_qualification->populate($framework_qualification);
                        $student_qualification->framework_id = $framework_qualification->framework_id; // although this isn't required
                        $student_qualification->tr_id = $tr->id;
                        if(DB_NAME!="am_reed" && DB_NAME!="am_reed_demo")
                        {
                            $student_qualification->start_date = $tr->start_date;
                            $student_qualification->end_date = $tr->target_date;
                        }
                        $student_qualification->a51a = '100'; // as per Khush's query
                        $student_qualification->unitsCompleted = '0';
                        $student_qualification->unitsNotStarted = '0';
                        $student_qualification->unitsBehind = '0';
                        $student_qualification->unitsOnTrack = '0';
                        $student_qualification->unitsUnderAssessment = '0';
                        $student_qualification->aptitude = '0';
                        $student_qualification->attitude = '0';
                        $student_qualification->comments = '0';
                        $student_qualification->username = '0';
                        $student_qualification->trading_name = '0';
                        $student_qualification->save($link, $framework->id, $tr->id);
                    }
                }
                else
                {
                    $framework_qualification = FrameworkQualification::loadFromDatabaseByQualificationFrameworkCourse($link, $id_parts[0], $framework->id, $course->id, $id_parts[1]);
                    if(is_null($framework_qualification))
                        throw new  Exception('Data Error: Framework: ' . $framework->id . ', Course: ' . $course->id . ', Qualification: ' . $id);
                    $student_qualification = new StudentQualification();
                    $student_qualification->populate($framework_qualification);
                    $student_qualification->framework_id = $framework_qualification->framework_id; // although this isn't required
                    $student_qualification->tr_id = $tr->id;
                    if(DB_NAME!="am_reed" && DB_NAME!="am_reed_demo")
                    {
                        $student_qualification->start_date = $tr->start_date;
                        $student_qualification->end_date = $tr->target_date;
                    }
                    $student_qualification->a51a = '100'; // as per Khush's query
                    $student_qualification->unitsCompleted = '0';
                    $student_qualification->unitsNotStarted = '0';
                    $student_qualification->unitsBehind = '0';
                    $student_qualification->unitsOnTrack = '0';
                    $student_qualification->unitsUnderAssessment = '0';
                    $student_qualification->aptitude = '0';
                    $student_qualification->attitude = '0';
                    $student_qualification->comments = '0';
                    $student_qualification->username = '0';
                    $student_qualification->trading_name = '0';
                    $student_qualification->save($link, $framework->id, $tr->id);
                }
			}

			$ilrTemplate = '';
			if(!is_null($contract->template) && $contract->template != '')
				$ilrTemplate = XML::loadSimpleXML($contract->template);

			$sql = <<<SQL
SELECT
submission
FROM
central.lookup_submission_dates
WHERE last_submission_date >= CURDATE()
AND contract_year = '$contract->contract_year'
AND contract_type = '$contract->funding_body'
ORDER BY
last_submission_date
LIMIT 1;
SQL;

			$submission = DAO::getSingleValue($link, $sql);

			$ilr = new ILRStruct2015($submission, $contract->id, $tr->id, $tr->l03);
			$ilr->populateFromLearner($participant);

			$learnerEmpStatus1 = new LearnerEmploymentStatusStruct();
			$learnerEmpStatus1->EmpStat = 10;
			if(!is_null($participant->l37) && $participant->l37 != '')
			{
				$learnerEmpStatus1->EmpStat = $participant->l37;
				if(!is_null($participant->lou) && $participant->lou != '')
					$learnerEmpStatus1->LOU = $participant->lou;
			}
			$learnerEmpStatus1->EmpId = '999999999';
			$start_date2 = new Date($start_date);
			$start_date2->subtractDays(1);
			$learnerEmpStatus1->DateEmpStatApp = $start_date2->formatMySQL();
			$ilr->addLearnerEmploymentStatus($learnerEmpStatus1);

			$learnerEmpStatus2 = new LearnerEmploymentStatusStruct();
			$learnerEmpStatus2->EmpStat = 10;
			if(!is_null($participant->l47) && $participant->l47 != '')
				$learnerEmpStatus2->EmpStat = $participant->l47;
			$learnerEmpStatus2->EmpId = '999999999';
			$learnerEmpStatus2->DateEmpStatApp = $sd;
			$ilr->addLearnerEmploymentStatus($learnerEmpStatus2);

			if($participant->is_participant)
			{
				// add the zesf delivery
				$zesf_delivery = new LearningDeliveryStruct('ZESF0001');
				$zesf_delivery->AimSeqNumber = 1;
				$zesf_delivery->AimType = 4;
				$zesf_delivery->LearnStartDate = $tr->start_date;
				$zesf_delivery->LearnPlanEndDate = $tr->start_date;
				$zesf_delivery->LearnActEndDate = $tr->start_date;
				$zesf_delivery->FundModel = 70;
				$zesf_delivery->ProgType = 99;
				if(DB_NAME=="am_reed" || DB_NAME=="am_reed_demo")
					$zesf_delivery->PartnerUKPRN = '10021172';
				$zesf_delivery->CompStatus = 2;
				$zesf_delivery->Outcome = 1;
				if(!is_null($participant->hhs) && $participant->hhs != '')
				{
					$hhs = explode(',', $participant->hhs);
					for($i = 0; $i < count($hhs); $i++)
					{
						$zesf_delivery->HHS[] = $hhs[$i];
					}
				}
				$zesf_delivery->SOF = '105';
				$ilr->addLearningDelivery($zesf_delivery);
			}
			else
			{
				// add the ZPROG001 delivery
				$zprog_delivery = new LearningDeliveryStruct('ZPROG001');
				$zprog_delivery->AimSeqNumber = 1;
				$zprog_delivery->AimType = 1;
				$zprog_delivery->LearnStartDate = $tr->start_date;
				$zprog_delivery->LearnPlanEndDate = $tr->target_date;
				$zprog_delivery->FundModel = $this->getValueFromTemplate($ilrTemplate,"ZPROG001","FundModel");
				if($zprog_delivery->FundModel == '')
				{
					if($course->programme_type == '1' || $course->programme_type == '2')
						$zprog_delivery->FundModel = 45;
					elseif($course->programme_type == '3')
						$zprog_delivery->FundModel = 21;
					elseif($course->programme_type == '4')
						$zprog_delivery->FundModel = 22;
					elseif($course->programme_type == '5')
						$zprog_delivery->FundModel = 70;
					elseif($course->programme_type == '6')
						$zprog_delivery->FundModel = 10;
				}
				if($course->programme_type != '6')
					$zprog_delivery->ProgType = $framework->framework_type;
				if($course->programme_type != '1' && $course->programme_type != '6')
					$zprog_delivery->FworkCode = $framework->framework_code;
				$zprog_delivery->PwayCode = $this->getValueFromTemplate($ilrTemplate,"ZPROG001","PwayCode");
				$zprog_delivery->DelLocPostCode = $tr->work_postcode;
				$zprog_delivery->CompStatus = 1;
				$zprog_delivery->SOF = $this->getValueFromTemplate2($ilrTemplate,str_replace("/" , "", "ZPROG001"),"SOF");
				$zprog_delivery->FFI = $this->getValueFromTemplate2($ilrTemplate,str_replace("/" , "", "ZPROG001"),"FFI");
				$ilr->addLearningDelivery($zprog_delivery);
			}

			if(DB_NAME!="am_reed" && DB_NAME!="am_reed_demo" && $_REQUEST['add_to_ilr'] == 'true')
			{
				$counter = 1; // start counter taking care of aim sequence number of zesf0001
                $previous_aims = "";

				foreach($selected_qualification_ids AS $id)
				{
					$id_parts = explode('_', $id);

                    // Check if it is from previous ILR
                    if(isset($id_parts[2]) && $id_parts[2]=="P")
                    {
                        // then get it from previous ilr
                        $LearnAimRef = $id_parts[0];
                        $this_aim = DAO::getSingleValue($link, "SELECT CONCAT('<LearningDelivery><LearnAimRef>',SUBSTR(ilr,LOCATE('$LearnAimRef',ilr),LOCATE('</LearningDelivery>',ilr,LOCATE('$LearnAimRef',ilr))-LOCATE('$LearnAimRef',ilr)),'</LearningDelivery>') FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE tr_id = '$previous_tr_id'  ORDER BY contracts.`contract_year` DESC, ilr.`submission` DESC LIMIT 0,1;");
                        if($this_aim=="<LearningDelivery><LearnAimRef></LearningDelivery>")
                            continue;
                        $previous_aims .= $this_aim;
                        // Then remove this aim from the previous ilr
                        // First get previous ilr xml
                        $previous_ilr_xml = DAO::getSingleValue($link, "SELECT ilr FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE tr_id = '$previous_tr_id'  ORDER BY contracts.`contract_year` DESC, ilr.`submission` DESC LIMIT 0,1;");
                        $previous_ilr_submission = DAO::getSingleValue($link, "SELECT submission FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE tr_id = '$previous_tr_id'  ORDER BY contracts.`contract_year` DESC, ilr.`submission` DESC LIMIT 0,1;");
                        $previous_ilr_contract_id = DAO::getSingleValue($link, "SELECT contract_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE tr_id = '$previous_tr_id'  ORDER BY contracts.`contract_year` DESC, ilr.`submission` DESC LIMIT 0,1;");
                        $previous_xml = simplexml_load_string($previous_ilr_xml);
                        $node = $previous_xml->xpath("/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']");
                        if(!empty($node))
                           unset($node[0][0]);
                        $previous_xml_text = $previous_xml->asXML();
                        $xml = str_replace('<?xml version="1.0"?>', '', $previous_xml_text);
                        $xml = str_replace("'", "&apos;", $xml);
                        DAO::execute($link,"update ilr set ilr = '$xml' where submission = '$previous_ilr_submission' and contract_id = '$previous_ilr_contract_id' and tr_id = '$previous_tr_id'");
                    }
                    else
                    {
                        if(!isset($id_parts[0]) or trim($id_parts[0])=='')
                            continue;
                        $counter++;
                        $delivery = new LearningDeliveryStruct($id_parts[0]);
                        $delivery->AimType = 3;
                        $delivery->AimSeqNumber = $counter;
                        $delivery->LearnStartDate = $tr->start_date;
                        $delivery->LearnPlanEndDate = $tr->target_date;
                        $delivery->FworkCode = $framework->framework_code;
                        $delivery->ProgType = $framework->framework_type;
                        $delivery->CompStatus = 1;
						if(in_array($id_parts[0], $aims_to_populate_partner_ukprn))
							$delivery->PartnerUKPRN = $college != ''?DAO::getSingleValue($link, "SELECT ukprn FROM organisations WHERE id = '{$college}'"):'';
                        $ilr->addLearningDelivery($delivery);
                    }
				}
			}
			$xml = $ilr->getILRXML();

            // Add aims
            $xml = str_replace('</Learner>',$previous_aims.'</Learner>',$xml);

			$xml = str_replace('<?xml version="1.0"?>', '', $xml);

			$xml = str_replace("'", "&apos;", $xml);

			if(DB_NAME=='am_reed' || DB_NAME=='am_reed_demo')
				$is_active = 0;
			else
				$is_active = 1;
			$sql = <<<SQL
INSERT INTO
ilr
	(L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id)
VALUES
	('','$ilr->LearnRefNumber','0','$xml','$submission','$contract->contract_type','$tr->id','0','0','0','$is_active','$contract->id')
;
SQL;
			DAO::execute($link, $sql);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		// Echo a simple success flag to the client
		header("Content-Type: text/plain");
		echo '1';
	}

	private static function getValueFromTemplate($ilr,$LearningAimRef,$Field)
	{
		if($ilr!='')
		{
			foreach($ilr->LearningDelivery as $delivery)
			{
				if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
					return $delivery->$Field;
			}
		}
	}

	public static function getValueFromTemplate2($ilr,$LearningAimRef,$Field)
	{
		if($ilr!='')
		{
			foreach($ilr->LearningDelivery as $delivery)
			{
				if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
					foreach($delivery->LearningDeliveryFAM as $ldf)
						if($ldf->LearnDelFAMType==$Field)
							return $ldf->LearnDelFAMCode;

			}
		}
	}
}
?>