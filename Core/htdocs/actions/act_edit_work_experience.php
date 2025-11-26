<?php
class edit_work_experience implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$vo = TrainingRecord::loadFromDatabase($link, $tr_id);
		$navigate = isset($_REQUEST['navigate'])?$_REQUEST['navigate']:'';
		$current_monthn = isset($_REQUEST['current_month'])?$_REQUEST['current_month']:'';
		$current_year = isset($_REQUEST['current_year'])?$_REQUEST['current_year']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_work_experience&tr_id=" . $tr_id . "&navigate=" . $navigate . "&current_month=" . $current_monthn . "&current_year=" . $current_year, "Work Experience");		
		
		$days_of_the_week = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
		$months_of_the_year = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

		$que = "select start_date from tr where id='$tr_id'";
		$course_start_date = trim(DAO::getSingleValue($link, $que));
		
		
		$que = "select target_date from tr where id='$tr_id'";
		$course_end_date = trim(DAO::getSingleValue($link, $que));

		$que = "select count(*) from workplace_visits where tr_id='$tr_id' and start_date is not null order by tr_id";
		$planned_work_experience = trim(DAO::getSingleValue($link, $que));
		
		$que = "select count(*) from workplace_visits where tr_id='$tr_id' and end_date is not null order by tr_id";
		$actual_work_experience = trim(DAO::getSingleValue($link, $que));
		
		$sd = new Date($course_start_date);
		$course_start_date = new Date($course_start_date);
		$course_end_date = new Date($course_end_date);
		

		if($navigate=='')
		{
			$current_date = new Date(date('d-m-Y'));
			$current_monthn = $current_date->getMonth();
			$current_montht = $months_of_the_year[$current_monthn-1];
			$current_year = $current_date->getYear();		
		}
		elseif($navigate=='next')
		{
			$current_monthn++;
			if($current_monthn>12)
			{
				$current_monthn=1;
				$current_year++;						
			}
			$current_montht = $months_of_the_year[$current_monthn-1];
		}
		elseif($navigate=='previous')
		{
			$current_monthn--;
			if($current_monthn<1)
			{
				$current_monthn=12;
				$current_year--;
			}
			$current_montht = $months_of_the_year[$current_monthn-1];
		}
		else
			$current_montht = $months_of_the_year[$current_monthn-1];
		
		
		$display_start_date = new Date("01/" . $current_monthn . "/" . $current_year);
		$display_end_date = new Date($this->days_in_month($current_monthn,$current_year) .  "/" . $current_monthn . "/" . $current_year);
		
		
		// Dropdowns
		$sql = "SELECT organisations.id, CONCAT(legal_name, ' (' , IF(workplaces_available IS NOT NULL, workplaces_available, '0') , '/', (SELECT COUNT(DISTINCT tr_id) from workplace_visits where workplace_id = organisations.id and start_date is not null), ')') FROM organisations WHERE organisation_type like '%7%' and dealer_participating=1 ORDER BY legal_name;";
		$workplaces = DAO::getResultset($link, $sql);
		
		// Getting evidences from table
			$sql = <<<HEREDOC
SELECT
	workplace_id, start_date, end_date, comments, tr_id, DAY(start_date) as day  
FROM
	workplace_visits
where 
	tr_id = $tr_id and MONTH(start_date)=$current_monthn and YEAR(start_date)=$current_year and start_date is not null;
HEREDOC;
		
		$st = $link->query($sql);	
		if($st) 
		{
			$data = Array();	
			while($row = $st->fetch())
			{
				$day = $row['day'];
				$data[$day]['workplace_id'] = $row['workplace_id'];
				$data[$day]['start_date'] = $row['start_date'];
				$data[$day]['end_date'] = $row['end_date'];
				$data[$day]['comments'] = $row['comments'];
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
		
		$visits = Array();
		$days_in_month = $this->days_in_month($current_monthn,$current_year);
		for($a = 1; $a<=$days_in_month; $a++)
		{
			
		
			if(isset($data[$a]))
			{
				$visits[$a]['workplace_id'] = $data[$a]['workplace_id'];
				$visits[$a]['start_date'] = $data[$a]['start_date'];
				$visits[$a]['end_date'] = $data[$a]['end_date'];
				$visits[$a]['comments'] = $data[$a]['comments'];
			}
			else
			{
				$visits[$a]['workplace_id'] = null;
				$visits[$a]['start_date'] = null;
				$visits[$a]['end_date'] = null;
				$visits[$a]['comments'] = null;
			}
		}
		
		
		include('tpl_edit_work_experience.php');
	}
	
	private function days_in_month($month, $year)
	{
		if($month < 1 || $month > 12)
		{
			throw new Exception("Month cannot be '$month'");
		}
		
		$days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		
		$is_leap_year = false;
		if($year % 400 == 0)
		{
			$is_leap_year = true;
		}elseif($year % 100 == 0)
		{
			$is_leap_year = false;
		}elseif($year % 4 == 0)
		{
			$is_leap_year = true;
		}
		
		
		if($is_leap_year && $month == 2)
		{
			return 29;
		}
		else
		{
			return $days[$month - 1];
		}
	}
	
	
}
?>