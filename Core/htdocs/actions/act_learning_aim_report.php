<?php
class learning_aim_report implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=learning_aim_report", "View Learning Aim Report");

		DAO::execute($link, "UPDATE student_qualifications SET aptitude = 1 WHERE LOCATE(REPLACE(id,'/',''), (SELECT ilr FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE tr_id = student_qualifications.`tr_id` ORDER BY contracts.`contract_year` DESC, submission DESC LIMIT 0,1)) = 0");
		DAO::execute($link, "delete from zprogs where year = 2013");
		DAO::execute($link, "INSERT INTO zprogs SELECT id, NULL, NULL, NULL, NULL, '1', '2013', (SELECT ilr FROM ilr LEFT JOIN contracts ON contracts.id = ilr.`contract_id` WHERE ilr.`tr_id` = tr.id ORDER BY contract_year DESC, submission DESC LIMIT 0,1) FROM tr WHERE tr.`contract_id` IN (SELECT id FROM contracts WHERE contract_year = 2013);");
		DAO::execute($link, 'DELETE FROM zprogs WHERE `year` = 2013 AND LOCATE("ZPROG001",ilr) = 0;');
		$q = 'UPDATE zprogs SET start_date = IF(MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnStartDate"),3,1)="/",CONCAT(MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnStartDate"),7,4),\'-\',MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnStartDate"),4,2),\'-\',LEFT(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnStartDate"),2)),extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnStartDate"))
,planned_end_date = IF(MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnPlanEndDate"),3,1)="/",CONCAT(MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnPlanEndDate"),7,4),\'-\',MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnPlanEndDate"),4,2),\'-\',LEFT(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnPlanEndDate"),2)),extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnPlanEndDate"))
,actual_end_date = IF(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnActEndDate")!="",IF(MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnActEndDate"),3,1)=\'/\',CONCAT(MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnActEndDate"),7,4),\'-\',MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnActEndDate"),4,2),\'-\',LEFT(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnActEndDate"),2)),extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearnActEndDate")),NULL)
,ach_date = IF(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate")!="",IF(MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate"),3,1)=\'/\',CONCAT(MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate"),7,4),\'-\',MID(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate"),4,2),\'-\',LEFT(extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate"),2)),extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate")),NULL)
,comp_status = extractvalue(ilr,"/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/CompStatus")
WHERE `year`>=2013';
		DAO::execute($link, $q);
		DAO::execute($link, 'update zprogs set ilr = NULL');


		DAO::execute($link, "DROP TABLE IF EXISTS dm");
		$sql = <<<HEREDOC
create temporary table dm
select
	DISTINCT
	tr.id as tr_id,
	tr.uln,
	tr.contract_id,
	tr.l03,
	employers.edrs,
	tr.firstnames,
	tr.surname,
	REPLACE(student_qualifications.id,'/','') as learning_aim_reference,
	tr.ni as ni_number,
	tr.dob as date_of_birth,
	assessors.firstnames as assessor_forename,
	assessors.surname as assessor_surname,
	employers.legal_name as employer,
	#employers.code,
	brands.title as brand,
	(SELECT description FROM lookup_employer_size WHERE lookup_employer_size.code = employers.code) AS employer_size,
	contracts.title as contract,
	frameworks.title as qualification_framework,
    courses.title as qualification_course,
	'' as qualification_status,
    student_qualifications.lsc_learning_aim as area_of_learning,
	student_qualifications.start_date AS start_date,
	tr.created AS creation_date,
	tr.closed_date AS closed_date,
	student_qualifications.awarding_body_date as registration_date,
	student_qualifications.awarding_body_reg as registration_number,
	student_qualifications.end_date AS planned_end_date,
	student_qualifications.actual_end_date AS actual_end_date,
	student_qualifications.achievement_date AS achievement_date,
	assessor_review.meeting_date AS last_review_date,
	student_qualifications.qualification_type,
	courses.programme_type,
	frameworks.id as framework_id,
	employers.manufacturer,
    IF(student_qualifications.end_date <= CURDATE(), '100', `student milestones subquery`.target) as progress_target,
	round(IF(unitsUnderAssessment>=100,100,unitsUnderAssessment)) as progress_percentage,
    '                                         ' AS home_region,
    '                                         ' AS employer_region,
	CASE
		WHEN aptitude = 1 THEN "<img src='/images/exempt.gif' border = '0'></img>"
		WHEN IF(unitsUnderAssessment>=100,100,unitsUnderAssessment) >= `student milestones subquery`.target THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
	END AS `progress_status`,
    '' as ilr_destination_code,
    '' as ilr_actual_end_date,
    '' as health_problems,
    '' as disability,
    '' as learning_difficulty,
    '' as ethnicity,
    contracts.contract_year,
	student_qualifications.aptitude,
	CASE
		WHEN aptitude = 1 THEN "<img src='/images/exempt.gif' border = '0'></img>"
		WHEN IF(unitsUnderAssessment>100,100,unitsUnderAssessment) >= `student milestones subquery`.target THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
	END AS `status`,
	tr.gender,
	tr.status_code,
	if(tr.target_date < CURDATE(),100,`student milestones subquery`.target) as target,
	'' as outcome,
    '' as project,
    lookup_contract_types.contract_type as funded,
    providers.legal_name as training_provider,
    '' as ukprn,
    '' as learner_postcode,
    '' as delivery_postcode,
    '' as additional_learning_need,
    '' as res_code,
    '' as project_a,
    tr.employer_id,
    tr.provider_id,
    employers.sector,
    lookup_reason_past_planned.description as reason_past_planned
from student_qualifications
	LEFT JOIN courses_tr on courses_tr.tr_id = student_qualifications.tr_id
	LEFT JOIN courses on courses.id = courses_tr.course_id
	LEFT JOIN frameworks on frameworks.id = courses.framework_id
	LEFT JOIN organisations on organisations.id = courses.organisations_id
	LEFT JOIN tr on tr.id = student_qualifications.tr_id
	LEFT JOIN lookup_reason_past_planned on lookup_reason_past_planned.id = tr.reason_unfunded
	#LEFT JOIN central.`lookup_postcode_la` AS hrla ON hrla.`postcode` = tr.home_postcode
	#LEFT JOIN central.`lookup_la_gor` AS hrgor ON hrgor.`local_authority` = hrla.`local_authority`
	LEFT JOIN users as assessors on tr.assessor = assessors.id
	LEFT JOIN organisations as employers on employers.id = tr.employer_id
	LEFT JOIN brands on brands.id = employers.manufacturer
	LEFT JOIN locations ON locations.`organisations_id` = employers.id AND locations.`is_legal_address` = 1
	LEFT JOIN central.`lookup_postcode_la` AS erla ON erla.`postcode` = locations.postcode
	LEFT JOIN central.`lookup_la_gor` AS ergor ON ergor.`local_authority` = erla.`local_authority`
	LEFT JOIN organisations as providers on providers.id = tr.provider_id
	LEFT JOIN contracts on contracts.id = tr.contract_id
    LEFT JOIN lookup_contract_types ON lookup_contract_types.id = contracts.`funded`
	LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND CONCAT(assessor_review.id,assessor_review.meeting_date) = (SELECT MAX(CONCAT(id,meeting_date)) FROM assessor_review WHERE tr_id = tr.id AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00')
	LEFT OUTER JOIN (
		SELECT
			tr.id AS 'tr_id',
			CASE timestampdiff(MONTH, tr.start_date, CURDATE())
				WHEN 0 THEN 0
				WHEN 1 THEN avg(student_milestones.month_1)
				WHEN 2 THEN avg(student_milestones.month_2)
				WHEN 3 THEN avg(student_milestones.month_3)
				WHEN 4 THEN avg(student_milestones.month_4)
				WHEN 5 THEN avg(student_milestones.month_5)
				WHEN 6 THEN avg(student_milestones.month_6)
				WHEN 7 THEN avg(student_milestones.month_7)
				WHEN 8 THEN avg(student_milestones.month_8)
				WHEN 9 THEN avg(student_milestones.month_9)
				WHEN 10 THEN avg(student_milestones.month_10)
				WHEN 11 THEN avg(student_milestones.month_11)
				WHEN 12 THEN avg(student_milestones.month_12)
				WHEN 13 THEN avg(student_milestones.month_13)
				WHEN 14 THEN avg(student_milestones.month_14)
				WHEN 15 THEN avg(student_milestones.month_15)
				WHEN 16 THEN avg(student_milestones.month_16)
    			WHEN 17 THEN avg(student_milestones.month_17)
				WHEN 18 THEN avg(student_milestones.month_18)
				WHEN 19 THEN avg(student_milestones.month_19)
				WHEN 20 THEN avg(student_milestones.month_20)
				WHEN 21 THEN avg(student_milestones.month_21)
				WHEN 22 THEN avg(student_milestones.month_22)
				WHEN 23 THEN avg(student_milestones.month_23)
				WHEN 24 THEN avg(student_milestones.month_24)
				WHEN 25 THEN avg(student_milestones.month_25)
				WHEN 26 THEN avg(student_milestones.month_26)
				WHEN 27 THEN avg(student_milestones.month_27)
				WHEN 28 THEN avg(student_milestones.month_28)
				WHEN 29 THEN avg(student_milestones.month_29)
				WHEN 30 THEN avg(student_milestones.month_30)
				WHEN 31 THEN avg(student_milestones.month_31)
				WHEN 32 THEN avg(student_milestones.month_32)
				WHEN 33 THEN avg(student_milestones.month_33)
				WHEN 34 THEN avg(student_milestones.month_34)
				WHEN 35 THEN avg(student_milestones.month_35)
				WHEN 36 THEN avg(student_milestones.month_36)
				ELSE 100
			END	AS `target`
		FROM
			tr
			LEFT JOIN student_milestones ON student_milestones.tr_id = tr.id
			LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id
			WHERE chosen=1 AND student_qualifications.aptitude != 1 AND student_milestones.qualification_id = student_qualifications.id
		GROUP BY
			tr.id ) AS `student milestones subquery` ON tr.id = `student milestones subquery`.tr_id

where student_qualifications.framework_id!='0' and aptitude != 1
UNION
SELECT zprogs.tr_id, tr.uln, tr.`contract_id`, tr.`l03`, organisations.edrs, tr.firstnames, tr.surname, 'ZPROG001' AS learning_aim_reference, tr.ni AS ni_number, tr.dob AS date_of_birth,
assessors.firstnames AS assessor_forename, assessors.surname AS assessor_surname, organisations.legal_name AS employer, brands.`title` AS brand,
(SELECT description FROM lookup_employer_size WHERE lookup_employer_size.code = organisations.code) AS employer_size,
contracts.title AS contract, frameworks.`title` AS qualification_framework, courses.`title` AS qualification_course, zprogs.comp_status AS qualification_status, '' AS area_of_learning,
zprogs.start_date AS start_date,
tr.created AS creation_date,
tr.closed_date AS closed_date,
'' AS registration_date,
'' AS registration_number,
zprogs.planned_end_date AS planned_end_date,
zprogs.actual_end_date AS actual_end_date,
zprogs.ach_date AS achievement_date,
assessor_review.meeting_date AS last_review_date,
'ProgramAim',
courses.programme_type,
frameworks.id as framework_id,
organisations.manufacturer,
IF(zprogs.planned_end_date <= CURDATE(), '100', tr.target) AS progress_target,
tr.l36 AS progress_percentage,
'                                              ' AS home_region,
'                                              ' AS employer_region,
CASE
	WHEN tr.l36>=tr.target THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
	ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
END AS `progress_status`,
'' AS ilr_destination_code,
'' AS ilr_actual_end_date,
'' AS health_problems,
'' AS disability,
'' AS learning_difficulty,
'' AS ethnicity,
contracts.contract_year,
'0' AS aptitude,
CASE
	WHEN tr.l36>=tr.target THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
	ELSE "<img src='/images/red-cross.gif' border='0'> </img>"
END AS `status`,
tr.gender,
tr.status_code,
IF(zprogs.planned_end_date < CURDATE(),100,tr.target) AS target,
'' AS outcome,
'' AS project,
lookup_contract_types.contract_type AS funded,
providers.legal_name AS training_provider,
'' AS ukprn,
'' AS learner_postcode,
'' AS delivery_postcode,
'' AS additional_learning_need,
'' as res_code,
'' as project_a,
tr.employer_id,
tr.provider_id,
employers.sector,
lookup_reason_past_planned.description as reason_past_planned
FROM zprogs
LEFT JOIN tr ON tr.id = zprogs.tr_id
LEFT JOIN lookup_reason_past_planned on lookup_reason_past_planned.id = tr.reason_unfunded
LEFT JOIN organisations AS providers ON providers.id = tr.provider_id
LEFT JOIN organisations as employers on employers.id = tr.employer_id
LEFT JOIN users AS assessors ON assessors.id = tr.assessor
#LEFT JOIN central.`lookup_postcode_la` AS hrla ON hrla.`postcode` = tr.home_postcode
#LEFT JOIN central.`lookup_la_gor` AS hrgor ON hrgor.`local_authority` = hrla.`local_authority`
LEFT JOIN contracts ON contracts.id = tr.`contract_id`
LEFT JOIN lookup_contract_types ON lookup_contract_types.id = contracts.`funded`
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
LEFT JOIN frameworks ON frameworks.id = courses.`framework_id`
LEFT JOIN organisations ON organisations.id = tr.`employer_id`
LEFT JOIN brands ON brands.`id` = organisations.`manufacturer`
LEFT JOIN locations ON locations.`organisations_id` = organisations.id AND locations.`is_legal_address` = 1
LEFT JOIN central.`lookup_postcode_la` AS erla ON erla.`postcode` = locations.postcode
LEFT JOIN central.`lookup_la_gor` AS ergor ON ergor.`local_authority` = erla.`local_authority`
LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND CONCAT(assessor_review.id,assessor_review.meeting_date) = (SELECT MAX(CONCAT(id,meeting_date)) FROM assessor_review WHERE tr_id = tr.id AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00')
	LEFT OUTER JOIN (
		SELECT
			tr.id AS 'tr_id',
			CASE timestampdiff(MONTH, tr.start_date, CURDATE())
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
			END	AS `target`
		FROM
			tr
			LEFT JOIN student_milestones ON student_milestones.tr_id = tr.id
			LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id
			WHERE chosen=1 AND student_qualifications.aptitude != 1 AND student_milestones.qualification_id = student_qualifications.id
		GROUP BY
			tr.id ) AS `student milestones subquery` ON tr.id = `student milestones subquery`.tr_id
HEREDOC;
        DAO::execute($link, $sql);
        DAO::execute($link, "update dm
LEFT JOIN tr on tr.id = dm.tr_id
LEFT JOIN central.`lookup_postcode_la` AS hrla ON hrla.`postcode` = tr.home_postcode
LEFT JOIN central.`lookup_la_gor` AS hrgor ON hrgor.`local_authority` = hrla.`local_authority`
set home_region = IF(hrgor.government_region is null, '', hrgor.government_region)");

		$view = ViewLearningAims::getInstance($link);
		$view->refresh($link, $_REQUEST);

		require_once('tpl_learning_aim_report.php');
	}
}
