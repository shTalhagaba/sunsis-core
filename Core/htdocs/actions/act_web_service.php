<?php
class web_service implements IAction
{
	public function execute(PDO $link)
	{
		
		
		$data = file_get_contents("https://applicants.remit.co.uk/vacancytest/xmltrvacancyexport.asp");

		throw new Exception($data);
		
		
		$data = new GetApplicants();
		$xml = $data->getQualification();
		
		throw new Exception($xml);
		
		// Presentation
		include('tpl_web_service.php');
	}
}
?>