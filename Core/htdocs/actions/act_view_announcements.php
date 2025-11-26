<?php
class view_announcements implements IAction
{
	public function execute(PDO $link)
	{
		$view = View::getViewFromSession('viewAnnouncements', 'view_announcements'); /* @var $view View */
		if(is_null($view))
		{
			$view = $_SESSION['viewAnnouncements'] = $this->buildView($link);
		}
		
		// $view->refreshFilters($link, $_REQUEST);
		// $this->createTempTable($link, $view);
		
		// check this
		$rs = $view->refresh($link, $_REQUEST);
		
		$_SESSION['bc']->add($link, "do.php?_action=view_announcements", "View Announcements");
		
		require('tpl_view_announcements.php');
	}

	
	private function buildView(PDO $link)
	{
		// Restrict user to posts from their own organisation
		//if($_SESSION['org']->id){
		//	$where = " AND announcements.organisations_id = ".$_SESSION['org']->id;
		//} else {
		//	$where = " AND announcements.organisations_id IS NULL ";
		//}
		
		$sql = <<<HEREDOC
SELECT
	announcements.*,
	'Perspective' AS `org_legal_name`,
	users.username AS `user_username`,
	users.firstnames AS `user_firstnames`,
	users.surname AS `user_surname`,
    announcements.users_id as author
FROM
	announcements
	LEFT OUTER JOIN users
		ON announcements.users_id = users.username
WHERE
	announcements.parent_id IS NULL
GROUP BY
	announcements.publication_date DESC,announcements.id DESC


HEREDOC;

		
		// review clm to sunesis methods
		$view = new View("view_announcements", $sql);
		$view->setSQL($sql);
			
		$format = 'WHERE (announcements.publication_date >= \'%1$s\') OR (announcements.modified >= \'%1$s 00:00:00\') ';
		$f = new DateViewFilter('start_date', $format, null);
		$f->setDescriptionFormat("Activity since: %s");
		$view->addFilter($f);
		
		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(0,'No limit',null,null));
		$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);
		
		$options = array(
			array(1, 'Publication date (asc)', null, 'ORDER BY publication_date ASC'),
			array(2, 'Publication date (desc)', null, 'ORDER BY publication_date DESC'),
			
			array(3, 'Modified date (asc)', null, 'ORDER BY modified ASC'),
			array(4, 'Modified date (desc)', null, 'ORDER BY modified DESC'));
		$f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 2, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);
		
		return $view;
	}
	
	
	private function createTempTable(PDO $link, View $view)
	{
/*		$where_date = "";
		$date_filter = Date::toMySQL($view->getFilterValue("start_date"));
		if($date_filter)
		{
			$where_date = " AND (publication_date >= '$date_filter' OR modified >= '$date_filter') ";
		}
		
	$sql = <<<HEREDOC
CREATE TEMPORARY TABLE tmp_comments (announcements_id BIGINT UNSIGNED NOT NULL, num_comments INTEGER NOT NULL, most_recent_comment DATE NOT NULL,
PRIMARY KEY(announcements_id)) ENGINE 'MEMORY'
SELECT
	parent_id AS `announcements_id`,
	COUNT(*) AS `num_comments`,
	MAX(modified) AS `most_recent_comment`
FROM
	announcements
WHERE
	parent_id IS NOT NULL
	$where_date
GROUP BY
	parent_id
HEREDOC;
		DAO::execute($link, $sql);
*/
	}
	

	
	private function renderView(PDO $link, View $view, $rs)
	{
		echo <<<HEREDOC
	<table class="resultset" border="0" cellspacing="0" cellpadding="6">
	<thead>
	<tr>
		<th rowspan="2">&nbsp;</th>
		<th colspan="2" class="topRow">Dates</th>
		<th rowspan="2">Title</th>
		<th rowspan="2">Subtitle</th>
		<th rowspan="2" class="topRow">Author</th>
		<th rowspan="2">Last<br/>Modified</th>
	</tr>
	<tr>
		<th>Publication</th>
		<th>Expiry</th>
	
	</tr>
	</thead>
	<tbody>		
HEREDOC;

		//Perform query
		$st = $link->query($view->getSQL());	
			
		//check result
		if($st) 
		{
			while( $row = $st->fetch() ) 
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_announcement&id=' . $row['id']);
				
				echo '<td align="center"><img src="/images/file.png" border="0" title="#'.$row['id'].'"/></td>';
				echo '<td>'.HTML::cell(Date::toShort($row['publication_date'])).'</td>';
				echo '<td>'.HTML::cell(Date::toShort($row['expiry_date'])).'</td>';
				echo '<td align="left">'.$row['title'].'</td>';
				echo '<td align="left">'.$row['subtitle'].'</td>';
				echo '<td align="left">'.$row['author'].'</td>';
				//echo '<td align="left">'.$row['org_legal_name'].'</td>';
				echo '<td>'.Date::toShort($row['modified']).'</td>';
				echo '</tr>';
			}
		}
		
		echo '</tbody></table>';
	}

	
	
	
	
	/*
	private function getPartnershipFilter(PDO $link, View $view)
	{
		$user = $_SESSION['user'];
		$org = $_SESSION['org'];
		
		switch($org->org_type_id)
		{
			case ORG_SYSADMIN:
				$options = $this->getAdminOrgFilterOptions($link);
				array_unshift($options, array(0, "All", null, "WHERE announcements.organisations_id IS NOT NULL"));
				array_unshift($options, array(-1, "None (Perspective)", null, "WHERE announcements.organisations_id IS NULL"));
				$f = new DropDownViewFilter("filter_partnership", $options, -1, false);
				$f->setDescriptionFormat("Partnership: %s");
				return $f;	
				break;
				
			default:
				break;
		}
		
	}
	
	
	private function getAdminOrgFilterOptions(PDO $link)
	{
		$key = $_SERVER['SERVER_NAME'].' announcements partnership dropdown';
		$dropdown = "";
		
		if(function_exists("xcache_get")){
			$dropdown = xcache_get($key);
		}
		
		if(!$dropdown)
		{
			$sql = <<<HEREDOC
CREATE TEMPORARY TABLE IF NOT EXISTS tmp_top_partnerships (id BIGINT UNSIGNED NOT NULL, PRIMARY KEY (id)) ENGINE 'MEMORY'
SELECT DISTINCT
	t1.partnership_id AS `id`
FROM
	partnership_org_lookup AS t1 LEFT OUTER JOIN partnership_org_lookup AS t2
		ON t1.partnership_id = t2.`org_id`
WHERE
	t2.`org_id` IS NULL;				
HEREDOC;
			DAO::execute($link, $sql);
			
			$sql = <<<HEREDOC
CREATE TEMPORARY TABLE IF NOT EXISTS tmp_partnerships (top_partnership_id BIGINT UNSIGNED NOT NULL, sub_partnership_id BIGINT UNSIGNED NOT NULL, PRIMARY KEY (top_partnership_id, sub_partnership_id)) ENGINE 'MEMORY'
SELECT
	tmp_top_partnerships.id AS `top_partnership_id`,
	acl_partnership_orgs.org_id AS `sub_partnership_id`
FROM
	tmp_top_partnerships INNER JOIN acl_partnership_orgs INNER JOIN organisations
		ON tmp_top_partnerships.id = acl_partnership_orgs.`partnership_id`
		AND acl_partnership_orgs.`org_id` = organisations.`id`
WHERE
	organisations.`org_type_id` = 4;			
HEREDOC;
			DAO::execute($link, $sql);
			
			$sql = <<<HEREDOC
SELECT
	children.id AS `value`,
	children.legal_name AS `label`,
	parents.legal_name AS `group`,
	CONCAT('WHERE announcements.organisations_id IN (',GROUP_CONCAT(acl_related_orgs.`related_org_id`),') OR announcements.organisations_id = ', tmp_partnerships.sub_partnership_id) AS `where`
FROM
	tmp_partnerships INNER JOIN acl_related_orgs INNER JOIN organisations AS parents INNER JOIN organisations AS children
		ON tmp_partnerships.sub_partnership_id = acl_related_orgs.`org_id`
		AND tmp_partnerships.top_partnership_id = parents.`id`
		AND tmp_partnerships.sub_partnership_id = children.`id`
WHERE
	children.org_type_id = 4
GROUP BY
	tmp_partnerships.top_partnership_id, tmp_partnerships.sub_partnership_id
ORDER BY
	parents.legal_name, children.legal_name	
HEREDOC;
			$dropdown = DAO::getResultset($link, $sql);
			if(function_exists('xcache_set')){
				//xcache_set($key, $dropdown, 600);
			}		
		}
		
		return $dropdown;
	}
	*/
}
?>
