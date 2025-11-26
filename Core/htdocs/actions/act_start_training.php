<?php
class start_training implements IAction
{
	public function execute(PDO $link)
	{
		
		$course_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		$_SESSION['bc']->add($link, "do.php?_action=start_training&id=" . $course_id, "Enrol Learners");

		$c_vo = Course::loadFromDatabase($link, $course_id);		
		$framework_id = $c_vo->framework_id;
		
		if($framework_id=='')
		{
			$framework_id = $this->getStoredValue('framework_id');
		}

		if($course_id=='')
		{
			$course_id = $this->getStoredValue('course_id');
		}
		
		
		// Create Value Objects
		$dao = new OrganisationDAO($link);
		$o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */
		
		$view = StartTraining::getInstance($course_id);
		$view->refresh($link, $_REQUEST);
		
		$framework_dropdown = "SELECT id, title, null FROM frameworks ORDER BY id;";
		$framework_dropdown = DAO::getResultset($link, $framework_dropdown);
		
		$courses_select = "SELECT id, title, null FROM courses where id='$course_id';";
		$courses_select = DAO::getResultset($link, $courses_select);
		
		$groups_select = "SELECT id, title, null FROM groups where courses_id=$course_id;";
		$groups_select = DAO::getResultset($link, $groups_select);

        $locations_select = "SELECT id, full_name, null FROM locations WHERE organisations_id IN (SELECT id FROM organisations WHERE organisation_type = 3)";
        $locations_select = DAO::getResultset($link, $locations_select);


		$legal_name = $_SESSION['user']->org->legal_name;	
		$legal_name_where_clause = "";
		if(DB_NAME=="am_lead")
		{
			switch($legal_name)
			{
				case 'Lean Education And Development Limited':
					$legal_name_where_clause = " AND (contracts.title LIKE '%LEAD%' OR contracts.title LIKE '%Lean Education And Development Limited%') ";
					break;
				case 'SWIFT ACI':
					$legal_name_where_clause = " AND (contracts.title LIKE '%SWIFT%' OR contracts.title LIKE '%SWIFT ACI%') ";
					break;
				default:
					$legal_name_where_clause = " AND (contracts.title LIKE '%$legal_name%') ";
					break;
			}
		}

		if($_SESSION['user']->type == '8')
			if(DB_NAME=='am_edudo')
                $contracts= DAO::getResultset($link,"SELECT id, title, contract_year from contracts where active = 1 and contract_year >= 2014 order by contract_year desc, title");
			elseif(DB_NAME=='am_lead')
	            $contracts= DAO::getResultset($link,"SELECT id, title, contract_year FROM contracts WHERE active = 1 AND contract_year >= 2014 " . $legal_name_where_clause  . " ORDER BY contract_year DESC, title");
            else
                $contracts= DAO::getResultset($link,"SELECT id, title, contract_year from contracts where active = 1 and contract_year >= 2014 and title like '%$legal_name%' order by contract_year desc, title");
		else
			$contracts= DAO::getResultset($link,"SELECT id, title, contract_year from contracts where active = 1 and contract_year >= 2014 order by contract_year desc, title");

		$sql = "SELECT start_date, end_date, id FROM contracts ";
		$contracts_dates = DAO::getResultset($link, $sql);
		
		require_once('tpl_start_training.php');
	}
	
	
	private function getStoredValue($name)
	{
		// Retrieve cached view
		$view = isset($_SESSION['view']) ? $_SESSION['view'] : NULL; /* @var $view View */
		
		$value = null;
		if(!is_null($view))
		{
			$value = $view->getPreference($name);
		}
		else
		{
			if(!array_key_exists($name, $_REQUEST))
			{
				throw new Exception("Missing querystring argument: $name");
			}
			else
			{
				$value = $_REQUEST[$name];
			}
		}		
		
		return $value;
	}
}
?>