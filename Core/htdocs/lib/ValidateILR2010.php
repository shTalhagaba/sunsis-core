<?php
class ValidateILR2010
{
	public function validate(PDO $link, Ilr2010 $ilr)
	{
		$class = new ReflectionClass(__CLASS__);
		$methods = $class->getMethods();

		$report = '';

		// Create separate connections for lis and lad
		$hostname = ini_get('mysqli.default_host');
		$port = ini_get('mysqli.default_port');

		$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis201011;port=".DB_PORT, DB_USER, DB_PASSWORD);
		$linklad = new PDO("mysql:host=".DB_HOST.";dbname=lad201011;port=".DB_PORT, DB_USER, DB_PASSWORD);
		
				
		foreach($methods as $method)
		{
			if(preg_match('/^rule/', $method->getName()) > 0)
			{
				$res='';
				// Call rule
				$method_name = $method->getName();
				
				$res = $this->$method_name($link, $linklad, $linklis, $ilr);

				if($res!='')
					$report .= "<error>" . $res . "</error>"; 
			}
		}

	
		if($report!='') 
			$report = '<report>' . $report . '</report>';
			
		return $report;

	}

	
	
	// Field Level Validations (A Series)
	
			private function rule_A01_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=(int)$ilr->learnerinformation->subaims;$sa++)
				{
					if(trim($ilr->learnerinformation->L01)!=trim($ilr->aims[$sa]->A01))
					{
						return "A01_1[".$sa."]: The provider number in the aim and learner must match ";
					}
				}
			}

			private function rule_A02_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A02)!='12' && trim($ilr->aims[$sa]->A02)!='00' && trim($ilr->aims[$sa]->A02)!='')
					{
						return "A02_2[".$sa."]: Contract type must be 12 or 00 ";
					}
				}
			}

			private function rule_A04_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A04)!='30' && trim($ilr->aims[$sa]->A02)!='35')
					{
						return "A04_1[".$sa."]: Data set identifier code must be 30 or 35 ";
					}
				}
			}
			
			
			private function rule_A05_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if((int)trim($ilr->aims[$sa]->A05)<1 || (int)trim($ilr->aims[$sa]->A05)>98)
					{
						return "A05_1[".$sa."]: Learning aim data set sequence must be numeric and between 01 and 98 ";
					}
				}
			}

			
			private function rule_A07_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A07)!='00')
					{
						return "A07_2[".$sa."]: HE data sets must be 00 ";
					}
				}
			}

			private function rule_A08_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A08)!='2')
					{
						return "A08_3[".$sa."]: Data set format must be 2 ";
					}
				}
			}


			private function rule_A09_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$query = "select learning_aim_ref from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$A09 = trim(DAO::getSingleValue($linklad, $query));

					if(trim($A09)!=trim($ilr->aims[$sa]->A09))
					{
						return "A09_1[".$sa."]: Learning Aim reference code is not valid ";
					}
				}
			}

			private function rule_A09_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A09)=='XE2E0001')
					{
						return "A09_2[".$sa."]: Cannot be XE2E0001 ";
					}
				}
			}
			
			private function rule_A10_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					if($A10!='45' && $A10!='46' && $A10!='70' && $A10!='80' && $A10!='81' && $A10!='82' && $A10!='99')
					{
						return "A10_4[".$sa."]: LSC funding stream must be 45, 46, 70, 80, 81, 82 or 99 ";
					}
				}
			}


			private function rule_A11a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A11a = trim($ilr->aims[$sa]->A11a);
					if($A11a=='' || $A11a=='000')
					{
						return "A11a_1[".$sa."]: A11a must have a value";
					}
				}
			}
			
			private function rule_A11b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A11b = trim($ilr->aims[$sa]->A11b);
					if($A11b=='' || $A11b=='000')
					{
						return "A11b_1[".$sa."]: A11a must have a value";
					}
				}
			}
			

			private function rule_A13_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A13 = trim($ilr->aims[$sa]->A13);
					if($A13!='00000')
					{
						return "A13_2[".$sa."]: Tuition fee received for year must be 00000 ";
					}
				}
			}
			
			private function rule_A14_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A14 = trim($ilr->aims[$sa]->A14);
					if($A14=='00' || $A14=='')
					{
						return "A14_1[".$sa."]: Must exist on the table";
					}
				}
			}

			private function rule_A15_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$a15 = trim($ilr->aims[$sa]->A15);
					if($a15!='2' && $a15!='3' && $a15!='10' && $a15!='11' && $a15!='12' && $a15!='13' && $a15!='14' && $a15!='99')
					{
						return "A15_3[".$sa."]: Programme Type must be 02, 03, 10, 11, 12, 13, 14 or 99 ";
					}
				}
			}
			
			private function rule_A16_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$query = "select programme_route_code from ilr_a16_programme_routes where programme_route_code='{$ilr->aims[$sa]->A16}'";
					$A16 = DAO::getSingleValue($linklis, $query);

					//$A16 = str_pad($A16,2,'0',STR_PAD_LEFT);

					if($A16!='' && $A16!=$ilr->aims[$sa]->A16)
					{
						return "A16_3[".$sa."]: Invalid Programme Route Code ";
					}
				}
			}

			private function rule_A17_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A17 = trim($ilr->aims[$sa]->A17);
					if($A17!='0')
					{
						return "A17_2[".$sa."]: Delivery mode must be 0 ";
					}
				}
			}


			private function rule_A18_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A18 = trim($ilr->aims[$sa]->A18);
				
					$query = "select Delivery_Method_Code from ilr_a18_delivery_methods where Delivery_Method_Code='{$ilr->aims[$sa]->A18}' and ER_Ind='Y'";
					$data = DAO::getSingleValue($linklis, $query);
					
					if($A18!=$data && $A18!='00')
					{
						return "A18_3[".$sa."]: Main delivery method must be valid ";
					}
				}
			}

			private function rule_A19_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A19 = trim($ilr->aims[$sa]->A19);
					if($A19!='0')
					{
						return "A19_2[".$sa."]: Employer role must be 0 ";
					}
				}
			}


			private function rule_A20_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A20 = trim($ilr->aims[$sa]->A20);
					if($A20!='0')
					{
						return "A20_2[".$sa."]: Resit must be 0 ";
					}
				}
			}

			private function rule_A22_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A22 = trim($ilr->aims[$sa]->A22);
					if($A22!='' && ($A22<10000000 || $A22>99999999))
					{
						return "A22_3[".$sa."]: If entered, must be in the range 10000000 and 99999999";
					}
				}
			}

			private function rule_A23_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A23 = trim($ilr->aims[$sa]->A23);
					if($A23!='')
					{
						$check = substr($A23,(strpos($A23," ")+1),3);
					 	$first = substr($check,0,1);
					 	$second = substr($check,1,1);
					 	$third = substr($check,2,1);
					}
					if($A23!='')
						if(ord($first)<48 || ord($first)>57 || ord($second)<65 || ord($second)>90 || ord($third)<65 || ord($third)>90 || $second=='C' || $second=='I' || $second=='K' || $second=='M' || $second=='O' || $second=='V' || $third=='C' || $third=='I' || $third=='K' || $third=='M' || $third=='O' || $third=='V')
							if($check!='ZZZ')
					{

						return "A23_3[".$sa."]: Delivery location postcode: The second part of the postcode must be in the correct format (nXX) where n = 0-9 and XX are capital letters excluding C, I, K, M, O and V,  or be 'ZZZ', or the whole postcode must be spaces \n";

					}
				}
			}

			private function rule_A23_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A23 = trim($ilr->aims[$sa]->A23);
					if($A23!='')
					{$check = substr($A23,0,(strpos($A23," ")));
					 $first = substr($check,0,1);
					}
					if($A23!='')
						if(ord($first)<65 || ord($first)>90 || strlen($check)<2 || strlen($check)>4)
							if($check!='Z99')
					{

						return "A23_4[".$sa."]: Invalid delivery location Postcode\n";

					}
				}
			}


			private function rule_A26_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A26 = trim($ilr->aims[$sa]->A26);
					
					$query = "select Framework_Code from frameworks where Framework_Code='{$ilr->aims[0]->A26}'";
					$A26t = trim(DAO::getSingleValue($linklad, $query));

					if($A26!='' && $A26!=$A26t)
					{
						return "A26_1[".$sa."]: Sector framework code is not valid \n";
					}
				}
			}


			private function rule_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A27 = trim($ilr->aims[$sa]->A27);
					if($A27=='' || $A27=='00000000' || $A27=='dd/mm/yyyy')
					{
						return "A27_1[".$sa."]: Learning start date is  mandatory\n";
					}
				}
			}

			private function rule_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
					}
					catch(Exception $e)
					{
						return "A27_2[".$sa."]: Invalid Learning start date\n";
					}
				}
			}

			private function rule_A27_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2000');
						if($A27->getDate()<$check->getDate())
						{

							return "A27_4[".$sa."]: Learning start date must not be more than 10 years ago\n";

						}
					}
					catch(Exception $e)
					{
						return "A27_4[".$sa."]: Learning start date must not be more than 10 years ago\n";
					}


				}
			}

			private function rule_A27_6(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$d = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2020');
						if($d->getDate()>$check->getDate())
						{

							return "A27_6[".$sa."]: Learning start date must not be more than 10 years in the future\n";

						}
					}
					catch(Exception $e)
					{
							return "A27_6[".$sa."]: Learning start date must not be more than 10 years in the future\n";

					}
				}
			}

			private function rule_A28_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A28 = trim($ilr->aims[$sa]->A28);
					if($A28=='' || $A28=='00000000' || $A28=='dd/mm/yyyy')
					{

						return "A28_1[".$sa."]: Planned end date is mandatory\n";

					}
				}
			}

			private function rule_A28_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$A28 = new Date($ilr->aims[$sa]->A28);
					}
					catch(Exception $e)
					{
						return "A28_2[".$sa."]: Invalid planned end date\n";
					}
				}
			}

			private function rule_A31_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$d = trim($ilr->aims[$sa]->A31);
					if($d!='00000000' && $d!='dd/mm/yyyy' && $d!='')
					{
							try
							{
								$A31 = new Date($ilr->aims[$sa]->A31);
							}
							catch(Exception $e)
							{
								return "A31_1[".$sa."]: Learning actual end date must be a valid date or 00000000 \n";
							}

					}
				}
			}


			private function rule_A31_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$check = new Date(date('d/m/Y'));
					$s = trim($ilr->aims[$sa]->A31);
					if($s!='00000000' && $s!='' && $s!='dd/mm/yyyy')
					{
							try
							{
								$A31=new Date($ilr->aims[$sa]->A31);
								if($A31->getDate()>$check->getDate())
								{
									return "A31_2[".$sa."]: Learning actual end date must be on or before current date";
								}
							}
							catch(Exception $e)
							{
								return "A31_2[".$sa."]: Learning actual end date must be on or before current date";
							}
					}
				}
			}

			private function rule_A32_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if($ilr->aims[$sa]->A32!='00000')
					{
						return "A32_3[".$sa."]: Guided Learner Hours must be 00000 \n";
					}
				}
			}
		

			private function rule_A34_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A34 = trim($ilr->aims[$sa]->A34);
					
					$query = "select Completion_Status_Code from ilr_a34_completion_status where Completion_Status_Code='{$ilr->aims[$sa]->A34}'";
					$A34t = trim(DAO::getSingleValue($linklis, $query));

					if($A34!=$A34t)
					{
						return "A34_3[".$sa."]: Invalid Completion Status Code \n";
					}
				}
			}


			private function rule_A35_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A35 = trim($ilr->aims[$sa]->A35);
					
					$query = "select Learning_Outcome_Code from ilr_a35_learning_outcomes where Learning_Outcome_Code='{$ilr->aims[$sa]->A35}'";
					$A35t = trim(DAO::getSingleValue($linklis, $query));

					if($A35!=$A35t)
					{
						return "A35_1[".$sa."]: Invalid Learning outcome code \n";
					}
				}
			}

			private function rule_A36_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A36 = trim($ilr->aims[$sa]->A36);
					
					$query = "select Learning_Outcome_Grade_Code from ilr_a36_learn_outcome_grades where Learning_Outcome_Grade_Code='{$ilr->aims[$sa]->A35}'";
					$A36t = trim(DAO::getSingleValue($linklis, $query));

					if($A36!='' && $A36!=$A36t)
					{
						return "A36_1[".$sa."]: Learning outcome grade must exist on the table if entered \n";
					}
				}
			}
			


			private function rule_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$d = trim($ilr->aims[$sa]->A40);
					if($d!='00000000' && $d!='dd/mm/yyyy' && $d!='')
					{
							try
							{
								$A40 = new Date($ilr->aims[$sa]->A40);
							}
							catch(Exception $e)
							{
								return "A40_1[".$sa."]: Achievement date must be a valid date or 00000000 \n";
							}

					}
				}
			}


			private function rule_A40_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$check = new Date(date('d/m/Y'));
					$s = trim($ilr->aims[$sa]->A40);
					if($s!='00000000' && $s!='dd/mm/yyyy' && $s!='')
					{
							try
							{
								$A40=new Date($ilr->aims[$sa]->A40);
								if($A40->getDate()>$check->getDate())
								{
									return "A40_2[".$sa."]: Achievement date must be less than or equals to current date \n";
								}
							}
							catch(Exception $e)
							{
								return "A40_2[".$sa."]: Learning actual end date must be on or before current date";
							}
					}
				}
			}


			private function rule_A44_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A44 = trim($ilr->aims[$sa]->A44);
					$st="0123456789";
					for($lp=0;$lp<9;$lp++)
					{
						if(strpos($st,substr($A44,$lp,1))===false)
						{
							return "A44_3[".$sa."]: Must be a 9 digit number.  Characters 1-9 must be a number from 0-9 and characters 10-30 must be spaces.";
						}
					}
					$st=" ";
					for($lp=9;$lp<strlen($A44);$lp++)
					{
						if(strpos($st,substr($A44,$lp,1))===false)
						{
							return "A44_3[".$sa."]: Must be a 9 digit number.  Characters 1-9 must be a number from 0-9 and characters 10-30 must be spaces.";
						}
					}
				}
			}


			private function rule_A45_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A45 = trim($ilr->aims[$sa]->A45);
					if($A45!='')
					{$check = substr($A45,(strpos($A45," ")+1),3);
					 $first = substr($check,0,1);
					 $second = substr($check,1,1);
					 $third = substr($check,2,1);
					}
					if($A45!='')
						if(ord($first)<48 || ord($first)>57 || ord($second)<65 || ord($second)>90 || ord($third)<65 || ord($third)>90 || $second=='C' || $second=='I' || $second=='K' || $second=='M' || $second=='O' || $second=='V' || $third=='C' || $third=='I' || $third=='K' || $third=='M' || $third=='O' || $third=='V')
							if($check!='ZZZ')
					{

						return "A45_3[".$sa."]: Workplace Location Postcode: The second part of the postcode must be in the correct format (nXX) where n = 0-9 and XX are capital letters excluding C, I, K, M, O and V,  or be 'ZZZ', or the whole postcode must be spaces \n";

					}
				}
			}

			private function rule_A45_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A45 = trim($ilr->aims[$sa]->A45);
					if($A45!='')
					{$check = substr($A45,0,(strpos($A45," ")));
					 $first = substr($check,0,1);
					}
					if($A45!='')
						if(ord($first)<65 || ord($first)>90 || strlen($check)<2 || strlen($check)>4)
							if($check!='Z99')
					{

						return "A45_4[".$sa."]: Invalid work location postcode \n";

					}
				}
			}

			private function rule_A46a_7(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46a = trim($ilr->aims[$sa]->A46a);
					
					$query = "select National_Learner_Aim_Code from ilr_a46_nat_learner_aims where ER_Ind='Y' and National_Learner_Aim_Code = '$A46a'";
					$A46at = DAO::getSingleValue($linklis, $query);
					
					if($A46a!=$A46at)
					{
						return "A46a_7[".$sa."]: National learning aim monitoring 1 Must exist on the table \n";
					}
				}
			}

			private function rule_A46b_7(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46b = trim($ilr->aims[$sa]->A46b);
					
					$query = "select National_Learner_Aim_Code from ilr_a46_nat_learner_aims where ER_Ind='Y' and National_Learner_Aim_Code = '$A46b'";
					$A46bt = DAO::getSingleValue($linklis, $query);
										
					if($A46b!=$A46bt)
					{
						return "A46b_7[".$sa."]: National learning aim monitoring 2 Must exist on the table \n";
					}
				}
			}
			
			private function rule_A47a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A47a = trim($ilr->aims[$sa]->A47a);

					if((int)$A47a<0)
					{

						return "A47a_1[".$sa."]: Local aim monitoring (1) must be greater or equal 0 if entered \n";

					}
				}
			}

			private function rule_A47b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A47b = trim($ilr->aims[$sa]->A47b);

					if((int)$A47b<0)
					{

						return "A47b_1[".$sa."]: Local aim monitoring (2) must be greater or equal 0 if entered \n";

					}
				}
			}


			private function rule_A48a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A48a = trim($ilr->aims[$sa]->A48a);

					if(strpos($A48a,'*')!= false || strpos($A48a,'?')!= false  || strpos($A48a,'%')!=false || strpos($A48a,'_')!=false)
					{
						return "A48a_1[".$sa."]: Provider specified learning aim data 1 (1) must not contain *, ?, % or _ symbols \n";
					}
				}
			}

			private function rule_A48b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A48b = trim($ilr->aims[$sa]->A48b);

					if(strpos($A48b,'*')!= false || strpos($A48b,'?')!= false  || strpos($A48b,'%')!=false || strpos($A48b,'_')!=false)
					{
						return "A48b_b[".$sa."]: Provider specified learning aim data 1 (2) must not contain *, ?, % or _ symbols \n";
					}
				}
			}

			private function rule_A49_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$query = "select Project_Code from ilr_a49_project_codes where project_code='{$ilr->aims[$sa]->A49}'";
					$A49t = trim(DAO::getSingleValue($linklis, $query));

					if($A49t!=trim($ilr->aims[$sa]->A49) && trim($ilr->aims[$sa]->A49)!='')
					{
						return "A49_3[".$sa."]: Invalid project code \n";
					}
				}
			}

			private function rule_A50_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A50 = trim($ilr->aims[$sa]->A50);
					
					$query = "select Reason_Learning_Ended_Code from ilr_a50_reason_learning_ended where Reason_Learning_Ended_code='{$ilr->aims[$sa]->A50}'";
					$A50t = trim(DAO::getSingleValue($linklis, $query));

					if($A50!=$A50t || $A50=='')
					{
						return "A50_1[".$sa."]: Must be entered \n";
					}
				}
			}
			
			
			private function rule_A51a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A51a = trim($ilr->aims[$sa]->A51a);

					if((int)$A51a<0 || (int)$A51a>100)
					{
						return "A51a_2[".$sa."]: Proportion of funding must be between 1 and 99\n";
					}
				}
			}


			private function rule_A52_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					
					if(trim($ilr->aims[$sa]->A52)!='0.000')
					{
						return "A52_4[".$sa."]: Invalid distance learning SLN\n";
					}
				}
			}
			
			private function rule_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A53 = trim($ilr->aims[$sa]->A53);
					
					$query = "select Additional_Learning_Need_Code from ilr_a53_add_learning_needs where Additional_Learning_Need_Code='{$ilr->aims[$sa]->A53}'";
					$A53t = trim(DAO::getSingleValue($linklis, $query));

					if($A53!=$A53t)
					{
						return "A53_1[".$sa."]: Invalid additional learning needs code\n";
					}
				}
			}



			private function rule_A55_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A55 = trim($ilr->aims[$sa]->A55);
					$L45 = trim($ilr->learnerinformation->L45);
					if($A55!=$L45)
					{
						return "A55_1[".$sa."]: Unique learner number in learner information and aim information must be the same \n";
					}
				}
			}

			private function rule_A56_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A56 = trim($ilr->aims[$sa]->A56);
					$L46 = trim($ilr->learnerinformation->L46);
					if($A56!=$L46)
					{
						return "A56_1[".$sa."]: UK provider reference number in learner information and aim information must be the same \n";
					}
				}
			}


			private function rule_A57_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A57 = trim($ilr->aims[$sa]->A57);
					if($A57!='00')
					{
						return "A57_2[".$sa."]: Source of tuition fees must be zeros \n";
					}
				}
			}

			private function rule_A58_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A58 = trim($ilr->aims[$sa]->A58);
					if($A58!='00')
					{
						return "A58_4[".$sa."]: ASL provision type must be zeros \n";
					}
				}
			}
			
			private function rule_A59_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A59 = (trim($ilr->aims[$sa]->A59));
					if($A59<0 || $A59>999)
					{
						return "A59_1[".$sa."]: Planned credit value must be between 000-999\n";
					}
				}
			}
			
			private function rule_A60_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A60 = (trim($ilr->aims[$sa]->A60));
					if($A60<0 || $A60>999)
					{
						return "A60_1[".$sa."]: Credit achieved value must be between 000-999\n";
					}
				}
			}

			private function rule_A61_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A61 = trim($ilr->aims[$sa]->A61);
		
					if($A61!='' && is_int(substr($A61,5,1))==false && substr($A61,5,1)!='L')
					{
						return "A61_1[".$sa."]: Invalid project dossier number \n";
					}
				}
			}
						
			private function rule_A62_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A62 = trim($ilr->aims[$sa]->A62);
		
					if(((int)$A62<0 || (int)$A62>999))
					{
						return "A62_1[".$sa."]: Invalid Local Project Number \n";
					}
				}
			}

			
			private function rule_A64_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A64 = trim($ilr->aims[$sa]->A64);
		
					if(((int)$A64<0 || (int)$A64>10000))
					{
						return "A64_1[".$sa."]: Invalid planned group-based hours \n";
					}
				}
			}
			
			private function rule_A65_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A65 = trim($ilr->aims[$sa]->A65);
		
					if(((int)$A65<0 || (int)$A65>10000))
					{
						return "A65_1[".$sa."]: Invalid Planned one-to-one contact hours \n";
					}
				}
			}
			
			
			

	// Single Field Validation (L Series) 		
			
			private function rule_L01_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$query = "select CAPN from providers where CAPN='{$ilr->learnerinformation->L01}'";
				$L01 = trim(DAO::getSingleValue($linklis, $query));
		
				if($L01!=trim($ilr->learnerinformation->L01))
				{
					return "L01_2: Invalid provider number\n";
				}
			}


			private function rule_L02_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L02 = trim($ilr->learnerinformation->L02);
				if($L02!='00')
				{
					return "L02_2: Contract type must be 00 \n";
				}
		
			}

			private function rule_L03_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L03 = trim($ilr->learnerinformation->L03);
				if($L03=='')
				{
					return "L03_1: You must enter learner reference number\n";
				}
		
			}
	
			private function rule_L03_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L03 = trim($ilr->learnerinformation->L03);
				$st="ÑabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ";
				for($lp=0;$lp<strlen($L03);$lp++)
				{
					if(strpos($st,substr($L03,$lp,1))==false)
					{
						return "L03_2: Learner reference number is not valid";
					}
				}
		
			}
	
	
	
			private function rule_L07_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L07 = trim($ilr->learnerinformation->L07);
				if($L07!='00')
				{
					return "L07_1: HE data sets must be 00 \n";
				}
		
			}
	
	
			private function rule_L08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L08 = trim($ilr->learnerinformation->L08);
				if($L08!='Y' && $L08!='N')
				{
					return "L08_1: Deletion must be N or Y \n";
				}
		
			}
	
			private function rule_L09_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L09 = trim($ilr->learnerinformation->L09);
				if($L09=='')
				{
					return "L09_1: Learner surname is mandatory \n";
				}
			}
	
			private function rule_L09_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L09 = trim($ilr->learnerinformation->L09);
				$L09 = str_replace("&apos;","'",$L09);
				$st="ÑabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ -'";
				for($lp=0;$lp<strlen($L09);$lp++)
				{
					if(strpos($st,substr($L09,$lp,1))==false)
					{
						return "L09_2: Learner surname contains invalid characters \n";
					}
				}
		
			}
	
			private function rule_L10_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L10 = trim($ilr->learnerinformation->L10);
				if($L10=='')
				{
					return "L10_1: Learner forenames is mandatory \n";
				}
		
			}
		
			private function rule_L10_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L10 = trim($ilr->learnerinformation->L10);
				$st="⌡♪ÑabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ -'";
				for($lp=0;$lp<strlen($L10);$lp++)
				{
					if(strpos($st,substr($L10,$lp,1))==false)
					{
						//throw new Exception($L10."-".$lp);
						return "L10_2: Learner forenames contains invalid characters \n";
					}
				}
		
			}
	
	
			private function rule_L11_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				if($ilr->learnerinformation->L11!='00000000')
				{
					try
					{
						$L11 = new Date($ilr->learnerinformation->L11);
					}
					catch(Exception $e)
					{
						return "L11_1: Invalid date of birth\n";
					}
				}
			}
	
	
			private function rule_L11_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				try
				{
					$d = new Date($ilr->learnerinformation->L11);
					$start = new Date('01/08/1884');
					$end   = new Date('01/08/2010');
					if($d->getDate() < $start->getDate() || $d->getDate()>$end->getDate())
					{
						return "L11_3: Date of birth must be between 01/08/1884 and 01/08/2010\n";
					}
				}
				catch(Exception $e)
				{
					return "L11_3: Date of birth must be between 01/08/1884 and 01/08/2010\n";
				
				}
		
			}
	
			private function rule_L11_6(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				try
				{
					$L11 = new Date($ilr->learnerinformation->L11);
				}
				catch(Exception $e)
				{
					return "L11_6: Date of birth is mandatory\n";
				}
			}
	
			private function rule_L12_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				 
				$query = "select Ethnicity_Code from ilr_l12_ethnicity where Ethnicity_Code='{$ilr->learnerinformation->L12}'";
				$L12 = trim(DAO::getSingleValue($linklis, $query));
		
				if($L12!=trim($ilr->learnerinformation->L12) || $ilr->learnerinformation->L12=='')
				{
					return "L12_2: Invalid ethnicity code\n";
				}
			}
	
			private function rule_L13_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L13 = trim($ilr->learnerinformation->L13);
				if($L13!='M' && $L13!='F')
				{
					return "L13_2: Invalid character for gender\n";
				}
			}
			
			private function rule_L14_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$query = "select Difficulty_Disability from ilr_l14_difficulty_disability where Difficulty_Disability='{$ilr->learnerinformation->L14}'";
				$L14 = trim(DAO::getSingleValue($linklis, $query));
			
				if($L14!=trim($ilr->learnerinformation->L14))
				{
					return "L14_2: Invalid Learning difficulties or disability \n";
				}
			}
	

	
			private function rule_L15_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$query = "select Disability_Code from ilr_l15_disability where Disability_Code='{$ilr->learnerinformation->L15}'";
				$L15 = trim(DAO::getSingleValue($linklis, $query));
			
				if($L15!=trim($ilr->learnerinformation->L15))
				{
					return "L15_2: Invalid disability code \n";
				}
			}
	

	
			private function rule_L16_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$query = "select Difficulty_Code from ilr_l16_difficulty where Difficulty_Code='{$ilr->learnerinformation->L16}'";
				$L16 = trim(DAO::getSingleValue($linklis, $query));
			
				if($L16!=trim($ilr->learnerinformation->L16))
				{
					return "L16_2: Invalid difficulty code \n";
				}
			}
	
	
			private function rule_L17_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L17 = trim($ilr->learnerinformation->L17);
				if($L17!='')
				{
					$check = substr($L17,(strpos($L17," ")+1),3);
					 $first = substr($check,0,1);
					 $second = substr($check,1,1);
					 $third = substr($check,2,1);
				}
			
				if($L17!='')
					if(ord($first)<48 || ord($first)>57 || ord($second)<65 || ord($second)>90 || ord($third)<65 || ord($third)>90 || $second=='C' || $second=='I' || $second=='K' || $second=='M' || $second=='O' || $second=='V' || $third=='C' || $third=='I' || $third=='K' || $third=='M' || $third=='O' || $third=='V')
						if($check!='ZZZ')
						{
							return "L17_3: Home Postcode: The second part of the postcode must be in the correct format (nXX) where n = 0-9 and XX are capital letters excluding C, I, K, M, O and V,  or be 'ZZZ', or the whole postcode must be spaces \n";
						}
			}
	

	
			private function rule_L17_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L17 = trim($ilr->learnerinformation->L17);
				if($L17!='')
				{
					$check = substr($L17,0,(strpos($L17," ")));
					$first = substr($check,0,1);
				}
			
				if($L17!='')
					if(ord($first)<65 || ord($first)>90 || strlen($check)<2 || strlen($check)>4)
						if($check!='Z99')
						{
							return "L17_4: Invalid home postcode\n";
						}
			}

	
			private function rule_L18_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L18 = trim($ilr->learnerinformation->L18);
				if($L18=='')
				{
					return "L18_1: Address is mandatory \n";
				}
			}
	
	
			private function rule_L18_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L18 = trim($ilr->learnerinformation->L18);
				$st="ôabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-'/.&![]+:;@ ";
			
				for($lp=0;$lp<strlen($L18);$lp++)
				{
					if(strpos($st,substr($L18,$lp,1))==false)
					{
						return "L18_2: Address Line 1 contains invalid characters \n";
					}
				}
			}
	

			private function rule_L19_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L19 = trim($ilr->learnerinformation->L19);
				$st="ôabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-'/.&![]+:;@ ";
			
				for($lp=0;$lp<strlen($L19);$lp++)
				{
					if(strpos($st,substr($L19,$lp,1))==false)
					{
						return "L19_1: Address Line 2 contains invalid characters \n";
					}
				}
			}
	

			private function rule_L20_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L20 = trim($ilr->learnerinformation->L20);
				$st="ôabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-'/.&![]+:;@ ";
		
				for($lp=0;$lp<strlen($L20);$lp++)
				{
					if(strpos($st,substr($L20,$lp,1))==false)
					{
						return "L20_1: Address Line 3 contains invalid characters \n";
					}
				}
			}

	
			private function rule_L21_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L21 = trim($ilr->learnerinformation->L21);
				$st="ôabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-'/.&![]+:;@ ";
			
				for($lp=0;$lp<strlen($L21);$lp++)
				{
					if(strpos($st,substr($L21,$lp,1))==false)
					{
						return "L21_1: Address Line 4 contains invalid characters \n";
					}
				}
			}
	
			private function rule_L22_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L22 = trim($ilr->learnerinformation->L22);
				if($L22!='')
				{
					$check = substr($L22,(strpos($L22," ")+1),3);
				 	$first = substr($check,0,1);
					$second = substr($check,1,1);
					$third = substr($check,2,1);
				}
			
				if($L22!='')
			
					if(ord($first)<48 || ord($first)>57 || ord($second)<65 || ord($second)>90 || ord($third)<65 || ord($third)>90 || $second=='C' || $second=='I' || $second=='K' || $second=='M' || $second=='O' || $second=='V' || $third=='C' || $third=='I' || $third=='K' || $third=='M' || $third=='O' || $third=='V')
						if($check!='ZZZ')
						{
							return "L22_3: Current Postcode L22: The second part of the postcode must be in the correct format (nXX) where n = 0-9 and XX are capital letters excluding C, I, K, M, O and V,  or be 'ZZZ', or the whole postcode must be spaces \n";
						}
			}
	

			private function rule_L22_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L22 = trim($ilr->learnerinformation->L22);
				if($L22!='')
				{
					$check = substr($L22,0,(strpos($L22," ")));
					$first = substr($check,0,1);
				}
			
				if($L22!='')
					if(ord($first)<65 || ord($first)>90 || strlen($check)<2 || strlen($check)>4)
						if($check!='Z99')
						{
							return "L22_4: Invalid current postcode\n";
						}
			}
	

	
			private function rule_L23_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$ilr->learnerinformation->L23 = trim($ilr->learnerinformation->L23);
				$L23 = trim($ilr->learnerinformation->L23);

				for($lp=0;$lp<strlen($L23);$lp++)
				{
					if(is_numeric(substr($L23,$lp,1))==false)
					{
						return "L23_1: Telephone number must contains only digits \n";
					}
				}
			}
	

	
			private function rule_L24_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$query = "select Domicile_Code from ilr_l24_domiciles where Domicile_Code='{$ilr->learnerinformation->L24}'";
				$L24 = trim(DAO::getSingleValue($linklis, $query));

				if($L24!=trim($ilr->learnerinformation->L24) || $ilr->learnerinformation->L24=='')
				{
					return "L24_3: Invalid country of domicile \n";
				}
			}

	
			private function rule_L26_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
		
				$L26 = trim($ilr->learnerinformation->L26);

				if($L26!='' && $L26!='         ')
				{
					if(strlen($L26)!=9)
					{
						return "L26_1: Invalid insurance number \n";
					}
				
					$one = substr($L26,0,1);
					$two = substr($L26,1,1);
					$digi = substr($L26,2,6);
					$st='0123456789';
					$nine = substr($L26,8,1);
	
					if(ord($one)<65 || ord($one)>90 || $one=='D' || $one=='F' || $one=='I' || $one=='Q' || $one=='U' || $one=='V')
					{
						return "L26_1: The first character of National Insurance no. must be an alphabet other than D, F, I, Q, U and V \n";
					}
	
					if(ord($two)<65 || ord($two)>90 || $two=='D' || $two=='F' || $two=='I' || $two=='O' || $two=='Q' || $two=='U' || $two=='V')
					{
						return "L26_1: The second character of National Insurance no. must be an alphabet other than D, F, I, O, Q, U and V \n";
					}
				
					for($lp=0;$lp<strlen($digi);$lp++)
					{
						if(strpos($st,substr($digi,$lp,1))==-1)
						{
							return "L26_1: Characters 3 to 8 of National Insuarnce no. must only be digits \n";
						}
					}
				
					if( ord($nine)<65 || ord($nine)>90 || ($nine!='A' && $nine!='B' && $nine!='C' && $nine!='D' && $nine!=' '))
					{
						return "L26_1: The character 9 of National Insurance no. must be A, B, C, D or space \n";
					}
				}
			}
	

	
			private function rule_L27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$query = "select Restricted_Use_Code from ilr_l27_restricted_uses where Restricted_Use_Code='{$ilr->learnerinformation->L27}'";
				$L27 = trim(DAO::getSingleValue($linklis, $query));
		
				if($L27!=trim($ilr->learnerinformation->L27))
				{
					return "L27_1: Invalid restricted use indicator \n";
				}
			}
	
	
			private function rule_L29_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L29 = trim($ilr->learnerinformation->L29);
				if($L29!='00')
				{
					return "L29_2: Additional support must be 00 \n";
				}
			}
	

			private function rule_L31_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L31 = trim($ilr->learnerinformation->L31);
				if($L31!='000000')
				{
					return "L31_4: Additional support cost must be 000000 \n";
				}
			}
	
	
			private function rule_L32_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L32 = trim($ilr->learnerinformation->L32);
				if($L32!='00')
				{
					return "L32_2: Eligibility for disadvantage uplift must be 00 \n";
				}
			}
	
	
			private function rule_L33_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L33 = trim($ilr->learnerinformation->L33);
				if($L33!='0.0000')
				{
					return "L33_2: Disadvantage uplift factor must be 0.0000 \n";
				}
			}
	

			private function rule_L34a_6(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				$query = "select Learner_Support_Reason_Code from ilr_l34_learner_supp_reasons where ER_Ind='Y' and Learner_Support_Reason_Code='{$ilr->learnerinformation->L34a}'";
				$L34a = trim(DAO::getSingleValue($linklis, $query));

				if($L34a!=$ilr->learnerinformation->L34a)
				{
					return "L34a_6: Learner support reason 1 must exist on the table \n";
				}
			}
			
	
			private function rule_L34b_6(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				$query = "select Learner_Support_Reason_Code from ilr_l34_learner_supp_reasons where ER_Ind='Y' and Learner_Support_Reason_Code='{$ilr->learnerinformation->L34b}'";
				$L34b = trim(DAO::getSingleValue($linklis, $query));

				if($L34b!=$ilr->learnerinformation->L34b)
				{
					return "L34b_6: Learner support reason 2 must exist on the table \n";
				}
			}
				
			private function rule_L34c_6(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				$query = "select Learner_Support_Reason_Code from ilr_l34_learner_supp_reasons where ER_Ind='Y' and Learner_Support_Reason_Code='{$ilr->learnerinformation->L34c}'";
				$L34c = trim(DAO::getSingleValue($linklis, $query));

				if($L34c!=$ilr->learnerinformation->L34c)
				{
					return "L34c_6: Learner support reason 3 must exist on the table \n";
				}
			}
			
			private function rule_L34d_6(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				$query = "select Learner_Support_Reason_Code from ilr_l34_learner_supp_reasons where ER_Ind='Y' and Learner_Support_Reason_Code='{$ilr->learnerinformation->L34d}'";
				$L34d = trim(DAO::getSingleValue($linklis, $query));

				if($L34d!=$ilr->learnerinformation->L34d)
				{
					return "L34d_6: Learner support reason 4 must exist on the table \n";
				}
			}
			

	
			private function rule_L35_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$query = "select count(*) from ilr_l35_prior_attainment_level where Prior_Attainment_Level_Code='{$ilr->learnerinformation->L35}'";
				$L35 = trim(DAO::getSingleValue($linklis, $query));

				if($L35==0)
				{
					return "L35_1: Invalid prior attainment level code  \n";
				}
			}
	
	
			private function rule_L36_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$query = "select Learner_Status_Code from ilr_l36_learner_status where Learner_Status_Code='{$ilr->learnerinformation->L36}'";
				$L36 = trim(DAO::getSingleValue($linklis, $query));
			
				if($L36!=trim($ilr->learnerinformation->L36))
				{
					return "L36_1: Invalid learner status on last working day before learning  \n";
				}
			}
	
	
			private function rule_L37_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$query = "select Employment_Status_First_Code from ilr_l37_employ_status_firsts where Employment_Status_First_Code='{$ilr->learnerinformation->L37}'";
				$L37 = trim(DAO::getSingleValue($linklis, $query));

				if($L37!=trim($ilr->learnerinformation->L37))
				{
					return "L37_1: Invalid employment status on first day of learning \n";
				}
			}
	

	
			private function rule_L39_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$query = "select Destination_Code from ilr_l39_destinations where Destination_Code='{$ilr->learnerinformation->L39}'";
				$L39 = trim(DAO::getSingleValue($linklis, $query));

				if($L39!=trim($ilr->learnerinformation->L39))
				{
					return "L39_2: Destination must exist on the table \n";
				}
			}
	

	
			private function rule_L40a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$query = "select National_Learner_Event_Code from ilr_l40_nat_learner_events where National_Learner_Event_Code='{$ilr->learnerinformation->L40a}'";
				$L40a = trim(DAO::getSingleValue($linklis, $query));

				if($L40a!=$ilr->learnerinformation->L40a)
				{
					return "L40a_2: National learner monitoring 1 must exist on table \n";
				}
			}
	

			private function rule_L40b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$query = "select National_Learner_Event_Code from ilr_l40_nat_learner_events where National_Learner_Event_Code='{$ilr->learnerinformation->L40b}'";
				$L40b = trim(DAO::getSingleValue($linklis, $query));
			
				if($L40b!=$ilr->learnerinformation->L40b)
				{
					return "L40b_2: National learner monitoring 2 must exist on table \n";
				}
			}
	
	
			private function rule_L41a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L41a = trim($ilr->learnerinformation->L41a);
				if((int)$L41a < 0)
				{
					return "L41a_1: Local learner monitoring 1 must be greater than or 0 if entered \n";
				}
			}
	

			private function rule_L41b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L41b = trim($ilr->learnerinformation->L41b);
				if((int)$L41b < 0)
				{
					return "L41b_1: Local learner monitoring 2 must be greater than or 0 if entered \n";
				}
			}
	

			private function rule_L42a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L42a = trim($ilr->learnerinformation->L42a);
				if(strpos($L42a,'*')!= false || strpos($L42a,'?')!= false  || strpos($L42a,'%')!=false || strpos($L42a,'_')!=false)
				{
					return "L42a_1: Provider specified learner data 1 may be any printable characters except for *, ?, % or _ symbols \n";
				}
			}
	

			private function rule_L42b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L42b = trim($ilr->learnerinformation->L42b);
		
				if(strpos($L42b,'*')!= false || strpos($L42b,'?')!= false  || strpos($L42b,'%')!=false || strpos($L42b,'_')!=false)
				{
					return "L42b_1: Provider specified learner data 2 may be any printable characters except for *, ?, % or _ symbols \n";
				}
			}

			private function rule_L45_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L45 = trim($ilr->learnerinformation->L45);
		
				if($L45<0 || $L45>9999999999)
				{
					return "L45_1: Must be in the format 1000000000 - 9999999999 (if entered)";
				}
			}
			
			private function rule_L47_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$query = "select Current_Emp_Status_Code from ilr_l47_current_emp_status where Current_Emp_Status_Code='{$ilr->learnerinformation->L47}'";
				$L47 = trim(DAO::getSingleValue($linklis, $query));
		
				if($L47!=trim($ilr->learnerinformation->L47))
				{
					return "L47_1: Invalid current employment status \n";
				}
			}
	
			
			private function rule_L48_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L48s = trim($ilr->learnerinformation->L48);
				if($L48s!='00000000' && $L48s!='00/00/0000' && $L48s!='dd/mm/yyyy' && $L48s!='')
				{
					try
					{
						$L48 = new Date($ilr->learnerinformation->L48);
					}
					catch(Exception $e)
					{
						return "L48_1: Date employment status changed must be a valid date or 00000000 \n";
					}
				}
			}
	

			private function rule_L48_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L48 = trim($ilr->learnerinformation->L48);
				$start = new Date('01/01/2002');
				$end   = new Date('01/08/2012');
			
				if($L48!='00000000' && $L48!='00/00/0000' && $L48!='dd/mm/yyyy' && $L48!='')
				{
					try
					{
						$d = new Date($ilr->learnerinformation->L48);
						
						if($d->getDate()<$start->getDate() || $d->getDate()>$end->getDate())
						{
							return "L48_2: Date of employment status changed must be between 01/01/2002 and 01/08/2012\n";
						}
					}
					catch(Exception $e)
					{
						return "L48_2: Date employment status changed must be a valid date or 00000000 \n";
					}
				}
			}
	

			private function rule_L49a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				if(trim($ilr->learnerinformation->L49a)!='00')
				{
					return "L49a_2: Discretionary learner support type 1 must be 00 \n";
				}
			}
	

			private function rule_L49b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				if(trim($ilr->learnerinformation->L49b)!='00')
				{
					return "L49b_2: Discretionary learner support type 2 must be 00 \n";
				}
			}
	
	
			private function rule_L49c_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				if(trim($ilr->learnerinformation->L49c)!='00')
				{
					return "L49c_2: Discretionary learner support type 3 must be 00 \n";
				}
			}
	
	
			private function rule_L49d_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				if(trim($ilr->learnerinformation->L49d)!='00')
				{
					return "L49d_2: Discretionary learner support type 4 must be 00 \n";
				}
			}
			
			
			// Cross Field Validations 

			private function rule_A05_L05_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if((int)trim($ilr->aims[$sa]->A05) > (int)trim($ilr->learnerinformation->L05))
					{
						return "A05_L05_1[".$sa."]: The data set sequence must be less than or equal to the number of datasets \n";
					}
				}
			}


			private function rule_A09_A10_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A09 = $ilr->aims[$sa]->A09;
					$A10 = $ilr->aims[$sa]->A10;
					
					if( ($A09 == 'XESF0001' || $A09 == 'ZESF0001') && $A10 != '70')
					{
						return "A09_A10_1[".$sa."]: If the learning aim is XESF0001 or ZESF0001 the Funding model must be <70> ESF funded \n";
					}
				}
			}
			

/*			private function rule_AD06_A15_A16_A26_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						// Calculating AD06
						$AD06 = new Date($ilr->programmeaim->A27);
						$A26 = trim($ilr->aims[$sa]->A26);
						$A16 = trim($ilr->aims[$sa]->A16);
						
						
						$query = "select LEFT(EFFECTIVE_TO,10) from frameworks where FRAMEWORK_CODE = '$A26'";
						$Effective_To = DAO::getSingleValue($linklad, $query);
						
						if($Effective_To!='')	
							$effective_to_date = new Date($Effective_To);
						else
							$effective_to_date = new Date($ilr->aims[$sa]->A27);
						
						
						if( ($A26!='' || $A26!='000') && $Effective_To!='')
							if(($A16=='9' || $A16=='10' || $A16=='' || $A16=='0') && $AD06->getDate()>$effective_to_date->getDate())
							{
								return $Effective_To . "AD06_A15_A16_A26_LAD_1[".$sa."]: If framework code is entered, and if that framework has a non-null 'EFFECTIVE_TO' date, then learner must not start the programme after that date if the Programme Entry Route is 'First-Time Entrant' and programme start date is after 31/07/2004  \n";
							}
						}
						catch(Exception $e)
						{
							return  "AD06_A15_A16_A26_LAD_1[".$sa."]: If framework code is entered, and if that framework has a non-null 'EFFECTIVE_TO' date, then learner must not start the programme after that date if the Programme Entry Route is 'First-Time Entrant' and programme start date is after 31/07/2004 \n";
						}
				}
			}
*/	
						
/*			
			private function rule_A09_A10_A15_A27_AD05_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						// Calculating AD05
						$A04 = trim($ilr->aims[$sa]->A04);
						$A16 = trim($ilr->aims[$sa]->A16);
						$A20 = trim($ilr->aims[$sa]->A20);
						$A26 = trim($ilr->aims[$sa]->A26);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2010');
						$ed = new Date('31/07/2011');
						
						if($A04=='30' && ($A16=='09' || $A16=='9' || $A16=='10' || $A16=='00' || $A16=='') && ($A20=='9' || $A20=='0' || $A20=='') && $A27->getDate()>=$sd->getDate() && $A27->getDate()<=$ed->getDate())
							$AD05 = 'Y';
						else
							$AD05 = 'N';
												
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						
						$query = "select LAST_DATE_FOR_NEW_STARTS from validity_details where FUND_MODEL_COLLECTION_CODE = 'ER_APP' and LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);
						
						$data = new Date($data);
						
						if( ($A10=='45' || $A10=='46') && ($A15==2 || $A15==3 || $A15==10) && $AD05=='Y' && $A27->getDate()>$data->getDate())
						{
							return "A09_A10_A15_A27_AD05_LAD_2[".$sa."]: Must be a valid learning aim for a new start on the LAD where the learning aim aim is funded through the employer responsive funding model for first-time entrants on a framework \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_A27_AD05_LAD_2[".$sa."]: Must be a valid learning aim for a new start on the LAD where the learning aim aim is funded through the employer responsive funding model for first-time entrants on a framework \n";
					}
				}
			}
*/			

			private function rule_A09_A10_A15_LAD_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						
						$query = "select LAST_DATE_FOR_NEW_STARTS from validity_details where FUND_MODEL_COLLECTION_CODE = 'ER_TtG' and LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);
						
						if($A10=='45' && $A15=='99' && $data=='')
						{
							return "A09_A10_A15_LAD_3[".$sa."]: Must exist in validity details table in the LAD for employer responsive learning aim which is not part of an apprenticeship programme and is funded through the employer responsive model \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_LAD_3[".$sa."]: Must exist in validity details table in the LAD for employer responsive learning aim which is not part of an apprenticeship programme and is funded through the employer responsive model \n";
					}
				}
			}
			
			private function rule_A09_A10_A15_LAD_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						
						$query = "select count(*) from validity_details where FUND_MODEL_COLLECTION_CODE = 'ER_APP' and LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);
						
						if( ($A10=='45' || $A10=='46') && ($A15==2 || $A15==3 || $A15==10) && $data==0)
						{
							return "A09_A10_A15_LAD_4[".$sa."]: Must exist in validity details table in the LAD for employer responsive learning aim which is part of an apprenticeship programme and is funded through the employer responsive model \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_LAD_4[".$sa."]: Must exist in validity details table in the LAD for employer responsive learning aim which is part of an apprenticeship programme and is funded through the employer responsive model \n";
					}
				}
			}
			
/*
			private function rule_A09_A10_A15_LAD_5(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						
						$query = "select LAST_DATE_FOR_NEW_STARTS from validity_details where FUND_MODEL_COLLECTION_CODE = 'ER_TtG' and LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);

						$data = new Date($data);
						$date = new Date('31/07/2010');
						
						if($A10=='45' && $A15=='99' && $data->getDate()<=$date->getDate())
						{
							return "A09_A10_A15_LAD_5[".$sa."]: If the learning aim is not part of an apprenticeship programme and is funded through the employer responsive model the validity end date on the LAD for this aim must be after 31 July 2010 if entered. \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_LAD_5[".$sa."]: If the learning aim is not part of an apprenticeship programme and is funded through the employer responsive model the validity end date on the LAD for this aim must be after 31 July 2010 if entered. \n";
					}
				}
			}
			
			private function rule_A09_A10_A15_LAD_6(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						
						$query = "select LAST_DATE_FOR_NEW_STARTS from validity_details where FUND_MODEL_COLLECTION_CODE = 'ER_APP' and LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);

						$data = new Date($data);
						$date = new Date('31/07/2010');
						
						if( ($A10=='45' || $A10=='46') && ($A15==2 || $A15==3 || $A15==10) && $data->getDate()<=$date->getDate())
						{
							return "A09_A10_A15_LAD_6[".$sa."]: If the learning aim is part of an apprenticeship programme and is funded through the employer responsive model the validity end date on the LAD for this aim must be after 31 July 2010 if entered. \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_LAD_6[".$sa."]: If the learning aim is part of an apprenticeship programme and is funded through the employer responsive model the validity end date on the LAD for this aim must be after 31 July 2010 if entered. \n";
					}
				}
			}
*/			

/*			private function rule_A09_A10_AD05_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						// Calculating AD05
						$A04 = trim($ilr->aims[$sa]->A04);
						$A16 = trim($ilr->aims[$sa]->A16);
						$A20 = trim($ilr->aims[$sa]->A20);
						$A26 = trim($ilr->aims[$sa]->A26);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2010');
						$ed = new Date('31/07/2011');
						
						if($A04=='30' && ($A16=='09' || $A16=='10' || $A16=='00') && ($A20=='9' || $A20=='0') && $A27>=$sd && $A27<=$ed)
							$AD05 = 'Y';
						else
							$AD05 = 'N';
												
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						
						$query = "select NON_LSC_FUNDED_STATUS_CODE from all_annual_values where LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);

						if( ($A10=='70' || $A10=='80' || $A10=='81' || $A10=='82' || $A10=='99') && $AD05=='Y' && $data=='2')
						{
							return "A09_A10_AD05_LAD_1[".$sa."]: Must be valid learning aim in the LAD for this year for a new start for provision which is not funded through the learner responsive, ASL or employer responsive funding model, for first-time entrants on aims for which Programme Entry Route is not collected \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_AD05_LAD_1[".$sa."]: Must be valid learning aim in the LAD for this year for a new start for provision which is not funded through the learner responsive, ASL or employer responsive funding model, for first-time entrants on aims for which Programme Entry Route is not collected \n";
					}
				}
			}
*/
			private function rule_A09_A10_LAD_14(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A09 = trim($ilr->aims[$sa]->A09);
					$A10 = trim($ilr->aims[$sa]->A10);

					$query = "select learning_aim_type_code from learning_aim where LEARNING_AIM_REF='$A09'";
					$learning_aim_type_code = trim(DAO::getSingleValue($linklad, $query));
					
					if( $A10!='80' && $A10!='81' && $A10!='82' && $A10!='70' && $learning_aim_type_code=='1438')
					{
						return "A09_A10_LAD_14[".$sa."]: Learning aims categorised as soft outcomes in the LAD are only valid for ESF co-financed learning aims or aims that are Other LSC funded \n";
					}
				}
			}
			
			private function rule_A10_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$A10 = $ilr->aims[$sa]->A10;
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2010');

						if($A10=='80' && $A27->getDate()>=$sd->getDate()) 
						{
							return "A10_A27_2[".$sa."]: If the learning aim is funded through 'Other LSC funding', the Learning aim start date must not be on or after 1 August 2010. \n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A27_2[".$sa."]: If the learning aim is funded through 'Other LSC funding', the Learning aim start date must not be on or after 1 August 2010. \n";
					}
				}
			}
			
			private function rule_A10_A11a_A11b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = $ilr->aims[$sa]->A10;
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					
					if( ($A10=='21' || $A10=='22' || $A10=='80') && ($A11a != '105' && $A11a!='106' && $A11a!='107') && ($A11b!='105' && $A11b!='106' && $A11b!='107'))
					{
						return "A10_A11a_A11b_1[".$sa."]: If the learning aim is funded through the learner responsive model or is 'Other LSC funding',  then either occurence of the Sources of funding field must be completed with 105 (SFA), 106 (YPLA) or 107 (Local authority YPLA funds) \n";
					}
				}
			}
			
			private function rule_A10_A11a_A11b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = $ilr->aims[$sa]->A10;
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					
					if( ($A10=='99') && ($A11a == '105' || $A11a=='106' || $A11a=='107' || $A11b=='105' || $A11b=='106' || $A11b=='107'))
					{
						return "A10_A11a_A11b_2[".$sa."]: If the Funding model field is 'No SFA or YPLA funding' for the learning aim then the Sources of funding field must not be completed with an SFA/YPLA/Local Authority funding code \n";
					}
				}
			}
			
			private function rule_A10_A11a_A11b_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = $ilr->aims[$sa]->A10;
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					
					if(($A10=='10' || $A10=='45' || $A10=='46' || $A10=='70' || $A10=='81') && ($A11a != '105') && ($A11b!='105'))
					{
						return "A10_A11a_A11b_3[".$sa."]: If the Funding model field is 'ASL, employer responsive, ESF funded or funded through an 'Other SFA model', then either occurrence of the Sources of funding must be completed with 105 (SFA)  \n";
					}
				}
			}
			
			private function rule_A10_A11a_A11b_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = $ilr->aims[$sa]->A10;
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					
					if(($A10=='82') && ($A11a != '106' && $A11a != '107') && ($A11b!='106' && $A11b!='107'))
					{
						return "A10_A11a_A11b_4[".$sa."]: If the learning aim is funded through an 'Other YPLA funding model', then either occurrence of the Sources of funding must be 106 (YPLA) or 107 (Local authority (YPLA funds))  \n";
					}
				}
			}
			
			
			private function rule_A11a_A11b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					
					if($A11a!='999' && $A11a===$A11b)
					{
						return "A11a_A11b_1[".$sa."]: Source of Funding entries must be different unless value = 999  \n";
					}
				}
			}
			
			private function rule_A11a_A11b_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					
					if($A11a!='105' && ($A11b=='106' || $A11b=='107' || $A11b=='108'))
					{
						return "A11a_A11b_4[".$sa."]: Cannot have a combination of SFA funding or YPLA funding \n";
					}
				}
			}
			
			private function rule_A11a_A11b_5(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					
					if($A11b!='105' && ($A11a=='106' || $A11a=='107' || $A11a=='108'))
					{
						return "A11a_A11b_5[".$sa."]: Cannot have a combination of SFA funding or YPLA funding \n";
					}
				}
			}
			
			
			private function rule_A04_A14_A69_AD04_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
			
					$testalpha = new Date($ilr->aims[$sa]->A27);
					$testbeta = new Date($ilr->learnerinformation->L11);

					$years = $this->getAge($ilr->learnerinformation->L11,$ilr->aims[$sa]->A27);
					
					if($years>=16 && $years<19)
						$AD04 = 1;
					else
						if($years>=19 && $years<25)
							$AD04 = 2;
						else
							if($years>=25)
								$AD04 = 3;
							else
								return  "The learner is too young. Please check the date of birth again";

					$a04 = $ilr->aims[$sa]->A04;
					$a14 = $ilr->aims[$sa]->A14;
					$a69 = $ilr->aims[$sa]->A69;
					
					if($a04=='30' && ($a14=='01' || $a14=='1') && $AD04>1 && ($a69!='02' && $a69!='2'))
					{
						return $years . "A04_A14_A69_AD04_1[".$sa."]: If the learning aim is not a programme aim, the Reason for full funding/co-funding of the aim must not be '16-18 year old learner'  if the learner's age at the start of the aim is 19 or over unless the learner is entitled to 16-18 employer responsive funding. \n";
					}
				}
			}
			
			private function rule_A14_L11_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
					
						$L11 = new Date($ilr->learnerinformation->L11);
						$A14 = $ilr->aims[$sa]->A14;
						$d = new Date('01/08/1945');
						
						if($L11->getDate()<=$d->getDate() && $A14=='15')
						{
							return "A14_L11_1[".$sa."]: Reason for full funding/co-funding of learning aim must not be Job seekers allowance if the learner is 65 or over at the 1st of August of the training year. \n";
						}
					}
					catch(Exception $e)
					{
						return "A14_L11_1[".$sa."]: Reason for full funding/co-funding of learning aim must not be Job seekers allowance if the learner is 65 or over at the 1st of August of the training year. \n";
					}
				}
			}
			
			private function rule_A04_A15_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04);
					$A15 = trim($ilr->aims[$sa]->A15); 
					
					if($A04=='35' && $A15=='99')
					{
						return "A04_A15_1[".$sa."]: If the learning aim is a programme aim, the programme type must not be 'none of the above' \n";
					}
				}
			}
			
			private function rule_A04_A15_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					
					if($A46a!='112' && $A46a!='113' && $A46a!='114' && $A46a!='115' && $A46b!='112' && $A46b!='113' && $A46b!='114' && $A46b!='115' && $A15=='19' && $A04=='35')
					{
						return "A04_A15_A46a_A46b_1[".$sa."]: If the programme aim is part of a foundation learning programme, the National learning aim monitoring field must be completed with a code from the range 112 - 115.  \n";
					}
				}
			}

			private function rule_A10_A15_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('31/07/2004');
						if($A10=='46' && $A15=='99' && $A27->getDate()>$ed->getDate())
						{
							return "A10_A15_A27_1[".$sa."]: A main aim funded as part of a employer responsive funded apprenticeship programme must not have a programme type of 'none of the above' for starts after 31 July 2004 \n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A15_A27_1[".$sa."]: A main aim funded as part of a employer responsive funded apprenticeship programme must not have a programme type of 'none of the above' for starts after 31 July 2004 \n";
					}
				}
			}
			
			private function rule_A15_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A15 = trim($ilr->aims[$sa]->A15);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('01/08/2010');
						if( ($A15=='11' || $A15=='12' || $A15=='13' || $A15=='14') && $A27->getDate()>=$ed->getDate())
						{
							return "A15_A27_1[".$sa."]: If the programme aim or learning aim is Progression Pathway the learning aim start date must not be on or after 1 August 2010 \n";
						}
					}
					catch(Exception $e)
					{
						return "A15_A27_1[".$sa."]: If the programme aim or learning aim is Progression Pathway the learning aim start date must not be on or after 1 August 2010 \n";
					}
				}
			}
			
			private function rule_A10_A15_A16_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A16 = trim($ilr->aims[$sa]->A16);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('31/07/2004');
						if($A10=='45' && ($A15=='02' || $A15=='03' || $A15=='10') && $A16=='00' && $A27->getDate()>$ed->getDate())
						{
							return "A10_A15_A16_A27_2[".$sa."]: WBL Learners cannot be funded for an NVQ level 1, 2 or 3 programme if they start after 31 July 2006 \n";
						}
					}
					catch(Exception $e)
					{
							return "A10_A15_A16_A27_2[".$sa."]: WBL Learners cannot be funded for an NVQ level 1, 2 or 3 programme if they start after 31 July 2006 \n";
					}
				}
			}
			
			private function rule_A10_A16_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A16 = trim($ilr->aims[$sa]->A16);
					
					if($A10=='46' && ($A16=='00' || $A16==''))
					{
						return "A10_A16_1[".$sa."]: Programme entry route must be entered for employer responsive funded main aims. \n";
					}
				}
			}
			
			private function rule_A15_A16_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A16 = trim($ilr->aims[$sa]->A16);

					if(($A15=='02' || $A15=='2' || $A15=='3'  || $A15=='03' || $A15=='10') && $A16=='10')
					{

						return "A15_A16_3[".$sa."]: If the programme type is Apprenticeship, Advanced Apprenticeship or Higher Apprenticeship the programme entry route must not be first time entrant on other WBL programme \n";

					}
				}
			}
			
			private function rule_A15_A16_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A15 = trim($ilr->aims[$sa]->A15);
					$A16 = trim($ilr->aims[$sa]->A16);

					if(($A15!='02' && $A15!='2' && $A15!='03' && $A15!='3' && $A15!='10') && $A16=='09')
					{
						return "A15_A16_4[".$sa."]: If the programme type is not Apprenticeship, Advanced Apprenticeship or Higher Apprenticeship then the programme entry route must not be first time entrant on Apprenticeship or Advanced Apprenticship \n";
					}
				}
			}
			
			private function rule_A15_A16_5(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A15 = trim($ilr->aims[$sa]->A15);
					$A16 = trim($ilr->aims[$sa]->A16);

					if(($A15!='02' && $A15!='10') && ($A16=='03' || $A16=='15'))
					{
						return "A15_A16_5[".$sa."]: If programme entry route is 'progress to advanced apprenticeship from young apprenticeship' or 'progress to advanced apprenticeship from programme led pathway delivered in FE' the programme type must be advanced apprenticeship or higher apprenticeship \n";
					}
				}
			}
			
			private function rule_A15_A16_6(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A16 = trim($ilr->aims[$sa]->A16);

					if($A15!='03' && ($A16=='13' || $A16=='14'))
					{

						return "A15_A16_6[".$sa."]: If programme entry route is 'progress to apprenticeship from young apprenticeship' or 'progress to apprenticeship from programme led pathway delivered in FE' the programme type must be apprenticeship \n";

					}
				}
			}
			
			private function rule_A16_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A16 = trim($ilr->aims[$sa]->A16);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2007');
	
						if($A16=='04' && $A27->getDate()>=$check->getDate())
						{
							return "A16_A27_1[".$sa."]: If the start date is on or after 1 August 2007 then the programme entry route cannot be progress to NVQ level 3 from NVQ level 2 \n";
						}
					}
					catch(Exception $e)
					{
						return "A16_A27_1[".$sa."]: If the start date is on or after 1 August 2007 then the programme entry route cannot be progress to NVQ level 3 from NVQ level 2 \n";
					}
				}
			}
			
			private function rule_A16_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A16 = trim($ilr->aims[$sa]->A16);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2010');
	
						if( ($A16=='01' || $A16=='1') && $A27->getDate()>=$check->getDate())
						{
							return "A16_A27_2[".$sa."]: If the start date is on or after 1 August 2010 then the programme entry route cannot be 'Direct' \n";
						}
					}
					catch(Exception $e)
					{
						return "A16_A27_2[".$sa."]: If the start date is on or after 1 August 2010 then the programme entry route cannot be 'Direct' \n";
					}
				}
			}
			
			private function rule_A16_AD06_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A16 = $ilr->aims[$sa]->A16;
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2003');

						if(($A16=='01' || $A16=='1') && $A27->getDate()>=$d->getDate())
						{
							return "A16_AD06_1[".$sa."]: the programme entry type must be not be direct for programmes starting after 1 August 2003 \n";
						}
					}
					catch(Exception $e)
					{
						return "A16_AD06_1[".$sa."]: the programme entry type must be not be direct for programmes starting on or after 1 August 2003 \n";
					}
				}
			}
			
			private function rule_A04_A18_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A04 = $ilr->aims[$sa]->A04;
						$A18 = $ilr->aims[$sa]->A18;
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2010');
	
						if( ($A04=='30' && $A18=='') && $A27->getDate()>=$check->getDate())
						{
							return "A04_A18_A27_1[".$sa."]: Main delivery method must be entered for all learning aims except programme aims if the Learning aim start date is on or after 1 August 2010 \n";
						}
					}
					catch(Exception $e)
					{
						return "A04_A18_A27_1[".$sa."]: Main delivery method must be entered for all learning aims except programme aims if the Learning aim start date is on or after 1 August 2010 \n";
					}
				}
			}
			
			private function rule_A10_A23_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A23 = trim($ilr->aims[$sa]->A23);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2008');
	
						if( ($A10=='45' || $A10=='46') && $A23=='' && $A27->getDate()>=$check->getDate())
						{
							return "A10_A23_A27_1[".$sa."]: The delivery location postcode must be entered for all employer responsive funded learning aims that started on or after 1 August 2008 \n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A23_A27_1[".$sa."]: The delivery location postcode must be entered for all employer responsive funded learning aims that started on or after 1 August 2008 \n";
					}
				}
			}
			
			private function rule_A09_A10_A15_A26_A27_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				try
				{
					$check = '';
					for($sa=0;$sa<=$ilr->subaims;$sa++)
					{
	
						// Calculating AD05
						$A04 = trim($ilr->aims[$sa]->A04);
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$sd = new Date('01/08/2005');
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A26 = trim($ilr->aims[$sa]->A26);
						
						$query = "select count(*) from framework_aims where LEARNING_AIM_REF='$A09' and FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26'";
						$mr1 = DAO::getSingleValue($linklad, $query);
						
						$query = "select count(*) from framework_cmn_components where FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26' and COMMON_COMPONENT_CODE in (select COMMON_COMPONENT_CODE from learning_aim where LEARNING_AIM_REF='$A09')";
						$mr2 = DAO::getSingleValue($linklad, $query);

						if($A04=='30' && ($A10=='45' || $A10=='46') && $A27>=$sd && $A26!='000' && $mr1==0 && $mr2==0 && $A26!='')
						{
							return "A09_A10_A15_A26_A27_LAD_1[".$sa."]: For starts on or after 1 August 2005, if framework code is entered, it must match the framework for that learning aim in the LAD, for ER funded provision \n";
						}
					}
					return  $check;
				}
				catch(Exception $e)
				{
					return "A09_A10_A15_A26_A27_LAD_1[".$sa."]: For starts on or after 1 August 2005, if framework code is entered, it must match the framework for that learning aim in the LAD, for ER funded provision \n";
				}
			}
			
			private function rule_A15_A26_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);

					if($A15!='09' && $A15!='99' && ($A26=='000' || $A26==''))
					{
						return "A15_A26_3[".$sa."]: Framework code must be entered for all learning aims that are part of a programme, except for E2E \n";
					}
				}
			}
			
			private function rule_A15_A26_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);

					if( ($A15=='99' || $A15=='09') && $A26!='000' && $A26!='')
					{
						return "A15_A26_4[".$sa."]: Framework code must not be entered if the programme type is E2E or 'none of the above'. \n";
					}
				}
			}
			
/*			private function rule_A09_A10_A15_A26_A27_AD05_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					// Calculating AD05
					try
					{
						$A04 = trim($ilr->aims[$sa]->A04);
						$A16 = trim($ilr->aims[$sa]->A16);
						$A20 = trim($ilr->aims[$sa]->A20);
						$A26 = trim($ilr->aims[$sa]->A26);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2010');
						$ed = new Date('31/07/2011');
						
						if($A04=='30' && ($A16=='09' || $A16=='10' || $A16=='00') && ($A20=='9' || $A20=='0') && $A27>=$sd && $A27<=$ed)
							$AD05 = 'Y';
						else
							$AD05 = 'N';
						
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						
						$query = "select DATE_FORMAT(effective_to,'%Y/%m/%d') from FRAMEWORK_AIMS where FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26' and LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
						$effective_to = trim(DAO::getSingleValue($linklad, $query));
						if($effective_to!='')
							$effective_to_date = new Date($effective_to);
						
						if( ($A26!='000' || $A26!='') && $effective_to!='' && $AD05=='Y' && $A27>$effective_to_date)
						{
							return  "A09_A10_A15_A26_A27_AD05_LAD_1[".$sa."]: If framework code is entered, and then learner must not start the aim after the EFFECTIVE_TO date of this aim on this framework if this is a new start. \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_A26_A27_AD05_LAD_1[".$sa."]: If framework code is entered, and then learner must not start the aim after the EFFECTIVE_TO date of this aim on this framework if this is a new start. \n";
					}
				}
			}
*/			
			private function rule_A10_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date(date('d/m/Y'));

						if($A10=='46' && $A27->getDate()>$check->getDate())
						{

							return "A10_A27_1[".$sa."]: Start date must be on or before today's date for employer responsive funded main aims\n";

						}
					}
					catch(Exception $e)
					{
						return "A10_A27_1[".$sa."]: Start date must be on or before today's date for WBL employer responsive funded main aims \n";

					}
				}
			}
			
			private function rule_A10_A27_T08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$T08 = new Date(date('d/m/Y')); 
												
						if($A10=='46' && $A27->getDate()> $T08->getDate())
						{
							return "A10_A27_T08_1[".$sa."]: Start date must be on or before file creation date for employer responsive funded main aims \n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A27_T08_1[".$sa."]: Start date must be on or before file creation date for employer responsive funded main aims \n";
					}
				}
			}
			
			private function rule_A27_L11_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$L11 = new Date($ilr->learnerinformation->L11);

						if($L11->getDate() > $A27->getDate())
						{
							return "A27_L11_1[".$sa."]: Start date must be after date of birth \n";
						}
					}
					catch(Exception $e)
					{
						return "A27_L11_1[".$sa."]: Start date must be after date of birth \n";

					}
				}
			}
			
			private function rule_A27_A28_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A28 = new Date($ilr->aims[$sa]->A28);

						if($A28->getDate()<$A27->getDate())
						{
							return "A27_A28_1[".$sa."]: Planned end date must be on or after start date\n";
						}
					}
					catch(Exception $e)
					{
						return "A27_A28_1[".$sa."]: Planned end date must be on or after start date\n";
					}
				}
			}
			
			private function rule_A27_A28_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A28 = new Date($ilr->aims[$sa]->A28);
						$A27->addYears(10);

						if($A28->getDate()>$A27->getDate())
						{

							return "A27_A28_2[".$sa."]: Planned end date must be less than 10 years after the start date \n";

						}
					}
					catch(Exception $e)
					{
						return "A27_A28_2[".$sa."]: Planned end date must be less than 10 years after the start date \n";

					}
				}
			}
			
			private function rule_A27_A31_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
				

					$A31s = trim($ilr->aims[$sa]->A31);

					if($A31s!='00000000' && $A31s!='dd/mm/yyyy')
					{
						try
						{
							$A27 = new Date($ilr->aims[$sa]->A27);
							$A31d = new Date($ilr->aims[$sa]->A31);
							if($A31d->getDate() < $A27->getDate())
							{

								return "A27_A31_1[".$sa."]: If present, the learning actual end date to be on or after start date \n";

							}
						}
						catch(Exception $e)
						{
								return "A27_A31_1[".$sa."]: If present, the learning actual end date to be on or after start date \n";
	
						}
					}
				}
			}
			
			private function rule_A31_T08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A31 = trim($ilr->aims[$sa]->A31);
			
					if($A31!='00000000' && $A31!='dd/mm/yyyy' && $A31!='00/00/0000')
					{
						
						try
						{
							$A31d = new Date(trim($ilr->aims[$sa]->A31));
							$T08 = new Date(date('d/m/Y'));
							if($A31d->getDate() > $T08->getDate())
							{
								return "A31_T08_1[".$sa."]: The achievement date if entered must be on or after the actual end date \n";
							}
						}
						catch(Exception $e)
						{
							return trim($ilr->aims[$sa]->A31);
						}
					}
				}
			}
			
			private function rule_A31_A34_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A31 = trim($ilr->aims[$sa]->A31);
					$A34 = trim($ilr->aims[$sa]->A34);

					if($A34=='1' && $A31!='00000000' && $A31!='dd/mm/yyyy')
					{
						return "A31_A34_1[".$sa."]: Completion status must not be the learner is continuing or intending to continue the learning activities leading to the learning aim’ if the Learning actual end date is completed.\n";
					}
				}
			}

			private function rule_A31_A34_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A31 = trim($ilr->aims[$sa]->A31);
					$A34 = trim($ilr->aims[$sa]->A34);

					if($A34!='1' && ($A31=='00000000' || $A31=='dd/mm/yyyy'))
					{
						return "A31_A34_2[".$sa."]: Completion status must be the learner is continuing or intending to continue the learning activities leading to the learning aim’ if the Learning actual end date is not completed..\n";
					}
				}
			}
			
			private function rule_A34_A35_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A34 = trim($ilr->aims[$sa]->A34);
					$A35 = trim($ilr->aims[$sa]->A35);

					if($A35!='9' && $A34=='1')
					{
						return "A34_A35_2[".$sa."]: If the Completion Status is ‘the learner is continuing or intending to continue the learning activities leading to the learning aim’, the Learning outcome must be set to is ‘study continuing’. \n";
					}
				}
			}
			
			private function rule_A34_A35_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A34 = trim($ilr->aims[$sa]->A34);
					$A35 = trim($ilr->aims[$sa]->A35);

					if($A34=='3' && ($A35=='1' || $A35=='6' || $A35=='7'))
					{
						return "A34_A35_3[".$sa."]: If the Completion Status is 'the learner has withdrawn from the learning activities leading to the learning aim', the Learning outcome must not be 'Achieved'. \n";
					}
				}
			}
			
			private function rule_A09_A31_A35_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A09 = $ilr->aims[$sa]->A09;
					if($ilr->aims[$sa]->A31!='' && $ilr->aims[$sa]->A31!='00000000')
					{
						$A31 = new Date($ilr->aims[$sa]->A31);
						$A35 = trim($ilr->aims[$sa]->A35);
						$d = new Date('01/08/2010');
						
						$query = "select LEARNING_AIM_TYPE_CODE from learning_aim where LEARNING_AIM_REF='$A09'";
						$latc = DAO::getSingleValue($linklad, $query);
						
						if( ($latc=='0001' || $latc=='1') && $A31->getDate()>=$d->getDate() && $A35=='1')
						{
							return "A09_A31_A35_LAD_1[".$sa."]: If the learning aim is an AS level then the Learning outcome must not be 'Achieved', if the actual end date is on or after 1 August 2010. \n";
						}
					}
				}
			}
			
			private function rule_A09_A35_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A35 = trim($ilr->aims[$sa]->A35);
					$A09 = trim($ilr->aims[$sa]->A09);
					
					$query = "select LEARNING_AIM_TYPE_CODE from learning_aim where LEARNING_AIM_REF='$A09'";
					$latc = DAO::getSingleValue($linklad, $query);
					
					if( ($latc=='0001' || $latc=='1') && ($A35=='6' || $A35=='7'))
					{
						return "A09_A35_LAD_1[".$sa."]: If the Learning outcome is 'Achieved but uncashed' or 'Achieved and cashed' the learning aim must be an AS level. \n";
					}
				}
			}
			
			private function rule_A34_A35_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A34 = trim($ilr->aims[$sa]->A34);
					$A35 = trim($ilr->aims[$sa]->A35);

					if($A34!='1' && $A35=='9')
					{
						return "A34_A35_1[".$sa."]: If the Learning outcome is ‘study continuing’, the Completion Status must be set to ‘the learner is continuing or intending to continue the learning activities leading to the learning aim’, \n";
					}
				}
			}
			
			private function rule_A35_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A40 = trim($ilr->aims[$sa]->A40);
					$A35 = trim($ilr->aims[$sa]->A35);

					if($A40!='00000000' && $A35!='1')
					{
						return "A35_A40_1[".$sa."]: If the Achievement Date is entered then the Learning Outcome must be 'achieved'  \n";
					}
				}
			}
			
			private function rule_A09_A36_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$query = "select BASIC_SKILLS_DIAG_TEST from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$data = trim(DAO::getSingleValue($linklad, $query));
					
					$A36 = trim($ilr->aims[$sa]->A36);

					if($data=='Y' && ($A36=='LN' || $A36=='L2' || $A36=='L1' || $A36=='E3' || $A36=='E2' || $A36=='E1'))
					{
						return "A09_A36_LAD_1[".$sa."]: If learning aim is not 'basic skills diagnostic assessment', the learning outcome grade cannot be one of the grades reserved for such aims \n";
					}
				}
			}
			
			private function rule_A35_A36_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A35 = trim($ilr->aims[$sa]->A35);
					$A36 = trim($ilr->aims[$sa]->A36);

					if( ($A35=='4' || $A35=='5' || $A35=='9') && $A36!='')
					{
						return "A35_A36_3[".$sa."]: Learning outcome grade must not be entered if learning outcome is ‘exam taken but result not known’, ‘learning activities are complete but the exam has not yet been taken and there is an intention to take the exam’, or ‘study continuing’ \n";
					}
				}
			}
			
			private function rule_A27_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A40s = trim($ilr->aims[$sa]->A40);

						if($A40s!='00000000')
						{
							$A40d = new Date($ilr->aims[$sa]->A40);
							if($A40d->getDate() < $A27->getDate())
							{

								return  "A27_A40_1[".$sa."]: Achievement date must be greater than or equal to the start date if entered \n";

							}
						}
					}
					catch(Exception $e)
					{
							return "ExcA27_A40_1[".$sa."]: Achievement date must be greater than or equal to the start date if entered \n";
					}
				}
			}
			
			private function rule_A31_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A31 = trim($ilr->aims[$sa]->A31);
					$A40 = trim($ilr->aims[$sa]->A40);

					if($A40!='00000000' && $A40!='dd/mm/yyyy' && ($A31=='00000000' || $A31=='dd/mm/yyyy'))
					{
						return "A31_A40_1[".$sa."]: If the achievement date is entered the actual end date must be entered \n";
					}
				}
			}
			
			private function rule_A31_A40_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A31 = trim($ilr->aims[$sa]->A31);
						$A40 = trim($ilr->aims[$sa]->A40);
				
						if($A31!='00000000' && $A31!='dd/mm/yyyy' && $A40!='00000000' && $A40!='dd/mm/yyyy')
						{
							$A31d = new Date(trim($ilr->aims[$sa]->A31));
							$A40d = new Date(trim($ilr->aims[$sa]->A40));
							if($A40!='00000000' && $A40!='dd/mm/yyyy' && $A40d->getDate()<$A31d->getDate())
							{
								return "A31_A40_2[".$sa."]: The achievement date if entered must be on or after the actual end date \n";
							}
						}
					}
					catch(Exception $e)
					{
						return "A31_A40_2[".$sa."]: Invalid achievement date\n";
					}
				}
			}
			
			private function rule_A35_A40_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A40 = trim($ilr->aims[$sa]->A40);
					$A35 = trim($ilr->aims[$sa]->A35);

					if($A35=='1' && ($A40=='00000000' || $A40==''))
					{
						return "A35_A40_2[".$sa."]: If the Learning Outcome is 'achieved' then the Achievement Date must be entered \n";
					}
				}
			}
			
			private function rule_A40_T08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A40 = trim($ilr->aims[$sa]->A40);
			
					if($A40!='00000000' && $A40!='dd/mm/yyyy' && $A40!='00/00/0000')
					{
						try
						{
							$A40d = new Date(trim($ilr->aims[$sa]->A40));
							$T08 = new Date(date('d/m/Y'));
							if($A40d->getDate() > $T08->getDate())
							{
								return "A40_T08_1[".$sa."]: Achievement date must be on or before the file creation date \n";
							}
						}
						catch(Exception $e)
						{
							return trim($ilr->aims[$sa]->A40);
						}
					}
				}
			}
			
			private function rule_A10_A15_A44_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A44 = trim($ilr->aims[$sa]->A44);

					if($A10=='45' && $A15=='99' && ($A44=='000000000' || $A44=='999999999'))
					{
						return "A10_A15_A44_1[".$sa."]: Where the learning aim is funded through the employer responsive funding model and is not part of an apprenticeship programme, then the employer identifier must not be 000000000 or 999999999. \n";
					}
				}
			}
			
			private function rule_A10_A44_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A44 = trim($ilr->aims[$sa]->A44);

					if($A10=='46' && $A44=='888888880')
					{
						return "A10_A44_1[".$sa."]: If the learning aim is an employer responsive main aim of an apprenticeship programme the employer identifier of 888888880 must not be used. \n";
					}
				}
			}
			
			private function rule_A10_A44_L47_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A44 = trim($ilr->aims[$sa]->A44);
					$L47 = trim($ilr->learnerinformation->L47);	
					
					if($A10=='46' && $A44=='000000000' && ($L47=='1' || $L47=='01'))
					{
						return "A10_A44_L47_1[".$sa."]: If the learning aim is an employer responsive main aim of an apprenticeship programme and the learner's Current employment status is employed, then the employer identifier must not be zero.  \n";
					}
				}
			}
			
			private function rule_A44_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A44 = trim($ilr->aims[$sa]->A44);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					
					if($A44 == '888888880' && $A46a != '083' && $A46b != '083' )
					{
						return "A44_A46a_A46b_1[".$sa."]: The Employer identfier of 888888880 must only be used for Employability Skills Programme learning aims \n";
					}
				}
			}

			private function rule_AD03_A44_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					// Calculate AD03
					$A44 = trim($ilr->aims[$sa]->A44);
					$flag1 = true;
					for($a=0;$a<=8; $a++)
						if(!($this->isDigit(substr($A44,$a,1))))
							$flag1 = false;
					
					$flag2 = true;
					if(strlen($A44)>9)
						for($a=9;$a<=29; $a++)
							if((substr($A44,$a,1)!=' ') && (substr($A44,$a,1)!=''))
								$flag2 = false;
								
					if($flag1 && $flag2)
					{
						$res = 11-((9*(int)substr($A44,0,1)+8*(int)substr($A44,1,1)+7*(int)substr($A44,2,1)+6*(int)substr($A44,3,1)+5*(int)substr($A44,4,1)+4*(int)substr($A44,5,1) + 3*(int)substr($A44,6,1) + 2*(int)substr($A44,7,1)) % 11);
						if($res==11)
							$AD03='0';
						else
							if($res==10)
								$AD03='X';
							else
								$AD03=$res;
					}
					else
						$AD03 = 'T';
						
					if($AD03!='T' && $AD03!=substr($A44,8,1) && $A44!='999999999' && $A44!='000000000')
					{
						return "AD03_A44_1[".$sa."]: If an EDRS reference number is entered in the Employer identifier field it must be a valid EDRS number \n";
					}
				}
			}
			
			private function rule_A27_A46a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						
						$query = "select Valid_To from ilr_a46_nat_learner_aims where National_Learner_Aim_Code='$ilr->aims[$sa]->A46a' and (LR_Ind='Y' or ER_Ind='Y')";
						$d = DAO::getSingleValue($linklis, $query);
						
						if($d!='')
						{
							$dd = new Date($d);
							if($A27->getDate()>$dd->getDate() && $ilr->aims[$sa]->A46a!='999')
							{
								return "A27_A46a_1[".$sa."]: The learning aim start date must not be after the 'valid to date' in the A46 code table  \n";
							}
						}
					}
					catch(Exception $e)
					{
						return "A27_A46a_1[".$sa."]: The learning aim start date must not be after the 'valid to date' in the A46 code table  \n";

					}
				}
			}
			
			private function rule_A27_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
	
						$query = "select Valid_To from ilr_a46_nat_learner_aims where National_Learner_Aim_Code='{$ilr->aims[$sa]->A46b}' and (LR_Ind='Y' or ER_Ind='Y')";
						$d = DAO::getSingleValue($linklis, $query);
						
						if($d!='')
						{
							$d = new Date($d);
							if($A27->getDate()>$d->getDate() && $ilr->aims[$sa]->A46b!='999')
							{
								return "A27_A46b_1[".$sa."]: The learning aim start date must not be after the 'valid to date' in the A46 code table  \n";
							}
						}
					}
					catch(Exception $e)
					{
						return "A27_A46b_1[".$sa."]: The learning aim start date must not be after the 'valid to date' in the A46 code table  \n";
					}
				}
			}
			
			private function rule_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
				
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					
					if($A46a == $A46b && ($A46a!='999' && $A46b!='999'))
					{
						return "A46a_A46b_1[".$sa."]: The National Aim Monitoring Field entries must be different unless both ar 999 \n";
					}
				}
			}
			
			private function rule_A46a_A46b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
				
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					
					if($A46a=='999' && $A46b!='999')
					{
						return "A46a_A46b_2[".$sa."]: A46b should not be used if A46a is set to 999 \n";
					}
				}
			}
			
			private function rule_A10_A49_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$A49 = trim($ilr->aims[$sa]->A49);
					
					if($A10!='70' && ($A49=='SP014' || $A49=='SP015' || $A49=='SP016' || $A49=='SP017' || $A49=='SP018'))
					{
						return "A10_A49_1[".$sa."]: If the learning aim is part of the Response to Redundancy Programme the Funding model must be ESF.  \n";
					}
				}
			}
			
			private function rule_A10_A49_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$A49 = trim($ilr->aims[$sa]->A49);

					$a = array('SP019','SP020','SP021','SP022','SP023','SP024','SP025','SP026','SP027','SP028','SP029','SP030','SP031','SP032','SP033','SP034','SP035','SP036','SP037','SP038','SP039','SP040','SP041','SP042');
					
					if( ($A10!='80' && $A10!='81') && (in_array($A49,$a)))
					{
						return "A10_A49_2[".$sa."]: If the learning aim is part of the Programmes for the Unemployed, the Funding model must be Other funding.   \n";
					}
				}
			}
			
			private function rule_A10_A31_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A31 = trim($ilr->aims[$sa]->A31);
					$A50 = trim($ilr->aims[$sa]->A50);
					
					if(($A10=='45' || $A10=='46') && ($A31=='00000000' || $A31=='') && $A50!='96')
					{

						return "A10_A31_A50_1[".$sa."]: If there is no learning actual end date then Reason Learning Ended must be continuing for employer responsive funded aims \n";

					}
				}
			}

			private function rule_A31_A50_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A31 = trim($ilr->aims[$sa]->A31);
					$A50 = trim($ilr->aims[$sa]->A50);

					if($A50=='96' && $A31!='00000000' && $A31!='dd/mm/yyyy')
					{
						return "A31_A50_2[".$sa."]: If there is a learning actual end date then the learner must not be continuing \n";
					}
				}
			}
			
			private function rule_A46a_A46b_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
				
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					$A50 = trim($ilr->aims[$sa]->A50);
					
					if( ($A50=='27' || $A50=='28') && ($A46a!='034' && $A46b!='034') )
					{
						return "A46a_A46b_A50_1[".$sa."]: Codes 27 and 28 in reason learning ended field should not be used unless the learner is an Olass learner in custody \n";
					}
				}
			}
			
			private function rule_A04_A10_A14_A15_A46a_A46b_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
				
					$A04 = trim($ilr->aims[$sa]->A04);
					$A10 = trim($ilr->aims[$sa]->A10);
					$A14 = trim($ilr->aims[$sa]->A14);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					$A53 = trim($ilr->aims[$sa]->A53);
					
					if($A04=='30' && $A10=='45' && ($A14=='09' && $A14=='9') && $A15=='99' && $A46a!='083' && $A46b!='083' && $A53!='97')
					{
						return "A04_A10_A14_A15_A46a_A46b_A53_1[".$sa."]: If the learning aim is being delivered within a Train to Gain programme and the Reason for full funding/co-funding is Skills for Life, then the Additional learning needs field must be completed with 'assessed as having no additional learning or social needs' \n";
					}
				}
			}
			
			private function rule_A04_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A04) != '35' && trim($ilr->aims[$sa]->A53) == '')
					{
						return "A04_A53_1[".$sa."]: If the learning aim is not a programme aim, the additional learning aim needs field, must be completed \n";
					}
				}
			}
			
			private function rule_A04_A09_A27_A59_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$query = "select FFA_TYPE_CODE from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$ffa_type_code = trim(DAO::getSingleValue($linklad, $query));
					$A04 = trim($ilr->aims[$sa]->A04);
					$A59 = trim($ilr->aims[$sa]->A59);
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2008');
						
						if($A04=='30' && ($A59=='' || (int)$A59==0)&& $ffa_type_code!='X' && $ffa_type_code!='' && $A27->getDate()>=$d->getDate())
						{
							return "A04_A09_A59_LAD_1[".$sa."]: If the learning aim is a QCF aim in the LAD, the start date is on or after 1 August 2008, the planned Credit value must be entered \n";
						}
					}
					catch(Exception $e)
					{
						return "A04_A09_A59_LAD_1[".$sa."]: If the learning aim is a QCF aim in the LAD, the start date is on or after 1 August 2008, the planned Credit value must be entered \n";
					}
				}
			}
			
			private function rule_A04_A09_A27_A35_A60_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$query = "select FFA_TYPE_CODE from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$ffa_type_code = trim(DAO::getSingleValue($linklad, $query));
					$A04 = trim($ilr->aims[$sa]->A04);
					$A35 = trim($ilr->aims[$sa]->A35);
					$A60 = trim($ilr->aims[$sa]->A60);

					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2008');
						
						if($A04=='30' && ($A35=='1' || $A35=='2' || $A35=='6' || $A35=='7') && $A60=='' && $ffa_type_code!='X' && $ffa_type_code!='' && $A27>=$d)
						{
							return "A04_A09_A27_A35_A60_LAD_1[".$sa."]: If the learning aim is a QCF aim in the LAD, the start date is on or after 1 August 2008 and the learning outcome is 'Achieved' or 'Partial achievement', the Credits achieved must be entered \n";
						}
					}
					catch(Exception $e)
					{
							return "A04_A09_A27_A35_A60_LAD_1[".$sa."]: If the learning aim is a QCF aim in the LAD, the start date is on or after 1 August 2008 and the learning outcome is 'Achieved' or 'Partial achievement', the Credits achieved must be entered \n";
					}
				}
			}
			
			private function rule_A10_A61_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10); 
					$A61 = trim($ilr->aims[$sa]->A61); 
					
					if($A10 == '70' && $A61 == '')
					{
						return "A10_A61_1[".$sa."]: If the learning aim is ESF or Response to Redundancy funded the Project dossier number must be entered. \n";
					}
				}
			}
			
			private function rule_A10_A62_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10); 
					$A62 = trim($ilr->aims[$sa]->A62); 
					
					if($A10 == '70' && $A62 == '')
					{
						return "A10_A62_1[".$sa."]: If the learning aim is ESF or Response to Redundancy funded the Project dossier number must be entered. \n";
					}
				}
			}
			
			private function rule_A49_A61_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A61 = trim($ilr->aims[$sa]->A61);
					$A49 = trim($ilr->aims[$sa]->A49);

					$a = array('SP019','SP020','SP021','SP022','SP023','SP024','SP025','SP026','SP027','SP028','SP029','SP030','SP031','SP032','SP033','SP034','SP035','SP036','SP037','SP038','SP039','SP040','SP041','SP042');
					
					if( ($A61=='') && (in_array($A49,$a)))
					{
						return "A49_A61_1[".$sa."]: If the learning aim is part of the Programmes for the Unemployed, the ESF local project number must be entered.  \n";
					}
				}
			}
			
			private function rule_A49_A62_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A62 = trim($ilr->aims[$sa]->A62);
					$A49 = trim($ilr->aims[$sa]->A49);

					$a = array('SP019','SP020','SP021','SP022','SP023','SP024','SP025','SP026','SP027','SP028','SP029','SP030','SP031','SP032','SP033','SP034','SP035','SP036','SP037','SP038','SP039','SP040','SP041','SP042');
					
					if( ($A62=='') && (in_array($A49,$a)))
					{
						return "A49_A62_1[".$sa."]: If the learning aim is part of the Programmes for the Unemployed, the ESF local project number must be entered.  \n";
					}
				}
			}
			
			private function rule_A04_A63_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04); 
					$A63 = trim($ilr->aims[$sa]->A63); 
					
					if($A04 != '35' && $A63 == '')
					{
						return "A04_A63_1[".$sa."]: If the learning aim is not a programme aim, the National Skills Academy field must be completed. \n";
					}
				}
			}
			
			private function rule_A04_A27_A66_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04);
					$A66 = trim($ilr->aims[$sa]->A66);

					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2009');
						
						if($A04=='30' && $A66=='' && $A27->getDate()>=$d->getDate())
						{
							return "A04_A27_A66_1[".$sa."]: Employment status on day before starting learning aim must be completed for all learning aims that started on or after 1 August 2009. \n";
						}
					}
					catch(Exception $e)
					{
							return "A04_A27_A66_1[".$sa."]: Employment status on day before starting learning aim must be completed for all learning aims that started on or after 1 August 2009. \n";
					}
				}
			}
			
			private function rule_A10_A66_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10); 
					$A66 = trim($ilr->aims[$sa]->A66); 
					
					if($A10 == '70' && ($A66 == '' || $A66=='98'))
					{
						return "A10_A66_1[".$sa."]: If the learning aim is ESF funded the Employment status on day before starting learning aim must be entered and not be 'not known/not provided' \n";
					}
				}
			}
			
			private function rule_A49_A66_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A66 = trim($ilr->aims[$sa]->A66);
					$A49 = trim($ilr->aims[$sa]->A49);

					$a = array('SP014','SP015','SP016','SP017','SP018','SP019','SP020','SP021','SP022','SP023','SP024','SP025','SP026','SP027','SP028','SP029','SP030','SP031','SP032','SP033','SP034','SP035','SP036','SP037','SP038','SP039','SP040','SP041','SP042');
					
					if( ($A66!='04' && $A66!='4') && (in_array($A49,$a)))
					{
						return "A49_A66_1[".$sa."]: If the learning aim is part of the Programmes for the Unemployed, the Employment status on day before starting learning aim must be unemployed.  \n";
					}
				}
			}
			
			private function rule_A10_A67_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10); 
					$A67 = trim($ilr->aims[$sa]->A67); 
					
					if($A10 == '70' && ($A67 == '' || $A67=='98'))
					{
						return "A10_A67_1[".$sa."]: If the learning aim is ESF or Response to Redundancy funded the Length of unemployment before starting ESF project must be entered and not be 'not known/not provided' \n";
					}
				}
			}
			
			private function rule_A49_A67_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A67 = trim($ilr->aims[$sa]->A67);
					$A49 = trim($ilr->aims[$sa]->A49);

					$a = array('SP019','SP020','SP021','SP022','SP023','SP024','SP025','SP026','SP027','SP028','SP029','SP030','SP031','SP032','SP033','SP034','SP035','SP036','SP037','SP038','SP039','SP040','SP041','SP042');
					
					if( ($A67=='' || $A67=='98') && (in_array($A49,$a)))
					{
						return "A49_A67_1[".$sa."]: If the learning aim is part of the Programmes for the Unemployed, the Length of unemployment before starting ESF project must be entered and not be 'not known/not provided' \n";
					}
				}
			}
			
			private function rule_A66_A67_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A66 = trim($ilr->aims[$sa]->A66); 
					$A67 = trim($ilr->aims[$sa]->A67); 
					
					if( ($A66=='04' || $A66=='07') && $A67 == '99')
					{
						return "A66_A67_2[".$sa."]:  Length of Unemployment must not be Not Unemployed if Employment Status at start is Unemployed or 14-19 NEET.\n";
					}
				}
			}
			
			private function rule_A66_A67_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A66 = trim($ilr->aims[$sa]->A66); 
					$A67 = trim($ilr->aims[$sa]->A67); 
					
					if( ($A66 == '1' || $A66=='2' || $A66=='3' || $A66=='6') && $A67 != '99')
					{
						return "A66_A67_3[".$sa."]: If employment status before starting is employed, full time education, self employed,  economically inactive, then the Length of unemployment before starting ESF project must be not unemployed.  \n";
					}
				}
			}

			private function rule_A66_A67_4(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A66 = trim($ilr->aims[$sa]->A66); 
					$A67 = trim($ilr->aims[$sa]->A67); 
					
					if($A66 == '98' && $A67 != '98')
					{
						return "A66_A67_4[".$sa."]: If employment status before starting is 'not known/not provided', the Length of unemployment before starting ESF project must be 'not known/not provided' \n";
					}
				}
			}
			
			private function rule_A04_A69_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04); 
					$A69 = trim($ilr->aims[$sa]->A69); 
					
					if($A04 == '30' && $A69 == '')
					{
						return "A04_A69_1[".$sa."]: If the learning aim is not a programme aim, the Eligibility for enhanced ER funding must be completed. \n";
					}
				}
			}
			
			private function rule_A10_A15_A69_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10); 
					$A15 = trim($ilr->aims[$sa]->A15); 
					$A69 = trim($ilr->aims[$sa]->A69); 
					
					if($A10 == '45' && $A15 == '99' && $A69!='99')
					{
						return "A10_A15_A69_1[".$sa."]: If the learning aim is not part of an apprenticeship programme and is funded through the employer responsive model, the 'Eligibility for enhanced ER funding' must be 'not eligible'. \n";
					}
				}
			}
			
			private function rule_A14_A69_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A14 = trim($ilr->aims[$sa]->A14); 
					$A69 = trim($ilr->aims[$sa]->A69); 
					
					if($A14 != '28' && ($A69=='01' || $A69=='1'))
					{
						return "A14_A69_1[".$sa."]: If the learning aim is eligible for enhanced funding for 19+ apprenticeships the Reason for full funding/co-funding of learning aim must be 'Fully funded employer responsive provision'. \n";
					}
				}
			}
			
			private function rule_A14_A69_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A14 = trim($ilr->aims[$sa]->A14); 
					$A69 = trim($ilr->aims[$sa]->A69); 
					
					if($A14 != '1' && $A14!='01' && ($A69=='02' || $A69=='2'))
					{
						return "A14_A69_2[".$sa."]: If the learner is aged 19 or over and is entitled to 16-18 employer responsive funding then the Reason for full funding/co-funding of learning aim must be '16-18 year old learner'. \n";
					}
				}
			}
			
			private function rule_A14_A69_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A14 = trim($ilr->aims[$sa]->A14); 
					$A69 = trim($ilr->aims[$sa]->A69); 
					
					if($A14 != '32' && ($A69=='03' || $A69=='3'))
					{
						return "A14_A69_3[".$sa."]: If the learner is aged 25 or over and is entitled to 19-24 employer responsive funding then the Reason for full funding/co-funding of learning aim must be 'co-funded employer responsive provision'. \n";
					}
				}
			}
			
			private function rule_A10_A15_A70_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A70 = trim($ilr->aims[$sa]->A70);
					
					if($A10=='99' && ($A15=='2' || $A15=='3' || $A15=='10') && $A70!='')
					{
						return "A10_A15_A70_1[".$sa."]: If the Funding model is 'no SFA or YPLA funding for this learning aim' and is not part of an apprenticeship programme, this field must not be completed. \n";
					}
				}
			}
			
			private function rule_A10_A70_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$A70 = trim($ilr->aims[$sa]->A70);
					
					if($A10!='99' && $A70=='')
					{
						return "A10_A70_2[".$sa."]: If the funding model is not 'No SFA or YPLA funding for this learning aim', then the Contracting organisation code must be completed. \n";
					}
				}
			}
			
			private function rule_A11a_A11b_A70_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					$A70 = $ilr->aims[$sa]->A70;
					
					if( ($A11a=='105' || $A11b=='105') && substr($A70,0,2)!='SF')
					{
						return "A11a_A11b_A70_1[".$sa."]: If the learning aim is funded directly by the SFA then the Contracting organisation code field must be a valid SFA region code \n";
					}
				}
			}
			
			private function rule_A11a_A11b_A70_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					$A70 = $ilr->aims[$sa]->A70;
					
					if( ($A11a=='107' || $A11b=='107') && substr($A70,0,2)!='LA')
					{
						return "A11a_A11b_A70_2[".$sa."]: If the learning aim is funded directly by a local authority with YPLA funds then the Contracting organisation code must be a local authority code \n";
					}
				}
			}
			
			private function rule_A11a_A11b_A70_3(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A11a = $ilr->aims[$sa]->A11a;
					$A11b = $ilr->aims[$sa]->A11b;
					$A70 = $ilr->aims[$sa]->A70;
					
					if( ($A11a=='106' || $A11b=='106') && substr($A70,0,2)!='YP')
					{
						return "A11a_A11b_A70_3[".$sa."]: If the learning aim is funded directly by the YPLA then the Contracting organisation code must be a YPLA code \n";
					}
				}
			}

			private function rule_L05_L08_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$L05 = trim($ilr->learnerinformation->L05);
				$L08 = trim($ilr->learnerinformation->L08);
		
				if( ($L08=='N' || $L08==' ') && (int)$L05==0)
				{
					return "L05_L08_2: Must be at least one learning aim if there is no delete flag \n";
				}
			}

			private function rule_A10_A27_L11_5(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$L11 = $ilr->learnerinformation->L11;
						$d = new Date('01/08/2008');
						
						if($A10=='70' && $A27->getDate()>=$d->getDate() && ($L11=='' || $L11=='00000000' || $L11=='dd/mm/yyyy'))
						{
							return "A10_A27_L11_5[".$sa."]: Date of birth must be entered if the learning aim is ESF funded and the start date is on or after 1 August 2008 \n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A27_L11_5[".$sa."]: Date of birth must be entered if the learning aim is ESF funded and the start date is on or after 1 August 2008 \n";
					}
				}
			}

			private function rule_A09_A10_A15_A27_A46a_A46b_AD04_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					// Calculation for AD04
					try
					{
						//throw new Exception($AD04);			
					
						$testalpha = new Date($ilr->aims[$sa]->A27);
						$testbeta = new Date($ilr->learnerinformation->L11);
						
						$dob = substr($ilr->learnerinformation->L11,6,4) . "-" . substr($ilr->learnerinformation->L11,3,2) . "-" . substr($ilr->learnerinformation->L11,0,2);
						$start = substr($ilr->aims[$sa]->A27,6,4) . "-" . substr($ilr->aims[$sa]->A27,3,2) . "-" . substr($ilr->aims[$sa]->A27,0,2);
						
						$days = (strtotime($start) - strtotime($dob)) / (60 * 60 * 24);
						$years = $days/365;
						
						//$days = $this->dateDiff('/',$ilr->aims[$sa]->A27,$ilr->learnerinformation->L11);
						//$years = round($days/365,0);
						
						if($years>=16 && $years<19)
							$AD04 = 1;
						else
							if($years>=19 && $years<25)
								$AD04 = 2;
							else
								if($years>=25)
									$AD04 = 3;
								else
									return  "The learner is too young. Please check the date of birth again";
									
	
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2008');
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
						
						$query = "select LEARNING_AIM_TYPE_CODE from learning_aim where LEARNING_AIM_REF='$A09'";
						$latc = DAO::getSingleValue($linklad, $query);
						
						if($A46a!='083' && $A46b!='083' && $A10=='45' && $A15=='99' && $AD04<2 && $A27->getDate()>=$sd->getDate() && $A09!='10029424')
						{
							return "A09_A10_A15_A46a_A46b_AD04_1[".$sa."]: Learners undertaking non-apprenticeship employer responsive funded provision must be 19+ at the start of learning unless they are part of the Employability Skills Programme (including the employability awards)   \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_A46a_A46b_AD04_1[".$sa."]: Learners undertaking non-apprenticeship employer responsive funded provision must be 19+ at the start of learning unless they are part of the Employability Skills Programme (including the employability awards)   \n";
					}
				}
			}
			
			private function rule_A10_A15_A27_A46a_A46b_AD04_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					// Calculation for AD04
					try
					{
						//throw new Exception($AD04);			
					
						$testalpha = new Date($ilr->aims[$sa]->A27);
						$testbeta = new Date($ilr->learnerinformation->L11);
						
						$dob = substr($ilr->learnerinformation->L11,6,4) . "-" . substr($ilr->learnerinformation->L11,3,2) . "-" . substr($ilr->learnerinformation->L11,0,2);
						$start = substr($ilr->aims[$sa]->A27,6,4) . "-" . substr($ilr->aims[$sa]->A27,3,2) . "-" . substr($ilr->aims[$sa]->A27,0,2);
						
						$days = (strtotime($start) - strtotime($dob)) / (60 * 60 * 24);
						$years = $days/365;
						
						//$days = $this->dateDiff('/',$ilr->aims[$sa]->A27,$ilr->learnerinformation->L11);
						//$years = round($days/365,0);
						
						if($years>=16 && $years<19)
							$AD04 = 1;
						else
							if($years>=19 && $years<25)
								$AD04 = 2;
							else
								if($years>=25)
									$AD04 = 3;
								else
									return  "The learner is too young. Please check the date of birth again";
									
	
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2009');
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
						
						$query = "select LEARNING_AIM_TYPE_CODE from learning_aim where LEARNING_AIM_REF='$A09'";
						$latc = DAO::getSingleValue($linklad, $query);
						
						if($A46a!='083' && $A46b!='083' && $A10=='45' && $A15=='99' && $AD04<2 && $A27->getDate()>=$sd->getDate())
						{
							return "A10_A15_A27_A46a_A46b_AD04_1[".$sa."]: Learners who start on or after 1 August 2009 who are undertaking non-apprenticeship employer responsive funded provision must be 19+ at the start of learning unless they are part of the Employability Skills Programme (including the employability awards) \n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A15_A27_A46a_A46b_AD04_1[".$sa."]: Learners who start on or after 1 August 2009 who are undertaking non-apprenticeship employer responsive funded provision must be 19+ at the start of learning unless they are part of the Employability Skills Programme (including the employability awards) \n";
					}
				}
			}
			
			private function rule_A27_L14_L15_L16_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				$L14 = $ilr->learnerinformation->L14;
				$L15 = $ilr->learnerinformation->L15;
				$L16 = $ilr->learnerinformation->L16;
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2008');

						if($A27->getDate()>=$d->getDate() && $L14=='1' && $L15=='98' && $L16=='98')
						{
							return "A27_L14_L15_L16_1[".$sa."]: Date of birth must be entered if the learning aim is ESF funded and the start date is on or after 1 August 2008 \n";
						}
					}
					catch(Exception $e)
					{
						return "A27_L14_L15_L16_1[".$sa."]: Date of birth must be entered if the learning aim is ESF funded and the start date is on or after 1 August 2008 \n";
					}
				}
			}
			
			private function rule_L14_L15_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				
				$L14 = trim($ilr->learnerinformation->L14);
				$L15 = trim($ilr->learnerinformation->L15);
		
				if($L14=='9' && $L15!='99')
				{
					return "L14_L15_1: Disability or Health Problem must be set to Not known/ information not provided, if Learning difficulties and or disabilities is set to No information provided by the learner \n";
				}
			}
			
			private function rule_L14_L15_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				
				$L14 = trim($ilr->learnerinformation->L14);
				$L15 = trim($ilr->learnerinformation->L15);
		
				if($L14=='2' && $L15!='98')
				{
					return "L14_L15_2: Disability or Health Problem must be set to No disability if Learning difficulties and/or disabilities is set to Learner does not consider himself or herself to have a learning difficulty and/or disability or health problem. \n";
				}
			}
			
			private function rule_L14_L16_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$L14 = trim($ilr->learnerinformation->L14);
				$L16 = trim($ilr->learnerinformation->L16);
		
				if($L14=='9' && $L16!='99')
				{
					return "L14_L16_1: Learning Difficulty must be set to Not known/information not provided if Learning difficulties and/or disabilities is set to No information provided by the learner  \n";
				}
			}
			
			private function rule_L14_L16_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$L14 = trim($ilr->learnerinformation->L14);
				$L16 = trim($ilr->learnerinformation->L16);
		
				if($L14=='2' && $L16!='98')
				{
					return "L14_L16_2: Learening Difficulty must be set to No learning difficulty if Learning difficulties and/or disabilities is set to Learner does not consider himself or herself to have a learning difficulty and/or disability or health problem. \n";
				}
			}
			
			private function rule_L17_L24_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$L17 = trim($ilr->learnerinformation->L17);
				$L24 = trim($ilr->learnerinformation->L24);

				if( ($L24=='XK' || $L24=='XF' || $L24=='IM' || $L24=='XG' || $L24=='XH' || $L24=='XI' || $L24=='GB' || $L24=='XJ' || $L24=='XL' || $L24=='GG' || $L24=='JE') && $L17=='')
				{
					return "L17_L24_1: Postcode is mandatory unless country code is not UK \n";
				}
			}
			
			private function rule_L34abcd_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$L34a = trim($ilr->learnerinformation->L34a);
				$L34b = trim($ilr->learnerinformation->L34b);
				$L34c = trim($ilr->learnerinformation->L34c);
				$L34d = trim($ilr->learnerinformation->L34d);
				
				if($L34a != '99' && ($L34a==$L34b || $L34a==$L34c || $L34a==$L34d))
				{
					return "L34abcd_1: Learner Support Reasons must all be different unless they are 99 \n";
				}
			}
			
			private function rule_L34bcd_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$L34b = trim($ilr->learnerinformation->L34b);
				$L34c = trim($ilr->learnerinformation->L34c);
				$L34d = trim($ilr->learnerinformation->L34d);
				
				if($L34b != '99' && ($L34b==$L34c || $L34b==$L34d))
				{
					return "L34bcd_1: Learner Support Reasons must all be different unless they are 99 \n";
				}
			}
			
			private function rule_L34cd_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$L34c = trim($ilr->learnerinformation->L34c);
				$L34d = trim($ilr->learnerinformation->L34d);
				
				if($L34c != '99' && $L34c==$L34d)
				{
					return "L34cd_1: Learner Support Reasons must all be different unless they are 99 \n";
				}
			}
			
			private function rule_L35_A14_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
		
					$A14 = trim($ilr->aims[$sa]->A14);
					$L35 = trim($ilr->learnerinformation->L35);
		
					if($A14=='22' && ($L35=='02' || $L35=='03' || $L35=='04' || $L35=='05'))
					{
						return "L35_A14_1[".$sa."]: If the tuition fee remission is fees waived level 2 entitlement then the learners prior attainment must not be level 2 or above \n";
					}
				}
			}
			
			private function rule_L35_A14_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
			
					$A14 = trim($ilr->aims[$sa]->A14);
					$L35 = trim($ilr->learnerinformation->L35);
			
					if($A14=='24' && ($L35=='03' || $L35=='04' || $L35=='05'))
					{
						return "L35_A14_2[".$sa."]: If the date of change of employment status is zero then the current employment status must equal the employment status on first day of learning. \n";
					}
				}
			}

			private function rule_A27_L37_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{

				$L37 = $ilr->learnerinformation->L37;
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2009');
						
						if($A27->getDate()>=$d->getDate() && $L37=='02')
						{
							return "A27_L37_1[".$sa."]: If the learning aim started on or after 1 August 2009, the Employment status on first day of learning must not be 'not employed'. \n";
						}
					}
					catch(Exception $e)
					{
						return "A27_L37_1[".$sa."]: If the learning aim started on or after 1 August 2009, the Employment status on first day of learning must not be 'not employed'.\n";
					}
				}
			}
			
			private function rule_L37_L47_L48_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				
				$L37 = trim($ilr->learnerinformation->L37);
				$L47 = trim($ilr->learnerinformation->L47);
				$L48 = trim($ilr->learnerinformation->L48);
		
				if($L48=='00000000')
				 if($L37!=$L47)
				{
					return "L37_L47_L48_1: If the date of change of employment status is zero then the current employment status must equal the employment status on first day of learning. \n";
				}
			}
			
			private function rule_A04_A31_L39_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L39 = $ilr->learnerinformation->L39;
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04);
					$A31 = trim($ilr->aims[$sa]->A31); 
					
					if($A04=='30' && ($A31=='00000000' || $A31=='')&& $L39!='95')
					{
						return "A04_A31_L39_1[".$sa."]: For non programme aims, if the learning actual end date is not entered, then the destination must be continuing \n";
					}
				}
			}
			
			private function rule_L27_L39_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				
				$L27 = trim($ilr->learnerinformation->L27);
				$L39 = trim($ilr->learnerinformation->L39);
		
				if($L39=='61' && $L27!='2')
				{
					return "L27_L39_1: If destination code is set to 'death' then the restricted use indicator must be set to 'learner is not to be contacted' \n";
				}
			}

			private function rule_L39_A10_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				$L39 = $ilr->learnerinformation->L39;
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10);

					if($A10 == '70' && $L39 == '')
					{
						return "L39_A10_1[".$sa."]: If the learning aim is ESF co-financed the learner's Destination must be entered. \n";
					}
				}
			}
			
			private function rule_L40a_L40b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
		
				$L40a = trim($ilr->learnerinformation->L40a);
				$L40b = trim($ilr->learnerinformation->L40b);
		
				if($L40a==$L40b && $L40a!='99')
				{
					return "L40a_L40b_1: The National Learning Monitoring fields must not contain the same values unless they are both 99 \n";
				}
			}

			private function rule_A27_L47_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
			
				$L47 = $ilr->learnerinformation->L47;
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2009');
						
						if($A27->getDate()>=$d->getDate() && $L47=='02')
						{
							return "A27_L47_1[".$sa."]: If the learning aim started on or after 1 August 2009, the Current employment status must not be 'not employed'. \n";
						}
					}
					catch(Exception $e)
					{
						return "A27_L47_1[".$sa."]: If the learning aim started on or after 1 August 2009, the Current employment status must not be 'not employed'. \n";
					}
				}
			}
			
			private function rule_A09_A10_A15_A26_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A04 = trim($ilr->aims[$sa]->A04);
					$A09 = trim($ilr->aims[$sa]->A09);
					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);
					
					$query = "select Framework_Component_Type_Code from framework_aims where LEARNING_AIM_REF='$A09' and FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26'";
					$fctc = DAO::getSingleValue($linklad, $query);
					
					if($A10=='45' && ($A15=='02' || $A15=='03' || $A15=='10') && $fctc=='001')
					{
						return "A09_A10_A15_A26_LAD_1[".$sa."]: If the learning aim does have a framework component type of 001 within this framework, then the aim must be an employer responsive apprenticeship main aim \n";
					}
				}
			}
			
			private function rule_A09_A10_A15_A26_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A04 = trim($ilr->aims[$sa]->A04);
					$A09 = trim($ilr->aims[$sa]->A09);
					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);
					
					$query = "select Framework_Component_Type_Code from framework_aims where LEARNING_AIM_REF='$A09' and FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26'";
					$fctc = DAO::getSingleValue($linklad, $query);
					
					if($A10=='46' && ($A15=='02' || $A15=='03' || $A15=='10') && ($fctc!='001' || $fctc==''))
					{
						return $fctc . "A09_A10_A15_A26_LAD_2[".$sa."]: If the learning aim does not have a framework component type of 001 within this framework, then the aim cannnot be an employer responsive apprenticeship main aim (A10=46)   \n";
					}
				}
			}
		

	private function rule_R03(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
	{
		$L01 = trim($ilr->learnerinformation->L01);
		$A01 = trim($ilr->aims[0]->A01);
		$L03 = trim($ilr->learnerinformation->L03);
		$A03 = trim($ilr->aims[0]->A03);
		
		if($L01!=$A01 || $L03!=$A03)
		{
			return "R03: An Aim record must have an associated Learner record (same Provider Number and Learner Reference number)\n";
		}
	}
	

	
	private function rule_R23(PDO $link, PDO $linklad, PDO $linklis, ILR2010 $ilr)
	{
		$L39 = trim($ilr->learnerinformation->L39);
		$flag = "N";
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A31 = trim($ilr->aims[$sa]->A31);

			if($A31=='00000000' || $A31=='dd/mm/yyyy')
			{
				$flag="Y";
			}
		}
		if($flag=="N" && $L39=="95")
		{
				return "R23[".$sa."]: If A31 is not null on all aims for a learner then L39, Destination cannot be 95 \n";
		}
	}
	
	public static function isAlphaNum($ch)
	{
		if((ord($ch)>=48 && ord($ch)<=57) || (ord($ch)>=97 && ord($ch)<=122) || (ord($ch)>=65 && ord($ch)<=90))
			return true;
		else
			return false;
	}

	public static function isDigit($ch)
	{
		if(ord($ch)>=48 && ord($ch)<=57)
			return true;
		else
			return false;
	}
	
	public static function isAlpha($ch)
	{
		try
		{
			if((ord($ch)>=97 && ord($ch)<=122) || (ord($ch)>=65 && ord($ch)<=90)) 
				return true;
			else
				return false;
		}
		catch(Exception $e)
		{
			throw new Exception($ch);
		}
	}
	
	// Pass Separator i.e. /, end date and begin date
	public static function dateDiff($dformat, $endDate, $beginDate) 
	{     

		try
		{
			$date_parts1=explode($dformat, $beginDate);     
			$date_parts2=explode($dformat, $endDate);     
	
			$start 	= mktime(0,0,0,$date_parts1[0], $date_parts1[1], $date_parts1[2]);
			$end 	= mktime(0,0,0,$date_parts2[0], $date_parts2[1], $date_parts2[2]);
			
			$d = $end - $start;
			$fullDays = floor($d/(60*60*24));
			
			$start_date=gregoriantojd($date_parts1[0], $date_parts1[1], $date_parts1[2]);     
			$end_date=gregoriantojd($date_parts2[0], $date_parts2[1], $date_parts2[2]);     
		}
		catch(Exception $e)
		{
			throw new Exception("Wrong date");
		}
		//return $end_date - $start_date; 
		
		return $fullDays;
		
	}
	

	private function dummy($rubbish)
	{
		echo "<p>dummy()</p>";
	}
	
	
	function GetAge($DOB, $DOD) {

	// Get current date
	$CD = date("d/n/Y");
	list($cd,$cm,$cY) = explode("/",$CD);
	
	// Get date of birth
	list($bd,$bm,$bY) = explode("/",$DOB);
	// is there a date of death?
	
	if ($DOD!="" && $DOD != "0000-00-00") {

	// Animal is dead
		list($dd,$dm,$dY) = explode("/",$DOD);
			if ($bY == $dY) {
     			$months = $dm - $bm;
	     		if ($months == 0 || $months > 1) {
	     			return "$months months";
	     		} else
	    			return "$months month";
			} else 
   				$years = ( $dm.$dd < $bm.$bd ? $dY-$bY-1 : $dY-$bY );
	     		if ($years == 0 || $years > 1) {
	     			return $years;
				} else { 
	    			return $years;
				}

	} else {

	// Animal is alive
		if ($bY != "" && $bY != "0000") {	

	     	if ($bY == $cY) {
				// Birth year is current year
	     		$months = $cm - $bm;
		     		if ($months == 0 || $months > 1) {
		     			return "$months months";
		     		} else 
		    			return "$months month";
			} else if ($cY - $bY == 1 && $cm - $bm < 12) {
				// Born within 12 months, either side of 01 Jan
					//Determine days and therefore proportion of month
					if ($cd - $bd > 0) {
						$xm = 0;
					} else { 
						$xm = 1;
					}
				$months = 12 - $bm + $cm - $xm;
		     		if ($months == 0 || $months > 1) {
		     			return "$months months";
		     		} else { 
		    			return "$months month";
					}
			} 

			// Animal older than 12 months, return in years
			$years = (date("md") < $bm.$bd ? date("Y")-$bY-1 : date("Y")-$bY );
     		if ($years == 0 || $years > 1) {
     			return "$years years";
			} else { 
    			return "$years year";
			}
			
		} else	
    	return "No Date of Birth!";
	}	
}
	

public $report = NULL;

}

