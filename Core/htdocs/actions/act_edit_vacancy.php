<?php
class edit_vacancy implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_REQUEST['id']) ? $_REQUEST['id']:'';
		$employer_id = isset($_REQUEST['employer_id']) ? $_REQUEST['employer_id']:'';
		
		if($id == '')
		{
			// New record
			$vo = new Vacancy();
		}
		else
		{
			$vo = Vacancy::loadFromDatabase($link, $id);
		}

		$status_dropdown = "SELECT id, description, null FROM lookup_vacancy_status ORDER BY id;";
		$status_dropdown = DAO::getResultset($link, $status_dropdown);

		$type_dropdown = "SELECT id, description, null FROM lookup_vacancy_type ORDER BY description asc;";
		$type_dropdown = DAO::getResultset($link, $type_dropdown);
		
		$active_dropdown = array(array(0, 'Inactive', ''), array(1, 'Active', ''));
		$active_dropdown_enabled = true;
		$active_dropdown_pre_selected = $vo->active;
		if($_SESSION['user']->type == User::TYPE_BUSINESS_RESOURCE_MANAGER)
		{
			if($id == '')
				$active_dropdown_pre_selected = 0;
			if(!$_SESSION['user']->isAdmin())
				$active_dropdown_enabled = false;
		}

		$locations_dropdown = "SELECT id, CONCAT(full_name, ' (', postcode, ')') AS location, null FROM locations where organisations_id = '$employer_id' ORDER BY id;";
		$locations_dropdown = DAO::getResultset($link, $locations_dropdown);

		if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic"  || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
		{
			$sql = "SELECT id, description, null FROM lookup_vacancy_app_type where description != '' order by description asc;";
			$apprenticeship_types = DAO::getResultSet($link, $sql);
		}
		
		// there is no location for this vacancy
		if ( sizeof($locations_dropdown) <= 0 ) {
			throw new Exception('A vacancy cannot be set up for this Employer as it does not have any locations');
		}

		// set up a vacancy code
		if( $id == '' ) {
			$placeholder_sql = "SELECT max(id)+1 from vacancies";
			$placeholder_id = DAO::getSingleValue($link, $placeholder_sql);
			$vo->code = strtoupper(substr($locations_dropdown[0][1], 0, 3)).date("dms").str_pad($placeholder_id, 6,'0',STR_PAD_LEFT);		
		}

		$employer_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = " . $employer_id);


		if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic"  || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
		{
			$other_levels = DAO::getResultset($link, 'SELECT id, description FROM lookup_vacancy_app_type ORDER BY description');
		}



		if(DB_NAME=="am_demo" || DB_NAME=="am_baltic_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
		{
			// this gets the items which should be pre selected in the multi select
			$selected = '';
			if(isset($vo->id))
			{
				$sql = "select vacancies_extra_progress.vacancy_app_id from vacancies_extra_progress where vacancies_extra_progress.vacancy_id = " . $vo->id;
				$selected = DAO::getSingleColumn($link, $sql);
			}
		}

		if(DB_NAME == "am_baltic_demo" || DB_NAME == "am_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
			$vacancies_dropdown = DAO::getResultset($link, "SELECT id, CONCAT(CODE, ' - ', job_title,'') FROM vacancies WHERE code IS NOT NULL ORDER BY created ASC ");
		else
			$vacancies_dropdown = DAO::getResultset($link, "SELECT id, job_title FROM vacancies ");

		if(DB_NAME == "am_baltic_demo" || DB_NAME == "am_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams" || DB_NAME=="am_ray_recruit" || DB_NAME=="am_lcurve_demo")
		{
			$brm_dropdown = DAO::getResultset($link, "SELECT username, CONCAT(firstnames, ' ', surname, ' (', username, ')') AS name FROM users WHERE type = 23 ORDER BY name;");
			$status_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_vacancy_status ORDER BY description;");
			$region_dropdown = DAO::getResultset($link, "SELECT id, description FROM lookup_vacancy_regions ORDER BY description;");
		}

		if(DB_NAME == "am_ray_recruit")
		{
			$vacancy_job_type_dropdown = array(
				0=>array('P', 'Permanent',null,null),
				1=>array('C','Contract',null,null),
				2=>array('T','Temporary',null,null),
				3=>array('AL2','Apprenticeship Level 2',null,null),
				4=>array('AL3','Apprenticeship Level 3',null,null)
			);
		}
		else
		{
			$vacancy_job_type_dropdown = array(
				0=>array('P', 'Permanent',null,null),
				1=>array('C','Contract',null,null),
				2=>array('T','Temporary',null,null)
			);
		}
		$vacancy_job_hours_dropdown = array(
			0=>array('F', 'Full Time',null,null),
			1=>array('P','Part Time',null,null)
		);


		// Presentation
		include('tpl_edit_vacancy.php');
	}
}
?>