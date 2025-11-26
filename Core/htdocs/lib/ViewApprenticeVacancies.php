<?php
class ViewApprenticeVacancies extends View
{

	public static function getInstance($link, $id)
	{
		$key = 'view_'.__CLASS__.$id;
		if(!isset($_SESSION[$key]))
		{
			$where = ' WHERE employer_id = "' . $id . '" ';

			// Create new view object
			if(DB_NAME == "am_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams")
			{
				$sql = <<<HEREDOC
				SELECT
	vacancies.*, IF(vacancies.active = 1,'active',IF(vacancies.active = 0, 'Inactive', '')) AS vac_active_status, lookup_vacancy_type.description AS vac_type,
	lookup_vacancy_status.description AS vac_status
FROM
	vacancies
INNER JOIN lookup_vacancy_status ON vacancies.status = lookup_vacancy_status.id
LEFT JOIN lookup_vacancy_type ON lookup_vacancy_type.id = vacancies.type
$where

HEREDOC;
			}
			else
			{
				$sql = <<<HEREDOC
SELECT
	vacancies.*, IF(vacancies.active = 1,'active','closed') as vac_status, lookup_vacancy_type.description as vac_type
FROM
	vacancies 
INNER JOIN lookup_vacancy_status on vacancies.status = lookup_vacancy_status.id
LEFT JOIN lookup_vacancy_type on lookup_vacancy_type.id = vacancies.type

$where
HEREDOC;

			}

			$view = $_SESSION[$key] = new ViewApprenticeVacancies();
			$view->setSQL($sql);
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 100, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Type (asc), Level (asc)', null, 'ORDER BY code'),
				1=>array(2, 'Type (desc), Level (desc)', null, 'ORDER BY code DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
//		if(SOURCE_BLYTHE_VALLEY)pre($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator('left');
			if(DB_NAME == "am_demo" || DB_NAME=="am_baltic" || DB_NAME=="ams")
			{
				echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
				echo '<tr><th>&nbsp;</th><th>Code</th><th>Job Title</th><th>No. of Vacancies</th><th>Max Submissions</th><th>Vacancy Status</th><th>Active/Inactive</th><th>Vacancy Type</th><th>Postcode</th><th>Vacancy URL</th></tr>';
				echo '<tbody>';
				while($loc = $st->fetch())
				{
					echo HTML::viewrow_opening_tag('do.php?_action=read_vacancy&id=' . $loc['id']);
					echo '<td><a href="do.php?_action=read_location&id=' . $loc['id'] . '"><img src="/images/vacancy.jpg" border="0" /></a></td>';
					echo '<td>' . HTML::cell($loc['code']) . '</td>';
					echo '<td>' . HTML::cell($loc['job_title']) . '</td>';
					echo '<td align="center">' . HTML::cell($loc['no_of_vacancies']) . '</td>';
					echo '<td align="center">' . HTML::cell($loc['max_submissions']) . '</td>';
					echo '<td>' . HTML::cell($loc['vac_status']) . '</td>';
					echo '<td>' . HTML::cell($loc['vac_active_status']) . '</td>';
					echo '<td>' . HTML::cell($loc['vac_type']) . '</td>';
					echo '<td>' . HTML::cell($loc['postcode']) . '</td>';
					echo '<td><a href="do.php?_action=vacancy_detail&id='. $loc['id'] . '">URL (https://' . substr(DB_NAME, 3) . '.sunesis.uk.net/do.php?_action=vacancy_detail&id='.$loc['id'].')</a></td>';
					echo '</tr>';
				}
				echo '</tbody></table>';
			}
			else
			{
				echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
				echo '<tr><th>&nbsp;</th><th>Code</th><th>Job Title</th><th>No. of Vacancies</th><th>Max Submissions</th><th>Status</th><th>Vacancy Type</th><th>Postcode</th></tr>';
				echo '<tbody>';
				while($loc = $st->fetch())
				{
					echo HTML::viewrow_opening_tag('do.php?_action=read_vacancy&id=' . $loc['id']);
					echo '<td><a href="do.php?_action=read_location&id=' . $loc['id'] . '"><img src="/images/vacancy.jpg" border="0" /></a></td>';
					echo '<td>' . HTML::cell($loc['code']) . '</td>';
					echo '<td>' . HTML::cell($loc['job_title']) . '</td>';
					echo '<td align="center">' . HTML::cell($loc['no_of_vacancies']) . '</td>';
					echo '<td align="center">' . HTML::cell($loc['max_submissions']) . '</td>';
					echo '<td>' . HTML::cell($loc['vac_status']) . '</td>';
					echo '<td>' . HTML::cell($loc['vac_type']) . '</td>';
					echo '<td>' . HTML::cell($loc['postcode']) . '</td>';
					echo '</tr>';
				}
				echo '</tbody></table>';
			}
			echo $this->getViewNavigator('left');
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>