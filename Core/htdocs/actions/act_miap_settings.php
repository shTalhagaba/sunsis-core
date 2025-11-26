<?php
class miap_settings implements IAction
{
	public function execute(PDO $link)
	{
		$authorised = $_SESSION['user']->isAdmin() && (SOURCE_BLYTHE_VALLEY || SOURCE_LOCAL);
		if (!$authorised)
		{
			throw new UnauthorizedException();
		}
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';
		if($subaction == 'save')
		{
			$this->saveMIAPSettings($link);
		}

		$ddlYesNo =  array(array('0', 'No'),array('1', 'Yes'));

		$certificate = '';
		if (file_exists(Repository::getRoot() . '/MIAPCertificate/' . SystemConfig::getEntityValue($link, 'miap.soap.wsdl_local_cert')))
			$certificate = 'Uploaded Certificate: <a href="do.php?_action=downloader&path=/MIAPCertificate&f=' . SystemConfig::getEntityValue($link, 'miap.soap.wsdl_local_cert') . '">' . SystemConfig::getEntityValue($link, 'miap.soap.wsdl_local_cert') . '</a>';

		include("miap/tpl_miap_settings.php");
	}

	private function saveMIAPSettings(PDO $link)
	{
		$enabled = isset($_REQUEST['miap_soap_enabled'])?$_REQUEST['miap_soap_enabled']:'';
		$password = isset($_REQUEST['miap_soap_password'])?$_REQUEST['miap_soap_password']:'';
		$ukprn = isset($_REQUEST['miap_soap_ukprn'])?$_REQUEST['miap_soap_ukprn']:'';

		if($enabled == 0)
		{
			SystemConfig::setEntityValue($link, 'miap.soap.enabled', 0);
			return;
		}
		else
		{
			DAO::transaction_start($link);
			try
			{
				SystemConfig::setEntityValue($link, 'miap.soap.enabled', 1);
				SystemConfig::setEntityValue($link, 'miap.soap.password', $password);
				SystemConfig::setEntityValue($link, 'miap.soap.ukprn', $ukprn);
				$files = glob(Repository::getRoot() . '/MIAPCertificate/*');
				foreach($files as $file)
				{
					if(is_file($file))
						unlink($file);
				}
				$r = Repository::processFileUploads('miap_soap_wsdl_local_cert', 'MIAPCertificate', array('pem'));
				SystemConfig::setEntityValue($link, 'miap.soap.wsdl_local_cert', basename($r[0]));

				if(!isset($r[0]))
					throw new Exception('Error uploading file, please try again.');

				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link, $e);
				throw new WrappedException($e);
			}
		}
	}
}