<?php
class rec_update_nas_reference_data implements IAction
{
	public function execute(PDO $link)
	{
		if(!SOURCE_BLYTHE_VALLEY && !SOURCE_LOCAL)
			throw new UnauthorizedException();

		if(SystemConfig::getEntityValue($link, 'nas.soap.system_id') == '')
			pre('This functionality is not switched on this client site.');

		require_once './lib/NAS/NASReferenceData/NASReferenceDataAutoload.php';

		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == 'ApprenticeshipFrameworks')
		{
			echo $this->downloadApprenticeshipFrameworks($link);
			exit;
		}

		include_once('tpl_rec_update_nas_reference_data.php');
	}

	private function downloadApprenticeshipFrameworks(PDO $link)
	{
		$system_id = SystemConfig::getEntityValue($link, 'nas.soap.system_id');
		$public_key = SystemConfig::getEntityValue($link, 'nas.soap.public_key');
		$message_id = md5('NAS_GetApprenticeshipsFrameworks_'.$_SESSION['user']->id.'_'.time());
		if($system_id == '' || $public_key == '')
			return 'Required security parameters missing, operation aborted';

		try
		{
			$nASReferenceDataServiceGet = new NASReferenceDataServiceGet();
			$nASReferenceDataServiceGet->setSoapHeaderExternalSystemId($system_id);
			$nASReferenceDataServiceGet->setSoapHeaderPublicKey($public_key);
			$nASReferenceDataServiceGet->setSoapHeaderMessageId($message_id);

			if($nASReferenceDataServiceGet->GetApprenticeshipFrameworks())
			{
				$result = $nASReferenceDataServiceGet->getResult(); /* @var $result NASReferenceDataStructGetApprenticeshipFrameworksResponse */
				$frameworks = $result->getApprenticeshipFrameworks()->ApprenticeshipFrameworkAndOccupationData;
				if(is_array($frameworks))
				{
					foreach($frameworks AS $f)
					{
						$objFramework = new stdClass();
						$objFramework->ApprenticeshipFrameworkCodeName = $f->ApprenticeshipFrameworkCodeName;
						$objFramework->ApprenticeshipFrameworkFullName = $f->ApprenticeshipFrameworkFullName;
						$objFramework->ApprenticeshipFrameworkShortName = $f->ApprenticeshipFrameworkShortName;
						$objFramework->ApprenticeshipOccupationCodeName = $f->ApprenticeshipOccupationCodeName;
						$objFramework->ApprenticeshipOccupationFullName = $f->ApprenticeshipOccupationFullName;
						$objFramework->ApprenticeshipOccupationShortName = $f->ApprenticeshipOccupationShortName;
						DAO::saveObjectToTable($link, 'nas_apprenticeship_frameworks', $objFramework);
					}
				}
				else
				{
					return true;
				}
			}
			else
				return json_encode($nASReferenceDataServiceGet->getLastError());
		}
		catch(SoapFault $fault)
		{
			return trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
		}
		return true;
	}
}