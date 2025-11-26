<?php
class data_transfer implements IAction
{
	public function execute(PDO $link)
	{

		
		$handle = fopen("baltic2","r");
		$st = fgets($handle);
		
		$tr_id=0;
		$user = new User();
		$tr = new TrainingRecord();
		$usernames = array();
		
		while(!feof($handle))
		{
			$st = fgets($handle);
 
			$ilr = "<ilr><learner>";
			//$ilr .= "<L01>" . trim(substr($st,0,6)) . "</L01>"; 	
			$ilr .= "<L01>" . trim(substr($st,0,6)) . "</L01>"; 	// 	Provider Number
			$ilr .= "<L02>" . trim(substr($st,6,2)) . "</L02>";	//	Contract/ Allocation type
			$ilr .= "<L03>" . trim(substr($st,8,12)) . "</L03>";	//	Learner Reference Number 
			$ilr .= "<L04>" . trim(substr($st,20,2)) . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
			$ilr .= "<L05>" . trim(substr($st,22,2)) . "</L05>"; 	// 	How many learning aims data sets inner loop
			$ilr .= "<L06>" . trim(substr($st,24,2)) . "</L06>"; 	// 	How many ESF data sets. There isn't any in case of Toyota
			$ilr .= "<L07>" . trim(substr($st,26,2)) . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
			$ilr .= "<L08>" . trim(substr($st,28,1)) . "</L08>";	//	Deletion Flag
			$ilr .= "<L09>" . trim(substr($st,29,20)) . "</L09>";	
			//$ilr .= "<L09>" . "Davis               " . "</L09>";	
			$ilr .= "<L10>" . trim(substr($st,49,40)) . "</L10>";	//	Forenames
			$ilr .= "<L11>" . trim(substr($st,89,2)) . "/" . substr($st,91,2) . "/" . substr($st,93,4) . "</L11>"; // Date of Birth
			$ilr .= "<L12>" . trim(substr($st,97,2)) . "</L12>";	//	Ethnicity
			$ilr .= "<L13>" . trim(substr($st,99,1)) . "</L13>";	//	Sex
			$ilr .= "<L14>" . trim(substr($st,100,1)) . "</L14>";	//	Learning difficulties/ disabilities/ health problems
			$ilr .= "<L15>" . trim(substr($st,101,2)) . "</L15>";	//	Disability			
			$ilr .= "<L16>" . trim(substr($st,103,2)) . "</L16>";	//	Learning difficulty
			$ilr .= "<L17>" . trim(substr($st,105,8)) . "</L17>";	//	Home postcode
			$ilr .= "<L18>" . trim(substr($st,113,30)) . "</L18>";	//	Address line 1
			$ilr .= "<L19>" . trim(substr($st,143,30)) . "</L19>";	//	Address line 2
			$ilr .= "<L20>" . trim(substr($st,173,30)) . "</L20>";	//	Address line 3
			$ilr .= "<L21>" . trim(substr($st,203,30)) . "</L21>";	//	Address line 4
			$ilr .= "<L22>" . trim(substr($st,233,8)) . "</L22>";		//	Current postcode
			$ilr .= "<L23>" . trim(substr($st,241,15)) . "</L23>";	//	Home telephone
			$ilr .= "<L24>" . trim(substr($st,256,2)) . "</L24>";	//	Country of domicile
			$ilr .= "<L25>" . trim(substr($st,258,3)) . "</L25>";	//	LSC Number of funding LSC
			$ilr .= "<L26>" . trim(substr($st,261,9)) . "</L26>";	//	National insurance number
			//$ilr .= "<L26>" . "JJ547518B" . "</L26>";
			$ilr .= "<L27>" . trim(substr($st,270,1)) . "</L27>";	//	Restricted use indicator
			$ilr .= "<L28a>" . trim(substr($st,271,2)) . "</L28a>";	//	Eligibility for enhanced funding
			$ilr .= "<L28b>" . trim(substr($st,273,2)) . "</L28b>";	//	Eligibility for enhanced funding
			$ilr .= "<L29>" . trim(substr($st,275,2)) . "</L29>";	//	Additional support
			$ilr .= "<L31>" . trim(substr($st,277,6)) . "</L31>";	//	Additional support cost 
			$ilr .= "<L32>" . trim(substr($st,283,2)) . "</L32>";	//	Eligibility for disadvatnage uplift
			$ilr .= "<L33>" . trim(substr($st,285,6)) . "</L33>";	//	Disadvatnage uplift factor
			$ilr .= "<L34a>" . trim(substr($st,291,2)) . "</L34a>";	//	Learner support reason
			$ilr .= "<L34b>" . trim(substr($st,293,2)) . "</L34b>";	//	Learner support reason
			$ilr .= "<L34c>" . trim(substr($st,295,2)) . "</L34c>";	//	Learner support reason
			$ilr .= "<L34d>" . trim(substr($st,297,2)) . "</L34d>";	//	Learner support reason
			$ilr .= "<L35>" . (int)trim(substr($st,299,2)) . "</L35>";	//	Prior attainment level
			$ilr .= "<L36>" . trim(substr($st,301,2)) . "</L36>";	//	Learner status on last working day
			$ilr .= "<L37>" . trim(substr($st,303,2)) . "</L37>";	//	Employment status on first day of learning
			$ilr .= "<L38>" . trim(substr($st,305,2)) . "</L38>";	//	No longer use. Use blanks
			$ilr .= "<L39>" . trim(substr($st,307,2)) . "</L39>";	//	Destination
			$ilr .= "<L40a>" . trim(substr($st,309,2)) . "</L40a>";	//	National learner monitoring
			$ilr .= "<L40b>" . trim(substr($st,311,2)) . "</L40b>";	//	National learner monitoring
			$ilr .= "<L41a>" . trim(substr($st,313,12)) . "</L41a>";	//	Local learner monitoring
			$ilr .= "<L41b>" . trim(substr($st,325,12)) . "</L41b>";	//	Local learner monitoring
			$ilr .= "<L42a>" . trim(substr($st,337,12)) . "</L42a>";	//	Provider specified learner data
			$ilr .= "<L42b>" . trim(substr($st,349,12)) . "</L42b>";	//	Provider specified learner data
//			$ilr .= "<L44>" . trim(substr($st,361,3)) . "</L44>";	//	NES delivery LSC number
			$ilr .= "<L44>" . "</L44>";	//	NES delivery LSC number
			$ilr .= "<L45>" . trim(substr($st,364,10)) . "</L45>";	//	Unique learner number
			
			$ilr .= "<L46>" . trim(substr($st,374,8)) . "</L46>";	
			//$ilr .= "<L46>" . "10011337" . "</L46>";	
			$ilr .= "<L47>" . trim(substr($st,382,2)) . "</L47>";	//	Current employment status
			$ilr .= "<L48>" . trim(substr($st,384,2)) . "/" . substr($st,386,2) . "/" . substr($st,388,4) . "</L48>"; // Date employment status changed

			$learning_aims = (int)substr($st,22,2);
			
			$ilr .= "<subaims>" . ((int)substr($st,22,2)-1) . "</subaims>";	//	Subaims
			$ilr .= "</learner>";
			$ilr .= "<subaims>" . ((int)substr($st,22,2)-1) . "</subaims>";	//	Subaims

			$submission="W08";
			$contract_type = "ER 0809";
			$is_complete = 0;
			$is_valid = 0;
			$is_approved = 0;
			$is_active = 1;
			$contract_id = 10;
			$L01 = substr($st,0,6);
			$L03 = substr($st,8,12);
			
			$ilr .= "<programmeaim>";
			$ilr .= "<A02></A02>";
			$ilr .= "<A04>35</A04>";
			$ilr .= "<A09>ZPROG001</A09>";
			$ilr .= "<A10>45</A10>";
			$ilr .= "<A14></A14>";
			$ilr .= "<A15></A15>";
			$ilr .= "<A16></A16>";
			$ilr .= "<A23></A23>";
			$ilr .= "<A27></A27>";
			$ilr .= "<A28></A28>";
			$ilr .= "<A26></A26>";
			$ilr .= "<A31></A31>";
			$ilr .= "<A34></A34>";
			$ilr .= "<A35></A35>";
			$ilr .= "<A46a></A46a>";
			$ilr .= "<A46b></A46b>";
			$ilr .= "<A40></A40>";
			$ilr .= "<A50></A50>";
			$ilr .= "<A51a></A51a>";
			$ilr .= "</programmeaim>";
			

			for($aims = 1; $aims<= $learning_aims; $aims++)
			{
				$st = fgets($handle);
				
				if($aims==1)
				{	
					$ilr .= "<main>";
					$A09 = substr($st,29,8);
					$start_date = trim(substr($st,92,2)) . "/" . substr($st,94,2) . "/" . substr($st,96,4);
					$end_date = trim(substr($st,100,2)) . "/" . substr($st,102,2) . "/" . substr($st,104,4);
					$status_code = trim(substr($st,126,1));
				}
				else
				{
					$ilr .= "<subaim>";
					$A09 = substr($st,29,8);
				}
					
				//$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
				$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
				$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
				$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
				$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
				$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
				$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	ESF Co-financing data set
				$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
				$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
				$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
				$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
				$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
				$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
				$ilr .= "<A12>" . "      " . "</A12>";	//	Implied rate of LSC FE funding for ESF which is not ...
				$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year

				$ilr .= "<A14>" . (int)substr($st,56,2) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee

				$ilr .= "<A15>" . trim(substr($st,58,2)) . "</A15>";	//	Programme type
				$ilr .= "<A16>" . trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
				$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
				$ilr .= "<A18>" . trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
				$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
				$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
				$ilr .= "<A21>" . trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
				$ilr .= "<A22>" . trim(substr($st,69,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
				$ilr .= "<A23>" . trim(substr($st,77,8)) . "</A23>";	//	Delivery location postcode
				$ilr .= "<A24>" . trim(substr($st,85,4)) . "</A24>";	//	Occupation relating to learning aim
				$ilr .= "<A26>" . trim(substr($st,89,3)) . "</A26>";	//	Sector framework of learning 
				$ilr .= "<A27>" . trim(substr($st,92,2)) . "/" . substr($st,94,2) . "/" . substr($st,96,4) . "</A27>"; // Learning start date
				$ilr .= "<A28>" . trim(substr($st,100,2)) . "/" . substr($st,102,2) . "/" . substr($st,104,4) . "</A28>"; // Learning planned end date
				$ilr .= "<A31>" . trim(substr($st,108,2)) . "/" . substr($st,110,2) . "/" . substr($st,112,4) . "</A31>"; // Learning actual end date
				$ilr .= "<A32>" . trim(substr($st,116,5)) . "</A32>";	//	Guided learning hours
				$ilr .= "<A33>" . trim(substr($st,121,5)) . "</A33>";	//	Blank field
				$ilr .= "<A34>" . trim(substr($st,126,1)) . "</A34>";	//	Completion status
				$ilr .= "<A35>" . trim(substr($st,127,1)) . "</A35>";	//	Learning outcome
				$ilr .= "<A36>" . trim(substr($st,128,3)) . "</A36>";	//	Learning outcome grade
				$ilr .= "<A37>" . trim(substr($st,131,2)) . "</A37>";	//	Number of units completed
				$ilr .= "<A38>" . trim(substr($st,133,2)) . "</A38>";	//	Number of units to achieve full qualification
				$ilr .= "<A39>" . trim(substr($st,135,1)) . "</A39>";	//	Eligibility for achievement funding
				$ilr .= "<A40>" . trim(substr($st,136,2)) . "/" . substr($st,138,2) . "/" . substr($st,140,4) . "</A40>"; // Achivement date
				$ilr .= "<A43>" . trim(substr($st,144,2)) . "/" . substr($st,146,2) . "/" . substr($st,148,4) . "</A43>"; // Sector framework achievment date 
				$ilr .= "<A44>" . trim(substr($st,152,30)) . "</A44>";	//	Employer identifier
				$ilr .= "<A45>" . trim(substr($st,182,8)) . "</A45>";	//	Workplace location postcode
				$ilr .= "<A46a>" . trim(substr($st,190,3)) . "</A46a>";	//	National learning aim monitoring
				$ilr .= "<A46b>" . trim(substr($st,193,3)) . "</A46b>";	//	National learning aim monitoring
				$ilr .= "<A47a>" . trim(substr($st,196,12)) . "</A47a>";	//	Local learning aim monitoring
				$ilr .= "<A47b>" . trim(substr($st,208,12)) . "</A47b>";	//	Local learning aim monitoring
				$ilr .= "<A48a>" . trim(substr($st,220,12)) . "</A48a>";	//	Provider specified learning aim data
				$ilr .= "<A48b>" . trim(substr($st,233,12)) . "</A48b>";	//	Provider specified learning aim data
				$ilr .= "<A49>" . trim(substr($st,244,5)) . "</A49>";	//	Special projects and pilots
				$ilr .= "<A50>" . trim(substr($st,249,2)) . "</A50>";	//	Reason learning ended
				$ilr .= "<A51a>" . trim(substr($st,251,2)) . "</A51a>";	//	Proportion of funding remaining
				$ilr .= "<A52>" . trim(substr($st,253,5)) . "</A52>";	//	Distance learning funding
				$ilr .= "<A53>" . trim(substr($st,258,2)) . "</A53>";	//	Additional learning needs
				$ilr .= "<A54>" . trim(substr($st,260,10)) . "</A54>";	//	Broker contract number
				$ilr .= "<A55>" . trim(substr($st,270,10)) . "</A55>";	//	Unique learner number
				//$ilr .= "<A56>" . trim(substr($st,276,8)) . "</A56>";	
				$ilr .= "<A56>" . trim(substr($st,280,10)) . "</A56>";	//	UK Provider reference number
				$ilr .= "<A57>" . trim(substr($st,288,2)) . "</A57>";	//	Source of tuition fees
				$ilr .= "<A58>" . trim(substr($st,290,2)) . "</A58>";	//	ASL Provision Type
				$ilr .= "<A59>" . trim(substr($st,292,3)) . "</A59>";	//	Planned Credit
				$ilr .= "<A60>" . trim(substr($st,295,3)) . "</A60>";	//	Credit Achieved
				
				
				if($aims==1)
					$ilr .= "</main>";
				else
					$ilr .= "</subaim>"; 

			}
			
			$ilr .= "</ilr>";

			$ilr = str_replace("'", "&#039;", $ilr);
			
			// Write in database
$query = <<<HEREDOC
insert into
	ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id)
VALUES('$L01','$L03','$A09','$ilr','$submission','$contract_type',NULL,'$is_complete','$is_valid','$is_approved','$is_active','10');
HEREDOC;
			DAO::execute($link, $query);
		}
		
		fclose($handle);
	}
}
?>