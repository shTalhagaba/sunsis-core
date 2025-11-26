<?php
class refresh_charts_master_table implements IAction
{
	public function execute(PDO $link)
	{
		ini_set('max_execution_time', 0);
		$sql = <<<SQL
SELECT DISTINCT
  tr.l03,
  tr.id AS tr_id,
  tr.firstnames,
  tr.surname,
  '' AS ethnicity_code,
  contracts.funding_provision,
  CASE
  WHEN TIMESTAMPDIFF(YEAR, tr.`dob`, tr.`start_date`) BETWEEN 16 AND 18 THEN '16-18'
  WHEN TIMESTAMPDIFF(YEAR, tr.`dob`, tr.`start_date`) BETWEEN 19 AND 23 THEN '19-23'
  WHEN TIMESTAMPDIFF(YEAR, tr.`dob`, tr.`start_date`) > 23 THEN '24+'
  END AS age_band,
  tr.assessor AS assessor_id,
  (SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  tr.`start_date`,
  tr.`target_date` AS planned_end_date,
  tr.`closure_date` AS actual_end_date,
  '' AS completion_status,
  contracts.contract_year,
  '' AS lldd,
  '' AS primary_lldd,
  tr.employer_id,
  tr.provider_id,
  '' AS gender,
  tr.contract_id,
  '0' AS restart,
  organisations.region AS employer_region,
  #frameworks.framework_type
  '' AS framework_type,
  '' AS SSA1,
  (SELECT 
    NotionalEndLevel 
  FROM lars201718.Core_LARS_Standard 
    INNER JOIN frameworks ON lars201718.Core_LARS_Standard.StandardCode = frameworks.StandardCode
    INNER JOIN student_frameworks ON student_frameworks.id = frameworks.id
  WHERE
    frameworks.framework_type = 25 AND student_frameworks.tr_id = tr.id  
    ) AS learner_level
FROM
  tr
  LEFT JOIN contracts
    ON tr.`contract_id` = contracts.`id`
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
#  LEFT JOIN student_frameworks
#    ON student_frameworks.tr_id = tr.id
#  LEFT JOIN frameworks
#  	ON student_frameworks.id = frameworks.id

;

SQL;
		$st = $link->query($sql);
		if($st)
		{
			$current_contract_and_submission = DAO::getObject($link, "SELECT contract_year, submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date ORDER BY submission;");
			while($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$sub = 'W13';
				if((int)$row['contract_year']  == (int)$current_contract_and_submission->contract_year)
					$sub = $current_contract_and_submission->submission;
				$tr_id = $row['tr_id'];
				$contract_id = $row['contract_id'];
				/*
	 * age range
	 * restart
	 *
	 * continuing CompStat = 1, Outcome NULL, WithdrawReason NULL
	 * transfer CompStat = 3, Outcome 3, WithdrawReason 40
	 * BIL   CompStat = 6, Outcome 3, WithdrawReason NULL
	 * achieve  CompStat = 2, Outcome 1, WithdrawReason NULL
	 * withdrawn CompStat = 3, Outcome 3, WithdrawReason any value
	 */

				$ilr_sql = <<<SQL
SELECT
	extractvalue(ilr, '/Learner/LLDDandHealthProblem[PrimaryLLDD="1"]/LLDDCat') AS primary_lldd, extractvalue(ilr, '/Learner/LLDDHealthProb') AS lldd,
	extractvalue(ilr, '/Learner/Ethnicity') AS ethnicity_code,
	extractvalue(ilr, '/Learner/Sex') AS gender,
	extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"]/LearningDeliveryFAM[LearnDelFAMType="EEF"]/LearnDelFAMCode') AS EEF,
	extractvalue(ilr, '/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType="RES"][1]/LearnDelFAMCode') AS RES,
	extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"][1]/ProgType') AS ProgType,
	extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"][1]/FworkCode') AS FworkCode,
	extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"][1]/PwayCode') AS PwayCode,
	IF(
		LOCATE('ZPROG001', ilr.ilr) > 0,
		extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"][1]/CompStatus'),
		extractvalue(ilr, '/Learner/LearningDelivery[1]/CompStatus')
	) AS CompStatus,
	IF(
		LOCATE('ZPROG001', ilr.ilr) > 0,
		extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"][1]/Outcome'),
		extractvalue(ilr, '/Learner/LearningDelivery[1]/Outcome')
	) AS Outcome,
	IF(
		LOCATE('ZPROG001', ilr.ilr) > 0,
		extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"][1]/WithdrawReason'),
		extractvalue(ilr, '/Learner/LearningDelivery[1]/WithdrawReason')
	) AS WithdrawReason,
	extractvalue(ilr, '/Learner/LearningDelivery[LearnAimRef="ZPROG001"][1]/StdCode') AS StdCode
FROM
 	ilr WHERE ilr.submission = '$sub' AND ilr.tr_id = '$tr_id' AND ilr.contract_id = '$contract_id'
SQL;
				$ilr_info = DAO::getObject($link, $ilr_sql);
				$row['lldd'] = isset($ilr_info->lldd) ? $ilr_info->lldd : '';
				$row['primary_lldd'] = isset($ilr_info->primary_lldd) ? $ilr_info->primary_lldd : '';
				$row['gender'] = isset($ilr_info->gender) ? $ilr_info->gender : '';
				$row['ethnicity_code'] = isset($ilr_info->ethnicity_code) ? $ilr_info->ethnicity_code : '';
				$row['framework_type'] = isset($ilr_info->ProgType) ? $ilr_info->ProgType : '';
				switch ($row['framework_type'])
                {
                    case '2':
                        $row['learner_level'] = 'Level 3';
                        break;
                    case '3':
                        $row['learner_level'] = 'Level 2';
                        break;
                    case '20':
                        $row['learner_level'] = 'Level 4';
                        break;
                    case '21':
                        $row['learner_level'] = 'Level 5';
                        break;
                    case '22':
                        $row['learner_level'] = 'Level 6';
                        break;
                    case '23':
                        $row['learner_level'] = 'Level 7';
                        break;
                    case '24':
                        $row['learner_level'] = 'Traineeship';
                        break;
                    case '25':
                        $row['learner_level'] = 'Level ' . $row['learner_level'];
                        break;
                    default:
                        $row['learner_level'] = '';
                        break;
                }

				if((isset($ilr_info->EEF) && trim($ilr_info->EEF) != ''))
				{
					$row['age_band'] = $ilr_info->EEF == '2' ? '16-18' : '19-23';
				}
				if(isset($ilr_info->RES) && trim($ilr_info->RES) != '')
				{
					$row['restart'] = strpos($ilr_info->RES, '1') === false ? 0 : 1;
				}
				$CompStatus = isset($ilr_info->CompStatus) ? trim($ilr_info->CompStatus) : '';
				$Outcome = isset($ilr_info->Outcome) ? trim($ilr_info->Outcome) : '';
				$WithdrawReason = isset($ilr_info->WithdrawReason) ? trim($ilr_info->WithdrawReason) : '';
				if($CompStatus == '1' && $Outcome == '' && $WithdrawReason == '')
					$row['completion_status'] = '1';
				elseif($CompStatus == '3' && $Outcome == '3' && ($WithdrawReason == '40' || $WithdrawReason == '41'))
					$row['completion_status'] = '4';
				elseif($CompStatus == '6' && $Outcome == '3' && $WithdrawReason == '')
					$row['completion_status'] = '6';
				elseif($CompStatus == '2' && $Outcome == '1' && $WithdrawReason == '')
					$row['completion_status'] = '2';
				elseif($CompStatus == '3' && $Outcome == '3' && $WithdrawReason != '40' && $WithdrawReason != '41')
					$row['completion_status'] = '3';
				else
					$row['completion_status'] = '3';

				$_prog_type = isset($ilr_info->ProgType) ? $ilr_info->ProgType : '';
				$_fwork_code = isset($ilr_info->FworkCode) ? $ilr_info->FworkCode : '';
				$_pway_code = isset($ilr_info->PwayCode) ? $ilr_info->PwayCode : '';
				$_std_code = isset($ilr_info->StdCode) ? $ilr_info->StdCode : '';

				if($_std_code == '')
					$ssa1_sql = <<<SQL
SELECT
  CONCAT(
    CoreReference_LARS_SectorSubjectAreaTier1_Lookup.`SectorSubjectAreaTier1`,
    ' - ',
    CoreReference_LARS_SectorSubjectAreaTier1_Lookup.`SectorSubjectAreaTier1Desc`
  ) AS SSA1_Desc
FROM
  lars201718.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup`
  LEFT JOIN lars201718.`Core_LARS_Framework` ON `CoreReference_LARS_SectorSubjectAreaTier1_Lookup`.`SectorSubjectAreaTier1` = lars201718.`Core_LARS_Framework`.`SectorSubjectAreaTier1`
WHERE lars201718.`Core_LARS_Framework`.`FworkCode` = '$_fwork_code' AND lars201718.`Core_LARS_Framework`.`ProgType` = '$_prog_type' AND lars201718.`Core_LARS_Framework`.`PwayCode` = '$_pway_code' ;
SQL;
				else
					$ssa1_sql = <<<SQL
SELECT
  CONCAT(
    CoreReference_LARS_SectorSubjectAreaTier1_Lookup.`SectorSubjectAreaTier1`,
    ' - ',
    CoreReference_LARS_SectorSubjectAreaTier1_Lookup.`SectorSubjectAreaTier1Desc`
  ) AS SSA1_Desc
FROM
  lars201718.`CoreReference_LARS_SectorSubjectAreaTier1_Lookup`
  LEFT JOIN lars201718.`Core_LARS_Standard` ON `CoreReference_LARS_SectorSubjectAreaTier1_Lookup`.`SectorSubjectAreaTier1` = lars201718.`Core_LARS_Standard`.`SectorSubjectAreaTier1`
WHERE lars201718.`Core_LARS_Standard`.`StandardCode` = '$_std_code' ;
SQL;
				$ssa1 = DAO::getObject($link, $ssa1_sql);
				$row['SSA1'] = isset($ssa1->SSA1_Desc) ? $ssa1->SSA1_Desc : '';

				DAO::saveObjectToTable($link, 'charts_master', $row);
			}
			DAO::execute($link, "DELETE FROM charts_master WHERE charts_master.tr_id NOT IN (SELECT tr.id FROM tr)");
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}

		echo true;
	}
}