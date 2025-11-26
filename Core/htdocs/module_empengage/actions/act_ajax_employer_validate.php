<?php
class ajax_employer_validate implements IAction 
{
	public function execute(PDO $link)	{
		
		header('Content-Type: text/html;');		
		$sql_request_retrieval = 'SELECT organisations.id, organisations.legal_name, ';
		if ( isset($_REQUEST['edrs']) ) {
			$sql_request_retrieval .= 'organisations.edrs, ';
		}
		$sql_request_retrieval .= 'organisations.trading_name, locations.postcode from organisations left join locations on ';
		$sql_request_retrieval .= 'organisations.id = locations.organisations_id where ( ';
		$sql_request_retrieval .= 'legal_name like "%'.$_REQUEST['legal_name'].'%" or trading_name like "%'.$_REQUEST['trading_name'].'%" ';
		if ( isset($_REQUEST['edrs']) ) {
			$sql_request_retrieval .= 'or edrs like "%'.$_REQUEST['edrs'].'%" ';
		}
		if ( isset($_REQUEST['postcode']) ) {
			$sql_request_retrieval .= 'or locations.postcode = "'.$_REQUEST['postcode'].'" ';
		}
		$sql_request_retrieval .= ') ';

		$return_text = '<table><thead>';
		$return_text .= '<th>&nbsp;</th>';
		$return_text .= '<th>Legal Name</th>';
		if ( isset($_REQUEST['edrs']) ) {
			$return_text .= '<th>EDRS</th>';
		}
		$return_text .= '<th>Trading Name</th>';
		$return_text .= '<th>Postcode</th>';
		$return_text .= '</thead><tbody>';
		
		$match_count = 0;
		if( $result = $link->query($sql_request_retrieval) ) {		
			while( $row = $result->fetch() ) {
				$return_text .= '<tr>';
				$return_text .= '<td><input type="checkbox" value="'.$row['id'].'" name="ext_emp_'.$row['id'].'" checked="checked" ></td>';
				$return_text .= '<td><a href="?_action=read_employer&id='.$row['id'].'">'.$row['legal_name'].'</a></td>';
				if ( isset($_REQUEST['edrs']) ) {
					$return_text .= '<td>'.$row['edrs'].'</td>';
				}
				$return_text .= '<td>'.$row['trading_name'].'</td><td>'.$row['postcode']."</td></tr>\n";	
				$match_count++;	
			}		
		}
		
		if ( $match_count > 0 ) {
			$return_text = $match_count." Possible matches have been found!\n".$return_text."</tbody></table>";
			$return_text .= '<p>In order to save this employer, you will need to verify that none of the potential matches will create a duplicate, if you are happy that they will not, then <strong>uncheck</strong> it by clicking on the tickbox to the left of the legal name.</p>';
			$return_text .= '<p>Until you uncheck all the potential matches, you will not be able to set this employer up in Sunesis.</p>';
			$return_text .= '<p>If a match for your employer is found, click on the legal name to go to their full Sunesis details.</p>';
		}
		else {
			$return_text = '';
		}
		
		echo $return_text;
		return;
	}
}
?>
