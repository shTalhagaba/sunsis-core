<?php
class vq_manager implements IAction{
	private $org_GUID;
	private $org_Pass;
	private $user_auth;
	public $options;
	private $client;
	private $session_id;
	public function execute(PDO $link){
		$this->user_auth = array(
			"user_name"=>"CLM Support ",
			"password"=>md5("perspective"),
			"version"=>".01");
		$this->options = array(
	        "location" => 'https://sugar.perspective-uk.com/soap.php',
	        "uri" => 'https://sugar.perspective-uk.com/',
	        "trace" => 1
	        );

//	        pre(1);
//	    $this->$org_GUID = "{FC819309-A85F-48E7-B676-63CF82D6F3EC}";
	//    $this->$org_Pass = "Her8234BeFiUp";    
	    
	       $op = array("org_GUID"=>"FC819309-A85F-48E7-B676-63CF82D6F3EC","org_Pass"=>"Her8234BeFiUp");
	        
	    $this->client = new SoapClient("http://dev.vqmanager.co.uk/cgi/VQM_WS2.cgi/wsdl/IVQM");
	    
/*	try {
            $info = $this->client->__call("GetTestStr", array("org_GUID"=>"FC819309-A85F-48E7-B676-63CF82D6F3EC","org_Pass"=>"Her8234BeFiUp"));
        } catch (SoapFault $fault) {
            $error = 1;
            print("
            alert('Sorry, blah returned the following ERROR: ".$fault->faultcode."-".$fault->faultstring.". We will now take you back to our home page.');
            window.location = 'main.php';
            ");
        } 
*/	 
		  
		  $res = $this->client->GetTestStr("FC819309-A85F-48E7-B676-63CF82D6F3EC","Her8234BeFiUp",0);
		  $res = $this->client->GetVqList("FC819309-A85F-48E7-B676-63CF82D6F3EC","Her8234BeFiUp",0);
		  $res = $this->client->GetVq("FC819309-A85F-48E7-B676-63CF82D6F3EC","Her8234BeFiUp","B80780E8-83DD-463A-821A-43849D1DDF6B",0);
		  $res = $this->client->GetUnits("FC819309-A85F-48E7-B676-63CF82D6F3EC","Her8234BeFiUp","B80780E8-83DD-463A-821A-43849D1DDF6B",0);
		  $res = $this->client->GetQualProgress("FC819309-A85F-48E7-B676-63CF82D6F3EC","Her8234BeFiUp","B80780E8-83DD-463A-821A-43849D1DDF6B",0);
		  
		  $this->client->RegUser("FC819309-A85F-48E7-B676-63CF82D6F3EC","Her8234BeFiUp","Khushnood","Khan","khushnoodahmedkhan@hotmail.com","07525250128","07525250128","07525250128","","1","");
		  
		  
		  pre($res);
	    
	    
	    $case = $this->client->get_entry_list($this->session_id,"Cases","");
	    foreach($case->field_list as $field){
		   	if($field->name == "case_type_c"){
		   		$type = array(array("","--- Please Select ---"));
		   		foreach($field->options as $case_type){
		   			$type[] = array($case_type->name,$case_type->value);
		   		}
		   	}
		   	if($field->name == "priority_c"){
		   		$priority = array(array("","--- Please Select ---"));
		   		foreach($field->options as $priority_type){
		   			$priority[] = array($priority_type->name,str_replace("Show stopper", "Critical!", $priority_type->value));
		   		}
		   	}
	    }
		$source = array(
				array("","--- Please Select ---"),
				array("Provider","Provider"),
				array("School","School"),
				array("Partnership","Partnership"));
		$area = array(
				array("","--- Please Select ---"),
				array("New School/Provider/User","New School/Provider/User"),
				array("User Access","User Access"),
				array("Learners Training Records","Learners Training Records"),
				array("Registers","Registers"),
				array("Progress","Progress"),
				array("Documentation","Documentation"),
				array("Misc","Misc"));
		$sent = false;
		if(isset($_REQUEST['subaction']) && $_REQUEST['subaction']== "send"){
			#$this->debug($_FILES);
			$case_id = $this->createSugarCase($_POST, $_FILES);
			#$case_id = "30034";
			if($this->sendSupportEmail($_POST, $case_id, $_FILES)){
				$sent = true;
				$this->sendClientEmail($_POST, $case_id);
			}else{
				throw new Exception("An error has occurred sending your support request");
			}
			
		}
		include "tpl_support_form.php";
		$response = $this->client->logout($this->session_id);
	}
	private function debug($value){
		echo "<pre>";
		print_r($value);
		die;
	}
	private function getSoapClient(){
		$this->client = new SoapClient(null, $this->options);
		try{
			$response = $this->client->login($this->user_auth,"test");
		} catch (SoapFault $e){
			return false;
		}
		$this->session_id = $response->id;
	}
	
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
		$subject = "Perspective Support Request: $sub_c";
		$body = "
<strong><u>Request Details</u></strong><br />
<strong>Source:</strong>			{$post['source']}<br />
<strong>Enquiry Type:</strong>	{$post['type']}<br />
<strong>System Area:</strong>	{$post['area']}<br />
<strong>User Priority:</strong>	{$post['priority']}<br />
<strong>Date Logged:</strong>	".date("d/m/Y",time())."<br />
<strong>Details:</strong>		{$post['details']}<br />
";
		$template = <<<HEREDOC
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color:#000;">
<p style="font-size: small;">Dear {$_SESSION['user']->firstnames} {$_SESSION['user']->surname},</p>
<p><span style="font-size: small;">Thank you for sending your support request via CLM Website<br />
Your query has been logged $sugar_case</span></p>
<p><span style="font-size: small;">A member of the Support Team will contact you at the earliest opportunity</span></p>
<p><span style="font-size: small;">$body</span></p>
<p><span style="font-size: small;">Kind regards</span><br /><span style="font-size: small;">Perspective Team</span></p>
</div>
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color:#888;">
<p><strong style="color: #F60;">Perspective Support</strong></p>
<table style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color:#888;" border="0" cellspacing="0" cellpadding="1">
<tbody>
<tr>
<td style="color: #ff7022;" valign="top">T:</td>
<td valign="top">+44 (0) 121 506 9667</td>
<td style="border-left:1px solid black; padding-left:20px" valign="top">Perspective Limited</td>
</tr>
<tr>
<td style="color: #ff7022;" valign="top">F:</td>
<td valign="top">+44 (0) 121 506 9541</td>
<td style="border-left:1px solid black; padding-left:20px" valign="top">The Oracle Building, Blythe Valley     Park</td>
</tr>
<tr>
<td valign="top">&nbsp;</td>
<td valign="top">&nbsp;</td>
<td style="border-left:1px solid black; padding-left:20px" valign="top">Solihull B90 8AD</td>
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
<strong><a href="http://twitter.com/PDPerspective">http://twitter.com/PDPerspective</a></strong>
<strong> </strong><br /> <br /> 
<img src="http://www.perspective-uk.com/images/core/perspective-logo.gif" border="0" alt="Perspective" width="225" height="48" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
<img src="http://www.perspective-uk.com/images/core/sif-logo.gif" border="0" alt="SIFA-UK-Logo" width="120" height="39" /><br />
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
		#echo "<pre>";
		#echo $template;
		#echo "</pre>";
		if(mail($to,$subject,$template,$headers)){
			return true;
		}else{
			return false;
		}
	}
	
	private function sendSupportEmail($post, $case_id, $files){
		#ini_set("SMTP","smtp.blueyonder.co.uk");
		#ini_set("sendmail_from", "allan@almartin.co.uk");
		$to = "support@perspective-uk.com";
		// boundary 
		$semi_rand = md5(time()); 
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
		$headers = 'From: Perspective Support <support@perspective-uk.com>';
		$headers .= "\nMIME-Version: 1.0\nContent-Type: multipart/mixed;\n boundary=\"{$mime_boundary}\""; 
		
		$subject = "Support Request for {$_SESSION['user']->firstnames} {$_SESSION['user']->surname} of {$_SESSION['org']->legal_name}";
		if($case_id){
			$sugar_case = "<a href='https://sugar.perspective-uk.com/index.php?module=Home&query_string={$case_id}+&advanced=true&action=UnifiedSearch&search_form=false&search_mod_Cases=true&search_mod_Contacts=true'>{$case_id}</a>";
		}else{
			$sugar_case = "No case was created because of a communication error with Sugar";
		}
		$body = <<<HEREDOC
Contact Details<br />
Name:			{$_SESSION['user']->firstnames} {$_SESSION['user']->surname}<br />
Partnership:	{$_SESSION['org']->legal_name}<br />
Email:			{$post['email']}<br />
Telephone:		{$post['telephone']}<br />
Fax:			{$post['fax']}<br />
Sugar Case:		{$sugar_case}<br />
<br />
Request Details<br />
Source:			{$post['source']}<br />
Enquiry Type:	{$post['type']}<br />
System Area:	{$post['area']}<br />
User Priority:	{$post['priority']}<br />
Details:		{$post['details']}<br />
HEREDOC;
		$body = "This is a multi-part message in MIME format.\n\n --{$mime_boundary}\nContent-Type:text/html; charset=\"iso-8859-1\"\nContent-Transfer-Encoding: 7bit\n\n" . $body . "\n\n"; 
		$file_count = count($files['ufile']['name']);
		for($x=0;$x<$file_count;$x++){
			$file = fopen($files['ufile']['tmp_name'][$x],"rb");
			$data = fread($file,filesize($files['ufile']['tmp_name'][$x]));
			$data = chunk_split(base64_encode($data));
			$body .= "--{$mime_boundary}\nContent-Type:{$files['ufile']['type'][$x]};\n name=\"{$files['ufile']['name'][$x]}\"\nContent-Transfer-Encoding: base64\n\n" . $data . "\n\n--{$mime_boundary}\nContent-Disposition: attachment;\nfilename=\"{$files['ufile']['name'][$x]}\"\n"; 
			fclose($file);
//"" .
//" " .

		}
		
		if(mail($to,$subject,$body,$headers)){
			return true;
		}else{
			return false;
		}
	}
	
	private function createSugarCase($post, $files){
		
		
		$account_id = "";
		$account = $this->client->get_entry_list($this->session_id,"Accounts","clm_id_c='{$_SESSION['org']->id}'");
		if(isset($account->entry_list[0])){
			$account_id = $account->entry_list[0]->id;
		}
		$category = "Incident";
		if($post['type']=="Incident"){
			$category = "Enhancement Request";
		}
		$details = "Source:	{$post['source']}
Area:	{$post['area']}
Details:	{$post['details']}";
		$response = $this->client->set_entry($this->session_id, 'Cases', array(
			array("name" => 'name',"value" => "Support request: {$post["area"]} for {$_SESSION['user']->firstnames} {$_SESSION['user']->surname} of {$_SESSION['org']->legal_name}"),
			array("name" => "description","value" => $details),
			array("name" => "account_id","value" => $account_id),
			array("name" => "status", "value" => "New"),
			array("name" => "product_c", "value" => "CLM2"),
			array("name" => "priority_c", "value" => $post['priority']),
			array("name" => "type_c", "value" => $post['type']),
			array("name" => "origin_c", "value" => "Support Request Form"),
			array("name" => "category_c", "value" => $category)
			)
		);
		$case_id = $response->id;
		$response = $this->client->get_entry($this->session_id,"Cases",$case_id,array("case_number"));
		$case_number =  $response->entry_list[0]->name_value_list[0]->value;
		
		$file_count = count($files['ufile']['name']);
		for($i=0;$i<$file_count;$i++){
			$note=array(
  				array('name'=>'name','value'=>$files['ufile']['name'][$i]),
    			array('name'=>'description','value'=>$files['ufile']['name'][$i]),
    			array('name'=>'parent_type','value'=>'Cases')    ,
    			array('name'=>'parent_id','value'=>$case_id)
			);
			$create_note=$this->client->set_entry($this->session_id,"Notes",$note);
			
			$fp = fopen($files['ufile']['tmp_name'][$i], 'rb');
			$file = base64_encode(fread($fp, filesize($files['ufile']['tmp_name'][$i])));
			fclose($fp); 
			
			$result = $this->client->set_note_attachment($this->session_id, array('id'=>$create_note->id, 'filename'=>$files['ufile']['name'][$i], 'file'=> $file));
		}
		return $case_number;
	}
	

	
	private function getField($fieldName){
		if(isset($_POST[$fieldName])){
			return($_POST[$fieldName]);
		}else if(isset($_SESSION['user']->$fieldName)){
			return $_SESSION['user']->$fieldName;
		}else if(isset($_SESSION['org']->$fieldName)){
			return $_SESSION['org']->$fieldName;
		}
	}
}