<?php
class delete_vacancy implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$vacancy_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$employer_id = isset($_REQUEST['emp_id'])?$_REQUEST['emp_id']:'';

		if($vacancy_id != '')
		{
			
			$vo = Vacancy::loadFromDatabase($link, $vacancy_id);

			if( !is_null($vo) ) {
				$vo->delete($link);
			}
		}	
		// Presentation
		if ( $employer_id != '' ) {
			http_redirect('do.php?_action=read_employer&id='.$employer_id);
		}
		else {
			http_redirect('do.php?_action=view_employers');
		}
	}
}
?>