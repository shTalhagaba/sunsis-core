<?php
class view_dars_requests_staff implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->add($link, "do.php?_action=view_dars_requests_staff", "Your Support Requests");

		$requester = $_SESSION['user']->id;

		$requests = DAO::getResultset($link, "SELECT * FROM dars_requests WHERE requester = '{$requester}' ORDER BY id DESC", DAO::FETCH_ASSOC);

		$cases_table = "";

		foreach($requests AS $request)
		{
			$request = DARSRequest::loadFromDatabase($link, $request['id']);

			$cases_table .= '<table id="cases" ><tbody>';
			$cases_table .= "<tr class='header-row'><td colspan='5' >" . $request->getStatusDescription() . "</td></tr>";
			$cases_table .= "<tr class='case-info' >";
			$cases_table .= "<td><strong>ID</strong>: " . $request->id . "</td>";
			$cases_table .= "<td><strong>Raised</strong>: " . Date::toMedium($request->created) . "</td>";
			$cases_table .= "<td><strong>Type</strong>: " . $request->getTypeDescription() . "</td>";
			$cases_table .= "<td><strong>Raised By</strong>: " . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$request->requester}'") . "</td>";
			$cases_table .= "</tr>";
			$p = "";
			if(!is_null($request->participants))
			{
				$p .= '<p><strong>Participants:</strong><br>';
				$participants = explode(',', $request->participants);
				foreach($participants AS $participant)
					$p .= $participant . ' - ' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$participant}'") . '<br>';
				$p .= '</p>';
			}
			$cases_table .= "<tr><td colspan='5'><strong>Details</strong>:<br/>" . htmlspecialchars((string)$request->details) . $p . "</td></tr>";

			$request_history = DAO::getResultset($link, "SELECT * FROM dars_history WHERE dars_id = '{$request->id}' ORDER BY created ASC", DAO::FETCH_ASSOC);
			$resolution = "";
			$latest_date = "";
			foreach($request_history AS $rh)
			{
				$latest_date = Date::to($rh['created'], Date::DATETIME);
				$resolution .= $latest_date . ' - ' . htmlspecialchars((string)$rh['notes']) . '<br>';
			}
			if($resolution != '')
			{
				$cases_table .= "<tr class='case-solution'>";
				$cases_table .= "<td colspan='2'><strong>Feedback</strong></td>";
				$cases_table .= "<td><strong>Recent Feedback Date</strong>: " . $latest_date . "</td>";
				$cases_table .= "<td colspan='2'><strong>Type</strong>: ".'asd'."</td>";
				$cases_table .= "</tr>";
				$cases_table .= "<tr class='case-info'>";
				$cases_table .= "<td colspan='5'><strong>Details</strong>:<br/>" . $resolution . "</td>";
				$cases_table .= "</tr>";
			}
			if ($request->status == '3')
			{
				$page_load_timestamp = time()-(60*60);
				$cases_table .= "<tr class='case-feedback c" . $request->id . "' style='background-color: #fff;' >";
				$cases_table .= "<td colspan='5' style='background-color: #fff;'><input type='text' name='case-comment' id='case-comment-".$request->id."' value='your comments...' style='width: 99%; color: #999;' onfocus='if (this.value == \"your comments...\") {this.value = \"\" ;}' onblur='if (this.value == \"\") {this.value = \"your comments...\";}'/></td>\n";
				$cases_table .= "</tr>";
				$cases_table .= "<tr>";
				$cases_table .= "<td colspan='5' style='background-color: #fff;'><input type='checkbox' id='close".$request->id."' name='close".$request->id."' value=1 style='width: auto!important;'>&nbsp;Please tick this box if you are happy with our solution and for the support request to be closed</td>\n";
				$cases_table .= "</td>";
				$cases_table .= "</tr>";
				$cases_table .= "<tr class='case-solution c" . $request->id . " ' style='background-color: #fff;' >";
				$cases_table .= "<td colspan='5'>";
				$cases_table .= "<button id='do.php?_action=edit_dars_request&amp;subaction=update&amp;close-case=".$request->id."&amp;case_number=".$request->id."&amp;ts=".$page_load_timestamp."&amp;case-comment=' class='change-status r".$request->id."' style='float:right;' >Send us your comments</button>\n";
				$cases_table .= "</td>\n";
				$cases_table .= "</tr>";
			}
			//$cases_table .= "<tr><td colspan='5' style='border-bottom: none;' >&nbsp;</td></tr>";
			$cases_table .= "</tbody></table><br>";
		}

		include('tpl_view_dars_requests_staff.php');
	}
}
