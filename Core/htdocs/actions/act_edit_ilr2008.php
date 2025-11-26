<?php
class edit_ilr2008 implements IAction
{
	public function execute(PDO $link)
	{
		$submission = isset($_REQUEST['submission']) ? $_REQUEST['submission'] : '';
		$contract_id = isset($_REQUEST['contract_id']) ? $_REQUEST['contract_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$L03 = isset($_REQUEST['L03']) ? $_REQUEST['L03'] : '';
		$pdf = isset($_REQUEST['pdf']) ? $_REQUEST['pdf'] : '';

		$max_submission = DAO::getSingleValue($link, "SELECT submission FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = '$tr_id' AND (CONCAT(submission,contract_year) IN (SELECT CONCAT(submission,contract_year) FROM central.lookup_submission_dates WHERE last_submission_date >= CURDATE() ORDER BY last_submission_date))");

		$how_many = DAO::getSingleValue($link, "select count(*) from ilr where tr_id = $tr_id");

		if ($how_many > 1)
			$how_many = 0;
		else
			$how_many = 1;

		$_SESSION['bc']->add($link, "do.php?_action=edit_ilr0809&submission=" . $submission . "&contract_id=" . $contract_id . "&tr_id=" . $tr_id . "&L03=" . $L03, "Add/ Edit ILR Form");

		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		];

		$sslCa = getenv('PERSPECTIVE_DB_SSL_CA');
		if ($sslCa && file_exists($sslCa)) {
			$options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
			$options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
		}

		$linklis = new PDO(
			"mysql:host=" . DB_HOST . ";dbname=lis200809;port=" . DB_PORT . ";charset=utf8mb4",
			DB_USER,
			DB_PASSWORD,
			$options
		);

		$linklad = new PDO(
			"mysql:host=" . DB_HOST . ";dbname=lad200809;port=" . DB_PORT . ";charset=utf8mb4",
			DB_USER,
			DB_PASSWORD,
			$options
		);


		if ($submission == '' || $contract_id == '' || $tr_id == '') {
			$vo = new Ilr2008();
			$vo->learnerinformation = new LearnerInformation();
			$vo->aims[0] = new Aim();
			$vo->programmeaim = new Aim();
			$vo->programmeaim->A04 = "35";
			$vo->programmeaim->A09 = "ZPROG001";
			$vo->programmeaim->A10 = "45";


			$tr_id = DAO::getSingleValue($link, "select max(tr_id) from ilr");
			$tr_id += 1;
		} else {
			$vo = Ilr2008::loadFromDatabase($link, $submission, $contract_id, $tr_id, $L03);
		}

		$con = Contract::loadFromDatabase($link, $contract_id);

		if ($submission != 'W01') {
			$previous_submission = (int)substr($submission, 1, 2);
			$previous_submission--;
			if ($previous_submission <= 9)
				$previous_submission = "W0" . $previous_submission;
			else
				$previous_submission = "W" . $previous_submission;

			$previous_vo = Ilr2008::loadFromDatabase($link, $previous_submission, $contract_id, $tr_id, $L03);
			if ($previous_vo->learnerinformation->L09 == '')
				$previous_vo = $vo;
		} else {
			$previous_vo = $vo;
		}

		if ($vo == null) {
			throw new Exception("Could not load from database");
		}

		// Drop down list arrays
		$type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_type ORDER BY id;";
		$type_dropdown = DAO::getResultset($link, $type_dropdown);

		$L25_dropdown = "SELECT CONCAT(Code,Satellite_Office), LEFT(CONCAT(Code,Satellite_Office,' ', Name),50),null from LSC order by Code;";
		$L25_dropdown = DAO::getResultset($linklis, $L25_dropdown);

		$L44_dropdown = "SELECT CONCAT(Code,Satellite_Office), LEFT(CONCAT(Code,Satellite_Office,' ', Name),50),null from LSC order by Code;";
		$L44_dropdown = DAO::getResultset($linklis, $L44_dropdown);

		$L46_dropdown = "SELECT UKPRN, LEFT(CONCAT(UKPRN,' ',Name),50),null from Providers order by UKPRN;";
		$L46_dropdown = DAO::getResultset($linklis, $L46_dropdown);

		$A56_dropdown = "SELECT value, description,null from dropdown0809 where code='L46' order by value;";
		$A56_dropdown = DAO::getResultset($link, $A56_dropdown);

		$L12_dropdown = "SELECT Ethnicity_Code, LEFT(CONCAT(Ethnicity_Code, ' ', Ethnicity_Desc), 50), null from ILR_L12_Ethnicity order by Ethnicity_Code;";
		$L12_dropdown = DAO::getResultset($linklis, $L12_dropdown);

		$L24_dropdown = "SELECT Domicile_Code, LEFT(CONCAT(Domicile_Code, ' ', Domicile_Desc),50), null from ILR_L24_Domiciles order by Domicile_Code;";
		$L24_dropdown = DAO::getResultset($linklis, $L24_dropdown);

		$L14_dropdown = "SELECT Difficulty_Disability, LEFT(CONCAT(Difficulty_Disability, ' ', Difficulty_Disability_Desc),50), null from ILR_L14_Difficulty_Disability order by Difficulty_Disability;";
		$L14_dropdown = DAO::getResultset($linklis, $L14_dropdown);

		$L15_dropdown = "SELECT Disability_Code, LEFT(CONCAT(Disability_Code, ' ', Disability_Desc), 50), null from ILR_L15_Disability order by Disability_Code;";
		$L15_dropdown = DAO::getResultset($linklis, $L15_dropdown);

		$L16_dropdown = "SELECT Difficulty_Code, LEFT(CONCAT(Difficulty_Code,' ',Difficulty_Desc),50),null from ILR_L16_Difficulty order by Difficulty_Code;";
		$L16_dropdown = DAO::getResultset($linklis, $L16_dropdown);

		$L35_dropdown = "SELECT Prior_Attainment_Level_Code, LEFT(CONCAT(Prior_Attainment_Level_Code, ' ', Prior_Attainment_Level_Desc),50), null from ILR_L35_Prior_Attainment_Level order by Prior_Attainment_Level_Code;";
		$L35_dropdown = DAO::getResultset($linklis, $L35_dropdown);

		$L36_dropdown = "SELECT Learner_Status_Code, LEFT(CONCAT(Learner_Status_Code,' ', Learner_Status_Desc),50) ,null from ILR_L36_Learner_Status order by Learner_Status_Code;";
		$L36_dropdown = DAO::getResultset($linklis, $L36_dropdown);

		$L37_dropdown = "SELECT Employment_Status_First_Code, LEFT(CONCAT(Employment_Status_First_Code,' ',Employment_Status_First_Desc),50), null from ILR_L37_Employ_Status_Firsts  order by Employment_Status_First_Code;";
		$L37_dropdown = DAO::getResultset($linklis, $L37_dropdown);

		$L47_dropdown = "SELECT Current_Emp_Status_Code, LEFT(CONCAT(Current_Emp_Status_Code, ' ', Current_Emp_Status_Desc),50),null from ILR_L47_Current_Emp_Status order by Current_Emp_Status_Code;";
		$L47_dropdown = DAO::getResultset($linklis, $L47_dropdown);

		$L28_dropdown = "SELECT Eligibility_Enhanced_Code, LEFT(CONCAT(Eligibility_Enhanced_Code,' ',Eligibility_Enhanced_Desc),50), null from ILR_L28_Eligibil_Enhance_Fnds order by Eligibility_Enhanced_Code;";
		$L28_dropdown = DAO::getResultset($linklis, $L28_dropdown);

		$L39_dropdown = "SELECT Destination_Code, LEFT(CONCAT(Destination_Code,' ', Destination_Desc),50) ,null from ILR_L39_Destinations order by Destination_Code;";
		$L39_dropdown = DAO::getResultset($linklis, $L39_dropdown);

		$A02_dropdown = "SELECT value, description,null from dropdown0809 where code='A02' order by value;";
		$A02_dropdown = DAO::getResultset($link, $A02_dropdown);

		$A10_dropdown = "SELECT value, description,null from dropdown0809 where code='A10' order by value;";
		$A10_dropdown = DAO::getResultset($link, $A10_dropdown);

		$A14_dropdown = "SELECT non_payment_reason_code, LEFT(CONCAT(non_payment_reason_code, ' ', non_payment_reason_desc),50) ,null from ILR_A14_Non_Payment_Reasons order by non_payment_reason_code;";
		$A14_dropdown = DAO::getResultset($linklis, $A14_dropdown);

		$A15_dropdown = "SELECT Programme_Type_Code, LEFT(CONCAT(Programme_Type_Code, ' ' , Programme_Type_Desc),50), null from ILR_A15_Programme_Types order by Programme_Type_Code;";
		$A15_dropdown = DAO::getResultset($linklis, $A15_dropdown);

		$A16_dropdown = "SELECT Programme_Route_Code, LEFT(CONCAT(Programme_Route_Code, ' ', Programme_Route_Desc), 50), null from ILR_A16_Programme_Routes order by Programme_Route_Code;";
		$A16_dropdown = DAO::getResultset($linklis, $A16_dropdown);

		$A18_dropdown = "SELECT Delivery_Method_Code, LEFT(CONCAT(Delivery_Method_Code, ' ', Delivery_Method_Desc), 50) ,null from ILR_A18_Delivery_Methods order by Delivery_Method_Code;";
		$A18_dropdown = DAO::getResultset($linklis, $A18_dropdown);

		$A24_dropdown = "SELECT SOC2000_Code_Code , LEFT(CONCAT(SOC2000_Code_Code, ' ', SOC2000_Code_Desc),50) ,null from SOC2000_CODES order by SOC2000_Code_Code;";
		$A24_dropdown = DAO::getResultset($linklad, $A24_dropdown);

		$A26_dropdown = "SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),50) ,null from FRAMEWORKS order by Framework_Code;";
		$A26_dropdown = DAO::getResultset($linklad, $A26_dropdown);

		$A34_dropdown = "SELECT Completion_Status_Code, LEFT(CONCAT(Completion_Status_Code, ' ', Completion_Status_Desc),50), null from ILR_A34_Completion_Status order by Completion_Status_Code;";
		$A34_dropdown = DAO::getResultset($linklis, $A34_dropdown);

		$A35_dropdown = "SELECT Learning_Outcome_Code, LEFT(CONCAT(Learning_Outcome_Code, ' ', Learning_Outcome_Desc),50) ,null from ILR_A35_Learning_Outcomes order by Learning_Outcome_Code;";
		$A35_dropdown = DAO::getResultset($linklis, $A35_dropdown);

		$A36_dropdown = "SELECT Learning_Outcome_Grade_Code, LEFT(CONCAT(Learning_Outcome_Grade_Code, ' ', Learning_Outcome_Grade_Desc),50) ,null from ILR_A36_Learn_Outcome_Grades order by Learning_Outcome_Grade_Code;";
		$A36_dropdown = DAO::getResultset($linklis, $A36_dropdown);

		$A46_dropdown = "SELECT National_Learner_Aim_Code, LEFT(CONCAT(National_Learner_Aim_Code, ' ', National_Learner_Aim_Desc),50) ,null from ILR_A46_Nat_Learner_Aims  order by National_Learner_Aim_Code;";
		$A46_dropdown = DAO::getResultset($linklis, $A46_dropdown);

		$A49_dropdown = "SELECT Project_Code, Project_Code ,null from ILR_A49_Project_Codes;";
		$A49_dropdown = DAO::getResultset($linklis, $A49_dropdown);

		$A50_dropdown = "SELECT Reason_Learning_Ended_Code, LEFT(CONCAT(Reason_Learning_Ended_Code, ' ', Reason_Learning_Ended_Desc),50), null from ILR_A50_Reason_Learning_Ended order by Reason_Learning_Ended_Code;";
		$A50_dropdown = DAO::getResultset($linklis, $A50_dropdown);

		$A53_dropdown = "SELECT Additional_Learning_Need_Code, LEFT(CONCAT(Additional_Learning_Need_Code, ' ', Additional_Learning_Need_Desc),50), null from ILR_A53_Add_Learning_Needs order by Additional_Learning_Need_Code;";
		$A53_dropdown = DAO::getResultset($linklis, $A53_dropdown);

		$A54_dropdown = "SELECT Broker_Contract_Number, LEFT(CONCAT(Broker_Contract_Number, ' ', Broker_Name),50), null from TtG_Broker_Contracts order by Broker_Contract_Number;";
		$A54_dropdown = DAO::getResultset($linklis, $A54_dropdown);

		$A06_dropdown = "SELECT value, description,null from dropdown0809 where code='A06' order by value;";
		$A06_dropdown = DAO::getResultset($link, $A06_dropdown);

		$L01_dropdown = "SELECT CAPN, LEFT(concat(CAPN, ' ', Name),35), null from Providers order by Name;";
		$L01_dropdown = DAO::getResultset($linklis, $L01_dropdown);

		$A01_dropdown = "SELECT CAPN, LEFT(concat(CAPN, ' ', Name),35), null from Providers order by Name;";
		$A01_dropdown = DAO::getResultset($linklis, $A01_dropdown);

		$E01_dropdown = "SELECT value, description,null from dropdown0809 where code='L01' order by value;";
		$E01_dropdown = DAO::getResultset($link, $E01_dropdown);

		$L40_dropdown = "SELECT National_Learner_Event_Code, LEFT(CONCAT(National_Learner_Event_Code,' ',National_Learner_Event_Desc),50), null from ILR_L40_Nat_Learner_Events order by National_Learner_Event_Code;";
		$L40_dropdown = DAO::getResultset($linklis, $L40_dropdown);

		$E11_dropdown = "SELECT value, description,null from dropdown0809 where code='E11' order by value;";
		$E11_dropdown = DAO::getResultset($link, $E11_dropdown);

		$E12_dropdown = "SELECT Employment_Status_ESF_Code, LEFT(CONCAT(Employment_Status_ESF_Code,' ', Employment_Status_ESF_Desc),50), null from ILR_E12_Employ_Status_ESF order by Employment_Status_ESF_Code;";
		$E12_dropdown = DAO::getResultset($linklis, $E12_dropdown);

		$E13_dropdown = "SELECT Learner_Employment_Status_Code, LEFT(CONCAT(Learner_Employment_Status_Code, ' ', Learner_Employment_Status_Desc), 50), null from ILR_E13_Learner_Employ_Status  order by Learner_Employment_Status_Code;";
		$E13_dropdown = DAO::getResultset($linklis, $E13_dropdown);

		$E14_dropdown = "SELECT Length_Unemployment_ESF_Code, LEFT(CONCAT(Length_Unemployment_ESF_Code,' ',Length_Unemployment_ESF_Desc),50),null from ILR_E14_Length_Unemploy_ESF order by Length_Unemployment_ESF_Code;";
		$E14_dropdown = DAO::getResultset($linklis, $E14_dropdown);

		$E15_dropdown = "SELECT Employer_Type_Code, LEFT(CONCAT(Employer_Type_Code,' ',Employer_Type_Desc),50) ,null from ILR_E15_Employer_Types order by Employer_Type_Code;";
		$E15_dropdown = DAO::getResultset($linklis, $E15_dropdown);

		$E16_dropdown = "SELECT Gender_Stereotype_Code, LEFT(CONCAT(Gender_Stereotype_Code,' ',Gender_Stereotype_Desc),50), null from ILR_E16_Gender_Stereotypes order by Gender_Stereotype_Code;";
		$E16_dropdown = DAO::getResultset($linklis, $E16_dropdown);

		$E18_dropdown = "SELECT Delivery_Mode_Code, LEFT(CONCAT(Delivery_Mode_Code,' ',Delivery_Mode_Desc),50) ,null from ILR_E18_ESF_Delivery_Modes order by Delivery_Mode_Code;";
		$E18_dropdown = DAO::getResultset($linklis, $E18_dropdown);

		$E19_dropdown = "SELECT Support_Measures_Code, LEFT(CONCAT(Support_Measures_Code,' ',Support_Measures_Desc),50), null from ILR_E19_ESF_Supp_Measures order by Support_Measures_Code;";
		$E19_dropdown = DAO::getResultset($linklis, $E19_dropdown);

		$E20_dropdown = "SELECT Learner_Background_Code, LEFT(CONCAT(Learner_Background_Code,' ',Learner_Background_Desc),50), null from ILR_E20_Learner_Backgrounds order by Learner_Background_Code;";
		$E20_dropdown = DAO::getResultset($linklis, $E20_dropdown);

		$E21_dropdown = "SELECT Disability_Supp_Measure_Code, LEFT(CONCAT(Disability_Supp_Measure_Code,' ',Disability_Supp_Measure_Code),50) ,null from ILR_E21_Disability_Supp_Meas order by Disability_Supp_Measure_Code;";
		$E21_dropdown = DAO::getResultset($linklis, $E21_dropdown);

		$level_checkboxes = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_level ORDER BY id;";
		$level_checkboxes = DAO::getResultset($link, $level_checkboxes);

		$linklis = NULL;
		$linklad = NULL;

		$contracts = DAO::getResultset($link, "select id, title, null from contracts where contract_year = 2009");

		require_once('tpl_edit_ilr2008.php');
	}


	private function checkPermissions(PDO $link, Course $c_vo)
	{
		if ($_SESSION['role'] == 'admin') {
			return true;
		} elseif ($_SESSION['org']->org_type_id == ORG_PROVIDER) {
			$acl = CourseACL::loadFromDatabase($link, $c_vo->id);
			$is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);

			return $is_employee && $is_local_admin;
		} elseif ($_SESSION['org']->org_type_id == ORG_SCHOOL) {
			return false;
		} else {
			return false;
		}
	}
}
