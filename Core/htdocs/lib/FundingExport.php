<?php

class FundingExport
{
	private $db;
	private $data;
	
	function __construct($link)	
	{
		$this->db = $link;
		$this->data = array();
		$linklad = new PDO("mysql:host=".DB_HOST.";dbname=lad200910;port=".DB_PORT, DB_USER, DB_PASSWORD);
		
		$sql = "SELECT * FROM frameworks;";
		
		$data = '';
		$st = $link->query($sql);	
		if($st)
		{
			$data = "<?xml version=\"1.0\" ?>\r\n";
			$data .= "<funding>";
			
			while($row = $st->fetch())
			{
				$this->data[$row['title']] = array(
					'info' => array(
						'title' => $row['title']
						,'duration' => $row['duration_in_months']
						,'id' => $row['id']
					)
				);

				$funding_body = 2;
				
				$sql11= "
				select 
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4) as trs,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 0) as year1,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 1) as year2,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.start_date)) - (RIGHT(CURDATE(),5)<RIGHT(tr.start_date,5))) = 2) as year3,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) >= 16 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) <= 18) as age16to18,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL && tr.start_date < CURRENT_DATE() && tr.status_code<>4 and ((YEAR(CURDATE())-YEAR(tr.dob)) - (RIGHT(CURDATE(),5)<RIGHT(tr.dob,5))) >= 19) as age19,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W01')) as W01Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W02')) as W02Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W03')) as W03Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W04')) as W04Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W05')) as W05Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W06')) as W06Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W07')) as W07Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W08')) as W08Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W09')) as W09Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W10')) as W10Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W11')) as W11Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.closure_date IS NULL or closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W12')) as W12Active,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W01')) as W01Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W01') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02')) as W02Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W02') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03')) as W03Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W03') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04')) as W04Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W04') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05')) as W05Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W05') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06')) as W06Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W06') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07')) as W07Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W07') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08')) as W08Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W08') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09')) as W09Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W09') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10')) as W10Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W10') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11')) as W11Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 2 and tr.closure_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W11') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W12')) as W12Completed,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W01')) as W01ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W01') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02')) as W02ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W02') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03')) as W03ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W03') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04')) as W04ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W04') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05')) as W05ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W05') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06')) as W06ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W06') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07')) as W07ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W07') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08')) as W08ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W08') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09')) as W09ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W09') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10')) as W10ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W10') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11')) as W11ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and tr.status_code = 1 and tr.target_date > (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W11') and tr.target_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W12')) as W12ToBeCompleted,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W01')) as W01Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W01') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W02')) as W02Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W02') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W03')) as W03Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W03') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W04')) as W04Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W04') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W05')) as W05Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W05') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W06')) as W06Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W06') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W07')) as W07Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W07') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W08')) as W08Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W08') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W09')) as W09Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W09') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W10')) as W10Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W10') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W11')) as W11Leavers,
				 (select count(*) from tr inner join courses_tr on courses_tr.tr_id = tr.id inner join courses on courses.id = courses_tr.course_id where courses.framework_id = frameworks.id and (tr.status_code = 3 or tr.status_code = 4) and tr.closure_date < (select last_submission_date from central.lookup_submission_dates where contract_type = '$funding_body' and contract_year=2009 and submission='W11') and tr.closure_date <= (select last_submission_date from central.lookup_submission_dates where contract_year=2009 and submission='W12')) as W12Leavers
				from frameworks
				where frameworks.id = '" . intval($row['id']) . "'
				";

				$st11 = $link->query($sql11);
				if($st11)
				{
					$row11 = $st11->fetch();

					$this->data[$row['title']]['info']['numactive'] = $row11['trs'];
					$this->data[$row['title']]['info']['year1'] = $row11['year1'];
					$this->data[$row['title']]['info']['year2'] = $row11['year2'];
					$this->data[$row['title']]['info']['year3'] = $row11['year3'];
					$this->data[$row['title']]['info']['age16to18'] = $row11['age16to18'];
					$this->data[$row['title']]['info']['age19'] = $row11['age19'];
					
					$this->data[$row['title']]['info']['w01active'] = $row11['W01Active'];
					$this->data[$row['title']]['info']['w02active'] = $row11['W02Active'];
					$this->data[$row['title']]['info']['w03active'] = $row11['W03Active'];
					$this->data[$row['title']]['info']['w04active'] = $row11['W04Active'];
					$this->data[$row['title']]['info']['w05active'] = $row11['W05Active'];
					$this->data[$row['title']]['info']['w06active'] = $row11['W06Active'];
					$this->data[$row['title']]['info']['w07active'] = $row11['W07Active'];
					$this->data[$row['title']]['info']['w08active'] = $row11['W08Active'];
					$this->data[$row['title']]['info']['w09active'] = $row11['W09Active'];
					$this->data[$row['title']]['info']['w10active'] = $row11['W10Active'];
					$this->data[$row['title']]['info']['w11active'] = $row11['W11Active'];
					$this->data[$row['title']]['info']['w12active'] = $row11['W12Active'];
						
					$this->data[$row['title']]['info']['w02completed'] = $row11['W02Completed'];
					$this->data[$row['title']]['info']['w03completed'] = $row11['W03Completed'];
					$this->data[$row['title']]['info']['w04completed'] = $row11['W04Completed'];
					$this->data[$row['title']]['info']['w05completed'] = $row11['W05Completed'];
					$this->data[$row['title']]['info']['w06completed'] = $row11['W06Completed'];
					$this->data[$row['title']]['info']['w07completed'] = $row11['W07Completed'];
					$this->data[$row['title']]['info']['w08completed'] = $row11['W08Completed'];
					$this->data[$row['title']]['info']['w09completed'] = $row11['W09Completed'];
					$this->data[$row['title']]['info']['w10completed'] = $row11['W10Completed'];
					$this->data[$row['title']]['info']['w11completed'] = $row11['W11Completed'];
					$this->data[$row['title']]['info']['w12completed'] = $row11['W12Completed'];
					
					$this->data[$row['title']]['info']['w02tobecompleted'] = $row11['W02ToBeCompleted'];
					$this->data[$row['title']]['info']['w03tobecompleted'] = $row11['W03ToBeCompleted'];
					$this->data[$row['title']]['info']['w04tobecompleted'] = $row11['W04ToBeCompleted'];
					$this->data[$row['title']]['info']['w05tobecompleted'] = $row11['W05ToBeCompleted'];
					$this->data[$row['title']]['info']['w06tobecompleted'] = $row11['W06ToBeCompleted'];
					$this->data[$row['title']]['info']['w07tobecompleted'] = $row11['W07ToBeCompleted'];
					$this->data[$row['title']]['info']['w08tobecompleted'] = $row11['W08ToBeCompleted'];
					$this->data[$row['title']]['info']['w09tobecompleted'] = $row11['W09ToBeCompleted'];
					$this->data[$row['title']]['info']['w10tobecompleted'] = $row11['W10ToBeCompleted'];
					$this->data[$row['title']]['info']['w11tobecompleted'] = $row11['W11ToBeCompleted'];
					$this->data[$row['title']]['info']['w12tobecompleted'] = $row11['W12ToBeCompleted'];

					$this->data[$row['title']]['info']['w02leavers'] = $row11['W02Leavers'];
					$this->data[$row['title']]['info']['w03leavers'] = $row11['W03Leavers'];
					$this->data[$row['title']]['info']['w04leavers'] = $row11['W04Leavers'];
					$this->data[$row['title']]['info']['w05leavers'] = $row11['W05Leavers'];
					$this->data[$row['title']]['info']['w06leavers'] = $row11['W06Leavers'];
					$this->data[$row['title']]['info']['w07leavers'] = $row11['W07Leavers'];
					$this->data[$row['title']]['info']['w08leavers'] = $row11['W08Leavers'];
					$this->data[$row['title']]['info']['w09leavers'] = $row11['W09Leavers'];
					$this->data[$row['title']]['info']['w10leavers'] = $row11['W10Leavers'];
					$this->data[$row['title']]['info']['w11leavers'] = $row11['W11Leavers'];
					$this->data[$row['title']]['info']['w12leavers'] = $row11['W12Leavers'];					
					
				}


				$sql2 = "
					SELECT
						framework_qualifications.*
					FROM
						framework_qualifications
					Where 
						framework_id = '" . intval($row['id']) . "'
				";
				
				$st2 = $link->query($sql2);
				if($st2)
				{
					while($row2 = $st2->fetch())
					{
						$this->data[$row['title']]['qualifications'][$row2['title']] = array(
							'id' => $row2['id']
							,'level' => $row2['level']
							,'type' => $row2['qualification_type']
						);

						
						$aim = str_replace('/', '', $row2['id']);
						$aim_res = "SELECT * from lsc_employer_annual_values where LEARNING_AIM_REF ='" . $aim  . "'";
						$aim_res = DAO::getResultset($linklad,$aim_res);
						
						if($aim_res)
						{
							$this->data[$row['title']]['qualifications'][$row2['title']]['yearcode'] = $aim_res[0][2];
							$this->data[$row['title']]['qualifications'][$row2['title']]['erwfc'] = $aim_res[0][3];
							$this->data[$row['title']]['qualifications'][$row2['title']]['awfc'] = $aim_res[0][4];
							$this->data[$row['title']]['qualifications'][$row2['title']]['ersc'] = $aim_res[0][5];
							$this->data[$row['title']]['qualifications'][$row2['title']]['fep'] = $aim_res[0][6];
							$this->data[$row['title']]['qualifications'][$row2['title']]['slna1'] = $aim_res[0][7];
							$this->data[$row['title']]['qualifications'][$row2['title']]['slner1'] = $aim_res[0][8];
							$this->data[$row['title']]['qualifications'][$row2['title']]['slner2'] = $aim_res[0][9];
						}
					}
				}
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}		
						
	}
	
	function toXML()
	{
		
	}
	
	function toExcel()
	{
		$output = <<<XML
<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>Perspective</Author>
  <LastAuthor>Perspective</LastAuthor>
  <Created>2009-07-03T14:13:07Z</Created>
  <Version>12.00</Version>
 </DocumentProperties>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>8415</WindowHeight>
  <WindowWidth>19095</WindowWidth>
  <WindowTopX>120</WindowTopX>
  <WindowTopY>150</WindowTopY>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s71">
   <Interior ss:Color="#DBE5F1" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s78">
   <Interior ss:Color="#538ED5" ss:Pattern="Solid"/>
  </Style>
 </Styles>
XML;
		
		foreach($this->data AS $frameworkTitle => $metaData)
		{
			$output .= '<Worksheet ss:Name="Framework ' . $metaData['info']['id'] . '">';
			$output .= '  <Table x:FullColumns="1"
   x:FullRows="1" ss:DefaultRowHeight="15">
   <Column ss:AutoFitWidth="0" ss:Width="279"/>
   <Column ss:AutoFitWidth="0" ss:Width="175"/>';
			
			// info for each framework
			foreach($metaData['info'] AS $key => $val)
			{
				$output .= '<Row><Cell ss:StyleID="s71"><Data ss:Type="String">' . $key . '</Data></Cell><Cell><Data ss:Type="String">' . $val . '</Data></Cell></Row>';
			}
			
			
			// qualifications
			//die(print_r($metaData['qualifications']));
			// ICK 11/11/09
			if ( isset($metaData['qualifications']) )
			{
				foreach($metaData['qualifications'] AS $qualificationName => $data)
				{
					$output .= '<Row><Cell><Data ss:Type="String"></Data></Cell></Row>';
					$output .= '<Row><Cell ss:StyleID="s78"><Data ss:Type="String">' . $qualificationName . '</Data></Cell></Row>';
					foreach($data AS $label => $value)
					{
						$output .= '<Row><Cell><Data ss:Type="String">' . $label . '</Data></Cell><Cell><Data ss:Type="String">' . $value . '</Data></Cell></Row>';
					}
				}
			}
			$output .= '</Table>';
			$output .= '  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Header x:Margin="0.3"/>
    <Footer x:Margin="0.3"/>
    <PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
   </PageSetup>
   <Selected/>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>';
			
			$output .= '</Worksheet>';
		}
		$output .= '</Workbook>';
		$output = str_replace('&', '&amp;', $output);
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=data-collection.xml');
		echo $output;
	}
}

?>