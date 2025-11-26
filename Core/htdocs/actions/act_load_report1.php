<?php
class load_report1 implements IAction
{
	public function execute(PDO $link)
	{
		
		
		header('Content-Type: text/xml');
		
		$hostname = ini_get('mysqli.default_host');
		$port = ini_get('mysqli.default_port');
		try
		{
			$linklad = new PDO("mysql:host=$hostname;dbname=lad200809;port=$port",ini_get('mysqli.default_user'), ini_get('mysqli.default_pw'));
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		
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
				$data .= "<title>" . $row['title'] . "</title>";
				$data .= "<duration>" . $row['duration_in_months'] . "</duration>";

				
				
//$sql11= <<<HEREDOC
//select 
//            (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4) as trs,
//            (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 0) as year1,
//            (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 1) as year2,
//            (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 2) as year3,
//            (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) >= 16 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) <= 18) as age16to18,
//            (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) >= 19) as age19
//from frameworks
//where frameworks.id = {$row['id']}
//HEREDOC;

$sql11= <<<HEREDOC
select 
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4) as trs,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 0) as year1,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 1) as year2,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 2) as year3,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) >= 16 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) <= 18) as age16to18,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) >= 19) as age19,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W01')) as W01Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W02')) as W02Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W03')) as W03Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W04')) as W04Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W05')) as W05Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W06')) as W06Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W07')) as W07Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W08')) as W08Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W09')) as W09Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W10')) as W10Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W11')) as W11Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() or closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W12')) as W12Active,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W01')) as W01Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W01') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W02')) as W02Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W02') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W03')) as W03Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W03') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W04')) as W04Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W04') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W05')) as W05Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W05') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W06')) as W06Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W06') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W07')) as W07Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W07') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W08')) as W08Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W08') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W09')) as W09Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W09') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W10')) as W10Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W10') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W11')) as W11Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W11') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W12')) as W12Completed,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W01')) as W01ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W01') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W02')) as W02ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W02') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W03')) as W03ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W03') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W04')) as W04ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W04') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W05')) as W05ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W05') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W06')) as W06ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W06') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W07')) as W07ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W07') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W08')) as W08ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W08') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W09')) as W09ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W09') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W10')) as W10ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W10') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W11')) as W11ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W11') and tr.target_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W12')) as W12ToBeCompleted,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W01')) as W01Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W01') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W02')) as W02Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W02') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W03')) as W03Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W03') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W04')) as W04Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W04') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W05')) as W05Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W05') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W06')) as W06Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W06') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W07')) as W07Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W07') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W08')) as W08Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W08') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W09')) as W09Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W09') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W10')) as W10Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W10') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W11')) as W11Leavers,
 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W11') and tr.closure_date <= (select last_submission_date from lookup_submission_dates where contract_year=2008 and submission='W12')) as W12Leavers
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
						$aim_res = "SELECT * from LSC_EMPLOYER_ANNUAL_VALUES where LEARNING_AIM_REF ='" . $aim  . "'";
						
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
			
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		

		$sql = "select description, count(value) as total from graph_data group by description";
		$st = $link->query($sql);	
		if($st) 
		{
			// for pie
			$xml = "<graph>";
			// for single bar
			$data = Array();
			$labels = Array();
			while($row = $st->fetch())
			{
				// for pie
				$xml .= "<record>";
				$xml .= "<description>" . $row['description'] . "</description>";
				$xml .= "<value>" . $row['total'] . "</value>";
				$xml .= "</record>";
				// for single bar
				$data[] = $row['total'];
				$labels[] = $row['description']; 
			}
			$xml .= "</graph>";
		}

		//echo $data;
		$this->showimage($xml);
		//require_once('tpl_download_funding.php');
	}





	public function showimage($xml)
	{

		
	$d = $xml;
	
	$colors[0] = "#FF0000";
	$colors[1] = "#FFFF00";
	$colors[2] = "#00FF00";
	$colors[3] = "#00FFFF";
	$colors[4] = "#0000FF";
	$colors[5] = "#FF00FF";
	$colors[6] = "#778899";
	$colors[7] = "#B0E0E6";
	$colors[8] = "#66CDAA";
	$colors[9] = "#808000";
	$colors[10] = "#BC8F8F";
	$colors[11] = "#FFFFFF";
	$colors[12] = "#000000";
	
	
	//$xml = new SimpleXMLElement($d);
	$xml = XML::loadSimpleXML($d);
	$index = 0;	
	foreach($xml->record as $record)
	{	
		$data[$index][0] = $record->description;
		$data[$index][1] = $record->value;
		$data[$index][2] = $colors[$index];
		$index++;
	}


	  	$pieWidth="200";
	  	$pieHeight="150";
  	
  	$ShadowDistance="15";
  	//$pieBackgroundColor="#99ccff";
  	$pieBackgroundColor="#FFFFFF";
  	$EQpieData=$data;
  	$legend='1';
  	  	
	if(!function_exists("imagecreatetruecolor")){
	  die("Error, GD Library 2 needed.");
	}

	//set some limitations
	if($pieWidth < 100    |$pieWidth > 500)      $pieWidth = 100;
	if($pieHeight < 100   |$pieHeight > 500)     $pieHeight = 100;
	if($ShadowDistance < 1|$ShadowDistance > 50) $ShadowDistance = 10;

	$pieWidth      = $pieWidth  *3;
	$pieHeight     = $pieHeight *3;
	$ShadowDistance = $ShadowDistance*3;
	$pieBackgroundColor     = $pieBackgroundColor;

        $pie = @ImageCreateTrueColor($pieWidth, $pieHeight+$ShadowDistance);

	$colR = hexdec(substr($pieBackgroundColor,1,2));
	$colG = hexdec(substr($pieBackgroundColor,3,2));
	$colB = hexdec(substr($pieBackgroundColor,5,2));
        $pieBG = ImageColorAllocate($pie, $colR, $colG, $colB);
        ImageFill($pie, 0, 0, $pieBG);

 	// get the total value for percentage calculations
	$total=0;

	$maxStringLenght = 0;
	foreach($EQpieData as $i => $value){
	  $total += $value[1];
          if(strlen($value[0]) > $maxStringLenght) $maxStringLenght = strlen($value[0]);
			
	}

	$pieParts = $i+1;
	reset($EQpieData);
	$legendWidth = (($legend > 0) ? ImageFontWidth(2)  * ($maxStringLenght + 6) + 40: 0 );

        // the first pie-part starts with offset in degrees up from horizantal right, looks better this way
        $pieStart = 135;

	foreach($EQpieData as $i => $value){ 

	  // the name  for each part is $value[0]
	  // the value for each part is $value[1]
          // the color for each part is $value[2]

	  $piePart = $value[1];
          $piePart100 = round(($piePart/$total*100),2);  // value in percentage, the rounding and * 100 for extra accuracy for pie w/o gaps
          $piePart360 = $piePart100 * 3.6 ;                    // in degrees

	  $colR = hexdec(substr($value[2],1,2));
	  $colG = hexdec(substr($value[2],3,2));
	  $colB = hexdec(substr($value[2],5,2));
          $PartColor = ImageColorAllocate($pie, $colR, $colG, $colB);

          $ShadowColR = (($colR > 79) ? $colR-80 : 0 );
          $ShadowColG = (($colG > 79) ? $colG-80 : 0 );
          $ShadowColB = (($colB > 79) ? $colB-80 : 0 );

          $ShadowColor = ImageColorAllocate($pie, $ShadowColR, $ShadowColG, $ShadowColB);

          //Here we create the shadow down-worths 
  	  for($i=0; $i<$ShadowDistance; $i++){
            ImageFilledArc($pie, $pieWidth/2, ($pieHeight/2+$i),  $pieWidth-20, $pieHeight-20, round($pieStart), round($pieStart+$piePart360), $ShadowColor, IMG_ARC_NOFILL);
          }

          $pieStart = $pieStart + $piePart360;

	}
	reset($EQpieData);

	$pieStart=135;

	foreach($EQpieData as $i => $value){
	   
	  $piePart = $value[1];

          $piePart100 = round(($piePart/$total*100),2);  // value in percentage, the rounding and * 100 for extra accuracy for pie w/o gaps
          $piePart360 = $piePart100 * 3.6 ;                    // in degrees

	  $colR = hexdec(substr($value[2],1,2));
	  $colG = hexdec(substr($value[2],3,2));
	  $colB = hexdec(substr($value[2],5,2));
          $PartColor = ImageColorAllocate($pie, $colR, $colG, $colB);


          //Here we create the real pie chart
          ImageFilledArc($pie, $pieWidth/2, $pieHeight/2,  $pieWidth-20, $pieHeight-20, round($pieStart), round($pieStart+$piePart360), $PartColor, IMG_ARC_PIE);

          $pieStart = $pieStart + $piePart360;

	}
	reset($EQpieData);

	// create final pie picture with proper background color
	$finalPie = ImageCreateTrueColor($pieWidth/3+$legendWidth,($pieHeight+$ShadowDistance)/3);
        ImageFill($finalPie, 0, 0, $pieBG);


	// resample with pieGraph inside (3x smaller)
	ImageCopyResampled($finalPie, $pie, 0, 0,  0, 0, $pieWidth/3,($pieHeight+$ShadowDistance)/3,$pieWidth,($pieHeight+$ShadowDistance));


	// Create the ledgend ...
	if($legendWidth > 0) {
 	  // Legend Box
	  $leg_width  = $legendWidth-10;
	  $leg_height = $pieParts*(ImageFontHeight(2)+2)+2;
	  $legendImage = ImageCreateTrueColor($leg_width, $leg_height);
          //ImageFill($legendImage, 0, 0, $pieBG);

	  $borderColor = ImageColorAllocate($pie, '155', '155', '155');
	  $boxColor = ImageColorAllocate($pie, '255', '255', '255');
	  $textColor = ImageColorAllocate($pie, '55', '55', '55');

	  ImageFilledRectangle($legendImage, 0, 0, $leg_width, $leg_height, $boxColor);
	  ImageRectangle($legendImage, 0, 0, $leg_width-1, $leg_height-1, $borderColor);

	  $box_width  = ImageFontHeight(2)-5;
	  $box_height = ImageFontHeight(2)-5;
 	  $yOffset = 2;

	  foreach($EQpieData as $i => $value){

	    $piePart = $value[1];
            $piePart100 = round(($piePart/$total*100),2);  // value in percentage, the rounding and * 100 for extra accuracy for pie w/o gaps

	    $colR = hexdec(substr($value[2],1,2));
	    $colG = hexdec(substr($value[2],3,2));
	    $colB = hexdec(substr($value[2],5,2));
            $PartColor = ImageColorAllocate($legendImage, $colR, $colG, $colB);

	    ImageFilledRectangle($legendImage,'5',$yOffset+2, '5'+$box_width, $yOffset+$box_height+2, $PartColor);
	    ImageRectangle($legendImage,'5',$yOffset+2, '5'+$box_width, $yOffset+$box_height+2, $borderColor);

	    $text=$value[0]." ".$piePart100."%";
	    ImageString($legendImage, 2, '20', $yOffset, $text, $textColor);
            $yOffset=$yOffset + 15;
	  }

	  reset($EQpieData); // reset pointer in array to first 

	  ImageCopyResampled($finalPie, $legendImage, $pieWidth/3, 10,  0, 0, $leg_width,$leg_height,$leg_width,$leg_height);
  	  ImageDestroy($legendImage);

	}
	//header('Content-type: image/png');
	//Imagepng($finalPie);
	imagedestroy($pie);

		$pdf = new Cezpdf($paper='A4',$orientation='portrait');
		$pdf->ezSetCmMargins( 1, 2, 1.5, 1.5 );
		$pdf->selectFont( "./lib/Helvetica.afm" );

		$pdf->ezText( "Qualifications", 12 );
		$pdf->ezNewPage();
		$pdf->ezText( "Framework", 12 );
		$pdf->addImage($finalPie,200,200,200,100,10000);
		$pdf->ezStream();

	imagedestroy($finalPie);
	
	}

}
?>