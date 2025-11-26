<?php
class load_funding implements IAction
{
	public function execute(PDO $link)
	{
		$start = microtime(true);
		
		
		require_once('./lib/FundingExport.php');
		$fundingLib = new FundingExport($link);
		$fundingLib->toExcel();
		die;
		//die('Executed in ' . (microtime(true) - $start) . ' seconds');
	}
	
	public function _execute(PDO $link)
	{
		$start = microtime(true);
		
		//header('Content-Type: text/xml');
		
		$linklad = new PDO("mysql:host=".DB_HOST.";dbname=lad200809;port=".DB_PORT, DB_USER, DB_PASSWORD);
		
		
		$sql = <<<HEREDOC
SELECT
	*
FROM
	frameworks;

HEREDOC;
		
		$data = '';
		$st = $link->query($sql);	
		if($st)
		{
			$data = "<?xml version=\"1.0\" ?>\r\n";
			$data .= "<funding>";
			
			while($row = $st->fetch())
			{
				$data .= "<framework>";
				$data .= "<framework_title>" . $row['title'] . "</framework_title>";
				$data .= "<duration>" . $row['duration_in_months'] . "</duration>";

				
				
$sql11= <<<HEREDOC
select 
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4) as trs,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 0) as year1,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 1) as year2,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 2) as year3,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) >= 16 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) <= 18) as age16to18,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) >= 19) as age19,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W01')) as W01Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02')) as W02Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03')) as W03Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04')) as W04Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05')) as W05Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06')) as W06Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07')) as W07Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08')) as W08Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09')) as W09Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10')) as W10Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11')) as W11Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W12')) as W12Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W01')) as W01Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W01') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02')) as W02Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03')) as W03Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04')) as W04Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05')) as W05Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06')) as W06Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07')) as W07Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08')) as W08Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09')) as W09Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10')) as W10Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11')) as W11Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W12')) as W12Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W01')) as W01ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W01') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02')) as W02ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03')) as W03ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04')) as W04ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05')) as W05ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06')) as W06ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07')) as W07ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08')) as W08ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09')) as W09ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10')) as W10ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11')) as W11ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W12')) as W12ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W01')) as W01Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W01') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02')) as W02Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03')) as W03Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04')) as W04Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05')) as W05Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06')) as W06Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07')) as W07Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08')) as W08Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09')) as W09Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10')) as W10Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11')) as W11Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W12')) as W12Leavers
from frameworks
where frameworks.id = {$row['id']}
HEREDOC;

				
				$st11 = $link->query($sql11);
				if($st11)
				{
					$row11 = $st11->fetch();
					$data .= "<numactive>" . $row11['trs'] . "</numactive>";
					$data .= "<year1>" . $row11['year1'] . "</year1>";
					$data .= "<year2>" . $row11['year2'] . "</year2>";
					$data .= "<year3>" . $row11['year3'] . "</year3>";
					$data .= "<age16to18>" . $row11['age16to18'] . "</age16to18>";
					$data .= "<age19>" . $row11['age19'] . "</age19>";
					$data .= "<w01active>" . $row11['W01Active'] . "</w01active>";
					$data .= "<w02active>" . $row11['W02Active'] . "</w02active>";
					$data .= "<w03active>" . $row11['W03Active'] . "</w03active>";
					$data .= "<w04active>" . $row11['W04Active'] . "</w04active>";
					$data .= "<w05active>" . $row11['W05Active'] . "</w05active>";
					$data .= "<w06active>" . $row11['W06Active'] . "</w06active>";
					$data .= "<w07active>" . $row11['W07Active'] . "</w07active>";
					$data .= "<w08active>" . $row11['W08Active'] . "</w08active>";
					$data .= "<w09active>" . $row11['W09Active'] . "</w09active>";
					$data .= "<w10active>" . $row11['W10Active'] . "</w10active>";
					$data .= "<w11active>" . $row11['W11Active'] . "</w11active>";
					$data .= "<w12active>" . $row11['W12Active'] . "</w12active>";
					
					$data .= "<w02completed>" . $row11['W02Completed'] . "</w02completed>";
					$data .= "<w03completed>" . $row11['W03Completed'] . "</w03completed>";
					$data .= "<w04completed>" . $row11['W04Completed'] . "</w04completed>";
					$data .= "<w05completed>" . $row11['W05Completed'] . "</w05completed>";
					$data .= "<w06completed>" . $row11['W06Completed'] . "</w06completed>";
					$data .= "<w07completed>" . $row11['W07Completed'] . "</w07completed>";
					$data .= "<w08completed>" . $row11['W08Completed'] . "</w08completed>";
					$data .= "<w09completed>" . $row11['W09Completed'] . "</w09completed>";
					$data .= "<w10completed>" . $row11['W10Completed'] . "</w10completed>";
					$data .= "<w11completed>" . $row11['W11Completed'] . "</w11completed>";
					$data .= "<w12completed>" . $row11['W12Completed'] . "</w12completed>";
					
					$data .= "<w02tobecompleted>" . $row11['W02ToBeCompleted'] . "</w02tobecompleted>";
					$data .= "<w03tobecompleted>" . $row11['W02ToBeCompleted'] . "</w03tobecompleted>";
					$data .= "<w04tobecompleted>" . $row11['W02ToBeCompleted'] . "</w04tobecompleted>";
					$data .= "<w05tobecompleted>" . $row11['W02ToBeCompleted'] . "</w05tobecompleted>";
					$data .= "<w06tobecompleted>" . $row11['W02ToBeCompleted'] . "</w06tobecompleted>";
					$data .= "<w07tobecompleted>" . $row11['W02ToBeCompleted'] . "</w07tobecompleted>";
					$data .= "<w08tobecompleted>" . $row11['W02ToBeCompleted'] . "</w08tobecompleted>";
					$data .= "<w09tobecompleted>" . $row11['W02ToBeCompleted'] . "</w09tobecompleted>";
					$data .= "<w10tobecompleted>" . $row11['W02ToBeCompleted'] . "</w10tobecompleted>";
					$data .= "<w11tobecompleted>" . $row11['W02ToBeCompleted'] . "</w11tobecompleted>";
					$data .= "<w12tobecompleted>" . $row11['W02ToBeCompleted'] . "</w12tobecompleted>";
				

					$data .= "<w02leavers>" . $row11['W02Leavers'] . "</w02leavers>";
					$data .= "<w03leavers>" . $row11['W03Leavers'] . "</w03leavers>";
					$data .= "<w04leavers>" . $row11['W04Leavers'] . "</w04leavers>";
					$data .= "<w05leavers>" . $row11['W05Leavers'] . "</w05leavers>";
					$data .= "<w06leavers>" . $row11['W06Leavers'] . "</w06leavers>";
					$data .= "<w07leavers>" . $row11['W07Leavers'] . "</w07leavers>";
					$data .= "<w08leavers>" . $row11['W08Leavers'] . "</w08leavers>";
					$data .= "<w09leavers>" . $row11['W09Leavers'] . "</w09leavers>";
					$data .= "<w10leavers>" . $row11['W10Leavers'] . "</w10leavers>";
					$data .= "<w11leavers>" . $row11['W11Leavers'] . "</w11leavers>";
					$data .= "<w12leavers>" . $row11['W12Leavers'] . "</w12leavers>";
					
				}


		$sql2 = <<<HEREDOC
SELECT
	framework_qualifications.*
FROM
	framework_qualifications
Where 
	framework_id = {$row['id']}
HEREDOC;
				
				$st2 = $link->query($sql2);
				if($st2)
				{
					while($row2 = $st2->fetch())
					{
						
						$data .= "<qualification>";
						$data .= "<id>" . $row2['id'] . "</id>";
						$data .= "<title>" . $row2['title'] . "</title>";
						$data .= "<level>" . $row2['level'] . "</level>";
						$data .= "<type>" . $row2['qualification_type'] . "</type>";
						
						
						$aim = str_replace('/', '', $row2['id']);
						$aim_res = "SELECT * from lsc_employer_annual_values where LEARNING_AIM_REF ='" . $aim  . "'";
						
						$aim_res = DAO::getResultset($linklad,$aim_res);
						//throw new Exception($aim_res[0][2]);
						
						if ( $aim_res)
						{
							
							$data .= "<yearcode>" . $aim_res[0][2] . "</yearcode>";                   
							$data .= "<erwfc>" . $aim_res[0][3] . "</erwfc>";           
							$data .= "<awfc>" . $aim_res[0][4] . "</awfc>";          
							$data .= "<ersc>" . $aim_res[0][5] . "</ersc>";               
							$data .= "<fep>" . $aim_res[0][6] . "</fep>";                 
							$data .= "<slna1>" . $aim_res[0][7] . "</slna1>";                   
							$data .= "<slner1>" . $aim_res[0][8] . "</slner1>";                         
							$data .= "<slner2>" . $aim_res[0][9] . "</slner2>";                         
							
						}
						
						
						$data .= "</qualification>";
					}
				}
				
				$data .= "</framework>";
			}
			
			$data .= "</funding>";
			
			$data = str_replace("&", "&amp;", $data);
			
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
		//die('Executed in ' . (microtime(true) - $start) . ' seconds');
		
		require_once('tpl_download_funding.php');			
	}
}
?>