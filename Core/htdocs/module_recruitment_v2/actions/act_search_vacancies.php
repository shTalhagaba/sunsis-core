<?php
define('METRES_IN_A_MILE', 1609.344);

class search_vacancies implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$region = isset($_REQUEST['region'])?$_REQUEST['region']:'';
		$sector = isset($_REQUEST['sector'])?$_REQUEST['sector']:'';
		$keywords = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:'';

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

		if(true || SOURCE_LOCAL || DB_NAME == "am_sd_demo" || DB_NAME == "am_demo")
			include_once('tpl_search_vacancies1.php');
		else
			include_once('tpl_search_vacancies.php');
	}

	private function viewVacancies(PDO $link, $sector = '', $region = '', $keywords = '')
	{
		$returnHTML = "";

		$where_clause = " WHERE (1 = 1) ";

		if($keywords != '')
			$where_clause .= " AND ((vacancies.vacancy_title LIKE '%{$keywords}%') OR (vacancies.short_description LIKE '%{$keywords}%') OR (vacancies.full_description LIKE '%{$keywords}%') OR (locations.full_name LIKE '%{$keywords}%')) ";

		if($sector != '')
			$where_clause .= " AND (vacancies.sector = '{$sector}') ";

		if($region != '')
			$where_clause .= " AND (locations.address_line_4 = '{$region}') ";

		$where_clause .= " AND (vacancies.closing_date >= CURRENT_DATE()) ";
		$where_clause .= " AND (vacancies.is_active = '1') ";
		// Find out how many items are in the table
		$total = $link->query('SELECT COUNT(*) FROM vacancies INNER JOIN locations ON vacancies.location_id = locations.id ' . $where_clause)->fetchColumn();

		// How many items to list per page
		$limit = 5;

		// How many pages will there be
		$pages = ceil($total / $limit);

		// What page are we currently on?
		$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
			'options' => array(
				'default'   => 1,
				'min_range' => 1,
			),
		)));

		// Calculate the offset for the query
		$offset = ($page - 1)  * $limit;

		// Some information to display to the user
		$start = $offset + 1;
		$end = min(($offset + $limit), $total);

		// The "back" link
		$prevlink = ($page > 1) ? '<button style="width:30px;margin-right:12px;" onclick="window.location.href=\'do.php?_action=search_vacancies&page=1&sector='.$sector.'&region='.$region.'&keywords='.$keywords.'\';" title="First page"><img src="/images/view-navigation/first.gif" width="10" height="16" border="0" /></button> <button style="width:30px;" onclick="window.location.href=\'do.php?_action=search_vacancies&page=' . ($page - 1) . '&sector='.$sector.'&region='.$region.'&keywords='.$keywords.'\';" title="Previous page"><img src="/images/view-navigation/previous.gif" /></button>' : '<button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/first-grey.gif" width="10" height="16" border="0" /></button> <button style="width:30px" disabled="disabled"><img src="/images/view-navigation/previous-grey.gif" width="8" height="16" border="0" /></button>';

		// The "forward" link
		$nextlink = ($page < $pages) ? '<button style="width:30px;margin-right:12px;" onclick="window.location.href=\'do.php?_action=search_vacancies&page=' . ($page + 1) . '&sector='.$sector.'&region='.$region.'&keywords='.$keywords.'\';" title="Next page"><img src="/images/view-navigation/next.gif" width="10" height="16" border="0" /></button> <button style="width:30px;margin-right:12px;" onclick="window.location.href=\'do.php?_action=search_vacancies&page=' . $pages . '&sector='.$sector.'&region='.$region.'&keywords='.$keywords.'\';" title="Last page"><img src="/images/view-navigation/last.gif" /></button>' : '<button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/previous-grey.gif" /></button> <button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/last-grey.gif" /></button>';

		// Display the paging information
		$returnHTML .= '<div id="paging" align="center"><p>' . $prevlink . ' Page <b>' . $page . '</b> of <b>' . $pages . '</b> pages, displaying <b>' . $start . '-' . $end . '</b> of <b>' . $total . '</b> results ' . $nextlink . ' </p></div><br>';


		$sql = <<<SQL
SELECT
  employers.id AS employer_id,
  employers.legal_name,
  locations.id AS employer_location_id,
  locations.full_name,
  locations.postcode AS employer_location_postcode,
  CONCAT(
    COALESCE(full_name),
    ' (',
    COALESCE(`address_line_1`, ''),
    ' ',
    COALESCE(`address_line_2`, ''),
    ')'
  ) AS employer_location_address,
  vacancies.id AS vacancy_id,
  vacancies.`vacancy_title`,
  IF(1 = 1,'Active','Inactive') AS `vacancy_status`,
  'primary' AS primary_sector,
  locations.postcode,
  vacancies.`vacancy_reference`,
  vacancies.`no_of_positions`,
  vacancies.`short_description`,
  vacancies.`created`,
  vacancies.`wage`,
  vacancies.`wage_type`,
  vacancies.`wage_text`,
  vacancies.`closing_date`
FROM
  vacancies
  INNER JOIN organisations AS employers
    ON vacancies.employer_id = employers.id
  INNER JOIN locations
    ON vacancies.location_id = locations.id

$where_clause

ORDER BY vacancies.`vacancy_title`

LIMIT
     :limit
OFFSET
      :offset
SQL;

		//pre($sql);
		// Prepare the paged query
		$stmt = $link->prepare($sql);

		// Bind the query params
		$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();

		if ($stmt->rowCount() > 0)
		{
			// Define how we want to fetch the results
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$iterator = new IteratorIterator($stmt);

			// Display the results
			foreach ($iterator as $row)
			{
				$vacancy_title = $row['vacancy_title'];
				$vacancy_short_desc = $row['short_description'];
				$vacancy_ref = $row['vacancy_reference'];
				$wage_info = "";
				if($row['wage'] != '' && !is_null($row['wage']))
					$wage_info = $row['wage'];
				if($row['wage_type'] == 'Weekly')
					$wage_info = $row['wage'] . ' per week';
				else
					$wage_info = $row['wage'] . ' ' . $row['wage_text'];
				$vacancy_closing_date = Date::toShort($row['closing_date']);
				$vacancy_ref = $row['vacancy_reference'];
				$diff = Date::dateDiffInfo(Date::toMySQL($row['created']), date('Y-m-d'), false);
				$diff = (int)$diff['days'];
				if($diff == 0)
					$diff_text = 'today';
				elseif($diff == 1)
					$diff_text = '1 day ago';
				elseif($diff > 1)
					$diff_text = $diff . ' days ago';
				$returnHTML .= <<<HTML
<div class="panel">
	<div class="panel-heading">$vacancy_title</div>
	<div class="panel-body" >
		<p>$vacancy_short_desc</p>


HTML;
				$returnHTML .= '<div style="float: left;">';
				$returnHTML .= '<p><span onclick="applyForVacancy(\''.$row['vacancy_id'].'\');" class="recButton searchPanelButton">Apply</span></p>';
				$returnHTML .= '<p><span onclick="window.location.href=\'do.php?_action=vacancy_detail&vacancy_id='.$row['vacancy_id'].'\'" class="recButton searchPanelButton">View&nbsp;Detail</span></p>';
				$returnHTML .= '</div>';
				$returnHTML .= '<div style="float: left;margin: 10px;">';
				$returnHTML .= '<p><span style="font-weight: 800;">Location: </span>' . $row['employer_location_address'] . '</p>';
				$returnHTML .= '<p><span style="font-weight: 800;">Wage: £</span>' . $wage_info . '</p>';
				$returnHTML .= '</div>';

				$returnHTML .= <<<HTML
		<div class="clearfix"></div>
		<p style="font-size: smaller;">Published: $diff_text | Reference: $vacancy_ref | Closing Date: $vacancy_closing_date</p>
	</div>
</div>
HTML;
			}

		}
		else
		{
			return '<div style="padding: 10px; padding-bottom: 30px;"><p><span style="font-weight: 800;">Sorry - There are currently no vacancies matching your search.</span></p><br>
<p>Please check back again soon, as we are always adding new apprenticeship vacancies.</p><br/>
<p>
Alternatively, you can <a href="do.php?_action=application">register with us here</a> and we will contact you regarding relevant opportunities, or you can try searching again with different options.</p>
</div>
';
		}

		return $returnHTML;
	}

	private function viewVacanciesOptimized(PDO $link, $sector = '', $region = '', $keywords = '')
	{
		$returnHTML = "";

		$where_clause = " WHERE (1 = 1) ";

		if($keywords != '')
			$where_clause .= " AND ((vacancies.vacancy_title LIKE '%{$keywords}%') OR (vacancies.short_description LIKE '%{$keywords}%') OR (vacancies.full_description LIKE '%{$keywords}%') OR (locations.full_name LIKE '%{$keywords}%')) ";

		if($sector != '')
			$where_clause .= " AND (vacancies.sector = '{$sector}') ";

		if($region != '')
			$where_clause .= " AND (locations.address_line_4 = '{$region}') ";

		$where_clause .= " AND (vacancies.closing_date >= CURRENT_DATE()) ";
		$where_clause .= " AND (vacancies.is_active = '1') ";
		// Find out how many items are in the table
		$total = $link->query('SELECT COUNT(*) FROM vacancies INNER JOIN locations ON vacancies.location_id = locations.id ' . $where_clause)->fetchColumn();

		// How many items to list per page
		$limit = 5;

		// How many pages will there be
		$pages = ceil($total / $limit);

		// What page are we currently on?
		$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
			'options' => array(
				'default'   => 1,
				'min_range' => 1,
			),
		)));

		// Calculate the offset for the query
		$offset = ($page - 1)  * $limit;

		// Some information to display to the user
		$start = $offset + 1;
		$end = min(($offset + $limit), $total);

		// The "back" link
		$prevlink = ($page > 1) ? '<button style="width:30px;margin-right:12px;" onclick="window.location.href=\'do.php?_action=search_vacancies&page=1&sector='.$sector.'&region='.$region.'&keywords='.$keywords.'\';" title="First page"><img src="/images/view-navigation/first.gif" width="10" height="16" border="0" /></button> <button style="width:30px;" onclick="window.location.href=\'do.php?_action=search_vacancies&page=' . ($page - 1) . '&sector='.$sector.'&region='.$region.'&keywords='.$keywords.'\';" title="Previous page"><img src="/images/view-navigation/previous.gif" /></button>' : '<button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/first-grey.gif" width="10" height="16" border="0" /></button> <button style="width:30px" disabled="disabled"><img src="/images/view-navigation/previous-grey.gif" width="8" height="16" border="0" /></button>';

		// The "forward" link
		$nextlink = ($page < $pages) ? '<button style="width:30px;margin-right:12px;" onclick="window.location.href=\'do.php?_action=search_vacancies&page=' . ($page + 1) . '&sector='.$sector.'&region='.$region.'&keywords='.$keywords.'\';" title="Next page"><img src="/images/view-navigation/next.gif" width="10" height="16" border="0" /></button> <button style="width:30px;margin-right:12px;" onclick="window.location.href=\'do.php?_action=search_vacancies&page=' . $pages . '&sector='.$sector.'&region='.$region.'&keywords='.$keywords.'\';" title="Last page"><img src="/images/view-navigation/last.gif" /></button>' : '<button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/previous-grey.gif" /></button> <button style="width:30px;margin-right:12px;" disabled="disabled"><img src="/images/view-navigation/last-grey.gif" /></button>';

		// Display the paging information
		$returnHTML .= '<br><div id="paging" align="center"><p>' . $prevlink . ' Page <b>' . $page . '</b> of <b>' . $pages . '</b> pages, displaying <b>' . $start . '-' . $end . '</b> of <b>' . $total . '</b> results ' . $nextlink . ' </p></div><br>';


		$sql = <<<SQL
SELECT
  employers.id AS employer_id,
  employers.legal_name,
  locations.id AS employer_location_id,
  locations.full_name,
  locations.postcode AS employer_location_postcode,
  CONCAT(
    COALESCE(full_name),
    ' (',
    COALESCE(`address_line_1`, ''),
    ' ',
    COALESCE(`address_line_2`, ''),
    ')'
  ) AS employer_location_address,
  vacancies.id AS vacancy_id,
  vacancies.`vacancy_title`,
  IF(1 = 1,'Active','Inactive') AS `vacancy_status`,
  'primary' AS primary_sector,
  locations.postcode,
  vacancies.`vacancy_reference`,
  vacancies.`no_of_positions`,
  vacancies.`short_description`,
  vacancies.`created`,
  vacancies.`wage`,
  vacancies.`wage_type`,
  vacancies.`wage_text`,
  vacancies.`closing_date`
FROM
  vacancies
  INNER JOIN organisations AS employers
    ON vacancies.employer_id = employers.id
  INNER JOIN locations
    ON vacancies.location_id = locations.id

$where_clause

ORDER BY vacancies.`vacancy_title`

LIMIT
     :limit
OFFSET
      :offset
SQL;

		//pre($sql);
		// Prepare the paged query
		$stmt = $link->prepare($sql);

		// Bind the query params
		$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
		$stmt->execute();

		if ($stmt->rowCount() > 0)
		{
			// Define how we want to fetch the results
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$iterator = new IteratorIterator($stmt);

			// Display the results
			foreach ($iterator as $row)
			{
				$vacancy_title = $row['vacancy_title'];
				$vacancy_id = $row['vacancy_id'];
				$vacancy_short_desc = $row['short_description'];
				$vacancy_ref = $row['vacancy_reference'];
				$wage_info = "";
				if($row['wage'] != '' && !is_null($row['wage']))
					$wage_info = $row['wage'];
				if($row['wage_type'] == 'Weekly')
					$wage_info = $row['wage'] . ' per week';
				else
					$wage_info = $row['wage'] . ' ' . $row['wage_text'];
				$vacancy_closing_date = Date::toShort($row['closing_date']);
				$vacancy_ref = $row['vacancy_reference'];
				$diff = Date::dateDiffInfo(Date::toMySQL($row['created']), date('Y-m-d'), false);
				$diff = (int)$diff['days'];
				if($diff == 0)
					$diff_text = 'today';
				elseif($diff == 1)
					$diff_text = '1 day ago';
				elseif($diff > 1)
					$diff_text = $diff . ' days ago';
				$returnHTML .= <<<HTML
<div class="box" style="box-shadow: 5px 5px 20px 0px rgba(255,105,180,0.95);">
	<div class="box-header with-border"><h3 class="pull-left">$vacancy_title</h3>
		<div class="pull-right">
			<span class="btn btn-sm btn-info" onclick="window.location.href='do.php?_action=vacancy_detail&vacancy_id=$vacancy_id'"><i class="fa fa-folder-open"></i> View Detail</span>
			<span class="btn btn-sm btn-success" onclick="applyForVacancy('$vacancy_id');"><i class="fa fa-briefcase"></i> &nbsp; Apply</span>

		</div>
	</div>
	<div class="box-body" >
		<div class="well bg-pink">$vacancy_short_desc</div>
HTML;
				$returnHTML .= '<div style="margin-left: 15px;">';

				$returnHTML .= '<div class="row">';

				$returnHTML .= '<div class="col-sm-4"><dl class="dl-vertical">';
				$returnHTML .= '<dt>Location: </dt><dd>' . $row['employer_location_address'] . '</dd>';
				$returnHTML .= '<dt></dt><dd><a  href="http://maps.google.co.uk/maps?f=q&hl=en&q=' . urlencode($row['employer_location_postcode']) . '" target="_blank">' . $row['employer_location_postcode'] . '</a></dd>';
				$returnHTML .= '</dl></div>';

				$returnHTML .= '<div class="col-sm-4"><dl class="dl-vertical">';
				$returnHTML .= '<dt>Wage: </dt><dd>£' . $wage_info . '</dd>';
				$returnHTML .= '</dl></div>';

				$returnHTML .= '<div class="col-sm-4"><dl class="dl-vertical">';
				$returnHTML .= '<dt>Closing Date: </dt><dd>' . $vacancy_closing_date . '</dd>';
				$returnHTML .= '</dl></div>';

				$returnHTML .= '</div>';

				$returnHTML .= '</div>';
				$returnHTML .= <<<HTML
	</div>
	<div class="box-footer">
		<span class="text-bold">Reference:</span> $vacancy_ref
	</div>
</div>
HTML;
			}

		}
		else
		{
			return '<div style="padding: 10px; padding-bottom: 30px;"><p><span style="font-weight: 800;">Sorry - There are currently no vacancies matching your search.</span></p><br>
<p>Please check back again soon, as we are always adding new apprenticeship vacancies.</p><br/>
<p>
Alternatively, you can <a href="do.php?_action=application">register with us here</a> and we will contact you regarding relevant opportunities, or you can try searching again with different options.</p>
</div>
';
		}

		return $returnHTML;
	}
}