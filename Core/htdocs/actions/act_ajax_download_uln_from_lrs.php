<?php
class ajax_download_uln_from_lrs implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		$outputHTML = '';
		$firstnames = '';
		$surname = '';
		$dob = '';
		$gender = '';
		$postcode = '';
		$prev_family_name = '';
		$school_at_age_16 = '';
		$place_of_birth = '';
		$home_email = '';

		if($subaction == 'searchByDemographics')
		{
			$_REQUEST['dob'] = Date::to($_REQUEST['dob'], 'Y-m-d');
			//throw new Exception(implode(',', $_REQUEST));
			$this->getLRSResults($link, $_REQUEST);
			exit;
		}
		if($subaction == 'register')
		{
			$stdObject = json_decode(json_encode($_REQUEST));
			if(!$this->allMandatoryFieldsProvided($stdObject))
			{
				echo 'Error: Mandatory Information Missing';
				exit;
			}
			$stdObject->DateOfBirth = Date::to($stdObject->DateOfBirth, Date::MYSQL);
			$stdObject->DateOfAddressCapture = Date::to($stdObject->DateOfAddressCapture, Date::MYSQL);
			switch($stdObject->Gender)
			{
				case 'U':
					$stdObject->Gender = '0';
					break;
				case 'M':
					$stdObject->Gender = '1';
					break;
				case 'F':
					$stdObject->Gender = '2';
					break;
				default:
					$stdObject->Gender = '9';
					break;
			}
			//sleep(2);
			header("Content-Type: text/plain");
			echo $this->registerSingleLearner($link, $stdObject);
			//echo '012345678';exit;
		}
	}

	private function registerSingleLearner(PDO $link, stdClass $participant)
	{
		require_once './MIAP/MIAPAutoload.php';
		$miap_password = SystemConfig::getEntityValue($link, 'miap.soap.password');
		$miap_username = SystemConfig::getEntityValue($link, 'miap.soap.username');
		$miap_ukprn = SystemConfig::getEntityValue($link, 'miap.soap.ukprn');

		$connectionArray = array();
		$connectionArray['wsdl_local_cert'] = SystemConfig::getEntityValue($link, 'miap.wsdl_local_cert');

		$mIAPServiceLearner = new MIAPServiceLearner($connectionArray);

		// A find must precede a Register within a maximum period of 120 milliseconds otherwise the Register will fail (Error WSRC0021). The LRS performs a check to ascertain that the Find has taken place otherwise the Register will fail.
		$miap_learner = new MIAPStructLearnerByDemographicsRqst('FUL', $miap_password, $_SESSION['user']->username, $participant->FamilyName, $participant->GivenName, $participant->DateOfBirth, $participant->Gender, $participant->LastKnownPostCode,$miap_username, $miap_ukprn);
		$miap_learner->PreviousFamilyName = $participant->PreviousFamilyName;
		$miap_learner->SchoolAtAge16 = $participant->SchoolAtAge16;
		$miap_learner->PlaceOfBirth = $participant->PlaceOfBirth;
		$miap_learner->EmailAddress = $participant->EmailAddress;

		if($mIAPServiceLearner->learnerByDemographics($miap_learner))
		{
			$result = $mIAPServiceLearner->getResult();

			if($result->getResponseCode() == 'WSRC0001') // No match
			{
				// only starts this if the learner is not already in LRS
				$mIAPServiceRegister = new MIAPServiceRegister($connectionArray);

				$learner_to_register = new MIAPStructLearnerToRegister($participant->GivenName, $participant->FamilyName, $participant->LastKnownPostCode, $participant->DateOfBirth, $participant->Gender, $participant->VerificationType, $participant->AbilityToShare);
				$learner_to_register->Title = $participant->Title;
				$learner_to_register->MiddleOtherName = $participant->MiddleOtherName;
				$learner_to_register->PreferredGivenName = $participant->PreferredGivenName;
				$learner_to_register->PreviousFamilyName = $participant->PreviousFamilyName;
				//$learner_to_register->FamilyNameAtAge16 = $participant->FamilyNameAtAge16;
				$learner_to_register->SchoolAtAge16 = $participant->SchoolAtAge16;
				$learner_to_register->LastKnownAddressLine1 = $participant->LastKnownAddressLine1;
				$learner_to_register->LastKnownAddressLine2 = $participant->LastKnownAddressLine2;
				$learner_to_register->LastKnownAddressCountyOrCity = $participant->LastKnownAddressCountyOrCity;
				$learner_to_register->LastKnownAddressTown = $participant->LastKnownAddressTown;
				$learner_to_register->DateOfAddressCapture = $participant->DateOfAddressCapture;
				$learner_to_register->PlaceOfBirth = $participant->PlaceOfBirth;
				$learner_to_register->EmailAddress = $participant->EmailAddress;
				$learner_to_register->Nationality = $participant->Nationality;
				$learner_to_register->ScottishCandidateNumber = $participant->ScottishCandidateNumber;
				$learner_to_register->OtherVerificationDescription = $participant->OtherVerificationDescription;
				$learner_to_register->Notes = $participant->Notes;

				$request = new MIAPStructRegisterSingleLearnerRqst($miap_password, $_SESSION['user']->username, $learner_to_register, $miap_username, $miap_ukprn);

				if($mIAPServiceRegister->registerSingleLearner($request))
				{
					$result = $mIAPServiceRegister->getResult();//pre($result);
					if($result->getResponseCode() == 'WSRC0005') // learner successfully registered and ULN assigned
					{
						$matched_learner = new MIAPStructRegisterSingleLearnerResp($result->getResponseCode());
						return $matched_learner->getULN();
					}
					else
					{
						$outputHTML = '<table><caption><strong>An Error Occurred. LRS Return Code (' . $result->getResponseCode() . ')</strong></caption>';
						$outputHTML .= '<tr><td>Reason(s)</td><td>This Response Code may have been caused by the following
								<ol>
									<li>Learner could not be registered as the same learner already exists on the LRS PORTAL. This is confirmed when a learner with the same given name, family name, date of birth, gender and last known postcode, is confirmed to already exist in the PORTAL.</li>
									<li>Learner could not be registered as the correct registration procedure has not been followed. </li>
									<li>Learner could not be registered as the system fails to find the supplied details in the Recent Searches Audit Log table carried out within the Maximum Time.</li>
									<li>Learner could not be registered as the Recent search result is a Possible Match but not within the Minimum Time.</li>
								</ol>
								<strong>Please contact <a href="do.php?_action=support_form&header=1">Sunesis Support</a> for further information</strong>
								</td></tr>';
						$outputHTML .= '</table>';
						return $outputHTML;
					}
				}
				else
				{
					$outputHTML = '<table><caption><strong>An Error Occurred. LRS Return Code (' . $result->getResponseCode() . ')</strong></caption>';
					$outputHTML .= '<tr><td>Reason(s)</td><td>This Response Code may have been caused by the following
								<ol>
									<li>Learner could not be registered as the same learner already exists on the LRS PORTAL. This is confirmed when a learner with the same given name, family name, date of birth, gender and last known postcode, is confirmed to already exist in the PORTAL.</li>
									<li>Learner could not be registered as the correct registration procedure has not been followed. </li>
									<li>Learner could not be registered as the system fails to find the supplied details in the Recent Searches Audit Log table carried out within the Maximum Time.</li>
									<li>Learner could not be registered as the Recent search result is a Possible Match but not within the Minimum Time.</li>
								</ol>
								<strong>Please contact <a href="do.php?_action=support_form&header=1">Sunesis Support</a> for further information</strong>
								</td></tr>';
					$outputHTML .= '</table>';
					return $outputHTML;
				}
			}
			else
			{
				$outputHTML = '<table><caption><strong>An Error Occurred. LRS Return Code (' . $result->getResponseCode() . ')</strong></caption>';
				$outputHTML .= '<tr><td>Reason(s)</td><td>This Response Code may have been caused by the following
								<ol>
									<li>Learner could not be registered as the same learner already exists on the LRS PORTAL. This is confirmed when a learner with the same given name, family name, date of birth, gender and last known postcode, is confirmed to already exist in the PORTAL.</li>
									<li>Learner could not be registered as the correct registration procedure has not been followed. </li>
									<li>Learner could not be registered as the system fails to find the supplied details in the Recent Searches Audit Log table carried out within the Maximum Time.</li>
									<li>Learner could not be registered as the Recent search result is a Possible Match but not within the Minimum Time.</li>
								</ol>
								<strong>Please contact Sunesis Support for further information</strong>
								</td></tr>';
				$outputHTML .= '</table>';
				return $outputHTML;
			}
		}
		else
		{
			$error = $mIAPServiceLearner->getLastError();
			$mIAPException = new MIAPStructMIAPAPIException($error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->ErrorCode,
				$error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->ErrorActor,$error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->Description,
				$error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->FurtherDetails, $error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->ErrorTimestamp);
			throw new Exception($mIAPException->getErrorCode() . ': ' . $mIAPException->getFurtherDetails());
		}
	}

	private function allMandatoryFieldsProvided(stdClass $stdObject)
	{
		if(is_null($stdObject))
			return false;
		$mandatoryFields = array(
			'GivenName'
			,'FamilyName'
			,'LastKnownPostCode'
			,'DateOfBirth'
			,'Gender'
			,'VerificationType'
			,'AbilityToShare'
		);
		foreach($mandatoryFields AS $field)
		{
			if(is_null($stdObject->$field) || trim($stdObject->$field) == '')
				return false;
		}
		if($stdObject->VerificationType == '999' && trim($stdObject->OtherVerificationDescription == ''))
			return false;
		return true;
	}

	private function getGenderDescription($id)
	{
		switch($id)
		{
			case 1:
				return 'Male';
			case 2:
				return 'Female';
			case 0:
				return 'Unknown';
			case 9:
				return 'Withheld';
			default:
				return '';
		}
	}

	public function getLRSResults(PDO $link, $_learner)
	{
		require_once './MIAP/MIAPAutoload.php';
		$miap_password = SystemConfig::getEntityValue($link, 'miap.soap.password');
		$miap_username = null;
		$miap_ukprn = SystemConfig::getEntityValue($link, 'miap.soap.ukprn');
		$miap_wsdl_local_cert = Repository::getRoot().'/MIAPCertificate/'.SystemConfig::getEntityValue($link, 'miap.soap.wsdl_local_cert');

		$connectionArray = array();
		$connectionArray['wsdl_local_cert'] = $miap_wsdl_local_cert;

		$mIAPServiceLearner = new MIAPServiceLearner($connectionArray);

		$lrs_gender = '';
		switch($_learner['gender'])
		{
			case 'M':
				$lrs_gender = '1';
				break;
			case 'F':
				$lrs_gender = '2';
				break;
			case 'U':
				$lrs_gender = '0';
				break;
			case 'W':
				$lrs_gender = '9';
				break;
			default:
				$lrs_gender = '';
				break;
		}

		$learner = new MIAPStructLearnerByDemographicsRqst($_learner['find_type'], $miap_password, $_SESSION['user']->username, $_learner['surname'], $_learner['firstnames'], $_learner['dob'], $lrs_gender, $_learner['home_postcode'],$miap_username, $miap_ukprn);

		if($mIAPServiceLearner->learnerByDemographics($learner))
		{
			$result = $mIAPServiceLearner->getResult();

			if($learner->getFindType() == 'FUL' && ($result->getResponseCode() == 'WSRC0001' || $result->getResponseCode() == 'WSRC0002')) // No match or too many matches
			{
				if($result->getResponseCode() == 'WSRC0001')
					echo 'No match found. LRS Return Code (' . $result->getResponseCode() . ')';
				elseif($result->getResponseCode() == 'WSRC0002')
					echo 'Too many matches found. Please provide optional information if left blank. LRS Return Code (' . $result->getResponseCode() . ')';
			}
			elseif($learner->getFindType() == 'FUL' && $result->getResponseCode() == 'WSRC0004') // Exact match
			{
				$matched_learner = new MIAPStructFindLearnerResp($result->getResponseCode());
				$matched_learner = $result->getLearner(); // this method returns an array of MIAPStructLearner objects in exact match there will only be one such object
				$matched_learner = $matched_learner[0];
				echo '(' . $result->getResponseCode() . ')' . $matched_learner->getULN();
			}
			elseif($learner->getFindType() == 'FUL' && $result->getResponseCode() == 'WSRC0003') // Possible matches
			{

				$outHtml = 'Exception: Possible matches found within LRS records. LRS Return Code (' . $result->getResponseCode() . ')';
				$matched_learners = $result->getLearner();
				$counter = 0;
				foreach($matched_learners AS $matched_learner)
				{
					$uln = $matched_learner->getULN();
					$given_name = $matched_learner->getGivenName();
					$family_name = $matched_learner->getFamilyName();
					$gender = $this->getGenderDescription($matched_learner->getGender());
					$last_known_postcode = $matched_learner->getLastKnownPostCode();
					$date_of_birth = Date::to($matched_learner->getDateOfBirth(), Date::SHORT);
					$created_date = Date::to($matched_learner->getCreatedDate(), Date::SHORT);
					$last_updated_date = Date::to($matched_learner->getLastUpdatedDate(), Date::SHORT);
					$ability_to_share = DAO::getSingleValue($link, "SELECT description FROM lookup_ability_to_share WHERE id = '" . $matched_learner->getAbilityToShare() . "'");
					$lrs_learner_status = DAO::getSingleValue($link, "SELECT description FROM lookup_lrs_learner_status WHERE id = '" . $matched_learner->getLearnerStatus() . "'");
					$school_at_16 = $matched_learner->getSchoolAtAge16();
					$last_known_address1 = $matched_learner->getLastKnownAddressLine1();
					$email_address = $matched_learner->getEmailAddress();
					$nationality = $matched_learner->getNationality();
					$verification_type = DAO::getSingleValue($link, "SELECT description FROM lookup_verification_type WHERE code = '" . $matched_learner->getVerificationType() . "'");

					$counter++;

					$jquery_update_uln = "document.getElementById('l45').value='" . $matched_learner->getULN() . "'; saveCandidateULN();";
					$outHtml .= <<<HTML
<fieldset>
	<legend>LRS Record $counter</legend>
	<div>
		<table border="0" cellspacing="2" cellpadding="6">
			<tr><td class="fieldLabel">ULN:</td><td class="fieldValue">$uln</td><td class="fieldLabel">Given Name:</td><td class="fieldValue">$given_name</td><td class="fieldLabel">Family Name:</td><td class="fieldValue">$family_name</td></tr>
			<tr><td class="fieldLabel">Gender:</td><td class="fieldValue">$gender</td><td class="fieldLabel">Last Known Postcode:</td><td class="fieldValue">$last_known_postcode</td><td class="fieldLabel">Date of Birth:</td><td class="fieldValue">$date_of_birth</td></tr>
		</table>
		<p>
		<table class="resultset" cellspacing="0" cellpadding="4" style="font-size: smaller;">
			<caption><strong>Additional Learner Details in LRS</strong></caption>
			<thead>
				<tr>
					<th>Created Date</th>
					<th>Last Updated Date</th>
					<th>Ability To Share</th>
					<th>Learner Status</th>
					<th>School At Age16</th>
					<th>Address Line 1</th>
					<th>Email Address</th>
					<th>Nationality</th>
					<th>Verification Type</th>
				</tr>
			</thead>
			<tbody>
				<tr class="Data">
					<td>$created_date</td>
					<td>$last_updated_date</td>
					<td>$ability_to_share</td>
					<td>$lrs_learner_status</td>
					<td>$school_at_16</td>
					<td>$last_known_address1</td>
					<td>$email_address</td>
					<td>$nationality</td>
					<td>$verification_type</td>
				</tr>
			</tbody>
		</table>
		</p>
		<p><span class="button" onclick="$jquery_update_uln">&nbsp;&nbsp;&nbsp; Select &nbsp;&nbsp;&nbsp;</span></p>
	</div>
</fieldset>
HTML;
				}
				echo $outHtml;
			}
		}
		else
		{
			$error = $mIAPServiceLearner->getLastError();
			echo json_encode($error);
			return;
			$mIAPException = new MIAPStructMIAPAPIException($error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->ErrorCode,
				$error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->ErrorActor,$error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->Description,
				$error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->FurtherDetails, $error['MIAPServiceLearner::learnerByDemographics']->detail->{'MIAPAPIException'}->ErrorTimestamp);
			//$mIAPException = $error['MIAPServiceLearner::learnerByDemographics']->detail;
			//pre($mIAPException);
			throw new Exception($mIAPException->getErrorCode() . ': ' . $mIAPException->getFurtherDetails());
		}
	}

}