<?php
class populate_ilrs_from_batch implements IAction
{
	public function execute(PDO $link)
	{

		// Populate ILRs from Batch of 2009/10
		$handle = fopen("ilr200910.w13","r");
		$st = fgets($handle);
		$tr_id = 30000;		
		while(!feof($handle))
		{
			$st = fgets($handle);

			if(trim(substr($st,20,2))=='10')
			{
				$ilr = "<ilr><learner>";
				$ilr .= "<L01>" . trim(substr($st,0,6)) . "</L01>"; 	
				$ilr .= "<L02>" . trim(substr($st,6,2)) . "</L02>";	//	Contract/ Allocation type
				$ilr .= "<L03>" . trim(substr($st,8,12)) . "</L03>";	//	Learner Reference Number 
				$ilr .= "<L04>" . trim(substr($st,20,2)) . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
				$ilr .= "<L05>" . trim(substr($st,22,2)) . "</L05>"; 	// 	How many learning aims data sets inner loop
				//$ilr .= "<L06>" . trim(substr($st,24,2)) . "</L06>"; 	// 	How many ESF data sets. There isn't any in case of Toyota
				$ilr .= "<L07>" . trim(substr($st,24,2)) . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
				$ilr .= "<L08>" . trim(substr($st,26,1)) . "</L08>";	//	Deletion Flag
				$ilr .= "<L09>" . trim(substr($st,27,20)) . "</L09>";	
				$ilr .= "<L10>" . trim(substr($st,47,40)) . "</L10>";	//	Forenames
				$ilr .= "<L11>" . trim(substr($st,87,2)) . "/" . substr($st,89,2) . "/" . substr($st,91,4) . "</L11>"; // Date of Birth
				$ilr .= "<L12>" . trim(substr($st,95,2)) . "</L12>";	//	Ethnicity
				$ilr .= "<L13>" . trim(substr($st,97,1)) . "</L13>";	//	Sex
				$ilr .= "<L14>" . (int)trim(substr($st,98,1)) . "</L14>";	//	Learning difficulties/ disabilities/ health problems
				$ilr .= "<L15>" . (int)trim(substr($st,99,2)) . "</L15>";	//	Disability			
				$ilr .= "<L16>" . (int)trim(substr($st,101,2)) . "</L16>";	//	Learning difficulty
				$ilr .= "<L17>" . trim(substr($st,103,8)) . "</L17>";	//	Home postcode
				$ilr .= "<L18>" . trim(substr($st,111,30)) . "</L18>";	//	Address line 1
				$ilr .= "<L19>" . trim(substr($st,141,30)) . "</L19>";	//	Address line 2
				$ilr .= "<L20>" . trim(substr($st,171,30)) . "</L20>";	//	Address line 3
				$ilr .= "<L21>" . trim(substr($st,201,30)) . "</L21>";	//	Address line 4
				$ilr .= "<L22>" . trim(substr($st,231,8)) . "</L22>";		//	Current postcode
				$ilr .= "<L23>" . trim(substr($st,239,15)) . "</L23>";	//	Home telephone
				$ilr .= "<L24>" . trim(substr($st,254,2)) . "</L24>";	//	Country of domicile
				$ilr .= "<L25>" . trim(substr($st,256,3)) . "</L25>";	//	LSC Number of funding LSC
				$ilr .= "<L26>" . trim(substr($st,259,9)) . "</L26>";	//	National insurance number
				$ilr .= "<L27>" . trim(substr($st,268,1)) . "</L27>";	//	Restricted use indicator
				$ilr .= "<L28a>" . trim(substr($st,269,2)) . "</L28a>";	//	Eligibility for enhanced funding
				$ilr .= "<L28b>" . trim(substr($st,271,2)) . "</L28b>";	//	Eligibility for enhanced funding
				$ilr .= "<L29>" . trim(substr($st,273,2)) . "</L29>";	//	Additional support
				$ilr .= "<L31>" . trim(substr($st,275,6)) . "</L31>";	//	Additional support cost 
				$ilr .= "<L32>" . trim(substr($st,281,2)) . "</L32>";	//	Eligibility for disadvatnage uplift
				$ilr .= "<L33>" . trim(substr($st,283,6)) . "</L33>";	//	Disadvatnage uplift factor
				$ilr .= "<L34a>" . trim(substr($st,289,2)) . "</L34a>";	//	Learner support reason
				$ilr .= "<L34b>" . trim(substr($st,291,2)) . "</L34b>";	//	Learner support reason
				$ilr .= "<L34c>" . trim(substr($st,293,2)) . "</L34c>";	//	Learner support reason
				$ilr .= "<L34d>" . trim(substr($st,295,2)) . "</L34d>";	//	Learner support reason
				$ilr .= "<L35>" . (int)trim(substr($st,297,2)) . "</L35>";	//	Prior attainment level
				$ilr .= "<L36>" . trim(substr($st,299,2)) . "</L36>";	//	Learner status on last working day
				$ilr .= "<L37>" . (int)trim(substr($st,301,2)) . "</L37>";	//	Employment status on first day of learning
				$ilr .= "<L38></L38>";									//	No longer use. Use blanks
				$ilr .= "<L39>" . (int)trim(substr($st,303,2)) . "</L39>";	//	Destination
				$ilr .= "<L40a>" . trim(substr($st,305,2)) . "</L40a>";	//	National learner monitoring
				$ilr .= "<L40b>" . trim(substr($st,307,2)) . "</L40b>";	//	National learner monitoring
				$ilr .= "<L41a>" . trim(substr($st,309,12)) . "</L41a>";	//	Local learner monitoring
				$ilr .= "<L41b>" . trim(substr($st,321,12)) . "</L41b>";	//	Local learner monitoring
				$ilr .= "<L42a>" . trim(substr($st,333,12)) . "</L42a>";	//	Provider specified learner data
				$ilr .= "<L42b>" . trim(substr($st,345,12)) . "</L42b>";	//	Provider specified learner data
				if(trim(substr($st,357,3))=='000')
					$ilr .= "<L44>" . "</L44>";	//	NES delivery LSC number
				else
					$ilr .= "<L44>" . trim(substr($st,357,3)) . "</L44>";	//	NES delivery LSC number
				$ilr .= "<L45>" . trim(substr($st,360,10)) . "</L45>";	//	Unique learner number
				$ilr .= "<L46>" . trim(substr($st,370,8)) . "</L46>";	
				$ilr .= "<L47>" . (int)trim(substr($st,378,2)) . "</L47>";	//	Current employment status
				$ilr .= "<L48>" . trim(substr($st,380,2)) . "/" . substr($st,382,2) . "/" . substr($st,384,4) . "</L48>"; // Date employment status changed
				$ilr .= "<L49a>" . trim(substr($st,388,2)) . "</L49a>";	//	Current employment status
				$ilr .= "<L49b>" . trim(substr($st,390,2)) . "</L49b>";	//	Current employment status
				$ilr .= "<L49c>" . trim(substr($st,392,2)) . "</L49c>";	//	Current employment status
				$ilr .= "<L49d>" . trim(substr($st,394,2)) . "</L49d>";	//	Current employment status
								
				$learning_aims = (int)substr($st,22,2);
				
			//	$ilr .= "<subaims>" . ((int)substr($st,22,2)-1) . "</subaims>";	//	Subaims
				$ilr .= "</learner>";
			//	$ilr .= "<subaims>" . ((int)substr($st,22,2)-1) . "</subaims>";	//	Subaims
			}
				
			$submission="W13";
			$contract_type = "LSC 0910";
			$is_complete = 0;
			$is_valid = 0;
			$is_approved = 0;
			$is_active = 1;
			$contract_id = 6;
			$L01 = substr($st,0,6);
			$L03 = substr($st,8,12);
			$aims = 0;
			for($aims = 1; $aims <= $learning_aims; $aims++)
			{
				$st = fgets($handle);
				$A09 = substr($st,27,8);
				if($A09=='ZPROG001')
				{
					$ilr .= "<subaims>" . (((int)$learning_aims)-2) . "</subaims>";	//	Subaims
					$ilr .= "<programmeaim>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,24,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,26,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,27,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,35,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,37,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,40,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,44,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,48,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,50,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,52,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,54,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,55,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,57,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,58,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,59,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,61,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,69,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,77,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,80,2)) . "/" . substr($st,82,2) . "/" . substr($st,84,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,88,2)) . "/" . substr($st,90,2) . "/" . substr($st,92,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,96,2)) . "/" . substr($st,98,2) . "/" . substr($st,100,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,104,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,109,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,110,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,111,6)) . "</A36>";	//	Learning outcome grade
	//				$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
	//				$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
	//				$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,117,2)) . "/" . substr($st,119,2) . "/" . substr($st,121,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,125,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,155,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,163,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,166,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,169,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,181,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,193,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,205,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,217,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,222,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,224,3)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,227,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,232,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,234,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,244,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,254,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,262,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,264,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,266,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,269,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61>" . trim(substr($st,272,9)) . "</A61>";	//	Source of tuition fees
					$ilr .= "<A62>" . trim(substr($st,281,3)) . "</A62>";	//	Source of tuition fees
					$ilr .= "<A63>" . (int)trim(substr($st,284,2)) . "</A63>";	//	Source of tuition fees
					$ilr .= "<A64>" . trim(substr($st,286,5)) . "</A64>";	//	Source of tuition fees
					$ilr .= "<A65>" . trim(substr($st,291,5)) . "</A65>";	//	Source of tuition fees
					$ilr .= "<A66>" . (int)trim(substr($st,296,2)) . "</A66>";	//	Source of tuition fees
					$ilr .= "<A67>" . (int)trim(substr($st,298,2)) . "</A67>";	//	Source of tuition fees
					$ilr .= "<A68>" . (int)trim(substr($st,300,2)) . "</A68>";	//	Source of tuition fees
					$ilr .= "</programmeaim>";					
					$st = fgets($handle);
					$aims++;
					$A09m = $A09;
					$ilr .= "<main>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,24,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,26,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,27,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,35,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,37,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,40,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,44,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,48,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,50,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,52,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,54,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,55,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,57,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,58,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,59,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,61,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,69,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,77,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,80,2)) . "/" . substr($st,82,2) . "/" . substr($st,84,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,88,2)) . "/" . substr($st,90,2) . "/" . substr($st,92,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,96,2)) . "/" . substr($st,98,2) . "/" . substr($st,100,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,104,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,109,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,110,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,111,6)) . "</A36>";	//	Learning outcome grade
	//				$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
	//				$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
	//				$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,117,2)) . "/" . substr($st,119,2) . "/" . substr($st,121,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,125,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,155,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,163,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,166,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,169,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,181,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,193,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,205,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,217,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,222,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,224,3)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,227,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,232,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,234,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,244,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,254,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,262,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,264,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,266,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,269,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61>" . trim(substr($st,272,9)) . "</A61>";	//	Source of tuition fees
					$ilr .= "<A62>" . trim(substr($st,281,3)) . "</A62>";	//	Source of tuition fees
					$ilr .= "<A63>" . (int)trim(substr($st,284,2)) . "</A63>";	//	Source of tuition fees
					$ilr .= "<A64>" . trim(substr($st,286,5)) . "</A64>";	//	Source of tuition fees
					$ilr .= "<A65>" . trim(substr($st,291,5)) . "</A65>";	//	Source of tuition fees
					$ilr .= "<A66>" . (int)trim(substr($st,296,2)) . "</A66>";	//	Source of tuition fees
					$ilr .= "<A67>" . (int)trim(substr($st,298,2)) . "</A67>";	//	Source of tuition fees
					$ilr .= "<A68>" . (int)trim(substr($st,300,2)) . "</A68>";	//	Source of tuition fees
					$ilr .= "</main>";					
				}
				elseif($A09=='ZESF0001') 
				{	
					$aims++;
					$st = fgets($handle);
					$ilr .= "<subaims>" . (((int)$learning_aims)-2) . "</subaims>";	//	Subaims
					$ilr .= "<programmeaim>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,24,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,26,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,27,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,35,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,37,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,40,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,44,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,48,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,50,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,52,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,54,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,55,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,57,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,58,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,59,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,61,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,69,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,77,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,80,2)) . "/" . substr($st,82,2) . "/" . substr($st,84,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,88,2)) . "/" . substr($st,90,2) . "/" . substr($st,92,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,96,2)) . "/" . substr($st,98,2) . "/" . substr($st,100,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,104,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,109,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,110,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,111,6)) . "</A36>";	//	Learning outcome grade
	//				$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
	//				$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
	//				$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,117,2)) . "/" . substr($st,119,2) . "/" . substr($st,121,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,125,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,155,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,163,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,166,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,169,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,181,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,193,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,205,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,217,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,222,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,224,3)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,227,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,232,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,234,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,244,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,254,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,262,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,264,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,266,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,269,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61>" . trim(substr($st,272,9)) . "</A61>";	//	Source of tuition fees
					$ilr .= "<A62>" . trim(substr($st,281,3)) . "</A62>";	//	Source of tuition fees
					$ilr .= "<A63>" . (int)trim(substr($st,284,2)) . "</A63>";	//	Source of tuition fees
					$ilr .= "<A64>" . trim(substr($st,286,5)) . "</A64>";	//	Source of tuition fees
					$ilr .= "<A65>" . trim(substr($st,291,5)) . "</A65>";	//	Source of tuition fees
					$ilr .= "<A66>" . (int)trim(substr($st,296,2)) . "</A66>";	//	Source of tuition fees
					$ilr .= "<A67>" . (int)trim(substr($st,298,2)) . "</A67>";	//	Source of tuition fees
					$ilr .= "<A68>" . (int)trim(substr($st,300,2)) . "</A68>";	//	Source of tuition fees
					$ilr .= "</programmeaim>";					
					$A09m = $A09;
					$ilr .= "<main>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,24,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,26,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,27,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,35,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,37,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,40,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,44,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,48,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,50,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,52,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,54,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,55,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,57,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,58,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,59,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,61,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,69,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,77,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,80,2)) . "/" . substr($st,82,2) . "/" . substr($st,84,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,88,2)) . "/" . substr($st,90,2) . "/" . substr($st,92,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,96,2)) . "/" . substr($st,98,2) . "/" . substr($st,100,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,104,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,109,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,110,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,111,6)) . "</A36>";	//	Learning outcome grade
	//				$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
	//				$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
	//				$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,117,2)) . "/" . substr($st,119,2) . "/" . substr($st,121,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,125,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,155,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,163,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,166,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,169,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,181,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,193,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,205,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,217,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,222,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,224,3)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,227,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,232,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,234,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,244,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,254,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,262,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,264,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,266,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,269,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61>" . trim(substr($st,272,9)) . "</A61>";	//	Source of tuition fees
					$ilr .= "<A62>" . trim(substr($st,281,3)) . "</A62>";	//	Source of tuition fees
					$ilr .= "<A63>" . (int)trim(substr($st,284,2)) . "</A63>";	//	Source of tuition fees
					$ilr .= "<A64>" . trim(substr($st,286,5)) . "</A64>";	//	Source of tuition fees
					$ilr .= "<A65>" . trim(substr($st,291,5)) . "</A65>";	//	Source of tuition fees
					$ilr .= "<A66>" . (int)trim(substr($st,296,2)) . "</A66>";	//	Source of tuition fees
					$ilr .= "<A67>" . (int)trim(substr($st,298,2)) . "</A67>";	//	Source of tuition fees
					$ilr .= "<A68>" . (int)trim(substr($st,300,2)) . "</A68>";	//	Source of tuition fees
					$ilr .= "</main>";					
				}
				elseif($aims==1)
				{
					$ilr .= "<programmeaim>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,24,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,26,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,27,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,35,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,37,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,40,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,44,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,48,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,50,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,52,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,54,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,55,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,57,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,58,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,59,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,61,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,69,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,77,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,80,2)) . "/" . substr($st,82,2) . "/" . substr($st,84,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,88,2)) . "/" . substr($st,90,2) . "/" . substr($st,92,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,96,2)) . "/" . substr($st,98,2) . "/" . substr($st,100,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,104,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,109,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,110,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,111,6)) . "</A36>";	//	Learning outcome grade
	//				$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
	//				$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
	//				$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,117,2)) . "/" . substr($st,119,2) . "/" . substr($st,121,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,125,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,155,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,163,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,166,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,169,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,181,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,193,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,205,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,217,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,222,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,224,3)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,227,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,232,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,234,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,244,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,254,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,262,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,264,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,266,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,269,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61>" . trim(substr($st,272,9)) . "</A61>";	//	Source of tuition fees
					$ilr .= "<A62>" . trim(substr($st,281,3)) . "</A62>";	//	Source of tuition fees
					$ilr .= "<A63>" . (int)trim(substr($st,284,2)) . "</A63>";	//	Source of tuition fees
					$ilr .= "<A64>" . trim(substr($st,286,5)) . "</A64>";	//	Source of tuition fees
					$ilr .= "<A65>" . trim(substr($st,291,5)) . "</A65>";	//	Source of tuition fees
					$ilr .= "<A66>" . (int)trim(substr($st,296,2)) . "</A66>";	//	Source of tuition fees
					$ilr .= "<A67>" . (int)trim(substr($st,298,2)) . "</A67>";	//	Source of tuition fees
					$ilr .= "<A68>" . (int)trim(substr($st,300,2)) . "</A68>";	//	Source of tuition fees
					$ilr .= "</programmeaim>";					
					$ilr .= "<main>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,24,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,26,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,27,8)) . "</A09>";	//	Learning aim reference
					$A09m = trim(substr($st,27,8));
					$ilr .= "<A10>" . trim(substr($st,35,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,37,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,40,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,44,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,48,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,50,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,52,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,54,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,55,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,57,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,58,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,59,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,61,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,69,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,77,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,80,2)) . "/" . substr($st,82,2) . "/" . substr($st,84,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,88,2)) . "/" . substr($st,90,2) . "/" . substr($st,92,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,96,2)) . "/" . substr($st,98,2) . "/" . substr($st,100,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,104,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,109,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,110,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,111,6)) . "</A36>";	//	Learning outcome grade
	//				$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
	//				$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
	//				$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,117,2)) . "/" . substr($st,119,2) . "/" . substr($st,121,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,125,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,155,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,163,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,166,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,169,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,181,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,193,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,205,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,217,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,222,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,224,3)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,227,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,232,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,234,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,244,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,254,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,262,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,264,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,266,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,269,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61>" . trim(substr($st,272,9)) . "</A61>";	//	Source of tuition fees
					$ilr .= "<A62>" . trim(substr($st,281,3)) . "</A62>";	//	Source of tuition fees
					$ilr .= "<A63>" . (int)trim(substr($st,284,2)) . "</A63>";	//	Source of tuition fees
					$ilr .= "<A64>" . trim(substr($st,286,5)) . "</A64>";	//	Source of tuition fees
					$ilr .= "<A65>" . trim(substr($st,291,5)) . "</A65>";	//	Source of tuition fees
					$ilr .= "<A66>" . (int)trim(substr($st,296,2)) . "</A66>";	//	Source of tuition fees
					$ilr .= "<A67>" . (int)trim(substr($st,298,2)) . "</A67>";	//	Source of tuition fees
					$ilr .= "<A68>" . (int)trim(substr($st,300,2)) . "</A68>";	//	Source of tuition fees
					$ilr .= "</main>";					
				}
				else
				{
					$ilr .= "<subaim>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,24,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,26,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,27,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,35,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,37,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,40,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,44,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,48,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,50,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,52,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,54,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,55,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,57,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,58,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,59,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,61,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,69,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,77,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,80,2)) . "/" . substr($st,82,2) . "/" . substr($st,84,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,88,2)) . "/" . substr($st,90,2) . "/" . substr($st,92,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,96,2)) . "/" . substr($st,98,2) . "/" . substr($st,100,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,104,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,109,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,110,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,111,6)) . "</A36>";	//	Learning outcome grade
	//				$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
	//				$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
	//				$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,117,2)) . "/" . substr($st,119,2) . "/" . substr($st,121,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,125,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,155,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,163,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,166,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,169,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,181,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,193,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,205,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,217,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,222,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,224,3)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,227,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,232,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,234,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,244,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,254,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,262,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,264,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,266,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,269,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61>" . trim(substr($st,272,9)) . "</A61>";	//	Source of tuition fees
					$ilr .= "<A62>" . trim(substr($st,281,3)) . "</A62>";	//	Source of tuition fees
					$ilr .= "<A63>" . (int)trim(substr($st,284,2)) . "</A63>";	//	Source of tuition fees
					$ilr .= "<A64>" . trim(substr($st,286,5)) . "</A64>";	//	Source of tuition fees
					$ilr .= "<A65>" . trim(substr($st,291,5)) . "</A65>";	//	Source of tuition fees
					$ilr .= "<A66>" . (int)trim(substr($st,296,2)) . "</A66>";	//	Source of tuition fees
					$ilr .= "<A67>" . (int)trim(substr($st,298,2)) . "</A67>";	//	Source of tuition fees
					$ilr .= "<A68>" . (int)trim(substr($st,300,2)) . "</A68>";	//	Source of tuition fees
					$ilr .= "</subaim>";					
				}
			}
			
			$ilr .= "</ilr>";

			
			// Write in database
$tr_id++;
$ilr = str_replace("'","&apos;",$ilr);

$query = <<<HEREDOC
insert into
	ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id)
VALUES('$L01','$L03','$A09m','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');
HEREDOC;
			DAO::execute($link, $query);
		}
		
		fclose($handle);

		
		
/*		
		// Populate ILRs from Batch of 2008/09
		$handle = fopen("ilr200809.w13","r");
		$st = fgets($handle);
		$tr_id = 20000;		
		while(!feof($handle))
		{
			$st = fgets($handle);

			if(trim(substr($st,20,2))=='10')
			{
				$ilr = "<ilr><learner>";
				$ilr .= "<L01>" . trim(substr($st,0,6)) . "</L01>"; 	
				$ilr .= "<L02>" . trim(substr($st,6,2)) . "</L02>";	//	Contract/ Allocation type
				$ilr .= "<L03>" . trim(substr($st,8,12)) . "</L03>";	//	Learner Reference Number 
				$ilr .= "<L04>" . trim(substr($st,20,2)) . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
				$ilr .= "<L05>" . trim(substr($st,22,2)) . "</L05>"; 	// 	How many learning aims data sets inner loop
				$ilr .= "<L06>" . trim(substr($st,24,2)) . "</L06>"; 	// 	How many ESF data sets. There isn't any in case of Toyota
				$ilr .= "<L07>" . trim(substr($st,26,2)) . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
				$ilr .= "<L08>" . trim(substr($st,28,1)) . "</L08>";	//	Deletion Flag
				$ilr .= "<L09>" . trim(substr($st,29,20)) . "</L09>";	
				$ilr .= "<L10>" . trim(substr($st,49,40)) . "</L10>";	//	Forenames
				$ilr .= "<L11>" . trim(substr($st,89,2)) . "/" . substr($st,91,2) . "/" . substr($st,93,4) . "</L11>"; // Date of Birth
				$ilr .= "<L12>" . trim(substr($st,97,2)) . "</L12>";	//	Ethnicity
				$ilr .= "<L13>" . trim(substr($st,99,1)) . "</L13>";	//	Sex
				$ilr .= "<L14>" . (int)trim(substr($st,100,1)) . "</L14>";	//	Learning difficulties/ disabilities/ health problems
				$ilr .= "<L15>" . (int)trim(substr($st,101,2)) . "</L15>";	//	Disability			
				$ilr .= "<L16>" . (int)trim(substr($st,103,2)) . "</L16>";	//	Learning difficulty
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
				$ilr .= "<L27>" . trim(substr($st,270,1)) . "</L27>";	//	Restricted use indicator
				$ilr .= "<L28a>" . trim(substr($st,271,2)) . "</L28a>";	//	Eligibility for enhanced funding
				$ilr .= "<L28b>" . trim(substr($st,273,2)) . "</L28b>";	//	Eligibility for enhanced funding
				$ilr .= "<L29>" . trim(substr($st,275,2)) . "</L29>";	//	Additional support
				$ilr .= "<L31>" . trim(substr($st,277,6)) . "</L31>";	//	Additional support cost 
				$ilr .= "<L32>" . trim(substr($st,283,2)) . "</L32>";	//	Eligibility for disadvatnage uplift
				$ilr .= "<L33>" . trim(substr($st,285,6)) . "</L33>";	//	Disadvatnage uplift factor
				$ilr .= "<L34a>" . trim(substr($st,291,2)) . "</L34a>";	//	Learner support reason
				$ilr .= "<L34b>" . trim(substr($st,293,2)) . "</L34b>";	//	Learner support reason
				$ilr .= "<L34c>" . trim(substr($st,293,2)) . "</L34c>";	//	Learner support reason
				$ilr .= "<L34d>" . trim(substr($st,297,2)) . "</L34d>";	//	Learner support reason
				$ilr .= "<L35>" . (int)trim(substr($st,299,2)) . "</L35>";	//	Prior attainment level
				$ilr .= "<L36>" . trim(substr($st,301,2)) . "</L36>";	//	Learner status on last working day
				$ilr .= "<L37>" . (int)trim(substr($st,303,2)) . "</L37>";	//	Employment status on first day of learning
				$ilr .= "<L38></L38>";									//	No longer use. Use blanks
				$ilr .= "<L39>" . (int)trim(substr($st,307,2)) . "</L39>";	//	Destination
				$ilr .= "<L40a>" . trim(substr($st,309,2)) . "</L40a>";	//	National learner monitoring
				$ilr .= "<L40b>" . trim(substr($st,311,2)) . "</L40b>";	//	National learner monitoring
				$ilr .= "<L41a>" . trim(substr($st,313,12)) . "</L41a>";	//	Local learner monitoring
				$ilr .= "<L41b>" . trim(substr($st,325,12)) . "</L41b>";	//	Local learner monitoring
				$ilr .= "<L42a>" . trim(substr($st,337,12)) . "</L42a>";	//	Provider specified learner data
				$ilr .= "<L42b>" . trim(substr($st,349,12)) . "</L42b>";	//	Provider specified learner data
				if(trim(substr($st,361,3))=='000')
					$ilr .= "<L44>" . "</L44>";	//	NES delivery LSC number
				else
					$ilr .= "<L44>" . trim(substr($st,361,3)) . "</L44>";	//	NES delivery LSC number
				$ilr .= "<L45>" . trim(substr($st,364,10)) . "</L45>";	//	Unique learner number
				$ilr .= "<L46>" . trim(substr($st,374,8)) . "</L46>";	
				$ilr .= "<L47>" . (int)trim(substr($st,382,2)) . "</L47>";	//	Current employment status
				$ilr .= "<L48>" . trim(substr($st,384,2)) . "/" . substr($st,386,2) . "/" . substr($st,388,4) . "</L48>"; // Date employment status changed
				$ilr .= "<L49a>" . trim(substr($st,392,2)) . "</L49a>";	//	Current employment status
				$ilr .= "<L49b>" . trim(substr($st,394,2)) . "</L49b>";	//	Current employment status
				$ilr .= "<L49c>" . trim(substr($st,396,2)) . "</L49c>";	//	Current employment status
				$ilr .= "<L49d>" . trim(substr($st,398,2)) . "</L49d>";	//	Current employment status
								
				$learning_aims = (int)substr($st,22,2);
				
			//	$ilr .= "<subaims>" . ((int)substr($st,22,2)-1) . "</subaims>";	//	Subaims
				$ilr .= "</learner>";
			//	$ilr .= "<subaims>" . ((int)substr($st,22,2)-1) . "</subaims>";	//	Subaims
			}
				
			$submission="W13";
			$contract_type = "LSC 0809";
			$is_complete = 1;
			$is_valid = 1;
			$is_approved = 1;
			$is_active = 1;
			$contract_id = 4;
			$L01 = substr($st,0,6);
			$L03 = substr($st,8,12);
			$aims = 0;
			for($aims = 1; $aims <= $learning_aims; $aims++)
			{
				$st = fgets($handle);
				$A09 = substr($st,29,8);
				if($A09=='ZPROG001')
				{
					$ilr .= "<subaims>" . (((int)$learning_aims)-2) . "</subaims>";	//	Subaims
					$ilr .= "<programmeaim>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,69,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,77,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A24>" . trim(substr($st,85,4)) . "</A24>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,89,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,92,2)) . "/" . substr($st,94,2) . "/" . substr($st,96,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,100,2)) . "/" . substr($st,102,2) . "/" . substr($st,104,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,108,2)) . "/" . substr($st,110,2) . "/" . substr($st,112,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,116,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,126,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,127,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,128,3)) . "</A36>";	//	Learning outcome grade
					$ilr .= "<A37>" . trim(substr($st,131,2)) . "</A37>";	//	Number of units completed
					$ilr .= "<A38>" . trim(substr($st,133,2)) . "</A38>";	//	Number of units to achieve full qualification
					$ilr .= "<A39>" . trim(substr($st,135,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,136,2)) . "/" . substr($st,138,2) . "/" . substr($st,140,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,152,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,182,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,190,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,193,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,196,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,208,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,220,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,232,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,244,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,249,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,251,2)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,253,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,258,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,260,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,270,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,280,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,288,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,290,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,292,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,295,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61></A61>";	//	Source of tuition fees
					$ilr .= "<A62></A62>";	//	Source of tuition fees
					$ilr .= "<A63></A63>";	//	Source of tuition fees
					$ilr .= "<A64></A64>";	//	Source of tuition fees
					$ilr .= "<A65></A65>";	//	Source of tuition fees
					$ilr .= "<A66></A66>";	//	Source of tuition fees
					$ilr .= "<A67></A67>";	//	Source of tuition fees
					$ilr .= "<A68></A68>";	//	Source of tuition fees
					$ilr .= "</programmeaim>";					
					$st = fgets($handle);
					$aims++;
					$A09m = $A09;
					$ilr .= "<main>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,69,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,77,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A24>" . trim(substr($st,85,4)) . "</A24>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,89,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,92,2)) . "/" . substr($st,94,2) . "/" . substr($st,96,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,100,2)) . "/" . substr($st,102,2) . "/" . substr($st,104,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,108,2)) . "/" . substr($st,110,2) . "/" . substr($st,112,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,116,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,126,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,127,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,128,3)) . "</A36>";	//	Learning outcome grade
					$ilr .= "<A37>" . trim(substr($st,131,2)) . "</A37>";	//	Number of units completed
					$ilr .= "<A38>" . trim(substr($st,133,2)) . "</A38>";	//	Number of units to achieve full qualification
					$ilr .= "<A39>" . trim(substr($st,135,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,136,2)) . "/" . substr($st,138,2) . "/" . substr($st,140,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,152,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,182,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,190,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,193,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,196,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,208,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,220,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,232,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,244,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,249,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,251,2)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,253,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,258,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,260,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,270,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,280,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,288,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,290,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,292,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,295,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61></A61>";	//	Source of tuition fees
					$ilr .= "<A62></A62>";	//	Source of tuition fees
					$ilr .= "<A63></A63>";	//	Source of tuition fees
					$ilr .= "<A64></A64>";	//	Source of tuition fees
					$ilr .= "<A65></A65>";	//	Source of tuition fees
					$ilr .= "<A66></A66>";	//	Source of tuition fees
					$ilr .= "<A67></A67>";	//	Source of tuition fees
					$ilr .= "<A68></A68>";	//	Source of tuition fees
					$ilr .= "</main>";					
				}
				elseif($aims==1)
				{
					$ilr .= "<programmeaim>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>ZPROG0001</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,69,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,77,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A24>" . trim(substr($st,85,4)) . "</A24>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,89,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,92,2)) . "/" . substr($st,94,2) . "/" . substr($st,96,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,100,2)) . "/" . substr($st,102,2) . "/" . substr($st,104,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,108,2)) . "/" . substr($st,110,2) . "/" . substr($st,112,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,116,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,126,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,127,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,128,3)) . "</A36>";	//	Learning outcome grade
					$ilr .= "<A37>" . trim(substr($st,131,2)) . "</A37>";	//	Number of units completed
					$ilr .= "<A38>" . trim(substr($st,133,2)) . "</A38>";	//	Number of units to achieve full qualification
					$ilr .= "<A39>" . trim(substr($st,135,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,136,2)) . "/" . substr($st,138,2) . "/" . substr($st,140,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,152,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,182,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,190,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,193,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,196,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,208,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,220,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,232,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,244,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,249,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,251,2)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,253,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,258,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,260,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,270,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,280,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,288,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,290,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,292,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,295,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61></A61>";	//	Source of tuition fees
					$ilr .= "<A62></A62>";	//	Source of tuition fees
					$ilr .= "<A63></A63>";	//	Source of tuition fees
					$ilr .= "<A64></A64>";	//	Source of tuition fees
					$ilr .= "<A65></A65>";	//	Source of tuition fees
					$ilr .= "<A66></A66>";	//	Source of tuition fees
					$ilr .= "<A67></A67>";	//	Source of tuition fees
					$ilr .= "<A68></A68>";	//	Source of tuition fees
					$ilr .= "</programmeaim>";					
					$ilr .= "<main>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,69,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,77,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A24>" . trim(substr($st,85,4)) . "</A24>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,89,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,92,2)) . "/" . substr($st,94,2) . "/" . substr($st,96,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,100,2)) . "/" . substr($st,102,2) . "/" . substr($st,104,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,108,2)) . "/" . substr($st,110,2) . "/" . substr($st,112,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,116,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,126,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,127,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,128,3)) . "</A36>";	//	Learning outcome grade
					$ilr .= "<A37>" . trim(substr($st,131,2)) . "</A37>";	//	Number of units completed
					$ilr .= "<A38>" . trim(substr($st,133,2)) . "</A38>";	//	Number of units to achieve full qualification
					$ilr .= "<A39>" . trim(substr($st,135,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,136,2)) . "/" . substr($st,138,2) . "/" . substr($st,140,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,152,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,182,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,190,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,193,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,196,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,208,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,220,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,232,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,244,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,249,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,251,2)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,253,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,258,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,260,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,270,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,280,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,288,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,290,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,292,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,295,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61></A61>";	//	Source of tuition fees
					$ilr .= "<A62></A62>";	//	Source of tuition fees
					$ilr .= "<A63></A63>";	//	Source of tuition fees
					$ilr .= "<A64></A64>";	//	Source of tuition fees
					$ilr .= "<A65></A65>";	//	Source of tuition fees
					$ilr .= "<A66></A66>";	//	Source of tuition fees
					$ilr .= "<A67></A67>";	//	Source of tuition fees
					$ilr .= "<A68></A68>";	//	Source of tuition fees
					if(substr($st,24,2)=='01')
					{
						$st = fgets($handle);
						$ilr .= "<E01>" . trim(substr($st,0,6)) . "</E01>";
						$ilr .= "<E02>" . trim(substr($st,6,2)) . "</E02>";
						$ilr .= "<E03>" . trim(substr($st,8,12)) . "</E03>";
						$ilr .= "<E04>" . trim(substr($st,20,2)) . "</E04>";
						$ilr .= "<E05>" . trim(substr($st,22,2)) . "</E05>";
						$ilr .= "<E06>" . trim(substr($st,24,2)) . "</E06>";
						$ilr .= "<E07>" . trim(substr($st,26,2)) . "</E07>";
						$ilr .= "<E08>" . substr($st,28,2) . "/" . substr($st,30,2) . "/" . substr($st,32,4) . "</E08>";
						$ilr .= "<E09>" . substr($st,36,2) . "/" . substr($st,38,2) . "/" . substr($st,40,4) . "</E09>";
						$ilr .= "<E10>" . substr($st,44,2) . "/" . substr($st,46,2) . "/" . substr($st,48,4) . "</E10>";
						$ilr .= "<E11>" . trim(substr($st,52,2)) . "</E11>";
						$ilr .= "<E12>" . trim(substr($st,54,2)) . "</E12>";
						$ilr .= "<E13>" . trim(substr($st,56,2)) . "</E13>";
						$ilr .= "<E14>" . trim(substr($st,58,2)) . "</E14>";
						$ilr .= "<E15>" . trim(substr($st,60,2)) . "</E15>";
						$ilr .= "<E16a>" . trim(substr($st,62,1)) . "</E16a>";
						$ilr .= "<E16b>" . trim(substr($st,63,1)) . "</E16b>";
						$ilr .= "<E16c>" . trim(substr($st,64,1)) . "</E16c>";
						$ilr .= "<E16d>" . trim(substr($st,65,1)) . "</E16d>";
						$ilr .= "<E16e>" . trim(substr($st,66,1)) . "</E16e>";
						$ilr .= "<E17>" . trim(substr($st,67,5)) . "</E17>";
						$ilr .= "<E18a>" . trim(substr($st,72,1)) . "</E18a>";
						$ilr .= "<E18b>" . trim(substr($st,73,1)) . "</E18b>";
						$ilr .= "<E18c>" . trim(substr($st,74,1)) . "</E18c>";
						$ilr .= "<E18d>" . trim(substr($st,75,1)) . "</E18d>";
						$ilr .= "<E19a>" . trim(substr($st,76,1)) . "</E19a>";
						$ilr .= "<E19b>" . trim(substr($st,77,1)) . "</E19b>";
						$ilr .= "<E19c>" . trim(substr($st,78,1)) . "</E19c>";
						$ilr .= "<E19d>" . trim(substr($st,79,1)) . "</E19d>";
						$ilr .= "<E19e>" . trim(substr($st,80,1)) . "</E19e>";
						$ilr .= "<E20a>" . trim(substr($st,81,2)) . "</E20a>";
						$ilr .= "<E20b>" . trim(substr($st,83,2)) . "</E20b>";
						$ilr .= "<E20c>" . trim(substr($st,85,2)) . "</E20c>";
						$ilr .= "<E21>" . trim(substr($st,87,2)) . "</E21>";
						$ilr .= "<E22>" . trim(substr($st,89,9)) . "</E22>";
						$ilr .= "<E23>" . trim(substr($st,98,3)) . "</E23>";
						$ilr .= "<E24>" . trim(substr($st,101,10)) . "</E24>";
						$ilr .= "<E25>" . trim(substr($st,111,8)) . "</E25>";
					}
					$ilr .= "</main>";					
				}
				else 
				{	
					$ilr .= "<subaim>";
					$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
					$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
					$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
					$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
					$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
					$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
					$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
					$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
					$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
					$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
					$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
					$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
					$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
					$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
					$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
					$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
					$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
					$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
					$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
					$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
					$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
					$ilr .= "<A22>" . trim(substr($st,69,8)) . "</A22>";	//	Franchised out and partnership delivery provider number
					$ilr .= "<A23>" . trim(substr($st,77,8)) . "</A23>";	//	Delivery location postcode
					$ilr .= "<A24>" . trim(substr($st,85,4)) . "</A24>";	//	Delivery location postcode
					$ilr .= "<A26>" . trim(substr($st,89,3)) . "</A26>";	//	Sector framework of learning 
					$ilr .= "<A27>" . trim(substr($st,92,2)) . "/" . substr($st,94,2) . "/" . substr($st,96,4) . "</A27>"; // Learning start date
					$ilr .= "<A28>" . trim(substr($st,100,2)) . "/" . substr($st,102,2) . "/" . substr($st,104,4) . "</A28>"; // Learning planned end date
					$ilr .= "<A31>" . trim(substr($st,108,2)) . "/" . substr($st,110,2) . "/" . substr($st,112,4) . "</A31>"; // Learning actual end date
					$ilr .= "<A32>" . trim(substr($st,116,5)) . "</A32>";	//	Guided learning hours
					$ilr .= "<A34>" . trim(substr($st,126,1)) . "</A34>";	//	Completion status
					$ilr .= "<A35>" . trim(substr($st,127,1)) . "</A35>";	//	Learning outcome
					$ilr .= "<A36>" . trim(substr($st,128,3)) . "</A36>";	//	Learning outcome grade
					$ilr .= "<A37>" . trim(substr($st,131,2)) . "</A37>";	//	Number of units completed
					$ilr .= "<A38>" . trim(substr($st,133,2)) . "</A38>";	//	Number of units to achieve full qualification
					$ilr .= "<A39>" . trim(substr($st,135,1)) . "</A39>";	//	Eligibility for achievement funding
					$ilr .= "<A40>" . trim(substr($st,136,2)) . "/" . substr($st,138,2) . "/" . substr($st,140,4) . "</A40>"; // Achivement date
	//				$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
					$ilr .= "<A44>" . trim(substr($st,152,30)) . "</A44>";	//	Employer identifier
					$ilr .= "<A45>" . trim(substr($st,182,8)) . "</A45>";	//	Workplace location postcode
					$ilr .= "<A46a>" . (int)trim(substr($st,190,3)) . "</A46a>";	//	National learning aim monitoring
					$ilr .= "<A46b>" . (int)trim(substr($st,193,3)) . "</A46b>";	//	National learning aim monitoring
					$ilr .= "<A47a>" . trim(substr($st,196,12)) . "</A47a>";	//	Local learning aim monitoring
					$ilr .= "<A47b>" . trim(substr($st,208,12)) . "</A47b>";	//	Local learning aim monitoring
					$ilr .= "<A48a>" . trim(substr($st,220,12)) . "</A48a>";	//	Provider specified learning aim data
					$ilr .= "<A48b>" . trim(substr($st,232,12)) . "</A48b>";	//	Provider specified learning aim data
					$ilr .= "<A49>" . trim(substr($st,244,5)) . "</A49>";	//	Special projects and pilots
					$ilr .= "<A50>" . (int)trim(substr($st,249,2)) . "</A50>";	//	Reason learning ended
					$ilr .= "<A51a>" . trim(substr($st,251,2)) . "</A51a>";	//	Proportion of funding remaining
					$ilr .= "<A52>" . trim(substr($st,253,5)) . "</A52>";	//	Distance learning funding
					$ilr .= "<A53>" . trim(substr($st,258,2)) . "</A53>";	//	Additional learning needs
					$ilr .= "<A54>" . trim(substr($st,260,10)) . "</A54>";	//	Broker contract number
					$ilr .= "<A55>" . trim(substr($st,270,10)) . "</A55>";	//	Unique learner number
					$ilr .= "<A56>" . trim(substr($st,280,8)) . "</A56>";	//	UK Provider reference number
					$ilr .= "<A57>" . trim(substr($st,288,2)) . "</A57>";	//	Source of tuition fees
					$ilr .= "<A58>" . trim(substr($st,290,2)) . "</A58>";	//	Source of tuition fees
					$ilr .= "<A59>" . trim(substr($st,292,3)) . "</A59>";	//	Source of tuition fees
					$ilr .= "<A60>" . trim(substr($st,295,3)) . "</A60>";	//	Source of tuition fees
					$ilr .= "<A61></A61>";	//	Source of tuition fees
					$ilr .= "<A62></A62>";	//	Source of tuition fees
					$ilr .= "<A63></A63>";	//	Source of tuition fees
					$ilr .= "<A64></A64>";	//	Source of tuition fees
					$ilr .= "<A65></A65>";	//	Source of tuition fees
					$ilr .= "<A66></A66>";	//	Source of tuition fees
					$ilr .= "<A67></A67>";	//	Source of tuition fees
					$ilr .= "<A68></A68>";	//	Source of tuition fees
					if(substr($st,24,2)=='01')
					{
						$st = fgets($handle);
						$ilr .= "<E01>" . trim(substr($st,0,6)) . "</E01>";
						$ilr .= "<E02>" . trim(substr($st,6,2)) . "</E02>";
						$ilr .= "<E03>" . trim(substr($st,8,12)) . "</E03>";
						$ilr .= "<E04>" . trim(substr($st,20,2)) . "</E04>";
						$ilr .= "<E05>" . trim(substr($st,22,2)) . "</E05>";
						$ilr .= "<E06>" . trim(substr($st,24,2)) . "</E06>";
						$ilr .= "<E07>" . trim(substr($st,26,2)) . "</E07>";
						$ilr .= "<E08>" . substr($st,28,2) . "/" . substr($st,30,2) . "/" . substr($st,32,4) . "</E08>";
						$ilr .= "<E09>" . substr($st,36,2) . "/" . substr($st,38,2) . "/" . substr($st,40,4) . "</E09>";
						$ilr .= "<E10>" . substr($st,44,2) . "/" . substr($st,46,2) . "/" . substr($st,48,4) . "</E10>";
						$ilr .= "<E11>" . trim(substr($st,52,2)) . "</E11>";
						$ilr .= "<E12>" . trim(substr($st,54,2)) . "</E12>";
						$ilr .= "<E13>" . trim(substr($st,56,2)) . "</E13>";
						$ilr .= "<E14>" . trim(substr($st,58,2)) . "</E14>";
						$ilr .= "<E15>" . trim(substr($st,60,2)) . "</E15>";
						$ilr .= "<E16a>" . trim(substr($st,62,1)) . "</E16a>";
						$ilr .= "<E16b>" . trim(substr($st,63,1)) . "</E16b>";
						$ilr .= "<E16c>" . trim(substr($st,64,1)) . "</E16c>";
						$ilr .= "<E16d>" . trim(substr($st,65,1)) . "</E16d>";
						$ilr .= "<E16e>" . trim(substr($st,66,1)) . "</E16e>";
						$ilr .= "<E17>" . trim(substr($st,67,5)) . "</E17>";
						$ilr .= "<E18a>" . trim(substr($st,72,1)) . "</E18a>";
						$ilr .= "<E18b>" . trim(substr($st,73,1)) . "</E18b>";
						$ilr .= "<E18c>" . trim(substr($st,74,1)) . "</E18c>";
						$ilr .= "<E18d>" . trim(substr($st,75,1)) . "</E18d>";
						$ilr .= "<E19a>" . trim(substr($st,76,1)) . "</E19a>";
						$ilr .= "<E19b>" . trim(substr($st,77,1)) . "</E19b>";
						$ilr .= "<E19c>" . trim(substr($st,78,1)) . "</E19c>";
						$ilr .= "<E19d>" . trim(substr($st,79,1)) . "</E19d>";
						$ilr .= "<E19e>" . trim(substr($st,80,1)) . "</E19e>";
						$ilr .= "<E20a>" . trim(substr($st,81,2)) . "</E20a>";
						$ilr .= "<E20b>" . trim(substr($st,83,2)) . "</E20b>";
						$ilr .= "<E20c>" . trim(substr($st,85,2)) . "</E20c>";
						$ilr .= "<E21>" . trim(substr($st,87,2)) . "</E21>";
						$ilr .= "<E22>" . trim(substr($st,89,9)) . "</E22>";
						$ilr .= "<E23>" . trim(substr($st,98,3)) . "</E23>";
						$ilr .= "<E24>" . trim(substr($st,101,10)) . "</E24>";
						$ilr .= "<E25>" . trim(substr($st,111,8)) . "</E25>";
					}
					$ilr .= "</subaim>";
				}
			}
			
			$ilr .= "</ilr>";

			
			// Write in database
$tr_id++;
$ilr = str_replace("'","&apos;",$ilr);

		$tt = DAO::getSingleValue($link, "select tr_id from ilr where l03 = '$L03' LIMIT 0,1");
		if($tt=='')
			$tt = DAO::getSingleValue($link, "select id from tr where L03 = '$L03' LIMIT 0,1");
		if($tt=='')
			$tt = $tr_id;


$query = <<<HEREDOC
insert into
	ilr
VALUES('$L01','$L03','$A09','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');
HEREDOC;
			$st = $link->query($query);
			if($st == false)
			{
				throw new Exception(implode($link->errorInfo()), $link->errorCode());
			}
		}
		
		fclose($handle);

*/		
		
/*	
		// Populate ILRs from Batch of 2006/07		
		$masterhandle = fopen("rttg2006071.w13","r");
		$masterst = fgets($masterhandle);
		$tr_id = 11000;		
		while(!feof($masterhandle))
		{
			$masterst = fgets($masterhandle);

			if(trim(substr($masterst,20,2))=='30')
			{
				$masterl03 = substr($masterst,8,12);
				$master_start_date = substr($masterst,90,8);
				$subaims = 0;
				
				$handle = fopen("rttg2006072.w13","r");
				$st = fgets($handle);
				while(!feof($handle))
				{
					$submission="W13";
					$contract_type = "LSC";
					$is_complete = 1;
					$is_valid = 0;
					$is_approved = 0;
					$is_active = 1;
					$contract_id = 1;
					$L01 = substr($st,0,6);
					$L03 = substr($st,8,12);
					
					$st = fgets($handle);
					if(trim(substr($st,20,2))=='10' && substr($st,8,12)==$masterl03)
					{
						$ilr = "<ilr><learner>";
						$ilr .= "<L01>" . trim(substr($st,0,6)) . "</L01>"; 	
						$ilr .= "<L02>" . trim(substr($st,6,2)) . "</L02>";	//	Contract/ Allocation type
						$ilr .= "<L03>" . trim(substr($st,8,12)) . "</L03>";	//	Learner Reference Number 
						$ilr .= "<L04>" . trim(substr($st,20,2)) . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
						$ilr .= "<L05>" . trim(substr($st,22,2)) . "</L05>"; 	// 	How many learning aims data sets inner loop
						$ilr .= "<L06>" . trim(substr($st,24,2)) . "</L06>"; 	// 	How many ESF data sets. There isn't any in case of Toyota
						$ilr .= "<L07>" . trim(substr($st,26,2)) . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
						$ilr .= "<L08>" . trim(substr($st,28,1)) . "</L08>";	//	Deletion Flag
						$ilr .= "<L09>" . trim(substr($st,29,20)) . "</L09>";	
						$ilr .= "<L10>" . trim(substr($st,49,40)) . "</L10>";	//	Forenames
						$ilr .= "<L11>" . trim(substr($st,89,2)) . "/" . substr($st,91,2) . "/" . substr($st,93,4) . "</L11>"; // Date of Birth
						$ilr .= "<L12>" . trim(substr($st,97,2)) . "</L12>";	//	Ethnicity
						$ilr .= "<L13>" . trim(substr($st,99,1)) . "</L13>";	//	Sex
						$ilr .= "<L14>" . (int)trim(substr($st,100,1)) . "</L14>";	//	Learning difficulties/ disabilities/ health problems
						$ilr .= "<L15>" . (int)trim(substr($st,101,2)) . "</L15>";	//	Disability			
						$ilr .= "<L16>" . (int)trim(substr($st,103,2)) . "</L16>";	//	Learning difficulty
						$ilr .= "<L17>" . trim(substr($st,105,8)) . "</L17>";	//	Home postcode
						$ilr .= "<L18>" . trim(substr($st,113,30)) . "</L18>";	//	Address line 1
						$ilr .= "<L19>" . trim(substr($st,143,30)) . "</L19>";	//	Address line 2
						$ilr .= "<L20>" . trim(substr($st,173,30)) . "</L20>";	//	Address line 3
						$ilr .= "<L21>" . trim(substr($st,203,30)) . "</L21>";	//	Address line 4
						$ilr .= "<L22>" . trim(substr($st,233,8)) . "</L22>";		//	Current postcode
						$ilr .= "<L23>" . trim(substr($st,241,15)) . "</L23>";	//	Home telephone
						$ilr .= "<L24>" . trim(substr($st,256,3)) . "</L24>";	//	Country of domicile
						$ilr .= "<L25>" . trim(substr($st,259,3)) . "</L25>";	//	LSC Number of funding LSC
						$ilr .= "<L26>" . trim(substr($st,262,9)) . "</L26>";	//	National insurance number
						$ilr .= "<L27>" . trim(substr($st,271,1)) . "</L27>";	//	Restricted use indicator
						$ilr .= "<L28a>" . trim(substr($st,272,2)) . "</L28a>";	//	Eligibility for enhanced funding
						$ilr .= "<L28b>" . trim(substr($st,274,2)) . "</L28b>";	//	Eligibility for enhanced funding
						$ilr .= "<L29>" . trim(substr($st,276,2)) . "</L29>";	//	Additional support
						$ilr .= "<L31>" . trim(substr($st,278,6)) . "</L31>";	//	Additional support cost 
						$ilr .= "<L32>" . trim(substr($st,284,2)) . "</L32>";	//	Eligibility for disadvatnage uplift
						$ilr .= "<L33>" . trim(substr($st,286,6)) . "</L33>";	//	Disadvatnage uplift factor
						$ilr .= "<L34a>" . trim(substr($st,292,2)) . "</L34a>";	//	Learner support reason
						$ilr .= "<L34b>" . trim(substr($st,294,2)) . "</L34b>";	//	Learner support reason
						$ilr .= "<L34c>" . trim(substr($st,296,2)) . "</L34c>";	//	Learner support reason
						$ilr .= "<L34d>" . trim(substr($st,298,2)) . "</L34d>";	//	Learner support reason
						$ilr .= "<L35>" . (int)trim(substr($st,300,2)) . "</L35>";	//	Prior attainment level
						$ilr .= "<L36>" . trim(substr($st,302,2)) . "</L36>";	//	Learner status on last working day
						$ilr .= "<L37>" . (int)trim(substr($st,304,2)) . "</L37>";	//	Employment status on first day of learning
						$ilr .= "<L38>". (int)trim(substr($st,306,2))  ."</L38>";									//	No longer use. Use blanks
						$ilr .= "<L39>" . (int)trim(substr($st,308,2)) . "</L39>";	//	Destination
						$ilr .= "<L40a>" . trim(substr($st,310,2)) . "</L40a>";	//	National learner monitoring
						$ilr .= "<L40b>" . trim(substr($st,312,2)) . "</L40b>";	//	National learner monitoring
						$ilr .= "<L41a>" . trim(substr($st,314,12)) . "</L41a>";	//	Local learner monitoring
						$ilr .= "<L41b>" . trim(substr($st,326,12)) . "</L41b>";	//	Local learner monitoring
						$ilr .= "<L42a>" . trim(substr($st,338,12)) . "</L42a>";	//	Provider specified learner data
						$ilr .= "<L42b>" . trim(substr($st,350,12)) . "</L42b>";	//	Provider specified learner data
						$ilr .= "<L44>" . trim(substr($st,362,3)) . "</L44>";	//	NES delivery LSC number
						$ilr .= "<L45>" . trim(substr($st,365,10)) . "</L45>";	//	Unique learner number
						$ilr .= "<L46>" . trim(substr($st,375,8)) . "</L46>";	
						$ilr .= "<L47></L47>";	//	Current employment status
						$ilr .= "<L48></L48>"; // Date employment status changed
						$ilr .= "<L49a></L49a>";	//	Current employment status
						$ilr .= "<L49b></L49b>";	//	Current employment status
						$ilr .= "<L49c></L49c>";	//	Current employment status
						$ilr .= "<L49d></L49d>";	//	Current employment status
						$ilr .= "<subaims>k</subaims>";	//	Current employment status
						$ilr .= "</learner>";
					}
					elseif(trim(substr($st,20,2))=='30' && substr($st,8,12)==$masterl03 && substr($st,90,8)==$master_start_date)
					{
						$ilr .= "<programmeaim>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,6)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54>" . trim(substr($st,256,10)) . "</A54>";	//	Broker contract number
						$ilr .= "<A55>" . trim(substr($st,266,10)) . "</A55>";	//	Unique learner number
						$ilr .= "<A56>" . trim(substr($st,276,8)) . "</A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</programmeaim>";					
						$A09 = trim(substr($st,29,8));
						$ilr .= "<main>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,6)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54>" . trim(substr($st,256,10)) . "</A54>";	//	Broker contract number
						$ilr .= "<A55>" . trim(substr($st,266,10)) . "</A55>";	//	Unique learner number
						$ilr .= "<A56>" . trim(substr($st,276,8)) . "</A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						if(substr($st,24,2)=='01')
						{
							$st = fgets($handle);
							$ilr .= "<E01>" . trim(substr($st,0,6)) . "</E01>";
							$ilr .= "<E02>" . trim(substr($st,6,2)) . "</E02>";
							$ilr .= "<E03>" . trim(substr($st,8,12)) . "</E03>";
							$ilr .= "<E04>" . trim(substr($st,20,2)) . "</E04>";
							$ilr .= "<E05>" . trim(substr($st,22,2)) . "</E05>";
							$ilr .= "<E06>" . trim(substr($st,24,2)) . "</E06>";
							$ilr .= "<E07>" . trim(substr($st,26,2)) . "</E07>";
							$ilr .= "<E08>" . substr($st,28,2) . "/" . substr($st,30,2) . "/" . substr($st,32,4) . "</E08>";
							$ilr .= "<E09>" . substr($st,36,2) . "/" . substr($st,38,2) . "/" . substr($st,40,4) . "</E09>";
							$ilr .= "<E10>" . substr($st,44,2) . "/" . substr($st,46,2) . "/" . substr($st,48,4) . "</E10>";
							$ilr .= "<E11>" . trim(substr($st,52,2)) . "</E11>";
							$ilr .= "<E12>" . trim(substr($st,54,2)) . "</E12>";
							$ilr .= "<E13>" . trim(substr($st,56,2)) . "</E13>";
							$ilr .= "<E14>" . trim(substr($st,58,2)) . "</E14>";
							$ilr .= "<E15>" . trim(substr($st,60,2)) . "</E15>";
							$ilr .= "<E16a>" . trim(substr($st,62,1)) . "</E16a>";
							$ilr .= "<E16b>" . trim(substr($st,63,1)) . "</E16b>";
							$ilr .= "<E16c>" . trim(substr($st,64,1)) . "</E16c>";
							$ilr .= "<E16d>" . trim(substr($st,65,1)) . "</E16d>";
							$ilr .= "<E16e>" . trim(substr($st,66,1)) . "</E16e>";
							$ilr .= "<E17>" . trim(substr($st,67,5)) . "</E17>";
							$ilr .= "<E18a>" . trim(substr($st,72,1)) . "</E18a>";
							$ilr .= "<E18b>" . trim(substr($st,73,1)) . "</E18b>";
							$ilr .= "<E18c>" . trim(substr($st,74,1)) . "</E18c>";
							$ilr .= "<E18d>" . trim(substr($st,75,1)) . "</E18d>";
							$ilr .= "<E19a>" . trim(substr($st,76,1)) . "</E19a>";
							$ilr .= "<E19b>" . trim(substr($st,77,1)) . "</E19b>";
							$ilr .= "<E19c>" . trim(substr($st,78,1)) . "</E19c>";
							$ilr .= "<E19d>" . trim(substr($st,79,1)) . "</E19d>";
							$ilr .= "<E19e>" . trim(substr($st,80,1)) . "</E19e>";
							$ilr .= "<E20a>" . trim(substr($st,81,2)) . "</E20a>";
							$ilr .= "<E20b>" . trim(substr($st,83,2)) . "</E20b>";
							$ilr .= "<E20c>" . trim(substr($st,85,2)) . "</E20c>";
							$ilr .= "<E21>" . trim(substr($st,87,2)) . "</E21>";
							$ilr .= "<E22>" . trim(substr($st,89,9)) . "</E22>";
							$ilr .= "<E23>" . trim(substr($st,98,3)) . "</E23>";
							$ilr .= "<E24>" . trim(substr($st,101,10)) . "</E24>";
							$ilr .= "<E25>" . trim(substr($st,111,8)) . "</E25>";
						}
						$ilr .= "</main>";					
					}
					elseif( (substr($st,37,2)!='' && substr($st,37,2)!='40') && substr($st,8,12)==$masterl03 && substr($st,90,8)==$master_start_date)
					{
						$subaims++;
						$ilr .= "<subaim>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,6)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54>" . trim(substr($st,256,10)) . "</A54>";	//	Broker contract number
						$ilr .= "<A55>" . trim(substr($st,266,10)) . "</A55>";	//	Unique learner number
						$ilr .= "<A56>" . trim(substr($st,276,8)) . "</A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</subaim>";					
					}
				}
			
				$ilr .= "</ilr>";
				fclose($handle);
				$tr_id++;
				$ilr = str_replace("<subaims>k</subaims>","<subaims>".$subaims."</subaims>",$ilr);
				$ilr = str_replace("'","&apos;",$ilr);
			// Write in database
$query = <<<HEREDOC
insert into
	ilr
VALUES('$L01','$masterl03','$A09','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');
HEREDOC;
			$st = $link->query($query);
			if($st == false)
			{
				throw new Exception(implode($link->errorInfo()), $link->errorCode());
			}
		}
	}
		
fclose($masterhandle);
		
*/		
		
/*		
		// Populate ILRs from Batch of 2007/08		
		$masterhandle = fopen("rttg2007081.w13","r");
		$masterst = fgets($masterhandle);
		$tr_id = 11000;		
		while(!feof($masterhandle))
		{
			$masterst = fgets($masterhandle);

			if(trim(substr($masterst,20,2))=='30')
			{
				$masterl03 = substr($masterst,8,12);
				$master_start_date = substr($masterst,90,8);
				$subaims = 0;
				
				$handle = fopen("rttg2007082.w13","r");
				$st = fgets($handle);
				while(!feof($handle))
				{
					$submission="W13";
					$contract_type = "LSC";
					$is_complete = 1;
					$is_valid = 0;
					$is_approved = 0;
					$is_active = 1;
					$contract_id = 2;
					$L01 = substr($st,0,6);
					$L03 = substr($st,8,12);
					
					$st = fgets($handle);
					if(trim(substr($st,20,2))=='10' && substr($st,8,12)==$masterl03)
					{
						$ilr = "<ilr><learner>";
						$ilr .= "<L01>" . trim(substr($st,0,6)) . "</L01>"; 	
						$ilr .= "<L02>" . trim(substr($st,6,2)) . "</L02>";	//	Contract/ Allocation type
						$ilr .= "<L03>" . trim(substr($st,8,12)) . "</L03>";	//	Learner Reference Number 
						$ilr .= "<L04>" . trim(substr($st,20,2)) . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
						$ilr .= "<L05>" . trim(substr($st,22,2)) . "</L05>"; 	// 	How many learning aims data sets inner loop
						$ilr .= "<L06>" . trim(substr($st,24,2)) . "</L06>"; 	// 	How many ESF data sets. There isn't any in case of Toyota
						$ilr .= "<L07>" . trim(substr($st,26,2)) . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
						$ilr .= "<L08>" . trim(substr($st,28,1)) . "</L08>";	//	Deletion Flag
						$ilr .= "<L09>" . trim(substr($st,29,20)) . "</L09>";	
						$ilr .= "<L10>" . trim(substr($st,49,40)) . "</L10>";	//	Forenames
						$ilr .= "<L11>" . trim(substr($st,89,2)) . "/" . substr($st,91,2) . "/" . substr($st,93,4) . "</L11>"; // Date of Birth
						$ilr .= "<L12>" . trim(substr($st,97,2)) . "</L12>";	//	Ethnicity
						$ilr .= "<L13>" . trim(substr($st,99,1)) . "</L13>";	//	Sex
						$ilr .= "<L14>" . (int)trim(substr($st,100,1)) . "</L14>";	//	Learning difficulties/ disabilities/ health problems
						$ilr .= "<L15>" . (int)trim(substr($st,101,2)) . "</L15>";	//	Disability			
						$ilr .= "<L16>" . (int)trim(substr($st,103,2)) . "</L16>";	//	Learning difficulty
						$ilr .= "<L17>" . trim(substr($st,105,8)) . "</L17>";	//	Home postcode
						$ilr .= "<L18>" . trim(substr($st,113,30)) . "</L18>";	//	Address line 1
						$ilr .= "<L19>" . trim(substr($st,143,30)) . "</L19>";	//	Address line 2
						$ilr .= "<L20>" . trim(substr($st,173,30)) . "</L20>";	//	Address line 3
						$ilr .= "<L21>" . trim(substr($st,203,30)) . "</L21>";	//	Address line 4
						$ilr .= "<L22>" . trim(substr($st,233,8)) . "</L22>";		//	Current postcode
						$ilr .= "<L23>" . trim(substr($st,241,15)) . "</L23>";	//	Home telephone
						$ilr .= "<L24>" . trim(substr($st,256,3)) . "</L24>";	//	Country of domicile
						$ilr .= "<L25>" . trim(substr($st,259,3)) . "</L25>";	//	LSC Number of funding LSC
						$ilr .= "<L26>" . trim(substr($st,262,9)) . "</L26>";	//	National insurance number
						$ilr .= "<L27>" . trim(substr($st,271,1)) . "</L27>";	//	Restricted use indicator
						$ilr .= "<L28a>" . trim(substr($st,272,2)) . "</L28a>";	//	Eligibility for enhanced funding
						$ilr .= "<L28b>" . trim(substr($st,274,2)) . "</L28b>";	//	Eligibility for enhanced funding
						$ilr .= "<L29>" . trim(substr($st,276,2)) . "</L29>";	//	Additional support
						$ilr .= "<L31>" . trim(substr($st,278,6)) . "</L31>";	//	Additional support cost 
						$ilr .= "<L32>" . trim(substr($st,284,2)) . "</L32>";	//	Eligibility for disadvatnage uplift
						$ilr .= "<L33>" . trim(substr($st,286,6)) . "</L33>";	//	Disadvatnage uplift factor
						$ilr .= "<L34a>" . trim(substr($st,292,2)) . "</L34a>";	//	Learner support reason
						$ilr .= "<L34b>" . trim(substr($st,294,2)) . "</L34b>";	//	Learner support reason
						$ilr .= "<L34c>" . trim(substr($st,296,2)) . "</L34c>";	//	Learner support reason
						$ilr .= "<L34d>" . trim(substr($st,298,2)) . "</L34d>";	//	Learner support reason
						$ilr .= "<L35>" . (int)trim(substr($st,300,2)) . "</L35>";	//	Prior attainment level
						$ilr .= "<L36>" . trim(substr($st,302,2)) . "</L36>";	//	Learner status on last working day
						$ilr .= "<L37>" . (int)trim(substr($st,304,2)) . "</L37>";	//	Employment status on first day of learning
						$ilr .= "<L38>". (int)trim(substr($st,306,2))  ."</L38>";									//	No longer use. Use blanks
						$ilr .= "<L39>" . (int)trim(substr($st,308,2)) . "</L39>";	//	Destination
						$ilr .= "<L40a>" . trim(substr($st,310,2)) . "</L40a>";	//	National learner monitoring
						$ilr .= "<L40b>" . trim(substr($st,312,2)) . "</L40b>";	//	National learner monitoring
						$ilr .= "<L41a>" . trim(substr($st,314,12)) . "</L41a>";	//	Local learner monitoring
						$ilr .= "<L41b>" . trim(substr($st,326,12)) . "</L41b>";	//	Local learner monitoring
						$ilr .= "<L42a>" . trim(substr($st,338,12)) . "</L42a>";	//	Provider specified learner data
						$ilr .= "<L42b>" . trim(substr($st,350,12)) . "</L42b>";	//	Provider specified learner data
						$ilr .= "<L44>" . trim(substr($st,362,3)) . "</L44>";	//	NES delivery LSC number
						$ilr .= "<L45>" . trim(substr($st,365,10)) . "</L45>";	//	Unique learner number
						$ilr .= "<L46>" . trim(substr($st,375,8)) . "</L46>";	
						$ilr .= "<L47>" . trim(substr($st,383,2)) . "</L47>";	//	Current employment status
						$ilr .= "<L48>" . trim(substr($st,385,2)) . "/" . substr($st,387,2) . "/"  . substr($st,389,4) . "</L48>"; // Date employment status changed
						$ilr .= "<L49a></L49a>";	//	Current employment status
						$ilr .= "<L49b></L49b>";	//	Current employment status
						$ilr .= "<L49c></L49c>";	//	Current employment status
						$ilr .= "<L49d></L49d>";	//	Current employment status
						$ilr .= "<subaims>k</subaims>";	//	Current employment status
						$ilr .= "</learner>";
					}
					elseif(trim(substr($st,20,2))=='30' && substr($st,8,12)==$masterl03 && substr($st,90,8)==$master_start_date)
					{
						$ilr .= "<programmeaim>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54>" . trim(substr($st,256,10)) . "</A54>";	//	Broker contract number
						$ilr .= "<A55>" . trim(substr($st,266,10)) . "</A55>";	//	Unique learner number
						$ilr .= "<A56>" . trim(substr($st,276,8)) . "</A56>";	//	UK Provider reference number
						$ilr .= "<A57>" . trim(substr($st,284,2)) . "</A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</programmeaim>";					
						$A09 = trim(substr($st,29,8));
						$ilr .= "<main>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54>" . trim(substr($st,256,10)) . "</A54>";	//	Broker contract number
						$ilr .= "<A55>" . trim(substr($st,266,10)) . "</A55>";	//	Unique learner number
						$ilr .= "<A56>" . trim(substr($st,276,8)) . "</A56>";	//	UK Provider reference number
						$ilr .= "<A57>" . trim(substr($st,284,2)) . "</A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						if(substr($st,24,2)=='01')
						{
							$st = fgets($handle);
							$ilr .= "<E01>" . trim(substr($st,0,6)) . "</E01>";
							$ilr .= "<E02>" . trim(substr($st,6,2)) . "</E02>";
							$ilr .= "<E03>" . trim(substr($st,8,12)) . "</E03>";
							$ilr .= "<E04>" . trim(substr($st,20,2)) . "</E04>";
							$ilr .= "<E05>" . trim(substr($st,22,2)) . "</E05>";
							$ilr .= "<E06>" . trim(substr($st,24,2)) . "</E06>";
							$ilr .= "<E07>" . trim(substr($st,26,2)) . "</E07>";
							$ilr .= "<E08>" . substr($st,28,2) . "/" . substr($st,30,2) . "/" . substr($st,32,4) . "</E08>";
							$ilr .= "<E09>" . substr($st,36,2) . "/" . substr($st,38,2) . "/" . substr($st,40,4) . "</E09>";
							$ilr .= "<E10>" . substr($st,44,2) . "/" . substr($st,46,2) . "/" . substr($st,48,4) . "</E10>";
							$ilr .= "<E11>" . trim(substr($st,52,2)) . "</E11>";
							$ilr .= "<E12>" . trim(substr($st,54,2)) . "</E12>";
							$ilr .= "<E13>" . trim(substr($st,56,2)) . "</E13>";
							$ilr .= "<E14>" . trim(substr($st,58,2)) . "</E14>";
							$ilr .= "<E15>" . trim(substr($st,60,2)) . "</E15>";
							$ilr .= "<E16a>" . trim(substr($st,62,1)) . "</E16a>";
							$ilr .= "<E16b>" . trim(substr($st,63,1)) . "</E16b>";
							$ilr .= "<E16c>" . trim(substr($st,64,1)) . "</E16c>";
							$ilr .= "<E16d>" . trim(substr($st,65,1)) . "</E16d>";
							$ilr .= "<E16e>" . trim(substr($st,66,1)) . "</E16e>";
							$ilr .= "<E17>" . trim(substr($st,67,5)) . "</E17>";
							$ilr .= "<E18a>" . trim(substr($st,72,1)) . "</E18a>";
							$ilr .= "<E18b>" . trim(substr($st,73,1)) . "</E18b>";
							$ilr .= "<E18c>" . trim(substr($st,74,1)) . "</E18c>";
							$ilr .= "<E18d>" . trim(substr($st,75,1)) . "</E18d>";
							$ilr .= "<E19a>" . trim(substr($st,76,1)) . "</E19a>";
							$ilr .= "<E19b>" . trim(substr($st,77,1)) . "</E19b>";
							$ilr .= "<E19c>" . trim(substr($st,78,1)) . "</E19c>";
							$ilr .= "<E19d>" . trim(substr($st,79,1)) . "</E19d>";
							$ilr .= "<E19e>" . trim(substr($st,80,1)) . "</E19e>";
							$ilr .= "<E20a>" . trim(substr($st,81,2)) . "</E20a>";
							$ilr .= "<E20b>" . trim(substr($st,83,2)) . "</E20b>";
							$ilr .= "<E20c>" . trim(substr($st,85,2)) . "</E20c>";
							$ilr .= "<E21>" . trim(substr($st,87,2)) . "</E21>";
							$ilr .= "<E22>" . trim(substr($st,89,9)) . "</E22>";
							$ilr .= "<E23>" . trim(substr($st,98,3)) . "</E23>";
							$ilr .= "<E24>" . trim(substr($st,101,10)) . "</E24>";
							$ilr .= "<E25>" . trim(substr($st,111,8)) . "</E25>";
						}
						$ilr .= "</main>";					
					}
					elseif( (substr($st,37,2)!='' && substr($st,37,2)!='40') && substr($st,8,12)==$masterl03 && substr($st,90,8)==$master_start_date)
					{
						$subaims++;
						$ilr .= "<subaim>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54>" . trim(substr($st,256,10)) . "</A54>";	//	Broker contract number
						$ilr .= "<A55>" . trim(substr($st,266,10)) . "</A55>";	//	Unique learner number
						$ilr .= "<A56>" . trim(substr($st,276,8)) . "</A56>";	//	UK Provider reference number
						$ilr .= "<A57>" . trim(substr($st,284,2)) . "</A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</subaim>";					
					}
				}
			
				$ilr .= "</ilr>";
				fclose($handle);
				$tr_id++;
				$ilr = str_replace("<subaims>k</subaims>","<subaims>".$subaims."</subaims>",$ilr);
				$ilr = str_replace("'","&apos;",$ilr);
			// Write in database
$query = <<<HEREDOC
insert into
	ilr
VALUES('$L01','$masterl03','$A09','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');
HEREDOC;
			$st = $link->query($query);
			if($st == false)
			{
				throw new Exception(implode($link->errorInfo()), $link->errorCode());
			}
		}
	}
		
fclose($masterhandle);
		
*/		
/*
		// Populate ILRs from Batch of 2004/05		
		$masterhandle = fopen("file7.jlr","r");
		$masterst = fgets($masterhandle);
		$tr_id = 13000;		
		while(!feof($masterhandle))
		{
			$masterst = fgets($masterhandle);

			if(trim(substr($masterst,37,2))=='40')
			{
				$masterl03 = substr($masterst,8,12);
				$master_start_date = substr($masterst,90,8);
				$subaims = 0;
				
				$handle = fopen("file8.jlr","r");
				$st = fgets($handle);
				while(!feof($handle))
				{
					$submission="W13";
					$contract_type = "LSC";
					$is_complete = 1;
					$is_valid = 0;
					$is_approved = 0;
					$is_active = 1;
					$contract_id = 3;
					$L01 = substr($st,0,6);
					$L03 = substr($st,8,12);
					
					$st = fgets($handle);
					if(trim(substr($st,20,2))=='10' && substr($st,8,12)==$masterl03)
					{
						$ilr = "<ilr><learner>";
						$ilr .= "<L01>" . trim(substr($st,0,6)) . "</L01>"; 	
						$ilr .= "<L02>" . trim(substr($st,6,2)) . "</L02>";	//	Contract/ Allocation type
						$ilr .= "<L03>" . trim(substr($st,8,12)) . "</L03>";	//	Learner Reference Number 
						$ilr .= "<L04>" . trim(substr($st,20,2)) . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
						$ilr .= "<L05>" . trim(substr($st,22,2)) . "</L05>"; 	// 	How many learning aims data sets inner loop
						$ilr .= "<L06>" . trim(substr($st,24,2)) . "</L06>"; 	// 	How many ESF data sets. There isn't any in case of Toyota
						$ilr .= "<L07>" . trim(substr($st,26,2)) . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
						$ilr .= "<L08>" . trim(substr($st,28,1)) . "</L08>";	//	Deletion Flag
						$ilr .= "<L09>" . trim(substr($st,29,20)) . "</L09>";	
						$ilr .= "<L10>" . trim(substr($st,49,40)) . "</L10>";	//	Forenames
						$ilr .= "<L11>" . trim(substr($st,89,2)) . "/" . substr($st,91,2) . "/" . substr($st,93,4) . "</L11>"; // Date of Birth
						$ilr .= "<L12>" . trim(substr($st,97,2)) . "</L12>";	//	Ethnicity
						$ilr .= "<L13>" . trim(substr($st,99,1)) . "</L13>";	//	Sex
						$ilr .= "<L14>" . (int)trim(substr($st,100,1)) . "</L14>";	//	Learning difficulties/ disabilities/ health problems
						$ilr .= "<L15>" . (int)trim(substr($st,101,2)) . "</L15>";	//	Disability			
						$ilr .= "<L16>" . (int)trim(substr($st,103,2)) . "</L16>";	//	Learning difficulty
						$ilr .= "<L17>" . trim(substr($st,105,8)) . "</L17>";	//	Home postcode
						$ilr .= "<L18>" . trim(substr($st,113,30)) . "</L18>";	//	Address line 1
						$ilr .= "<L19>" . trim(substr($st,143,30)) . "</L19>";	//	Address line 2
						$ilr .= "<L20>" . trim(substr($st,173,30)) . "</L20>";	//	Address line 3
						$ilr .= "<L21>" . trim(substr($st,203,30)) . "</L21>";	//	Address line 4
						$ilr .= "<L22>" . trim(substr($st,233,8)) . "</L22>";		//	Current postcode
						$ilr .= "<L23>" . trim(substr($st,241,15)) . "</L23>";	//	Home telephone
						$ilr .= "<L24>" . trim(substr($st,256,3)) . "</L24>";	//	Country of domicile
						$ilr .= "<L25>" . trim(substr($st,259,3)) . "</L25>";	//	LSC Number of funding LSC
						$ilr .= "<L26>" . trim(substr($st,262,9)) . "</L26>";	//	National insurance number
						$ilr .= "<L27>" . trim(substr($st,271,1)) . "</L27>";	//	Restricted use indicator
						$ilr .= "<L28a>" . trim(substr($st,272,2)) . "</L28a>";	//	Eligibility for enhanced funding
						$ilr .= "<L28b>" . trim(substr($st,274,2)) . "</L28b>";	//	Eligibility for enhanced funding
						$ilr .= "<L29>" . trim(substr($st,276,2)) . "</L29>";	//	Additional support
						$ilr .= "<L31>" . trim(substr($st,278,6)) . "</L31>";	//	Additional support cost 
						$ilr .= "<L32>" . trim(substr($st,284,2)) . "</L32>";	//	Eligibility for disadvatnage uplift
						$ilr .= "<L33>" . trim(substr($st,286,6)) . "</L33>";	//	Disadvatnage uplift factor
						$ilr .= "<L34a>" . trim(substr($st,292,2)) . "</L34a>";	//	Learner support reason
						$ilr .= "<L34b>" . trim(substr($st,294,2)) . "</L34b>";	//	Learner support reason
						$ilr .= "<L34c>" . trim(substr($st,296,2)) . "</L34c>";	//	Learner support reason
						$ilr .= "<L34d>" . trim(substr($st,298,2)) . "</L34d>";	//	Learner support reason
						$ilr .= "<L35>" . (int)trim(substr($st,300,2)) . "</L35>";	//	Prior attainment level
						$ilr .= "<L36>" . trim(substr($st,302,2)) . "</L36>";	//	Learner status on last working day
						$ilr .= "<L37>" . (int)trim(substr($st,304,2)) . "</L37>";	//	Employment status on first day of learning
						$ilr .= "<L38>". (int)trim(substr($st,306,2))  ."</L38>";									//	No longer use. Use blanks
						$ilr .= "<L39>" . (int)trim(substr($st,308,2)) . "</L39>";	//	Destination
						$ilr .= "<L40a>" . trim(substr($st,310,2)) . "</L40a>";	//	National learner monitoring
						$ilr .= "<L40b>" . trim(substr($st,312,2)) . "</L40b>";	//	National learner monitoring
						$ilr .= "<L41a>" . trim(substr($st,314,12)) . "</L41a>";	//	Local learner monitoring
						$ilr .= "<L41b>" . trim(substr($st,326,12)) . "</L41b>";	//	Local learner monitoring
						$ilr .= "<L42a>" . trim(substr($st,338,12)) . "</L42a>";	//	Provider specified learner data
						$ilr .= "<L42b>" . trim(substr($st,350,12)) . "</L42b>";	//	Provider specified learner data
						$ilr .= "<L44>" . trim(substr($st,362,3)) . "</L44>";	//	NES delivery LSC number
						$ilr .= "<L45></L45>";	//	Unique learner number
						$ilr .= "<L46></L46>";	
						$ilr .= "<L47></L47>";	//	Current employment status
						$ilr .= "<L48></L48>"; // Date employment status changed
						$ilr .= "<L49a></L49a>";	//	Current employment status
						$ilr .= "<L49b></L49b>";	//	Current employment status
						$ilr .= "<L49c></L49c>";	//	Current employment status
						$ilr .= "<L49d></L49d>";	//	Current employment status
						$ilr .= "<subaims>k</subaims>";	//	Current employment status
						$ilr .= "</learner>";
					}
					elseif(trim(substr($st,37,2))=='40' && substr($st,8,12)==$masterl03 && substr($st,90,8)==$master_start_date)
					{
						$ilr .= "<programmeaim>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54></A54>";	//	Broker contract number
						$ilr .= "<A55></A55>";	//	Unique learner number
						$ilr .= "<A56></A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</programmeaim>";					
						$A09 = trim(substr($st,29,8));
						$ilr .= "<main>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54></A54>";	//	Broker contract number
						$ilr .= "<A55></A55>";	//	Unique learner number
						$ilr .= "<A56></A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</main>";					
					}
					elseif( (substr($st,37,2)!='' && substr($st,37,2)!='40') && substr($st,8,12)==$masterl03 && substr($st,90,8)==$master_start_date)
					{
						$subaims++;
						$ilr .= "<subaim>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54></A54>";	//	Broker contract number
						$ilr .= "<A55></A55>";	//	Unique learner number
						$ilr .= "<A56></A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</subaim>";					
					}
				}
			
				$ilr .= "</ilr>";
				fclose($handle);
				$tr_id++;
				$ilr = str_replace("<subaims>k</subaims>","<subaims>".$subaims."</subaims>",$ilr);
				$ilr = str_replace("'","&apos;",$ilr);
				
				$tt = DAO::getSingleValue($link, "select tr_id from ilr where l03 = '$masterl03' LIMIT 0,1");
				if($tt=='')
					$tt = $tr_id;
			// Write in database
$query = <<<HEREDOC
insert into
	ilr
VALUES('$L01','$masterl03','$A09','$ilr','$submission','$contract_type','$tt','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');
HEREDOC;
			$st = $link->query($query);
			if($st == false)
			{
				throw new Exception(implode($link->errorInfo()), $link->errorCode());
			}
		}
	}
		
fclose($masterhandle);
*/		
/*		
		// Populate ILRs from Batch of 2005/06		
		$masterhandle = fopen("file5.jlr","r");
		$masterst = fgets($masterhandle);
		$tr_id = 12000;		
		while(!feof($masterhandle))
		{
			$masterst = fgets($masterhandle);

			if(trim(substr($masterst,37,2))=='40')
			{
				$masterl03 = substr($masterst,8,12);
				$master_start_date = substr($masterst,90,8);
				$subaims = 0;
				
				$handle = fopen("file6.jlr","r");
				$st = fgets($handle);
				while(!feof($handle))
				{
					$submission="W13";
					$contract_type = "LSC";
					$is_complete = 1;
					$is_valid = 0;
					$is_approved = 0;
					$is_active = 1;
					$contract_id = 4;
					$L01 = substr($st,0,6);
					$L03 = substr($st,8,12);
					
					$st = fgets($handle);
					if(trim(substr($st,20,2))=='10' && substr($st,8,12)==$masterl03)
					{
						$ilr = "<ilr><learner>";
						$ilr .= "<L01>" . trim(substr($st,0,6)) . "</L01>"; 	
						$ilr .= "<L02>" . trim(substr($st,6,2)) . "</L02>";	//	Contract/ Allocation type
						$ilr .= "<L03>" . trim(substr($st,8,12)) . "</L03>";	//	Learner Reference Number 
						$ilr .= "<L04>" . trim(substr($st,20,2)) . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
						$ilr .= "<L05>" . trim(substr($st,22,2)) . "</L05>"; 	// 	How many learning aims data sets inner loop
						$ilr .= "<L06>" . trim(substr($st,24,2)) . "</L06>"; 	// 	How many ESF data sets. There isn't any in case of Toyota
						$ilr .= "<L07>" . trim(substr($st,26,2)) . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
						$ilr .= "<L08>" . trim(substr($st,28,1)) . "</L08>";	//	Deletion Flag
						$ilr .= "<L09>" . trim(substr($st,29,20)) . "</L09>";	
						$ilr .= "<L10>" . trim(substr($st,49,40)) . "</L10>";	//	Forenames
						$ilr .= "<L11>" . trim(substr($st,89,2)) . "/" . substr($st,91,2) . "/" . substr($st,93,4) . "</L11>"; // Date of Birth
						$ilr .= "<L12>" . trim(substr($st,97,2)) . "</L12>";	//	Ethnicity
						$ilr .= "<L13>" . trim(substr($st,99,1)) . "</L13>";	//	Sex
						$ilr .= "<L14>" . (int)trim(substr($st,100,1)) . "</L14>";	//	Learning difficulties/ disabilities/ health problems
						$ilr .= "<L15>" . (int)trim(substr($st,101,2)) . "</L15>";	//	Disability			
						$ilr .= "<L16>" . (int)trim(substr($st,103,2)) . "</L16>";	//	Learning difficulty
						$ilr .= "<L17>" . trim(substr($st,105,8)) . "</L17>";	//	Home postcode
						$ilr .= "<L18>" . trim(substr($st,113,30)) . "</L18>";	//	Address line 1
						$ilr .= "<L19>" . trim(substr($st,143,30)) . "</L19>";	//	Address line 2
						$ilr .= "<L20>" . trim(substr($st,173,30)) . "</L20>";	//	Address line 3
						$ilr .= "<L21>" . trim(substr($st,203,30)) . "</L21>";	//	Address line 4
						$ilr .= "<L22>" . trim(substr($st,233,8)) . "</L22>";		//	Current postcode
						$ilr .= "<L23>" . trim(substr($st,241,15)) . "</L23>";	//	Home telephone
						$ilr .= "<L24>" . trim(substr($st,256,3)) . "</L24>";	//	Country of domicile
						$ilr .= "<L25>" . trim(substr($st,259,3)) . "</L25>";	//	LSC Number of funding LSC
						$ilr .= "<L26>" . trim(substr($st,262,9)) . "</L26>";	//	National insurance number
						$ilr .= "<L27>" . trim(substr($st,271,1)) . "</L27>";	//	Restricted use indicator
						$ilr .= "<L28a>" . trim(substr($st,272,2)) . "</L28a>";	//	Eligibility for enhanced funding
						$ilr .= "<L28b>" . trim(substr($st,274,2)) . "</L28b>";	//	Eligibility for enhanced funding
						$ilr .= "<L29>" . trim(substr($st,276,2)) . "</L29>";	//	Additional support
						$ilr .= "<L31>" . trim(substr($st,278,6)) . "</L31>";	//	Additional support cost 
						$ilr .= "<L32>" . trim(substr($st,284,2)) . "</L32>";	//	Eligibility for disadvatnage uplift
						$ilr .= "<L33>" . trim(substr($st,286,6)) . "</L33>";	//	Disadvatnage uplift factor
						$ilr .= "<L34a>" . trim(substr($st,292,2)) . "</L34a>";	//	Learner support reason
						$ilr .= "<L34b>" . trim(substr($st,294,2)) . "</L34b>";	//	Learner support reason
						$ilr .= "<L34c>" . trim(substr($st,296,2)) . "</L34c>";	//	Learner support reason
						$ilr .= "<L34d>" . trim(substr($st,298,2)) . "</L34d>";	//	Learner support reason
						$ilr .= "<L35>" . (int)trim(substr($st,300,2)) . "</L35>";	//	Prior attainment level
						$ilr .= "<L36>" . trim(substr($st,302,2)) . "</L36>";	//	Learner status on last working day
						$ilr .= "<L37>" . (int)trim(substr($st,304,2)) . "</L37>";	//	Employment status on first day of learning
						$ilr .= "<L38>". (int)trim(substr($st,306,2))  ."</L38>";									//	No longer use. Use blanks
						$ilr .= "<L39>" . (int)trim(substr($st,308,2)) . "</L39>";	//	Destination
						$ilr .= "<L40a>" . trim(substr($st,310,2)) . "</L40a>";	//	National learner monitoring
						$ilr .= "<L40b>" . trim(substr($st,312,2)) . "</L40b>";	//	National learner monitoring
						$ilr .= "<L41a>" . trim(substr($st,314,12)) . "</L41a>";	//	Local learner monitoring
						$ilr .= "<L41b>" . trim(substr($st,326,12)) . "</L41b>";	//	Local learner monitoring
						$ilr .= "<L42a>" . trim(substr($st,338,12)) . "</L42a>";	//	Provider specified learner data
						$ilr .= "<L42b>" . trim(substr($st,350,12)) . "</L42b>";	//	Provider specified learner data
						$ilr .= "<L44>" . trim(substr($st,362,3)) . "</L44>";	//	NES delivery LSC number
						$ilr .= "<L45></L45>";	//	Unique learner number
						$ilr .= "<L46></L46>";	
						$ilr .= "<L47></L47>";	//	Current employment status
						$ilr .= "<L48></L48>"; // Date employment status changed
						$ilr .= "<L49a></L49a>";	//	Current employment status
						$ilr .= "<L49b></L49b>";	//	Current employment status
						$ilr .= "<L49c></L49c>";	//	Current employment status
						$ilr .= "<L49d></L49d>";	//	Current employment status
						$ilr .= "<subaims>k</subaims>";	//	Current employment status
						$ilr .= "</learner>";
					}
					elseif(trim(substr($st,37,2))=='40' && substr($st,8,12)==$masterl03 && substr($st,90,8)==$master_start_date)
					{
						$ilr .= "<programmeaim>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54></A54>";	//	Broker contract number
						$ilr .= "<A55></A55>";	//	Unique learner number
						$ilr .= "<A56></A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</programmeaim>";					
						$A09 = trim(substr($st,29,8));
						$ilr .= "<main>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54></A54>";	//	Broker contract number
						$ilr .= "<A55></A55>";	//	Unique learner number
						$ilr .= "<A56></A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</main>";					
					}
					elseif( (substr($st,37,2)!='' && substr($st,37,2)!='40') && substr($st,8,12)==$masterl03 && substr($st,90,8)==$master_start_date)
					{
						$subaims++;
						$ilr .= "<subaim>";
						$ilr .= "<A01>" . trim(substr($st,0,6)) . "</A01>";
						$ilr .= "<A02>" . trim(substr($st,6,2)) . "</A02>";	//	Contract/ Allocation Type
						$ilr .= "<A03>" . trim(substr($st,8,12)) . "</A03>";	//	Learner reference number
						$ilr .= "<A04>" . trim(substr($st,20,2)) . "</A04>";	//	Data set identifier code
						$ilr .= "<A05>" . trim(substr($st,22,2)) . "</A05>";	//	Learning aim data set sequence
						$ilr .= "<A06>" . trim(substr($st,24,2)) . "</A06>";	//	Learning aim data set sequence
						$ilr .= "<A07>" . trim(substr($st,26,2)) . "</A07>";	//	HE data sets
						$ilr .= "<A08>" . trim(substr($st,28,1)) . "</A08>";	//	Data set format
						$ilr .= "<A09>" . trim(substr($st,29,8)) . "</A09>";	//	Learning aim reference
						$ilr .= "<A10>" . trim(substr($st,37,2)) . "</A10>";	//	LSC funding stream
						$ilr .= "<A11a>" . trim(substr($st,39,3)) . "</A11a>";	//	Source of funding
						$ilr .= "<A11b>" . trim(substr($st,42,3)) . "</A11b>";	//	Source of funding
						$ilr .= "<A12a>" . trim(substr($st,45,3)) . "</A12a>";	//	Tuition fee received for year
						$ilr .= "<A12b>" . trim(substr($st,48,3)) . "</A12b>";	//	Tuition fee received for year
						$ilr .= "<A13>" . trim(substr($st,51,5)) . "</A13>";	//	Tuition fee received for year
						$ilr .= "<A14>" . (int)trim(substr($st,56,2)) . "</A14>";	//	Reason for partial or full non-peyment of tuition fee
						$ilr .= "<A15>" . (int)trim(substr($st,58,2)) . "</A15>";	//	Programme type
						$ilr .= "<A16>" . (int)trim(substr($st,60,2)) . "</A16>";	//	Programme entry route
						$ilr .= "<A17>" . trim(substr($st,62,1)) . "</A17>";	//	Delivery mode
						$ilr .= "<A18>" . (int)trim(substr($st,63,2)) . "</A18>";	//	Main delivery method
						$ilr .= "<A19>" . trim(substr($st,65,1)) . "</A19>";	//	Employer role
						$ilr .= "<A20>" . trim(substr($st,66,1)) . "</A20>";	//	Resit
						$ilr .= "<A21>" . (int)trim(substr($st,67,2)) . "</A21>";	//	Franchised out and partnership arrangement
						$ilr .= "<A22>" . trim(substr($st,69,6)) . "</A22>";	//	Franchised out and partnership delivery provider number
						$ilr .= "<A23>" . trim(substr($st,75,8)) . "</A23>";	//	Delivery location postcode
						$ilr .= "<A24>" . trim(substr($st,83,4)) . "</A24>";	//	Delivery location postcode
						$ilr .= "<A26>" . trim(substr($st,87,3)) . "</A26>";	//	Sector framework of learning 
						$ilr .= "<A27>" . trim(substr($st,90,2)) . "/" . substr($st,92,2) . "/" . substr($st,94,4) . "</A27>"; // Learning start date
						$ilr .= "<A28>" . trim(substr($st,98,2)) . "/" . substr($st,100,2) . "/" . substr($st,102,4) . "</A28>"; // Learning planned end date
						$ilr .= "<A31>" . trim(substr($st,106,2)) . "/" . substr($st,108,2) . "/" . substr($st,110,4) . "</A31>"; // Learning actual end date
						$ilr .= "<A32>" . trim(substr($st,114,5)) . "</A32>";	//	Guided learning hours
						$ilr .= "<A33>" . trim(substr($st,119,5)) . "</A33>";	//	Guided learning hours
						$ilr .= "<A34>" . trim(substr($st,124,1)) . "</A34>";	//	Completion status
						$ilr .= "<A35>" . trim(substr($st,125,1)) . "</A35>";	//	Learning outcome
						$ilr .= "<A36>" . trim(substr($st,126,3)) . "</A36>";	//	Learning outcome grade
						$ilr .= "<A37>" . trim(substr($st,129,2)) . "</A37>";	//	Number of units completed
						$ilr .= "<A38>" . trim(substr($st,131,2)) . "</A38>";	//	Number of units to achieve full qualification
						$ilr .= "<A39>" . trim(substr($st,133,1)) . "</A39>";	//	Eligibility for achievement funding
						$ilr .= "<A40>" . trim(substr($st,134,2)) . "/" . substr($st,136,2) . "/" . substr($st,138,4) . "</A40>"; // Achivement date
						$ilr .= "<A43>" . trim(substr($st,142,2)) . "/" . substr($st,144,2) . "/" . substr($st,146,4) . "</A43>"; // Sector framework achievment date 
						$ilr .= "<A44>" . trim(substr($st,150,30)) . "</A44>";	//	Employer identifier
						$ilr .= "<A45>" . trim(substr($st,180,8)) . "</A45>";	//	Workplace location postcode
						$ilr .= "<A46a>" . (int)trim(substr($st,188,2)) . "</A46a>";	//	National learning aim monitoring
						$ilr .= "<A46b>" . (int)trim(substr($st,190,2)) . "</A46b>";	//	National learning aim monitoring
						$ilr .= "<A47a>" . trim(substr($st,192,12)) . "</A47a>";	//	Local learning aim monitoring
						$ilr .= "<A47b>" . trim(substr($st,204,12)) . "</A47b>";	//	Local learning aim monitoring
						$ilr .= "<A48a>" . trim(substr($st,216,12)) . "</A48a>";	//	Provider specified learning aim data
						$ilr .= "<A48b>" . trim(substr($st,228,12)) . "</A48b>";	//	Provider specified learning aim data
						$ilr .= "<A49>" . trim(substr($st,240,5)) . "</A49>";	//	Special projects and pilots
						$ilr .= "<A50>" . (int)trim(substr($st,245,2)) . "</A50>";	//	Reason learning ended
						$ilr .= "<A51a>" . trim(substr($st,247,2)) . "</A51a>";	//	Proportion of funding remaining
						$ilr .= "<A52>" . trim(substr($st,249,5)) . "</A52>";	//	Distance learning funding
						$ilr .= "<A53>" . trim(substr($st,254,2)) . "</A53>";	//	Additional learning needs
						$ilr .= "<A54></A54>";	//	Broker contract number
						$ilr .= "<A55></A55>";	//	Unique learner number
						$ilr .= "<A56></A56>";	//	UK Provider reference number
						$ilr .= "<A57></A57>";	//	Source of tuition fees
						$ilr .= "<A58></A58>";	//	Source of tuition fees
						$ilr .= "<A59></A59>";	//	Source of tuition fees
						$ilr .= "<A60></A60>";	//	Source of tuition fees
						$ilr .= "<A61></A61>";	//	Source of tuition fees
						$ilr .= "<A62></A62>";	//	Source of tuition fees
						$ilr .= "<A63></A63>";	//	Source of tuition fees
						$ilr .= "<A64></A64>";	//	Source of tuition fees
						$ilr .= "<A65></A65>";	//	Source of tuition fees
						$ilr .= "<A66></A66>";	//	Source of tuition fees
						$ilr .= "<A67></A67>";	//	Source of tuition fees
						$ilr .= "<A68></A68>";	//	Source of tuition fees
						$ilr .= "</subaim>";					
					}
				}
			
				$ilr .= "</ilr>";
				fclose($handle);
				$tr_id++;
				$ilr = str_replace("<subaims>k</subaims>","<subaims>".$subaims."</subaims>",$ilr);
				$ilr = str_replace("'","&apos;",$ilr);
				
				$tt = DAO::getSingleValue($link, "select tr_id from ilr where l03 = '$masterl03' LIMIT 0,1");
				if($tt=='')
					$tt = $tr_id;
			// Write in database
$query = <<<HEREDOC
insert into
	ilr
VALUES('$L01','$masterl03','$A09','$ilr','$submission','$contract_type','$tt','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');
HEREDOC;
			$st = $link->query($query);
			if($st == false)
			{
				throw new Exception(implode($link->errorInfo()), $link->errorCode());
			}
		}
	}
		
fclose($masterhandle);
		
		
*/		
	}
}
?>