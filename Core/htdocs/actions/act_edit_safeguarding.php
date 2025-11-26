<?php
class edit_safeguarding implements IAction
{
	public function execute(PDO $link)
	{
		if( !in_array($_SESSION['user']->username, ['dparks', 'hgibson1', 'tellis12', 'mattward1', 'lajameson']) )
		{
			throw new Exception('You are not authorised to access this screen.');
		}

		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';

		if( $tr_id == '' )
		{
			throw new Exception("Missing querystring argument id.");
		}

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
		{
			throw new Exception("Invalid tr id");
		}

		if($id == '')
		{
			$safeguarding = new Safeguarding();
			$safeguarding->tr_id = $tr->id;
		}
		else
		{
			$safeguarding = Safeguarding::loadFromDatabase($link, $id);
		}

		$_SESSION['bc']->add($link, "do.php?_action=edit_safeguarding&id={$id}&tr_id={$tr_id}", "Add/Edit Safeguarding");


		include('tpl_edit_safeguarding.php');
	}

	private function renderOtherIncidents(PDO $link, Safeguarding $safeguarding)
	{
		$records = DAO::getResultset($link, "SELECT * FROM safeguarding WHERE safeguarding.tr_id = '{$safeguarding->tr_id}' AND safeguarding.id != '{$safeguarding->id}' ORDER BY created ", DAO::FETCH_ASSOC);
		if(count($records) == 0)
		{
			echo '<i class="fa fa-info-circle"></i> No other records.';
		}
		else
		{
			$incident_categories = Safeguarding::getListIncidentCategory();
			$related_users = DAO::getSingleColumn($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE FIND_IN_SET(id, {$row['related_users']}) ");

			echo '<div class="row">';
			foreach($records AS $row)
			{
				$category = isset($incident_categories[$row['incident_category']]) ? $incident_categories[$row['incident_category']] : $row['incident_category'];
				echo '<div class="col-sm-12">';
				echo '<span class="text-bold">Incident Date & Time: </span>' . Date::toShort($row['incident_date']) . ' ' . $row['incident_time'] . ' | ';
				echo '<span class="text-bold">Incident Category: </span>' . $category . ' | ';
				echo '<span class="text-bold">Related Users: </span>' . implode(",", $related_users) . ' | ';
				echo '<span class="text-bold">Agencies Contacted: </span>' . $row['agencies_contacted'] . ' | ';
				echo '<span class="text-bold">Created By: </span>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$row['created_by']}'") . ' | ';
				echo '<span class="text-bold">Created at: </span>' . Date::to($row['created'], Date::DATETIME) . ' | ';
				echo '<span class="text-bold">Last modified at: </span>' . Date::to($row['modified'], Date::DATETIME) . '<br>';
				echo '<span class="text-bold">Detail: </span><br><small>' . nl2br((string) $row['incident_detail'] ?: '') . '</small>';
				echo '</div> ';
				echo '<hr> ';
			}

			echo '</div> ';
		}
	}
}
?>