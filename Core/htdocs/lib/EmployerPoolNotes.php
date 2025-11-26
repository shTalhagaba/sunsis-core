<?php
class EmployerPoolNotes extends Entity
{
	public static function loadFromDatabase(PDO $link, $emp_id)
	{
		if($emp_id == '') {
			return null;
		}
			
		$key = addslashes((string)$emp_id);
		$query = <<<HEREDOC
		
SELECT
	central.emp_pool.auto_id,
	central.emp_pool.company,
	central.emp_pool.address1,
	central.emp_pool.postcode,
	employerpool_notes.*
FROM
central.emp_pool LEFT JOIN employerpool_notes 
ON
central.emp_pool.dpn = employerpool_notes.organisation_id
WHERE 
central.emp_pool.dpn = '$key'
ORDER BY date desc;
HEREDOC;
			
		$st = $link->query($query);

		if( $st )	{
			$employerpool_notes = new EmployerPoolNotes();
			$employerpool_notes->emp_id = $key;
						
			while( $row = $st->fetch() ) {
				$employerpool_notes->comments[] = array(
					'id' => $key,
					'note' => $row['agreed_action'],
					'username' => $row['by_whom'],
					'next_action' => $row['date'],
					'created' => '',
					'status' => ''
				);
				
				$employerpool_notes->organisations_id = $row['auto_id'];
				$employerpool_notes->next_action = $row['date'];
				$employerpool_notes->status = '';
				$employerpool_notes->company = $row['company'];
				$employerpool_notes->address = $row['address1'];
				$employerpool_notes->postcode = $row['postcode'];
			}
		}
		return $employerpool_notes;	
	}
	
	public function render(PDO $link) {
		
		// date drop down populations
  		$day = array(array('','dd'),array('01',1),array('02',2),array('03',3),array('04',4),array('05',5),array('06',6),array('07',7),array('08',8),array('09',9),array('10',10),array('11',11),array('12',12),array(13,13),array(14,14),array(15,15),array(16,16),array(17,17),array(18,18),array(19,19),array(20,20),array(21,21),array(22,22),array(23,23),array(24,24),array(25,25),array(26,26),array(27,27),array(28,28),array(29,29),array(30,30),array(31,31)); 
  		$month = array(array('','mon'),array('01','Jan'),array('02','Feb'),array('03','Mar'),array('04','Apr'),array('05','May'),array('06','Jun'),array('07','Jul'),array('08','Aug'),array('09','Sep'),array(10,'Oct'),array(11,'Nov'),array(12,'Dec'));
  		$year = array(array('','yyyy'));
  		for( $a = 2020; $a>=2010; $a-- ) {
  			$year[] = array($a,$a);	
  		}
  		
  		$note_date = array(date('Y'), date('m'), date('d'));

  		if ( $this->next_action != '' ) {
  			$note_date = preg_split("/-/", $this->next_action);
  		}
		
		
			$emp_notes = '<div style="width:800px;">';
			
			$org_matches = '';
			
			$exists = DAO::getSingleValue($link, "select count(*) from organisations where zone = '$this->emp_id'");
			
			if( !$exists ) {	
				
// -------------------------------------------------------------
				$this->company = DAO::getSingleValue($link, "select company from central.emp_pool where dpn = '$this->emp_id'");
				$org_pool_name = preg_split('/ /', $this->company);
				$query = 'select organisations.id, organisations.legal_name, organisations.edrs, locations.postcode from organisations, locations where organisations.id = locations.organisations_id and locations.is_legal_address = 1 ';
				$count = 0;
				foreach ( $org_pool_name as $org_name_id => $org_name_element ) {
				 	
					if ( $count == 0 ) {
						$query .= ' and ( ';
					}
					if ( $org_name_element != '' && strlen($org_name_element) >= 4 && $org_name_element != 'limited' ) {
						$query .= 'organisations.legal_name like "%'.$org_name_element.'%" or ';
					}
					$count++;
			 	}
				$query .= 'organisations.legal_name like "%dummydatatest%") ';
				$query .= 'order by organisations.legal_name asc';	

				$st = $link->query($query);
				
				$possible_matches = '';
				$possible_matches_flag = 1;
				
				if( $st ) {	
					$count = 0;
					while( $row = $st->fetch() ) {
						if ( $count == 0 ) {
							$possible_matches = '<table width="50%" style="float:left; border-collapse:collapse; border-spacing: 0; "><tr><td style="font-weight:bold; background-color: #eee;" colspan="3" >Potential Existing Employers</td></tr>';
						}
						$possible_matches .= '<tr style="';
						if( $odd = $count%2 ) {
							$possible_matches .= 'background-color: #E0EAD0;';
						} 
						$possible_matches .= '"><td style="border:none;" ><input type="radio" name="ext_emp_'.$this->emp_id.'" value="'.$row['id'].'" /></td><td style="border:none;" >'.$row['legal_name'].'</td><td style="border:none;" >'.$row['postcode'].'</td></tr>';	
						$count++;
					}
					$possible_matches .= '</table>';
					
					if ( $count == 0 ) {
						$possible_matches_flag = 0;
						$possible_matches = '<table width="50%" style="float:right; border-collapse:collapse; border-spacing: 0;"><tr><td style="font-weight:bold; background-color: #eee;" colspan="2">Potential Existing Employers</td></tr>';
						$possible_matches .= '<tr><td colspan="2" style="border:none;"><br/>There are no employers currently on the system that look similar to '.$this->company.'</td></tr>'; 	
						$possible_matches .= '</table>';	
							
					}
		
				}
					
// ---------------------------------------------------
				$org_matches .= '<div style="border: 1px solid #e9e9e9;">';
				$org_matches .= '<table width="50%" style="float:left; border-collapse:collapse; border-spacing: 0;"><tr><td style="font-weight:bold; background-color: #eee;">Convert to full employer</td></tr>';
				$org_matches .= '<tr><td style="border:none;"><br/>Once you are happy that the employer <strong>'.$this->company.'</strong> is suitable for conversion to a full Sunesis system employer, you can use the "<strong>Convert</strong>" button to transfer its details to the main system.<br/><br/>';
				$org_matches .= 'Once converted the organisation will no longer appear in the "<strong>Your Actions</strong>" section of the Recruitment Manager module.<br/><br/>';
				if ( $possible_matches_flag == 1 ) {
					$org_matches .= 'We have located some organisations already in Sunesis that are similar to this company.<br/><br/>If you can see <strong>'.$this->company.'</strong> in the list to the right ( it may have a slightly different name ), then check the radio box next to it and click "<strong>Convert</strong>", and this employer pool record will be linked to that Organisation.';
					$org_matches .= '<br/><br/>Alternatively, if you do not see a match, clicking on "<strong>Convert</strong>" will set <strong>'.$this->company.'</strong> up as a new employer in Sunesis';
				}
				$org_matches .= '</td></tr>';
				$org_matches .= '</table>';
				$org_matches .= $possible_matches;
				$org_matches .= '<div style="clear:both;height:1px;overflow:none;"></div>';
				$org_matches .= '<div style="text-align:center; padding: 5px;"><button onclick=convertEmployer('.'"'.$this->emp_id.'"'.') class="button" >Convert</button></div>';
				$org_matches .= '</div>';
			}
			//$emp_notes .= '<tr><td style="border: none;">Sales Region:</td><td style="border: none;">';
			//$regions = array(array('1','North West',''), array('2','North East',''), array('3','Midlands',''), array('4','East Midlands',''), array('5','West Midlands',''), array('6','London North',''), array('7','London South',''), array('8','Peterborough',''), array('9','Yorkshire',''));
			//$emp_notes .= HTML::select('emp_region', $regions, $this->status, true, true);  
			//$emp_notes .= '</td></tr>';
			//$emp_notes .= '<tr><td colspan="2" style="border: none;">Latest Comments<br/>';
			$emp_notes .= '</div>';
			$emp_notes .= '<div style="clear:both;height:1px;overflow:none;"></div>';
			$emp_notes .= $org_matches;
			$emp_notes .= '</div>';
			return $emp_notes;
	}

	public $emp_id = null;
	public $organisations_id = null;
	
	public $note = null;
	public $username = null;
	public $status = null;
	public $next_action = null;
	public $company = null;
	public $address = null;
	public $postcode = null;
	
	public $comments = array();
}
?>