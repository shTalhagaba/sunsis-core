<?php
class support_form implements IAction {
	
	private $user_auth;
	private $options;
	private $client;
	private $session_id;
	
	
	public function execute(PDO $link) {
		
		
		$this->user_auth = array(
			"user_name"	=> "Sunesis Support ",
			"password"	=> md5("perspective"),
			"version"	=> ".01"
		);
		
		$this->options = array(
	        "location" 	=> 'https://sugar.perspective-uk.com/soap.php',
	        "uri" 		=> 'https://sugar.perspective-uk.com/',
	        "trace" 	=> 1
	  	);
	  		  		  	
	  	// create the soap client
	    $this->getSoapClient();
	    
	    // ?? get the cases to find the types available
	    $case = $this->client->get_entry_list($this->session_id,"Cases","");

		$revised_request_types = [
			"Documentation",
			"Enhancement Request",
			"General Enquiry",
			"ILR Migration",
			"Reports",
			"Programming bug",
			"Training",
		];
	    
	    foreach( $case->field_list as $field ) {
		   	if( $field->name == "case_type_c" ) {
		   		$type = array(array("","--- Please Select ---"));
		   		sort($field->options);
		   		foreach( $field->options as $case_type ) {
		   			if ( !preg_match('/clm/i',$case_type->name) && !preg_match('/sif/i',$case_type->name) ) {
						if(in_array($case_type->name, $revised_request_types))
		   					$type[] = array($case_type->name,$case_type->value);
		   			}
		   		}
		   	}
		   	if( $field->name == "priority_c" ) {
		   		$priority = array(array("","--- Please Select ---"));
				$field->options = array_reverse($field->options);
		   		foreach( $field->options as $priority_type ) {
		   			$priority[] = array($priority_type->name,str_replace("Show stopper", "Critical!", $priority_type->value));
		   		}
		   	}
	    }

		$sent = false;
		
		if( isset($_REQUEST['subaction']) && $_REQUEST['subaction']== "send" ) {
			
			$case_id = $this->createSugarCase($_POST, $_FILES);
			
			if( $this->sendSupportEmail($_POST, $case_id, $_FILES) ) {
				$sent = true;
				$this->sendClientEmail($_POST, $case_id);
			}
			else {
				throw new Exception("An error has occurred sending your support request");
			}
			$_REQUEST['header'] = 1;
		}
		
		/**
		* 
		* Build the list of How To guides in the howto directory
		* - not required for EdExcel qualification manager system.
		*/
		$help_guide_html = '';
		if( ( DB_NAME != 'am_edexcel' ) && ( file_exists(DATA_ROOT."/uploads/am_demo/howto") ) ) {		
			$urls = Array();
			$TrackDir=opendir(DATA_ROOT."/uploads/am_demo/howto");
			// relmes - ensure evaluation of directory names
			// cannot stop the loop
			// - http://php.net/manual/en/function.readdir.php
			while ( false !== ( $file = readdir($TrackDir) ) ) { 
				if ($file != "." && $file != ".." && !is_dir(DATA_ROOT."/uploads/am_demo/howto/".$file) && $file != "On Boarding Software User Guide_Jan2021_V2.pdf" ) {	
					$icon_class = "icon-pdf";
					if (preg_match('/^HTS_([A-Z]{3})_([A-Z]{3})_([A-Z]{3}) [\- ]{0,1}(.*)\.pdf$/', $file, $cat_filedetails) ) {
						$filename = trim($cat_filedetails[4]);
						if( isset($urls[$file]) ) {
							$urls[$file] .=  '<td><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&f=" . $file . '" class="'.$icon_class.'" title="HTS_'.$cat_filedetails[1].'_'.$cat_filedetails[2].'_'.$cat_filedetails[3].'"><br />'.$filename.'</a></td>';
						}
						else {
							$urls[$file] = '<td><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&f=" . $file . '" class="'.$icon_class.'" title="HTS_'.$cat_filedetails[1].'_'.$cat_filedetails[2].'_'.$cat_filedetails[3].'" >'.$filename.'</a></td>';
						}
					}
					else {
						$filename = preg_replace('/Sunesis HTS/', '', $file);
						$filename = preg_replace('/\.pdf$/', '', $filename);
						if($file != "New Edexcel Qualification.pdf") {
							if( isset($urls[$file]) ) {
								$urls[$file] .=  '<td><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&amp;f=" . $file . '" class="'.$icon_class.'" ><br />'.$filename.'</a></td>';;
							}
							else {
								$urls[$file] = '<td><a href="do.php?_action=downloader&path=/am_demo/howto/'. "&amp;f=" . $file . '" class="'.$icon_class.'" >'.$filename.'</a></td>';
							}
						}
					}
				}
			}

			// relmes - sort the results to display alphabetically
			sort($urls);
			
			// build the How To guide table
			$n2 = 0;
			$help_guide_html = "<table><tbody><tr>";
			foreach ( $urls as $howto_file => $howto_link ) {
				$help_guide_html .= $howto_link;
				$n2++;
				// relmes - allow for multiple columns
				// check if we are at an even document and start a new line
				//if( $nl3 == 3 ) {
					$help_guide_html .= "</tr><tr>";
				//	$nl3 = 0;	
				//}
				//$nl3++;
			}
			// relmes - remove empty tr if present ast end of html.
			$help_guide_html = preg_replace('/\<\/tr\>\<tr\>$/', '', $help_guide_html);
			$help_guide_html .= "</tr></tbody></table>";
			closedir($TrackDir); 
		}

		include "tpl_support_form.php";
		$response = $this->client->logout($this->session_id);
	}
	
	/**
	 * 
	 * Error output
	 * @param unknown_type $value
	 */
	private function debug($value) {
		echo "<pre>";
		print_r($value);
		die;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	private function getSoapClient() {
		ini_set('soap.wsdl_cache_enabled',0);
		ini_set('soap.wsdl_cache_ttl',0);
		$this->client = new SoapClient(null, $this->options);
		try {
			$response = $this->client->login($this->user_auth,"test");
		} 
		catch (SoapFault $e) {
			if(SOURCE_BLYTHE_VALLEY)
			{
				pr($this->client->__getLastRequestHeaders());
				pr($this->client->__getLastRequest());
				pr($e->getMessage());
				pr($e->getCode());
				pre($e->getTrace());
			}
			return false;
		}
		$this->session_id = $response->id;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $post
	 * @param unknown_type $case_id
	 */
	private function sendClientEmail($post, $case_id){
		$to = $post['email'];
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Perspective Support <support@perspective-uk.com>' . "\r\n";
		if($case_id){
			$sugar_case = "and your case number is <strong>{$case_id}</strong><br />
(This number should be used in all future communications regarding this query)";
			$sub_c = "Case id: $case_id";
		}else{
			$sugar_case = "";
			$sub_c = "";
		}
		
		$priority = $post['priority'];
		if ( $priority == 'Show Stopper' ) {	
			$priority = 'Critical!';	
		}
		
		$subject = "Perspective Support Request: $sub_c";
		$body = "
<strong><u>Request Details</u></strong><br />
<strong>Enquiry Type:</strong>	{$post['type']}<br />
<strong>User Priority:</strong>	{$priority}<br />
<strong>Date Logged:</strong>	".date("d/m/Y",time())."<br />
<strong>Details:</strong>		{$post['details']}<br />
";
		$template = <<<HEREDOC
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color:#000;">
<p style="font-size: small;">Dear {$_SESSION['user']->firstnames} {$_SESSION['user']->surname},</p>
<p><span style="font-size: small;">Thank you for sending your support request via Sunesis<br />
Your query has been logged $sugar_case</span></p>
<p><span style="font-size: small;">A member of the Support Team will be assigned to deal with your requests</span></p>
<p><span style="font-size: small;">You can check on your support requests by visting the 'Your Support Requests' section on Sunesis.</span></p>
<p><span style="font-size: small;">$body</span></p>
<p><span style="font-size: small;">Kind regards</span><br /><span style="font-size: small;">Perspective Team</span></p>
</div>
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color:#888;">
<p><strong style="color: #F60;">Perspective Support</strong></p>
<table style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color:#888;" border="0" cellspacing="0" cellpadding="1">
<tbody>
<tr>
<td style="color: #ff7022;" valign="top">T:</td>
<td valign="top">+44 (0) 121 506 9400</td>
<td style="border-left:1px solid black; padding-left:20px" valign="top">Perspective Limited</td>
</tr>
<tr>
<td style="color: #ff7022;" valign="top">F:</td>
<td valign="top">+44 (0) 121 506 9405</td>
<td style="border-left:1px solid black; padding-left:20px" valign="top">Blythe Valley Innovation Centre, Blythe Valley Business Park</td>
</tr>
<tr>
<td valign="top">&nbsp;</td>
<td valign="top">&nbsp;</td>
<td style="border-left:1px solid black; padding-left:20px" valign="top">Solihull B90 8AJ</td>
</tr>
<tr>
<td width="25" valign="top">&nbsp;</td>
<td width="195" valign="top">&nbsp;</td>
<td style="border-left:1px solid black; padding-left:20px" width="359" valign="top">
<p><strong><a href="http://www.perspective-uk.com/">www.perspective-uk.com</a></strong></p>
</td>
</tr>
</tbody>
</table>
<p><strong>Follow Perspective on Twitter: </strong>
<strong><a href="http://twitter.com/PerspectiveUKL">http://twitter.com/PerspectiveUKL</a></strong>
<br /> <br /> 
 Perspective  Limited is registered in England  and Wales.  Company No. 05775742 <br /> 
 Registered Office: Granville Hall, Granville    Road, Leicester, Leicestershire, LE1 7RU <br /> <br /> 
 The  information contained within this message or any of its attachments is  privileged and confidential and 
 intended for the exclusive use of the intended  recipient. If you are not the intended recipient of this 
 message any  disclosure, reproduction, distribution or other use of this communication is  strictly prohibited. 
 If you have received this message in error, please return  it to the author and destroy this copy. 
 This e-mail was sent via the MessageLabs  Security and Anti-Virus Service. 
 However, please be aware that there can be no  absolute guarantee that any files attached to this e-mail are virus free. 
 You  should perform your own virus scan before opening any attachment in this  e-mail. 
 Perspective Limited cannot accept liability for any loss or damage  which may be caused by software viruses or by the interception 
 or modification  of this e-mail. Opinions expressed within this message are not necessarily the  official policy of 
 Perspective Limited and may be the personal views of the  author.</p>
</div>
HEREDOC;
		if( mail($to,$subject,$template,$headers, '-f support@perspective-uk.com') ) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $post
	 * @param unknown_type $case_id
	 * @param unknown_type $files
	 */
	private function sendSupportEmail($post, $case_id, $files){
		$to = "sunesis.support@perspective-uk.com";
		// boundary 
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
		$headers = 'From: Perspective Support <support@perspective-uk.com>';
		$headers .= "\nMIME-Version: 1.0\nContent-Type: multipart/mixed;\n boundary=\"{$mime_boundary}\""; 
		
		$subject = "Support Request for {$_SESSION['user']->firstnames} {$_SESSION['user']->surname} of {$_SESSION['user']->org->legal_name}";

		// updated to include the case id into the support email 
		if( $case_id ) {
			$sugar_case = "<a href='https://sugar.perspective-uk.com/index.php?module=Home&query_string={$case_id}+&advanced=true&action=UnifiedSearch&search_form=false&search_mod_Cases=true&search_mod_Contacts=true'>{$case_id}</a>";
			$subject = "Support Request {$case_id}: {$_SESSION['user']->firstnames} {$_SESSION['user']->surname} of {$_SESSION['user']->org->legal_name}";
		}
		else{
			$sugar_case = "No case was created because of a communication error with Sugar";			
		}
		
		
		$details = htmlspecialchars(utf8_encode($post['details']));
		
		$body = <<<HEREDOC
Contact Details<br/>
Name:			{$_SESSION['user']->firstnames} {$_SESSION['user']->surname}<br/>
Sunesis System Owner:	{$_SESSION['user']->org->legal_name}<br/>
Email:			{$post['email']}<br/>
Telephone:		{$post['telephone']}<br/>
Fax:			{$post['fax']}<br/>
Sugar Case:		{$sugar_case}<br/>
<br/>
Request Details<br/>
Enquiry Type:	{$post['type']}<br/>
User Priority:	{$post['priority']}<br/>
Details:		{$details}<br/>
HEREDOC;
		$body = "--{$mime_boundary}\nContent-Type:text/html; charset=\"iso-8859-1\"\nContent-Transfer-Encoding: 7bit\n\n".$body."\n\n"; 
		if ( !empty($files) && $files['ufile']['name'] != '' ) {
			$file = fopen($files['ufile']['tmp_name'],"rb");
			$data = fread($file,filesize($files['ufile']['tmp_name']));
			$data = chunk_split(base64_encode($data));
			$body .= "--{$mime_boundary}\nContent-Type:{$files['ufile']['type']};\n name=\"{$files['ufile']['name']}\"\nContent-Transfer-Encoding: base64\n\n" . $data . "\n\n--{$mime_boundary}\nContent-Disposition: attachment;\nfilename=\"{$files['ufile']['name']}\"\n"; 
			fclose($file);
		}
		$body .= "--{$mime_boundary}--";		
		if( mail($to,$subject,$body,$headers, '-f support@perspective-uk.com') ) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $post
	 * @param unknown_type $files
	 */
	private function createSugarCase( $post, $files ) {
		
		$account_id = "";
		$contact_id = "";
		$contact_email = "";
		$account_name = $_SESSION['user']->org->legal_name;
		
		// look for a particular account name and then get its ID
		// re - why does this not work?
		//      28/09/2011 - because the sql bit must have a space at the start. argghhh, take heed!
		$account = $this->client->get_entry_list($this->session_id, 'Accounts', ' `accounts`.`name` like "%'.$_SESSION['user']->org->legal_name.'%" ');
		if( isset($account->entry_list[0]) ) {
		 		$account_id = $account->entry_list[0]->id;
		 		$not_holding_acct = 1;
		}

		// if account_id is not set then we need a default one
		if ( "" == $account_id ) {
			$account = $this->client->get_entry_list($this->session_id, 'Accounts', ' `accounts`.`name` = "Sunesis Support Requests" ');
			$account_name = "Sunesis Support Requests";
			if( isset($account->entry_list[0]) ) {
		 		$account_id = $account->entry_list[0]->id;
			}
		}
		
		if ( "" == $account_id ) {
			
			return ' <strong>We cannot complete your request at this time!</strong> ';
		}		
		
		// if not putting it in the holding location
		// try and get the contact
		// --- 
		$contact = $this->client->get_entry_list($this->session_id, 'Contacts', ' `contacts`.`first_name` = "'.$_SESSION['user']->firstnames.'" and `contacts`.`last_name` = "'.$_SESSION['user']->surname.'" ');	

		// found one
		if( isset($contact->entry_list[0]) ) {
			$contact_id = $contact->entry_list[0]->id;
			$contact_email = $contact->entry_list[0]->name_value_list[23]->value;
		}
		// ok we will set a new one up
		// - to have got this far we must have an account
		if ( "" == $contact_id ) {		
			// ---
			// re - create a contact / check if one exists already
			$response = $this->client->set_entry($this->session_id, 'Contacts', array(
            	array("name" => 'first_name', "value" => "{$_SESSION['user']->firstnames}"),
            	array("name" => 'last_name', "value" => "{$_SESSION['user']->surname}"),
            	array("name" => 'email1', "value" => $post['email']),
            	array("name" => 'account_id', "value" => $account_id)
         		)
        	);
        	
        	$contact_id = $response->id;
			if(!isset($response->email1) || $response->email1 == '')
				$contact_email = $post['email'];
			else
				$contact_email = $response->email1;
		}       
		
		// re - onto the actual case now
		// ---
		$category = "Incident";
		if( isset($post['type']) && $post['type'] == "Incident" ) {
			$category = "Enhancement Request";
		}
		$details = "Details: ".htmlspecialchars(utf8_encode($post['details']));
		
		
		$response = $this->client->set_entry($this->session_id, 'Cases', array(
				array("name" => 'name',"value" => "Support request: {$_SESSION['user']->firstnames} {$_SESSION['user']->surname} of {$_SESSION['user']->org->legal_name}"),
				array("name" => "description","value" => $details),
				array("name" => "account_id","value" => $account_id),
				array("name" => "status", "value" => "New"),
				array("name" => "product_c", "value" => "Sunesis"),
				array("name" => "priority_c", "value" => $post['priority']),
				array("name" => "priority", "value" => $post['priority']),
				array("name" => "case_type_c", "value" => $post['type']),
				array("name" => "origin_c", "value" => "Support Request Form"),
				array("name" => "category_c", "value" => $category),
				array("name" => "contact_id", "value" => $contact_id),
				array("name" => "contact_email", "value" => $contact_email),
				array("name" => "contact_name", "value" => "{$_SESSION['user']->firstnames} {$_SESSION['user']->surname}"),				
			)
		);

		$case_id = $response->id;
		
		$response = $this->client->get_entry($this->session_id,"Cases",$case_id,array("case_number"));
				
		$case_number =  $response->entry_list[0]->name_value_list[0]->value;
		
		if ( $files['ufile']['name'] != '' ) {

			$note=array(
  				array('name'=>'name','value'=>$files['ufile']['name']),
    			array('name'=>'description','value'=>$files['ufile']['name']),
    			array('name'=>'parent_type','value'=>'Cases')    ,
    			array('name'=>'parent_id','value'=>$case_id)
			);
			$create_note=$this->client->set_entry($this->session_id,"Notes",$note);
			
			$fp = fopen($files['ufile']['tmp_name'], 'rb');
			$file = base64_encode(fread($fp, filesize($files['ufile']['tmp_name'])));
			fclose($fp); 
			
			$result = $this->client->set_note_attachment($this->session_id, array('id'=>$create_note->id, 'filename'=>$files['ufile']['name'], 'file'=> $file));
		}

		return $case_number;
	}
	

	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $fieldName
	 */
	private function getField($fieldName) {
		if( isset($_REQUEST[$fieldName]) ) {
			return($_REQUEST[$fieldName]);
		}
		elseif( isset($_POST[$fieldName]) ) {
			return($_POST[$fieldName]);
		}
		else if( isset($_SESSION['user']->$fieldName) ) {
			return $_SESSION['user']->$fieldName;
		}
		else if( isset($_SESSION['org']->$fieldName) ) {
			return $_SESSION['org']->$fieldName;
		}
	}
}
