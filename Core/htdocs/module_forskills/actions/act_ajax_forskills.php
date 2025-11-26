<?php
class ajax_forskills extends ActionController
{
	public function getUrl(PDO $link)
	{
		$forskills_api_key = SystemConfig::getEntityValue($link, 'forskills_api_key');
		$forskills_api_token = SystemConfig::getEntityValue($link, 'forskills_api_token');

		//$forskills_api_key = 'jzLYpXq4T8Py8RKZvsAp';
		//$forskills_api_token = '9neQqozuzgqbsAo3Yunh7zmVlQ1pOFLDJBDd2oEU';


		if($forskills_api_key == '' || $forskills_api_token == '')
			throw new Exception('Forskills / Skills Forward integration is not switched on for this site.');

		return "https://api.forskills.co.uk/?apiKey={$forskills_api_key}&apiToken={$forskills_api_token}";
	}

	public function indexAction( PDO $link )
	{

	}

	public function findSimilarUsersAction(PDO $link)
	{
		$filters = [];
		$filterUsername = [];
		$filterStudentRef = [];
		$filterEmail = [];
		$filterNINumber = [];

		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
		if($username != '')
		{
			foreach(explode(',', $username) AS $val)
				$filterUsername[] = trim($val);
		}
		if(count($filterUsername) > 0)
			$filters['username'] = $filterUsername;

		$studentRef = isset($_REQUEST['studentRef']) ? $_REQUEST['studentRef'] : '';
		if($studentRef != '')
		{
			foreach(explode(',', $studentRef) AS $val)
				$filterStudentRef[] = trim($val);
		}
		if(count($filterStudentRef) > 0)
			$filters['studentRef'] = $filterStudentRef;

		$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
		if($email != '')
		{
			foreach(explode(',', $email) AS $val)
				$filterEmail[] = trim($val);
		}
		if(count($filterEmail) > 0)
			$filters['email'] = $filterEmail;

		$ninumber = isset($_REQUEST['ninumber']) ? $_REQUEST['ninumber'] : '';
		if($ninumber != '')
		{
			foreach(explode(',', $ninumber) AS $val)
				$filterNINumber[] = trim($val);
		}
		if(count($filterNINumber) > 0)
			$filters['ninumber'] = $filterNINumber;

		$result = Forskills::getUserDetails($this->getUrl($link), $filters);

		echo $result;
	}

	public function registerUserAction(PDO $link)
	{
		$data = [];
		$data['studentRef'] = isset($_REQUEST['studentRef']) ? $_REQUEST['studentRef'] : '';
		$data['email'] = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
		$data['firstName'] = isset($_REQUEST['firstName']) ? $_REQUEST['firstName'] : '';
		$data['lastName'] = isset($_REQUEST['lastName']) ? $_REQUEST['lastName'] : '';
		$data['dob'] = isset($_REQUEST['dob']) ? $_REQUEST['dob'] : '';
		$data['gender'] = isset($_REQUEST['gender']) ? $_REQUEST['gender'] : '';
		$data['password'] = isset($_REQUEST['password']) ? ['password' => md5($_REQUEST['password']), 'format' => 'md5'] : '';

//		throw new Exception(json_encode($data));

		$result = Forskills::registerUser($this->getUrl($link), $data);

		echo $result;
	}

	public function getAllResultsForUserAction(PDO $link)
	{
		$filters = [];
		$filterIdUserInstitution = [];

		$idUserInstitution = isset($_REQUEST['idUserInstitution']) ? $_REQUEST['idUserInstitution'] : '';
		if($idUserInstitution == '')
		{
			echo 'idUserInstitution (Unique id of the user linked to your institution within ForSkills) is mandatory for this action.';
			return;
		}
		if(strpos($idUserInstitution, ',') != false)
		{
			echo 'You can only use one id for this action.';
			return;
		}

		$filters['idUserInstitution'] = $idUserInstitution;

		$result = Forskills::getAllResultsForUser($this->getUrl($link), $filters);
		$result = json_decode($result);

		$api_result_messages = '';
		if(!isset($result->code))
		{
			echo 'Connection to ForSkills unsuccessful, operation aborted.';
			return;
		}
		elseif($result->code != '200')
		{
			foreach($result->errors AS $error)
				$api_result_messages .= $error . '. ';
		}
		else
		{
			foreach($result->messages AS $message)
				$api_result_messages .= $message . '. ';
		}


		$html = <<<HTML
<div class="row">
	<div class="col-sm-12">
		<span class="text-bold">API Result</span><br>
		<span class="text-bold">Code: </span>$result->code<br>
		<span class="text-bold">Message: </span>$api_result_messages
	</div>
</div>
HTML;

		$rows = '';
		if(isset($result->data) && is_object($result->data) && is_array($result->data->results) && count($result->data->results) > 0)
		{
			foreach($result->data->results AS $row)
			{
				$tt = gmdate('H:i:s', $row->timetaken);
				$rows .= <<<HTML
				<tr>
					<td>$row->idUserDiagnostic</td>
					<td>$row->idAssessmentUser</td>
					<td>$row->assessmentTitle</td>
					<td>$row->level</td>
					<td>$row->assessmentArea</td>
					<td>$row->startdt to $row->finishdt</td>
					<td>$tt</td>
					<td>$row->subjectTitle</td>
					<td>$row->assessmentType</td>
				</tr>
HTML;
			}
			$html .= <<<HTML
<div class="row">
	<div class="col-sm-12">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>ID User Diagnostic</th>
					<th>ID Assessment User</th>
					<th>Assessment Title</th>
					<th>Level</th>
					<th>Assessment Area</th>
					<th>Duration</th>
					<th>Time Taken</th>
					<th>Subject Title</th>
					<th>Assessment Type</th>
				</tr>
			</thead>
			<tbody>
				$rows
			</tbody>
		</table>
	</div>
</div>
HTML;
		}
		else
		{
			$html .= 'No records found.';
		}
		echo $html;
	}

	public function getUserDetailsAction(PDO $link)
	{
		$filters = [];
		$filterUsername = [];
		$filterStudentRef = [];
		$filterEmail = [];
		$filterNINumber = [];

		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
		if($username != '')
		{
			foreach(explode(',', $username) AS $val)
				$filterUsername[] = trim($val);
		}
		if(count($filterUsername) > 0)
			$filters['username'] = $filterUsername;

		$studentRef = isset($_REQUEST['studentRef']) ? $_REQUEST['studentRef'] : '';
		if($studentRef != '')
		{
			foreach(explode(',', $studentRef) AS $val)
				$filterStudentRef[] = trim($val);
		}
		if(count($filterStudentRef) > 0)
			$filters['studentRef'] = $filterStudentRef;

		$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
		if($email != '')
		{
			foreach(explode(',', $email) AS $val)
				$filterEmail[] = trim($val);
		}
		if(count($filterEmail) > 0)
			$filters['email'] = $filterEmail;

		$ninumber = isset($_REQUEST['ninumber']) ? $_REQUEST['ninumber'] : '';
		if($ninumber != '')
		{
			foreach(explode(',', $ninumber) AS $val)
				$filterNINumber[] = trim($val);
		}
		if(count($filterNINumber) > 0)
			$filters['ninumber'] = $filterNINumber;

		$result = Forskills::getUserDetails($this->getUrl($link), $filters);
		$result = json_decode($result);

		if(!isset($result->code))
		{
			echo 'Connection to ForSkills unsuccessful, operation aborted.';
			return;
		}
		elseif($result->code == '404')
		{
			echo 'No records found matching filter parameters.';
			return;
		}

		$api_result_messages = '';
		foreach($result->messages AS $message)
			$api_result_messages .= $message . '. ';

		$api_data = '';
		if(is_array($result->data) && count($result->data) > 0)
		{
			foreach($result->data AS $record)
			{
				if($record->roleTitle != "Learner")
					continue;
				$api_data .= "<tr>";
				$api_data .= "<td><span class='btn btn-xs btn-info' title='View Assessment' onclick='getAssessmentByUsername(\"{$record->username}\");'><i class='fa fa-eye'></i></span> </td>";
				$api_data .= "<td>{$record->username}</td>";
				$api_data .= "<td>";
				$api_data .= "<span class='text-bold'>Forskills User ID: </span>{$record->idUser}<br>";
				$api_data .= "<span class='text-bold'>ForSkills ID linked to your institution: </span>{$record->idUserInstitution}<br>";
				$api_data .= "<span class='text-bold'>StudentRef/External ref: </span>{$record->studentref}<br>";
				$api_data .= "</td>";
				$api_data .= "<td>{$record->email}</td>";
				$api_data .= "<td>{$record->firstname}</td>";
				$api_data .= "<td>{$record->lastname}</td>";
				$api_data .= "<td>";
				$api_data .= $record->address1 != "" ? $record->address1 . "<br>" : "";
				$api_data .= $record->address2 != "" ? $record->address2 . "<br>" : "";
				$api_data .= $record->address3 != "" ? $record->address3 . "<br>" : "";
				$api_data .= $record->address4 != "" ? $record->address4 . "<br>" : "";
				$api_data .= $record->postcode != "" ? $record->postcode . "<br>" : "";
				$api_data .= $record->city != "" ? $record->city . "<br>" : "";
				$api_data .= $record->country != "" ? $record->country . "<br>" : "";
				$api_data .= "</td>";
				$api_data .= "<td>";
				$api_data .= $record->phone1 != "" ? $record->phone1 . "<br>" : "";
				$api_data .= $record->phone2 != "" ? $record->phone2 . "<br>" : "";
				$api_data .= "</td>";
				$api_data .= "<td>{$record->dateofbirth}</td>";
				$api_data .= "<td>{$record->ninumber}</td>";
				$api_data .= "<td>{$record->gender}</td>";
				$api_data .= "</tr>";
			}
		}

		$html = <<<HTML
<div class="row">
	<div class="col-sm-12">
		<span class="text-bold">API Result</span><br>
		<span class="text-bold">Code: </span>$result->code<br>
		<span class="text-bold">Message: </span>$api_result_messages
	</div>
</div>
HTML;
		if($api_data != '')
		{
			$html .= <<<HTML
<div class="row">
	<div class="col-sm-12">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th></th>
					<th>Username</th>
					<th>Identities</th>
					<th>Email</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Address</th>
					<th>Telephone/ Mobile</th>
					<th>DOB</th>
					<th>NI Number</th>
					<th>Gender</th>
				</tr>
			</thead>
			<tbody>
				$api_data
			</tbody>
		</table>
	</div>
</div>
HTML;
		}
		else
		{
			$html .= '<i>No records found.</i>';
		}

		echo $html;
	}

	public function getUserAssessmentsAction(PDO $link)
	{
		$filters = [];
		$filterUsername = [];
		$filterStudentRef = [];
		$filterEmail = [];
		$filterNINumber = [];

		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
		if($username != '')
		{
			$username = DAO::getSingleValue($link, "SELECT username FROM forskills_users WHERE sunesis_username = '{$username}'");
			foreach(explode(',', $username) AS $val)
				$filterUsername[] = trim($val);
		}
		if(count($filterUsername) > 0)
			$filters['username'] = $filterUsername;

		$studentRef = isset($_REQUEST['studentRef']) ? $_REQUEST['studentRef'] : '';
		if($studentRef != '')
		{
			foreach(explode(',', $studentRef) AS $val)
				$filterStudentRef[] = trim($val);
		}
		if(count($filterStudentRef) > 0)
			$filters['studentRef'] = $filterStudentRef;

		$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
		if($email != '')
		{
			foreach(explode(',', $email) AS $val)
				$filterEmail[] = trim($val);
		}
		if(count($filterEmail) > 0)
			$filters['email'] = $filterEmail;

		$ninumber = isset($_REQUEST['ninumber']) ? $_REQUEST['ninumber'] : '';
		if($ninumber != '')
		{
			foreach(explode(',', $ninumber) AS $val)
				$filterNINumber[] = trim($val);
		}
		if(count($filterNINumber) > 0)
			$filters['ninumber'] = $filterNINumber;

		$result = Forskills::getUserAssessments($this->getUrl($link), $filters);

		$result = json_decode($result);

		if(!isset($result->code))
		{
			echo 'Connection to ForSkills unsuccessful, operation aborted.';
			return;
		}
		elseif($result->code == '404')
		{
			echo 'No records found matching filter parameters.';
			return;
		}

		$resultUsers = Forskills::getUserDetails($this->getUrl($link), $filters);
		$resultUsers = json_decode($resultUsers);
		$users = [];
		foreach($resultUsers->data AS $record)
		{
			$users[$record->username] = (object)[
				'firstname' => $record->firstname,
				'lastname' => $record->lastname,
				'ninumber' => $record->ninumber
			];
		}

		$api_result_messages = '';
		foreach($result->messages AS $message)
			$api_result_messages .= $message . '. ';

		$html = <<<HTML
<div class="row">
	<div class="col-sm-12">
		<span class="text-bold">API Result</span><br>
		<span class="text-bold">Code: </span>$result->code<br>
		<span class="text-bold">Message: </span>$api_result_messages
	</div>
</div>
HTML;

		if(is_object($result->data))
		{
			foreach($result->data AS $username => $assessment)
			{
				$english_rows = '';
				$maths_rows = '';
				$ict_rows = '';
				if(isset($assessment->English))
				{
					foreach($assessment->English AS $key => $rows)
					{
						foreach($rows AS $row)
						{
							$tt = gmdate('H:i:s', $row->timetaken);
							$english_rows .= <<<HTML
								<tr>
									<td>$row->idinstance</td>
									<td>$row->assessmenttitle</td>
									<td>$row->idlevel - $row->level</td>
									<td>$row->start to $row->finish</td>
									<td>$tt</td>
									<td>$row->idsubject - $row->subjecttitle</td>
									<td>$row->possible</td>
									<td>$row->actual</td>
								</tr>
HTML;
						}
					}
				}
				if(isset($assessment->Maths))
				{
					foreach($assessment->Maths AS $key => $rows)
					{
						foreach($rows AS $row)
						{
							$tt = gmdate('H:i:s', $row->timetaken);
							$maths_rows .= <<<HTML
								<tr>
									<td>$row->idinstance</td>
									<td>$row->assessmenttitle</td>
									<td>$row->idlevel - $row->level</td>
									<td>$row->start to $row->finish</td>
									<td>$tt</td>
									<td>$row->idsubject - $row->subjecttitle</td>
									<td>$row->possible</td>
									<td>$row->actual</td>
								</tr>
HTML;
						}
					}
				}
				if(isset($assessment->ICT))
				{
					foreach($assessment->ICT AS $key => $rows)
					{
						foreach($rows AS $row)
						{
							$tt = gmdate('H:i:s', $row->timetaken);
							$ict_rows .= <<<HTML
								<tr>
									<td>$row->idinstance</td>
									<td>$row->assessmenttitle</td>
									<td>$row->idlevel - $row->level</td>
									<td>$row->start to $row->finish</td>
									<td>$tt</td>
									<td>$row->idsubject - $row->subjecttitle</td>
									<td>$row->possible</td>
									<td>$row->actual</td>
								</tr>
HTML;
						}
					}
				}
				$english_rows = $english_rows == '' ? '<tr><td colspan="8" class="text-red">No records found</td> </tr>' : $english_rows;
				$maths_rows = $maths_rows == '' ? '<tr><td colspan="8" class="text-red">No records found</td> </tr>' : $maths_rows;
				$ict_rows = $ict_rows == '' ? '<tr><td colspan="8" class="text-red">No records found</td> </tr>' : $ict_rows;

				$html .= <<<HTML
<div class="row">
	<div class="col-sm-12">
		<div class="well well-sm">
			<span class="text-bold">Username: </span>$username &nbsp; &nbsp;
			<span class="text-bold">First Name: </span>{$users[$username]->firstname} &nbsp; &nbsp;
			<span class="text-bold">Last Name: </span>{$users[$username]->lastname} &nbsp; &nbsp;
			<span class="text-bold">NI: </span>{$users[$username]->ninumber} &nbsp; &nbsp;
			<div class="table-responsive">
				<table class="table table-bordered bg-white" style="background-color: white;">
					<caption style="background-color: white;"><b>ENGLISH</b></caption>
					<thead>
					<tr>
						<th>ID Instance</th><th>Assessment Title</th><th>Level</th><th>Assessment Duration</th>
						<th>Time Taken</th><th>Subject Title</th><th>Possible Score</th><th>Actual Score</th>
					</tr>
					</thead>
					<tbody>
					$english_rows
					</tbody>
				</table>
				<table class="table table-bordered bg-white" style="background-color: white;">
					<caption style="background-color: white;"><b>MATHS</b></caption>
					<thead>
					<tr>
						<th>ID Instance</th><th>Assessment Title</th><th>Level</th><th>Assessment Duration</th>
						<th>Time Taken</th><th>Subject Title</th><th>Possible Score</th><th>Actual Score</th>
					</tr>
					</thead>
					<tbody>
					$maths_rows
					</tbody>
				</table>
				<table class="table table-bordered bg-white" style="background-color: white;">
					<caption style="background-color: white;"><b>ICT</b></caption>
					<thead>
					<tr>
						<th>ID Instance</th><th>Assessment Title</th><th>Level</th><th>Assessment Duration</th>
						<th>Time Taken</th><th>Subject Title</th><th>Possible Score</th><th>Actual Score</th>
					</tr>
					</thead>
					<tbody>
					$ict_rows
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
HTML;
			}
		}

		echo $html;
	}

	public function saveNewlyCreatedUserInSunesisAction(PDO $link)
	{
		$sunesis_username = isset($_REQUEST['sunesis_username']) ? $_REQUEST['sunesis_username'] : '';
		$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';
		$data = isset($_REQUEST['data']) ? $_REQUEST['data'] : '';
		if($data == '')
			return;

		$data = json_decode($data);

		if(!isset($data[0]->username))
			return;

		$forskills = new stdClass();
		$forskills->sunesis_username = $sunesis_username;
		$forskills->password = $password;
		$forskills->username = $data[0]->username;
		$forskills->user_details = json_encode($data[0]);
		DAO::saveObjectToTable($link, 'forskills_users', $forskills);

		echo 'success';
	}

}