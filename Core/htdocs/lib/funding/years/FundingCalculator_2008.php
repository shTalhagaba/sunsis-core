<?php

require_once('FundingCalculator.php');
class FundingCalculator_2008 extends FundingCalculator
{
	const LAD_DB = 'lad200809';
	const T2GSLN = '2775';
	const T2G_UPLIFT = 1.03;
	const ASLN = '2860';	
	
	function __construct($db, $contractInfo)
	{
		parent::__construct($db, $contractInfo);
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
		
		// 5) Build a cache of all tr_id's
		$trids = $this->getTrIDs();
		
		
		$sql = "
			SELECT 
				tr.L03
				, tr.id as tr_id
				, sq.title
				, c.contract_year
				, c.title as contract_name
				, c.start_date AS contract_start_date
				, c.end_date AS contract_end_date
				, c.proportion
				, sq.start_date AS learner_start_date
				#, tr.target_date AS learner_target_end_date
				, sq.end_date AS learner_end_date
				, sq.achievement_date AS learner_achievement_date
				, (CASE WHEN sq.achievement_date IS NOT NULL THEN 1 ELSE 0 END) AS achieved
				, (DATE_FORMAT(sq.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(sq.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) AS age 
				, tr.dob
				, CONCAT(tr.surname,', ', tr.firstnames) as name
				, tr.surname as surname
				, courses.id as course_id
				, courses.title as course_name
				, courses.programme_type
				, sq.id as qualid
				, tr.l28a
				, org.legal_name as provider_name
				, COALESCE(aav.FEE_ELEMENT_PERCENTAGE,1) AS FEE_ELEMENT_PERCENTAGE
				, COALESCE(ldw.rate,1) as disadvantage_weighting
				, COALESCE(lac.rate,1) as area_cost_uplift
				# , aav.SLN_APPRENTICESHIP_1
				, COALESCE(fq.main_aim,1) as main_aim
				, tr.home_postcode
				, sq.a14
				########################## SLN VALUE
				, (CASE
						WHEN courses.programme_type = 1 THEN " . (self::T2GSLN * self::T2G_UPLIFT) . "
						ELSE (CASE
								WHEN (DATE_FORMAT(tr.start_date, '%Y') - DATE_FORMAT(tr.dob, '%Y') - (DATE_FORMAT(tr.start_date, '00-%m-%d') < DATE_FORMAT(tr.dob, '00-%m-%d'))) < 19 THEN 2860
								ELSE 2775 * COALESCE(((CASE WHEN tr.l28a IN (14,15) THEN 100 ELSE aav.FEE_ELEMENT_PERCENTAGE END)/100),1)
							END)
					END) as sln				
				########################## RATE PER/SLN VALUE
				, (CASE
					WHEN sq.a18 = 22 THEN COALESCE(aav.SLN_EMP_RESP_1,0)
					WHEN sq.a18 = 23 THEN COALESCE(aav.SLN_EMP_RESP_2,0)
					ELSE 1
					END) as ratepersln
				########################### PROGRAMME WEIGHTING
				, (CASE
					WHEN courses.programme_type = 1 THEN pw1.LSC_EMP_RESP_WGT_FACTOR_DESC
					ELSE pw2.LSC_EMP_RESP_WGT_FACTOR_DESC
					END) as programme_weighting
				########################## PROVIDER FACTOR
				, (COALESCE(ldw.rate,1) * COALESCE(lac.rate,1) * c.short_programme_modifier * c.success_factor) as provider_factor
				########################## FULLY FUNDED
				, COALESCE(lf.rate,1) as fully_funded
				, (COALESCE((CASE WHEN sq.a51a = 0 THEN 100 ELSE sq.a51a END),100)/100) as funding_remaining_weight
				, sq.auto_id
				, sq.title
				$hook_fields
			FROM
				student_qualifications AS sq
			LEFT JOIN
				tr ON (tr.id = sq.tr_id)
			LEFT JOIN
				locations as loc ON (tr.provider_location_id = loc.id)				
			LEFT JOIN
				contracts AS c ON (tr.contract_id = c.id)
			LEFT JOIN
				courses_tr ON (courses_tr.tr_id = tr.id)
			LEFT JOIN
				courses ON (courses.id = courses_tr.course_id)
			LEFT JOIN
				framework_qualifications as fq ON (fq.framework_id = sq.framework_id AND fq.id = sq.id)
			LEFT JOIN
				organisations as org ON (tr.provider_id = org.id)
			LEFT JOIN
				" . self::LAD_DB . ".lsc_employer_annual_values as aav ON (aav.LEARNING_AIM_REF = REPLACE(sq.id, '/', ''))
			LEFT JOIN
				" . self::LAD_DB . ".lsc_emp_resp_wgt_factors as pw1 ON pw1.LSC_EMP_RESP_WGT_FACTOR_CODE = aav.LSC_EMP_RESP_WGT_FACTOR_CODE
			LEFT JOIN
				" . self::LAD_DB . ".lsc_emp_resp_wgt_factors as pw2 ON pw2.LSC_EMP_RESP_WGT_FACTOR_CODE = aav.LSC_APPRNTSHP_WGT_FACTOR_CODE			
			LEFT JOIN
				central.lookup_fully_funded AS lf ON (lf.contract_year = c.contract_year AND lf.a14 = sq.a14)
			LEFT JOIN
				central.lookup_area_cost AS lac ON (lac.contract_year = c.contract_year AND lac.postcode = loc.postcode)	
			LEFT JOIN
				lookup_disadvantage_weighting AS ldw ON (ldw.contract_year = c.contract_year AND ldw.postcode = tr.home_postcode)		
			$hook_joins
			WHERE 
				sq.tr_id IN (" . $trids . ")
				AND tr.contract_id = '" . intval($this->contractInfo->id) . "'
				# SANITY FILTERS - these might need to be removed
				# AND tr.start_date < tr.closure_date
				$hook_where
			ORDER BY
				tr.surname, tr.firstnames
		;";
				
		//pre($sql);
		$st = $this->db->query($sql);
		$funding = array();
		$c = 0;
		while($row = $st->fetch())
		{
			// sanity checks
			if(1 != 1 AND empty($row['target_periods']))
			{
				//throw new UserErrorException('The lookup_submission_periods table needs to be populated with the new start_submission_date field. Alternative there may be some invalid dates in the qualifications for learner: ' . $row['surname'] . ',' . $row['forename']);
			}
			else if($row['programme_type'] == '')
			{
				//throw new UserErrorException('A flag to determine whether a course is a T2G or apprenticeship has not been set in the courses table for course_id = ' . $row['course_id']);
			}
			else
			{
				$funding["$c"] = $row;
				$funding["$c"]['target_periods'] = $tperiods[$row['auto_id']];
				if(substr($tperiods[$row['auto_id']], 0, 4) == 2007)
				{
					unset($funding["$c"]);
				}
				else
				{
					$funding["$c"]['contract_periods'] = $cperiods[$row['auto_id']];
					$funding["$c"]['onprogram_periods'] = $opperiods[$row['auto_id']];
					$funding["$c"]['achiever_periods'] = $aperiods[$row['auto_id']];
					$funding["$c"]['total_funding'] = $this->calculate_funding($row);
				}
				$c++;
			}
		}
		
		if(sizeof($funding) == 0)
		{
			throw new Exception('No learners with valid data were found. Therefore funding cannot be calculated');
		}

		return $funding;		
	}
	
	private function calculate_funding($data)
	{
		return $data['sln'] * $data['ratepersln'] * FundingCore::getWeighting($data['programme_weighting']) * $data['provider_factor'] * $data['fully_funded'] * $data['funding_remaining_weight'] * $data['proportion'] / 100;
	}
}

?>