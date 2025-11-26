<?php
class vacancy_detail implements IUnauthenticatedAction
{
	public function execute( PDO $link )
	{
		$vacancy_id = isset($_REQUEST['vacancy_id'])?$_REQUEST['vacancy_id']:'';
		if($vacancy_id == '')
			http_redirect('do.php?_action=search_vacancies');

		$vacancy = RecVacancy::loadFromDatabase($link, $vacancy_id);
		if(is_null($vacancy))
			http_redirect('do.php?_action=search_vacancies');

		if($vacancy->is_active == 0)
			http_redirect('do.php?_action=search_vacancies');
		$d1 = new Date($vacancy->closing_date);
		$today = new Date(date('Y-m-d'));
		if($d1->before($today))
			http_redirect('do.php?_action=search_vacancies');

		$type_ddl = DAO::getResultset($link, "SELECT id, description, NULL FROM lookup_sector_types WHERE id IN (1, 8) ORDER BY description ASC;");
		$sql_regions = <<<SQL
SELECT DISTINCT
  locations.`address_line_4`,
  locations.`address_line_4`,
  NULL
FROM
  locations
  INNER JOIN vacancies
    ON locations.id = vacancies.`location_id`
WHERE locations.`address_line_4` IS NOT NULL
ORDER BY locations.`address_line_4` ;
SQL;
		$region_ddl = DAO::getResultset($link, $sql_regions);

		$region = isset($_REQUEST['region'])?$_REQUEST['region']:'';
		$sector = isset($_REQUEST['sector'])?$_REQUEST['sector']:'';
		$keywords = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:'';

		if(true || SOURCE_LOCAL || DB_NAME == "am_sd_demo" || DB_NAME == "am_demo")
			require_once('tpl_vacancy_detail1.php');
		else
			require_once('tpl_vacancy_detail.php');
	}



}
?>
