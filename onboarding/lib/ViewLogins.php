<?php
class ViewLogins extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<SQL
SELECT
  DATE_FORMAT(logins.`date`, '%d/%m/%Y %H:%i:%s') AS date,
  logins.`username`,
  logins.`firstnames`,
  logins.`surname`,
  logins.`organisation_legal_name`,
  logins.`user_agent`,
  (SELECT description FROM lookup_user_types WHERE id = users.type) AS user_type
FROM
  logins
  LEFT JOIN users
    ON logins.`username` = users.username
  LEFT JOIN organisations
    ON users.employer_id = organisations.`id`
 ;
SQL;

			$view = $_SESSION[$key] = new ViewLogins();
			$view->setSQL($sql);
			
			// Add view filters
			$format = "WHERE `date` > SUBDATE('%s', 1)";
			$f = new DateViewFilter('start_date', $format, Date::toShort("today - 1 month"));
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);

			$format = "WHERE `date` < ADDDATE('%s', 1)";
			$f = new DateViewFilter('end_date', $format, date('d/m/Y'));
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_username', "WHERE logins.username LIKE '%s%%'", null);
			$f->setDescriptionFormat("Username: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE logins.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$options = <<<OPTIONS
SELECT
  organisations.id,
  CONCAT(legal_name, ' (', lookup_org_type.`org_type`, ')'),
  LEFT(legal_name, 1),
  CONCAT('WHERE organisations.id=', organisations.id)
FROM
  organisations
  INNER JOIN lookup_org_type ON organisations.`organisation_type` = lookup_org_type.id
WHERE organisations.`organisation_type` NOT IN (22, 33)
AND organisations.id IN (SELECT DISTINCT users.employer_id FROM users WHERE users.type != 5)
ORDER BY legal_name ;
OPTIONS;

			$f = new DropDownViewFilter('filter_organisation', $options, null, true);
			$f->setDescriptionFormat("Organisation: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(1, 'Date (desc)', null, 'ORDER BY logins.`date` DESC'),
				1=>array(2, 'Date (asc)', null, 'ORDER BY logins.`date` ASC'),
				2=>array(3, 'Username (asc), Date (desc)', null, 'ORDER BY username ASC, logins.`date` DESC'),
				3=>array(4, 'Username (asc), Date (asc)', null, 'ORDER BY username ASC, logins.`date` ASC'),
				4=>array(5, 'Username (desc), Date (desc)', null, 'ORDER BY username DESC, logins.`date` DESC'),
				5=>array(6, 'Username (desc), Date (asc)', null, 'ORDER BY username DESC, logins.`date` ASC')
				);
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		$st = $link->query($this->getSQL());
		if($st) 
		{
			echo $this->getViewNavigator();
			echo '<div class="table-responsive resultset"><table id="tblLogins" class="table table-bordered">';
			echo <<<HEREDOC
	<thead>
	<tr class="topRow"><th colspan="3">User Details</th><th colspan="4">Login Details</th></tr>
	<tr>
		<th class="bottomRow">Username & Name</th>
		<th class="bottomRow">Organisation</th>
		<th class="bottomRow">User Type</th>
		<th class="bottomRow">Browser Name</th>
		<th class="bottomRow">Platform</th>
		<th class="bottomRow">IP</th>
		<th class="bottomRow">Login At</th>
	</tr>
	</thead>
HEREDOC;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				$browser = $this->getBrowser($row['user_agent']);
				echo '<tr>';
				echo '<td>';
				echo '<p><code>' . HTML::cell($row['username']) . '</code></p>';
				echo $row['surname'] . ', ' . $row['firstnames'];
				echo '</td>';
				echo '<td>' . HTML::cell($row['organisation_legal_name']) . '</td>';
				echo '<td>' . HTML::cell($row['user_type']) . '</td>';
				echo '<td><span class="' . $browser['icon'] .'"></span>&nbsp;' . $browser['name'] . '&nbsp;' . $browser['version'] . '</td>';
				echo '<td><span class="fa fa-' . $browser['platform'] . '"></span>&nbsp;' . $browser['platform'] . '</td>';
				echo '<td><span class="fa fa-map-marker" title="Click to see IP geo location" style="cursor: pointer;"></span> <span>' . $browser['ip'] . '</span></td>';
				echo '<td>' . Date::to($row['date'], Date::DATETIME) . '</td>';
				echo '</tr>';
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}

	public static function getBrowser($u_agent = '')
	{

		$u_agent = $u_agent != '' ? $u_agent : $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
		$font_awesome_icon = "fa fa-window-maximize";
		$ip = "";

		// First get the platform?
		if (preg_match('/linux/i', $u_agent))
		{
			$platform = 'linux';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent))
		{
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent))
		{
			$platform = 'windows';
		}

		$ub = '';
		// Next get the name of the user agent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
			$font_awesome_icon = "fa fa-internet-explorer";

		}
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
			$font_awesome_icon = "fa fa-firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
			$font_awesome_icon = "fa fa-chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
			$font_awesome_icon = "fa fa-safari";
		}
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
			$font_awesome_icon = "fa fa-opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
			$font_awesome_icon = "fa fa-opera";
		}

		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches))
		{
			// we have no matching number just continue
		}

		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1)
		{
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub))
			{
				$version= $matches['version'][0];
			}
			else
			{
				$version= $matches['version'][1];
			}
		}
		else
		{
			$version= $matches['version'][0];
		}

		// check if we have a number
		if ($version == null || $version == "")
		{
			$version="?";
		}

		// now get the ip address as it is stored in the user agent string
		if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $u_agent, $ip_match))
		{
			$ip = isset($ip_match[0]) ? $ip_match[0] : "";
		}


		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern,
			'icon' => $font_awesome_icon,
			'ip' => $ip
		);


	}
}
?>