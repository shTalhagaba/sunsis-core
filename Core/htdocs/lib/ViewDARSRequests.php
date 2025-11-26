<?php
class ViewDARSRequests extends View
{
	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{

			$sql = new SQLStatement("
SELECT
	dars_requests.id,
	CONCAT(requesters.firstnames, ' ', requesters.surname) AS requester_name,
	legal_name,
	dars_requests.requester,
	dars_requests.type,
	dars_requests.status,
	dars_requests.priority,
	dars_requests.created
FROM
	dars_requests
	LEFT JOIN users AS requesters ON dars_requests.requester = requesters.id
	LEFT JOIN organisations ON organisations.id = requesters.employer_id
	");
			if($_SESSION['user']->isAdmin())
			{
				// nothing
			}
			else
			{
				throw new UnauthorizedException();
			}

			// Create new view object
			$view = $_SESSION[$key] = new ViewDARSRequests();
			$view->setSQL($sql->__toString());

			// Record ID filter
			$f = new TextboxViewFilter('filter_request_id', "WHERE dars_requests.id LIKE '%s%%'", null);
			$f->setDescriptionFormat("Request ID: %s");
			$view->addFilter($f);

			$options = DARSRequest::getRequestTypes();
			foreach($options AS &$option)
			{
				$option[] = '';
				$option[] = 'WHERE dars_requests.type = ' . $option[0];
			}
			$f = new DropDownViewFilter('filter_type', $options, null, true);
			$f->setDescriptionFormat("Type: %s");
			$view->addFilter($f);

			$options = DARSRequest::getRequestStatusList();
			foreach($options AS &$option)
			{
				$option[] = '';
				$option[] = 'WHERE dars_requests.status = ' . $option[0];
			}
			$f = new DropDownViewFilter('filter_status', $options, null, true);
			$f->setDescriptionFormat("Status: %s");
			$view->addFilter($f);

			$options = DAO::getResultset($link, "SELECT users.id, CONCAT(firstnames, ' ', surname), legal_name, CONCAT('WHERE requester=',users.id) FROM users INNER JOIN organisations ON users.employer_id = organisations.id WHERE users.`web_access` = 1 AND users.`type` != 5");
			$f = new DropDownViewFilter('filter_requester', $options, null, true);
			$f->setDescriptionFormat("Status: %s");
			$view->addFilter($f);

			// creation date filter
			$format = "WHERE dars_requests.created >= '%s'";
			$f = new DateViewFilter('filter_from_created', $format, '');
			$f->setDescriptionFormat("From Creation Date: %s");
			$view->addFilter($f);

			$format = "WHERE dars_requests.created <= '%s'";
			$f = new DateViewFilter('filter_to_created', $format, '');
			$f->setDescriptionFormat("To Creation Date: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'Request ID (Ascending Order)', null, 'ORDER BY dars_requests.id ASC')
			,1=>array(2, 'Request ID (Descending Order)', null, 'ORDER BY dars_requests.dars_requests DESC')
			,2=>array(3, 'Creation Date (Ascending Order)', null, 'ORDER BY dars_requests.created ASC')
			,3=>array(4, 'Creation Date(Descending Order)', null, 'ORDER BY dars_requests.created DESC')
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
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="10">';
			echo '<thead>';
			echo '<tr>';
			echo '<th>ID</th><th>Type</th><th>Status</th><th>Priority</th><th>Requested By</th><th>Requester Org.</th><th>Creation Date</th><th>Participant(s)</th><th>Details</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				$objRequest = DARSRequest::loadFromDatabase($link, $row['id']);
				if(is_null($objRequest))
					continue;

				echo HTML::viewrow_opening_tag('/do.php?_action=reply_dars_request&id=' . $objRequest->id);
				echo '<td align="center">' . $objRequest->id . '</td>';
				echo '<td align="center">' . $objRequest->getTypeDescription() . '</td>';
				echo '<td align="center">' . $objRequest->getStatusDescription() . '</td>';
				echo '<td align="center">' . $objRequest->getPriorityDescription() . '</td>';
				echo '<td align="center">' . $row['requester_name'] . '</td>';
				echo '<td align="center">' . $row['legal_name'] . '</td>';
				echo '<td align="center">' . Date::to($objRequest->created, Date::DATETIME) . '</td>';
/*
				$participants = explode(',', $objRequest->participants);
				if(count($participants) > 0)
				{
					echo '<td align="center">';
					foreach($participants AS $participant)
						echo DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$participant}'") . '-<br>';
					echo '</td>';
				}
*/
				echo '<td align="center">' . HTML::cell($objRequest->participants) . '</td>';
				echo '<td align="center" style="font-size:smaller;">' . HTML::cell($objRequest->details) . '</td>';
				echo '</tr>';

				unset($objRequest);
			}
			echo '</tbody></table></div>';
			echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}



}
?>