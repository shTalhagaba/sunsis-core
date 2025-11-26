<?php

require_once('FundingCalculator.php');
class FundingCalculator_2010 extends FundingCalculator
{
	
	const LAD_DB = 'lad201011';
	const T2GSLN = '2732';
	const T2G_UPLIFT = 1;
	const ASLN = '2920';	
	
	function __construct($db, $contracts)
	{
		parent::__construct($db, $contracts);
	}
	
	public function getData($hook_fields = '', $hook_joins = '', $hook_where = '')
	{
		// 1) First of all build a cache of learner target periods!
		$tperiods = $this->getTargetPeriods();
		
		// 2) Build a cache of learner on program periods!
		$opperiods = $this->getOnProgramPeriods();
		
		// 3) Build a cache of learner achiever periods!
		$aperiods = $this->getAchieverPeriods();
		
		// 4) Build an array of active contract periods
		$cperiods = $this->getContractPeriods();

		// 5) Build an array of marked periods
		$mperiods = $this->getMarkedPeriods();
		
		// 6) Build an array of unfunded periods
		$uperiods = $this->getUnfundedPeriods();
		
		// 7) Build a cache of all tr_id's
		$trids = $this->getTrIDs();
			
		$sql = "
			SELECT 
				tr.L03
				, tr.id as tr_id
				, sq.title
				, sq.a51a
				, c.contract_year
				, c.title as contract_name
				, c.id as contract_id
				, c.start_date AS contract_start_date
				, c.end_date AS contract_end_date
				, c.proportion
				, sq.start_date AS learner_start_date
				, sq.end_date AS learner_target_end_date
				, sq.actual_end_date AS learner_end_date
				, tr.marked_date as entry_end_date
				, sq.achievement_date AS learner_achievement_date
				, (CASE WHEN sq.actual_end_date IS NULL THEN 1 ELSE 0 END) AS continuing
				, (CASE WHEN sq.achievement_date IS NOT NULL THEN 1 ELSE 0 END) AS achieved
				, (DATE_FORMAT(sq.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(sq.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) AS age 
				, tr.dob
				, CONCAT(tr.surname,', ', tr.firstnames) as name
				, tr.surname as surname
				, courses.id as course_id
				, courses.title as course_name
				#, CONCAT(assessors.surname,', ', assessors.firstnames) as assessor
				, COALESCE(courses.programme_type,2) as programme_type
				, sq.id as qualid
				, tr.l28a
				, org.legal_name as provider_name
				, employers.legal_name as employer_name
				#, COALESCE(aav.FEE_ELEMENT_PERCENTAGE,1) AS FEE_ELEMENT_PERCENTAGE
				, COALESCE(ldw.Disadvantage_Uplift_Factor,1) as disadvantage_weighting
				, COALESCE(lac.Area_Cost_Factor,1) as area_cost_uplift
				# , aav.SLN_APPRENTICESHIP_1

				, COALESCE(fq.main_aim,0) as main_aim
				
				#, IF(sq.qualification_type = 'KS' or sq.qualification_type = 'BS' or sq.qualification_type = 'FS', .175, .5 ) as fee_proportion
				
				, tr.provider_location_id
				, tr.employer_location_id
				, loc.postcode as provider_postcode
				, sq.a14
				########################## SLN VALUE
				,(CASE
						WHEN COALESCE(courses.programme_type,2) = 1 
						THEN (CASE
								WHEN (DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) < 19 THEN 2732
								WHEN (DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) < 25 THEN (2732 * COALESCE(employer_factor.Discount,1) * IF(sq.a14='22',1,IF(sq.a14='28',1,.5)))
								ELSE (2732 * COALESCE(employer_factor.Discount,1) * IF(sq.a14='22',1,IF(sq.a14='28',1,.5))) END)
						ELSE (CASE
								WHEN (DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) < 19 THEN 2920 
								WHEN (DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) < 25 THEN 2732 * COALESCE(employer_factor.Discount,1) * COALESCE(((CASE WHEN sq.a14=1 THEN 100 ELSE (100-IF(sq.qualification_type = 'KS' or sq.qualification_type = 'BS' or sq.qualification_type = 'FS', 17.5, 50 )) END)/100),1)
								ELSE 2186 * COALESCE(((CASE WHEN sq.a14=1 THEN 100 ELSE (100-IF(sq.qualification_type = 'KS' or sq.qualification_type = 'BS' or sq.qualification_type = 'FS', 17.5, 50 )) END)/100),1)
							END)
					END) as sln				
				########################## RATE PER/SLN VALUE				  
				,COALESCE(fd1.SLN_VALUE_1,1) as ratepersln
								  
				########################### PROGRAMME WEIGHTING
				,if(pw.LSC_EMP_RESP_WGT_FACTOR_DESC='Employer Responsive Programme Weighting L - 1.2 (reducing to 1.15 in 2010/11)','1.15',LSC_EMP_RESP_WGT_FACTOR_DESC) as programme_weighting	
					
				########################## PROVIDER FACTOR
				, (IF(COALESCE(courses.programme_type,2) =1, 1,COALESCE(ldw.Disadvantage_Uplift_Factor,1)) * COALESCE(lac.Area_Cost_Factor,1) * c.short_programme_modifier * c.success_factor) as provider_factor
				########################## FULLY FUNDED
				, COALESCE(lf.rate,1) as fully_funded
				, (COALESCE((CASE WHEN sq.a51a IS NULL THEN 0 WHEN sq.a51a = 0 THEN 0 ELSE sq.a51a END),100)/100) as funding_remaining_weight
				, sq.auto_id
				, sq.title
				,(CASE
						WHEN DATEDIFF(sq.end_date,sq.start_date)/7 <= 24 AND DATEDIFF(sq.actual_end_date,sq.start_date)/7 < 2 THEN 0
						WHEN DATEDIFF(sq.end_date,sq.start_date)/7 <= 24 AND DATEDIFF(sq.actual_end_date,sq.start_date)/7 >= 2 THEN 1
						WHEN DATEDIFF(sq.end_date,sq.start_date)/7 > 24 AND DATEDIFF(sq.actual_end_date,sq.start_date)/7 < 6 THEN 0
						WHEN DATEDIFF(sq.end_date,sq.start_date)/7 > 24 AND DATEDIFF(sq.actual_end_date,sq.start_date)/7 >= 6 THEN 1
				END) AS qualify
				
				$hook_fields
			FROM
				student_qualifications AS sq
			LEFT JOIN
				tr ON (tr.id = sq.tr_id)
			LEFT JOIN
				locations as loc ON (tr.employer_location_id = loc.id)
			LEFT JOIN
				contracts AS c ON (tr.contract_id = c.id)
			LEFT JOIN
				courses_tr ON (courses_tr.tr_id = tr.id)
			LEFT JOIN 
				group_members ON group_members.tr_id = tr.id
			LEFT JOIN
				courses ON (courses.id = courses_tr.course_id)
			LEFT JOIN 
				groups on groups.courses_id = courses.id and group_members.groups_id = groups.id 
			LEFT JOIN 
				users as assessors on groups.assessor = assessors.username
			LEFT JOIN
				framework_qualifications as fq ON (fq.framework_id = sq.framework_id AND fq.id = sq.id)
			LEFT JOIN
				organisations as org ON (tr.provider_id = org.id)
			LEFT JOIN 
				organisations as employers on tr.employer_id = employers.id
			LEFT JOIN
				aim on aim.processid = sq.tr_id and aim.a09 = REPLACE(sq.id, '/', '')	
			LEFT JOIN 
				" . self::LAD_DB . ".lsc_employer_annual_values as aav ON (aav.LEARNING_AIM_REF = REPLACE(sq.id, '/', ''))
			LEFT JOIN 
				central.employer_factor on central.employer_factor.URN = employers.edrs
			LEFT JOIN
				" . self::LAD_DB . ".funding_details as fd1 on ((fd1.FUND_MODEL_COLLECTION_CODE='ER_TTG' and courses.programme_type=1) || (fd1.FUND_MODEL_COLLECTION_CODE='ER_APP' and courses.programme_type=2)) and fd1.LEARNING_AIM_REF = replace(sq.id,'/','')
			LEFT JOIN 
				" . self::LAD_DB . ".lsc_emp_resp_wgt_factors as pw on fd1.FUNDING_PROG_WGT_CODE = pw.LSC_EMP_RESP_WGT_FACTOR_CODE
			LEFT JOIN
				central.lookup_fully_funded AS lf ON (lf.contract_year = c.contract_year AND lf.a14 = sq.a14)
			LEFT JOIN
				central.2010postcode_area_cost_factors AS lac ON (lac.postcode = loc.postcode)	
			LEFT JOIN
				central.2010postcode_disadvantage_factors AS ldw ON (ldw.postcode = tr.home_postcode)		
			$hook_joins
			WHERE 
				#(sq.a18 != '' or sq.a18 != '0') and 
				sq.aptitude != 1 and 
				sq.id not in ('ZSPE0001','ZVOC0007', 'ERR', 'ZSPE0007', 'ZSPE0004', 'ZSPE0003') and 
				sq.tr_id IN (" . $trids . ") and
				tr.contract_id in (" . $this->contracts . ") 
				# SANITY FILTERS - these might need to be removed
				# AND tr.start_date < tr.closure_date
				$hook_where
			ORDER BY
				tr.surname, tr.firstnames
		;";

				
		// * IF(lf.rate=0,(100-aav.FEE_ELEMENT_PERCENTAGE)/100,1)		
		$st = $this->db->query($sql);
		$funding = array();
		$c = 0;
		while($row = $st->fetch())
		{
			// sanity checks
			if($row['programme_type'] == '')
			{
				//throw new UserErrorException('A flag to determine whether a course is a T2G or apprenticeship has not been set in the courses table for course_id = ' . $row['course_id']);
			}
			else
			{
				$funding["$c"] = $row;
			//	if(intval(substr($tperiods[$row['auto_id']], 0, 4)) <= 2007)
			 //   {
			 //    unset($funding["$c"]);
			//    }				
		///		else
				{
					$funding["$c"]['target_periods'] = $tperiods[$row['auto_id']];
					$funding["$c"]['contract_periods'] = $cperiods[$row['auto_id']];
					$funding["$c"]['onprogram_periods'] = $opperiods[$row['auto_id']];
					$funding["$c"]['achiever_periods'] = $aperiods[$row['auto_id']];
					$funding["$c"]['marked_periods'] = $mperiods[$row['auto_id']];
					$funding["$c"]['unfunded_periods'] = $uperiods[$row['auto_id']];
					$funding["$c"]['total_funding'] = $this->calculate_funding($row);
				}
				$c++;
			}
			
		//	if($row['L03']=='110287' && $row['qualid']=='100/6084/4')
		//	pre($row);
			
		}
		
		
		return $funding;		
	}
	
	private function calculate_funding($data)
	{
		//if($data['L03']=='1091NO203670')
		//	pre($data);
		//return $data['sln'] * $data['ratepersln'] * FundingCore::getWeighting($data['programme_weighting']) * $data['provider_factor'] * $data['fully_funded'] * $data['funding_remaining_weight'];
		return $data['sln'] * $data['ratepersln'] * FundingCore::getWeighting($data['programme_weighting']) * $data['provider_factor'] * $data['funding_remaining_weight'] * $data['proportion'] / 100;
	}
}

?>