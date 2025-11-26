<?php
class view_enquiries implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		$view = VoltView::getViewFromSession('ViewEnquiries', 'ViewEnquiries'); /* @var $view VoltView */
		if (is_null($view)) {
			$view = $_SESSION['ViewEnquiries'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		if ($subaction == 'export_csv') {
			$this->export_csv($link, $view);
			exit;
		}

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=view_enquiries", "View Enquiries");

		include_once('tpl_view_enquiries.php');
	}

	private function buildView(PDO $link)
	{
		$sql = new SQLStatement("
SELECT
	crm_enquiries.*,
	CASE crm_enquiries.`company_type`
		WHEN 'pool' THEN (SELECT legal_name FROM pool WHERE pool.id = crm_enquiries.`company_id`)
		WHEN 'employer' THEN (SELECT legal_name FROM organisations WHERE organisations.id = crm_enquiries.`company_id`)
		ELSE ''
	END AS company,
	CASE crm_enquiries.`company_type`
		WHEN 'pool' THEN (SELECT CONCAT(COALESCE(contact_title, ''), ' ',COALESCE(contact_name, ''), ', ',COALESCE(job_title, ''), ' ') FROM pool_contact WHERE contact_id = crm_enquiries.`main_contact_id`)
		WHEN 'pool' THEN (SELECT CONCAT(COALESCE(contact_title, ''), ' ',COALESCE(contact_name, ''), ', ',COALESCE(job_title, ''), ' ') FROM organisation_contact WHERE contact_id = crm_enquiries.`main_contact_id`)
		ELSE ''
	END AS company_contact,
	CASE crm_enquiries.`company_type`
		WHEN 'pool' THEN (SELECT company_rating FROM pool WHERE id = crm_enquiries.`company_id`)
		WHEN 'employer' THEN (SELECT company_rating FROM organisations WHERE id = crm_enquiries.`company_id`)
		ELSE ''
	END AS company_rating
FROM
	crm_enquiries
;
		");

		$view = new VoltView('ViewEnquiries', $sql->__toString());

		$f = new VoltTextboxViewFilter('filter_id', "WHERE crm_enquiries.id = '%s%%'", null);
		$f->setDescriptionFormat("ID: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_title', "WHERE crm_enquiries.enquiry_title LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("Title: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_company', "HAVING company LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("Company: %s");
		$view->addFilter($f);

		$options = "SELECT DISTINCT users.id, CONCAT(users.firstnames,' ',users.surname), null, CONCAT('WHERE crm_enquiries.created_by=',users.id) FROM users INNER JOIN crm_enquiries ON users.id = crm_enquiries.created_by ORDER BY users.firstnames";
		$f = $_SESSION['user']->isAdmin() ?
			new VoltDropDownViewFilter('filter_owner', $options, '', true) :
			new VoltDropDownViewFilter('filter_owner', $options, $_SESSION['user']->id, true);
		$f->setDescriptionFormat("Status: %s");
		$view->addFilter($f);

		$options = array(
			0 => array('1', 'New', null, 'WHERE crm_enquiries.status = "1"'),
			1 => array('2', 'In Progress', null, 'WHERE crm_enquiries.status = "2"'),
			2 => array('3', 'Successful', null, 'WHERE crm_enquiries.status = "3"'),
			3 => array('4', 'Unsuccessful', null, 'WHERE crm_enquiries.status = "4"')
		);
		$f = new VoltDropDownViewFilter('filter_status', $options, null, true);
		$f->setDescriptionFormat("Status: %s");
		$view->addFilter($f);

		$options = [
			0 => [1, 'Enquiry ID (descending)', null, 'ORDER BY crm_enquiries.id DESC'],
			1 => [2, 'Enquiry ID (ascending)', null, 'ORDER BY crm_enquiries.id ASC'],
			2 => [3, 'Enquiry Title', null, 'ORDER BY crm_enquiries.enquiry_title ASC'],
		];
		$f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);

		$options = array(
			0 => array(20, 20, null, null),
			1 => array(50, 50, null, null),
			2 => array(100, 100, null, null),
			3 => array(200, 200, null, null),
			4 => array(300, 300, null, null),
			5 => array(400, 400, null, null),
			6 => array(500, 500, null, null),
			7 => array(0, 'No limit', null, null)
		);
		$f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		//pr($view->getSQLStatement()->__toString());
		$st = $link->query($view->getSQLStatement()->__toString());
		if ($st) {
			$columns = array();
			for ($i = 0; $i < $st->columnCount(); $i++) {
				$column = $st->getColumnMeta($i);
				$columns[] = $column['name'];
			}
			echo $view->getViewNavigatorExtra('', $view->getViewName());
			echo '<div align="center" ><table id="tblEnquiries" class="table table-bordered">';
			echo '<thead class="bg-gray"><tr>';
			echo '<th>Enquiry ID/Ref.</th><th>Title</th><th>Enquiry Created By</th><th>Enquiry Status</th><th>Company</th><th>Address</th><th>Contact Person</th>';
			echo '</tr></thead>';
			echo '<tbody>';
			$trophies = [
				'Gold' => '<i title="GOLD Employer" class="fa fa-trophy fa-2x" style="color: gold;"></i>',
				'Silver' => '<i title="Silver Employer" class="fa fa-trophy fa-2x" style="color: silver;"></i>',
				'Bronze' => '<i title="Bronze Employer" class="fa fa-trophy fa-2x" style="color: #cd7f32;"></i>',
			];
			while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
				echo HTML::viewrow_opening_tag('do.php?_action=read_enquiry&id=' . $row['id']);
				echo '<td>' . $row['id'] . '</td>';
				echo '<td>' . $row['enquiry_title'] . '</td>';
				echo '<td>' . Enquiry::getCreatedByName($link, $row['created_by']) . '</td>';
				if ($row['status'] == 2)
					echo '<td><span class="label label-primary">' . Enquiry::getListEnquiryStatus($row['status']) . '</span></td>';
				elseif ($row['status'] == 3)
					echo '<td><span class="label label-success">' . Enquiry::getListEnquiryStatus($row['status']) . '</span></td>';
				elseif ($row['status'] == 1)
					echo '<td><span class="label label-info">' . Enquiry::getListEnquiryStatus($row['status']) . '</span></td>';
				else
					echo '<td><span class="label label-danger">' . Enquiry::getListEnquiryStatus($row['status']) . '</span></td>';
				echo '<td>';
				echo '<span class="text-bold text-blue">' . $row['company'] . '</span><br>';
				// $rating_changes = DAO::getSingleColumn($link, "SELECT notes.note FROM notes WHERE parent_id = '{$row['id']}' AND parent_table = 'crm_enquiries' AND notes.subject = 'Enquiry Updated' AND note LIKE '%[Rating]%' ORDER BY id;");
				// $_i = 1;
				// foreach($rating_changes AS $change)
				// {
				//     $pieces = explode(' ', $change);
				//     $from = str_replace("'", '', $pieces[3]);
				//     $to = str_replace("'", '', $pieces[5]);
				//     if($_i == 1)
				//         echo "{$trophies[$from]} <i class='fa fa-arrow-circle-right'></i> {$trophies[$to]} ";
				//     else
				//         echo "<i class='fa fa-arrow-circle-right'></i> {$trophies[$to]} ";
				//     $_i++;
				// }
				// if(count($rating_changes) == 0)
				// {
				//     switch ($row['company_rating'])
				//     {
				//         case 'G':
				//             echo "{$trophies['Gold']} ";
				//             break;
				//         case 'S':
				//             echo "{$trophies['Silver']} ";
				//             break;
				//         case 'B':
				//             echo "{$trophies['Bronze']} ";
				//             break;
				//         default:
				//             break;
				//     }
				// }
				echo '</td>';
				$company_address = '';
				if ($row['company_type'] == 'pool') {
					$location = DAO::getObject($link, "SELECT * FROM pool_locations WHERE id = '{$row['company_location_id']}'");
				}
				if ($row['company_type'] == 'employer') {
					$location = DAO::getObject($link, "SELECT * FROM locations WHERE id = '{$row['company_location_id']}'");
				}
				echo '<td>';
				echo isset($location->address_line_1) ? $location->address_line_1 . '<br>' : '';
				echo isset($location->address_line_2) ? $location->address_line_2 . '<br>' : '';
				echo isset($location->address_line_3) ? $location->address_line_3 . '<br>' : '';
				echo isset($location->address_line_4) ? $location->address_line_4 . '<br>' : '';
				echo isset($location->postcode) ? $location->postcode . '<br>' : '';
				echo '</td>';
				echo '<td>';
				echo $row['company_contact'];
				echo '</td>';
				echo '</tr>';
			}
			echo '</tbody></table></div><p><br></p>';
			echo $view->getViewNavigatorExtra('', $view->getViewName());
		} else {
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}

	private function export_csv(PDO $link, VoltView $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if ($st) {

			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=Enquiries.csv');
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			echo "Enquiry ID/Ref.,Title,Enquiry Created By,Enquiry Status,Company,Rating,Contact Person,Contact Person Tel.,Contact Person Mob.,Contact Person Email,Address,Postcode,Converted";
			echo "\n";
			while ($row = $st->fetch(DAO::FETCH_ASSOC)) {
				echo $row['id'] . ",";
				echo HTML::csvSafe($row['enquiry_title']) . ",";
				echo HTML::csvSafe(Enquiry::getCreatedByName($link, $row['created_by'])) . ",";
				echo HTML::csvSafe(Enquiry::getListEnquiryStatus($row['status'])) . ",";
				echo HTML::csvSafe($row['company']) . ",";
				$rating_changes = DAO::getSingleColumn($link, "SELECT notes.note FROM notes WHERE parent_id = '{$row['id']}' AND parent_table = 'crm_enquiries' AND notes.subject = 'Enquiry Updated' AND note LIKE '%[Rating]%' ORDER BY id;");
				$_i = 1;
				foreach ($rating_changes as $change) {
					$pieces = explode(' ', $change);
					$from = str_replace("'", '', $pieces[3]);
					$to = str_replace("'", '', $pieces[5]);
					if ($_i == 1)
						echo "{$from} to {$to} ";
					else
						echo "to {$to} ";
					$_i++;
				}
				if (count($rating_changes) == 0) {
					switch ($row['company_rating']) {
						case 'G':
							echo "Gold";
							break;
						case 'S':
							echo "Silver";
							break;
						case 'B':
							echo "Bronze";
							break;
						default:
							break;
					}
				}
				echo ",";
				echo HTML::csvSafe(
					($row['contact_title'] ?? '') . " " .
						($row['first_name'] ?? '') . " " .
						($row['surname'] ?? '')
				) . ",";
				echo HTML::csvSafe($row['phone'] ?? '') . ",";
				echo HTML::csvSafe($row['mobile'] ?? '') . ",";
				echo HTML::csvSafe($row['email'] ?? '') . ",";
				echo HTML::csvSafe(
					($row['p_addr'] ?? '') . "; " .
						($row['p_addr_city'] ?? '') . "; " .
						($row['p_addr_region'] ?? '')
				) . ",";
				echo HTML::csvSafe($row['p_addr_postcode'] ?? '');
				echo $row['converted'] == 1 ? "Yes" . "," : "No" . ",";
				echo "\n";
			}
		} else {
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}
}
