<?php
class EngagementHome extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link, $columns)	{
	}
	
	/**
	 * 
	 * Report on candidate status by region / screening score
	 * @param PDO $link
	 * @param unknown_type $columns
	 */
	public function render_report(PDO $link, $columns) {	
	}
	
	public static function homepageDashboard(PDO $link) {
		
		$organisation_status = array();
		
		$status_html = '';

		// employers
		// ---
		$org_status_sql = "SELECT org_status, org_status_comment, organisations_status.org_type, COUNT(organisations_status.org_type) as stat_type FROM organisations_status, organisations where organisations.id = organisations_status.org_id and organisations_status.org_type = 1 ";
		if ( isset($_SESSION['user']->department) ) {
			$org_status_sql .= 'AND organisations.region = "'.$_SESSION['user']->department.'" ';
		}
		$org_status_sql .= " GROUP BY organisations_status.org_type, org_status ORDER BY org_status, organisations_status.org_type";

		$st = $link->query($org_status_sql);
		if( $st ) {
			while ( $row = $st->fetch() ) {
				if ( !array_key_exists($row['org_status'], $organisation_status) ) {
					$organisation_status[$row['org_status']] = array('description' => $row['org_status_comment'], 'emp' => 0, 'pool' => 0);
				}
				$organisation_status[$row['org_status']]['emp']+=$row['stat_type'];
			}
		}

		// pool ones
		// ---
		$org_status_sql = "SELECT org_status, org_status_comment, organisations_status.org_type, COUNT(organisations_status.org_type) as stat_type FROM organisations_status where organisations_status.org_type = 2 ";
		$org_status_sql .= " GROUP BY organisations_status.org_type, org_status ORDER BY org_status, organisations_status.org_type";

		$st = $link->query($org_status_sql);
		if( $st ) {
			while ( $row = $st->fetch() ) {
				if ( !array_key_exists($row['org_status'], $organisation_status) ) {
					$organisation_status[$row['org_status']] = array('description' => $row['org_status_comment'], 'emp' => 0, 'pool' => 0);
				}
				$organisation_status[$row['org_status']]['pool']+=$row['stat_type'];
			}
		}

		foreach ( $organisation_status as $status_id => $status_data ) {
			$status_html .= '<tr><td>'.$status_data['description'].'</td><td><a href="do.php?_action=view_employers&amp;_reset=1&amp;ViewGroupEmployers_filter_crmstatus='.$status_id.'">'.$status_data['emp'].'</a></td><td><a href="do.php?_action=view_employers_pool&amp;_reset=1&amp;ViewEmployersPool_filter_status='.$status_id.'">'.$status_data['pool'].'</a></td><tr>';
		}
		return $status_html;
	}
}
?>
