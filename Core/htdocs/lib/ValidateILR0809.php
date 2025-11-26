<?php
class ValidateILR0809
{
	public function validate(PDO $link, ILR2008 $ilr)
	{
		$class = new ReflectionClass(__CLASS__);
		$methods = $class->getMethods();

		$report = '';

		// Create separate connections for lis and lad
		$hostname = ini_get('mysqli.default_host');
		$port = ini_get('mysqli.default_port');

		$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis200809;port=".DB_PORT, DB_USER, DB_PASSWORD);
		$linklad = new PDO("mysql:host=".DB_HOST.";dbname=lad200809;port=".DB_PORT, DB_USER, DB_PASSWORD);
		
				
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
	
			private function rule_A01_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=(int)$ilr->learnerinformation->subaims;$sa++)
				{

					if(trim($ilr->learnerinformation->L01)!=trim($ilr->aims[$sa]->A01))

					{

						return "A01_1[".$sa."]: The provider number in the aim and learner must match ";

					}
				}
			}

			private function rule_A02_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A02)!='12' && trim($ilr->aims[$sa]->A02)!='00')
					{

						return "A02_2[".$sa."]: Contract type must be 12 (if entered) ";

					}
				}
			}

			private function rule_A04_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A04)!='30' && trim($ilr->aims[$sa]->A02)!='35')
					{

						return "A04_1[".$sa."]: Data set identifier code must be 30 or 35 ";

					}
				}
			}
			
			
			private function rule_A05_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if((int)trim($ilr->aims[$sa]->A05)<1 || (int)trim($ilr->aims[$sa]->A05)>98)
					{

						return "A05_1[".$sa."]: Learning aim data set sequence must be numeric and between 01 and 98 ";

					}
				}
			}

			
			private function rule_A07_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A07)!='00')
					{

						return "A07_2[".$sa."]: HE data sets must be 00 ";

					}
				}
			}

			private function rule_A08_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A08)!='2')
					{

						return "A08_3[".$sa."]: Data set format must be 2 ";

					}
				}
			}


			private function rule_A09_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$query = "select learning_aim_ref from LEARNING_AIM where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$A09 = trim(DAO::getSingleValue($linklad, $query));

					if(trim($A09)!=trim($ilr->aims[$sa]->A09))
					{
						return "A09_1[".$sa."]: Learning Aim reference code is not valid ";
					}
				}
			}

			private function rule_A09_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A09)=='XE2E0001')
					{
						return "A09_2[".$sa."]: Cannot be XE2E0001 ";
					}
				}
			}
			
			private function rule_A10_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					if($A10!='45' && $A10!='46' && $A10!='70' && $A10!='80' && $A10!='99')
					{
						return "A10_4[".$sa."]: LSC funding stream must be 45, 46, 70, 80 or 99 ";
					}
				}
			}

			private function rule_A11a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A11a = trim($ilr->aims[$sa]->A11a);
					if($A11a!='000')
					{
						return "A11a_2[".$sa."]: Source of funding (1) must be 000 ";
					}
				}
			}

			private function rule_A11b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A11b = trim($ilr->aims[$sa]->A11b);
					if($A11b!='000')
					{

						return "A11b_2[".$sa."]: Source of funding (2) must be 000 ";

					}
				}
			}

			private function rule_A12a_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A12a = trim($ilr->aims[$sa]->A12a);
					if($A12a!='000')
					{

						return "A12a_3[".$sa."]: A12a must be 000 ";

					}
				}
			}

			private function rule_A12b_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A12b = trim($ilr->aims[$sa]->A12b);
					if($A12b!='000')
					{

						return "A12b_3[".$sa."]: A12b must be 000 ";

					}
				}
			}
			
			private function rule_A13_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A14_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
/*			
			private function rule_A14_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					//$A14 = trim($ilr->aims[$sa]->A14);
					//if($A14!='00')
					//{

						return "A14_2[".$sa."]: Need to ask";

					//}
				}
			}
*/
			private function rule_A15_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A16_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$query = "select programme_route_code from ILR_A16_Programme_Routes where programme_route_code='{$ilr->aims[0]->A16}'";
					$A16 = DAO::getSingleValue($linklis, $query);

					$A16 = str_pad($A16,2,'0',STR_PAD_LEFT);

					if($A16!='00' && $A16!=$ilr->aims[$sa]->A16)
					{
						return "A16_3[".$sa."]: Invalid Programme Route Code ";
					}
				}
			}

			private function rule_A17_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A18_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A18 = trim($ilr->aims[$sa]->A18);
				
					$query = "select Delivery_Method_Code from ILR_A18_Delivery_Methods where Delivery_Method_Code='{$ilr->aims[0]->A18}' and ER_Ind='Y'";
					$data = DAO::getSingleValue($linklis, $query);
					
					if($A18!=$data && $A18!='00')
					{
						return "A18_3[".$sa."]: Main delivery method must be valid ";
					}
				}
			}

			private function rule_A19_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A20_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A21_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A21 = trim($ilr->aims[$sa]->A21);
					if($A21!='00')
					{
						return "A21_2[".$sa."]: Franchised out partnership arrangements must be 00";
					}
				}
			}


			private function rule_A22_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A22 = $ilr->aims[$sa]->A22;
					if($A22!='      ' && $A22!='')
					{

						return "A22_1[".$sa."]: Franchising delivery provider number must be spaces\n";

					}
				}
			}


			
			private function rule_A23_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A23_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A24_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A24 = trim($ilr->aims[$sa]->A24);

					
					$query = "select SOC2000_CODE_CODE from SOC2000_CODES where SOC2000_CODE_CODE='{$ilr->aims[$sa]->A24}'";
					$A24t = trim(DAO::getSingleValue($linklad, $query));

					
					if($A24!='0000' && $A24!=$A24t)
					{
						return "A24_1[".$sa."]: Occupation relating to learner aim must exist on the lookup if entered \n";
					}
				}
			}


			private function rule_A26_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A26 = trim($ilr->aims[$sa]->A26);
					
					$query = "select Framework_Code from FRAMEWORKS where Framework_Code='{$ilr->aims[0]->A26}'";
					$A26t = trim(DAO::getSingleValue($linklad, $query));

					if($A26!='' && $A26!=$A26t)
					{
						return "A26_1[".$sa."]: Sector framework code is not valid \n";
					}
				}
			}


			private function rule_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A27_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/1998');
						if($A27->getDate()<$check->getDate())
						{

							return "A27_4[".$sa."]: Learning start date must not be more than 10 years ago\n";

						}
					}
					catch(Exception $e)
					{
						return "A27_2[".$sa."]: Invalid Learning start date\n";
					}


				}
			}

			private function rule_A27_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$d = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2018');
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

			private function rule_A28_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A28_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A31_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A31_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A32_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$d = trim($ilr->aims[$sa]->A32);

					if((int)$d<0 || (int)$d>10000)
					{

						return "A32_1[".$sa."]: Guided learning hours must be in the range of 00000 and 10000\n";

					}
				}
			}

			private function rule_A34_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A34 = trim($ilr->aims[$sa]->A34);
					
					$query = "select Completion_Status_Code from ILR_A34_Completion_Status where Completion_Status_Code='{$ilr->aims[$sa]->A34}'";
					$A34t = trim(DAO::getSingleValue($linklis, $query));

					if($A34!=$A34t)
					{
						return "A34_3[".$sa."]: Invalid Completion Status Code \n";
					}
				}
			}


			private function rule_A35_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A35 = trim($ilr->aims[$sa]->A35);
					
					$query = "select Learning_Outcome_Code from ILR_A35_Learning_Outcomes where Learning_Outcome_Code='{$ilr->aims[$sa]->A35}'";
					$A35t = trim(DAO::getSingleValue($linklis, $query));

					if($A35!=$A35t)
					{
						return "A35_1[".$sa."]: Invalid Learning outcome code \n";
					}
				}
			}

			private function rule_A36_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A36 = trim($ilr->aims[$sa]->A36);
					
					$query = "select Learning_Outcome_Grade_Code from ILR_A36_Learn_Outcome_Grades where Learning_Outcome_Grade_Code='{$ilr->aims[$sa]->A35}'";
					$A36t = trim(DAO::getSingleValue($linklis, $query));

					if($A36!='' && $A36!=$A36t)
					{
						return "A36_1[".$sa."]: Learning outcome grade must exist on the table if entered \n";
					}
				}
			}
			
			private function rule_A37_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A37 = $ilr->aims[$sa]->A37;

					if(!((int)$A37>=0))
					{

						return "A37_1[".$sa."]: Number of units completed must be 0 or more \n";

					}
				}
			}

			private function rule_A38_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A38 = $ilr->aims[$sa]->A38;

					if(!((int)$A38>=0))
					{

						return "A38_1[".$sa."]: Number of units to achieve full qualification must be 0 or more \n";

					}
				}
			}

			private function rule_A39_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A39 = $ilr->aims[$sa]->A39;

					if($A39!='0')
					{

						return "A39_3[".$sa."]: Eligibility for achievement funding must be 0 \n";

					}
				}
			}

			private function rule_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A40_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A43_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A43s = trim($ilr->aims[$sa]->A43);
					if($A43s!='00000000' && $A43s!='dd/mm/yyyy' && $A43s!='')
					{
						return "A43_3[".$sa."]: Sector framework achievement date must be 00000000 \n";
					}
				}
			}


			private function rule_A44_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A44 = trim($ilr->aims[$sa]->A44);

					if(strpos($A44,'*')!= false || strpos($A44,'?')!= false  || strpos($A44,'%')!=false || strpos($A44,'_')!=false)
					{
						return "A44_1[".$sa."]: Employer identifier must not contain *, ?, % or _ symbols \n";
					}
				}
			}


			private function rule_A45_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A45_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A46a_7(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46a = trim($ilr->aims[$sa]->A46a);
					
					$query = "select National_Learner_Aim_Code from ILR_A46_Nat_Learner_Aims where ER_Ind='Y' and National_Learner_Aim_Code = '$A46a'";
					$A46at = DAO::getSingleValue($linklis, $query);
					
					if($A46a!=$A46at)
					{
						return "A46a_7[".$sa."]: National learning aim monitoring 1 Must exist on the table \n";
					}
				}
			}

			private function rule_A46b_7(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46b = trim($ilr->aims[$sa]->A46b);
					
					$query = "select National_Learner_Aim_Code from ILR_A46_Nat_Learner_Aims where ER_Ind='Y' and National_Learner_Aim_Code = '$A46b'";
					$A46bt = DAO::getSingleValue($linklis, $query);
										
					if($A46b!=$A46bt)
					{
						return "A46b_7[".$sa."]: National learning aim monitoring 2 Must exist on the table \n";
					}
				}
			}
			
			private function rule_A47a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A47b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A48a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A48b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A49_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$query = "select Project_Code from ILR_A49_Project_Codes where project_code='{$ilr->aims[$sa]->A49}'";
					$A49t = trim(DAO::getSingleValue($linklis, $query));

					if($A49t!=trim($ilr->aims[$sa]->A49) && trim($ilr->aims[$sa]->A49)!='')
					{
						return "A49_3[".$sa."]: Invalid project code \n";
					}
				}
			}

			private function rule_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A50 = trim($ilr->aims[$sa]->A50);
					
					$query = "select Reason_Learning_Ended_Code from ILR_A50_Reason_Learning_Ended where Reason_Learning_Ended_code='{$ilr->aims[$sa]->A50}'";
					$A50t = trim(DAO::getSingleValue($linklis, $query));

					if($A50!=$A50t)
					{
						return "A50_1[".$sa."]: Invalid reason learning ended \n";
					}
				}
			}


			private function rule_A51a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A51a = trim($ilr->aims[$sa]->A51a);

					if((int)$A51a<0 || (int)$A51a>99)
					{
						return "A51a_2[".$sa."]: Proportion of funding must be between 1 and 99\n";
					}
				}
			}


			private function rule_A52_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					
					if(trim($ilr->aims[$sa]->A52)!='0.000')
					{
						return "A52_3[".$sa."]: Invalid distance learning SLN\n";
					}
				}
			}
			
			private function rule_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A53 = trim($ilr->aims[$sa]->A53);
					
					$query = "select Additional_Learning_Need_Code from ILR_A53_Add_Learning_Needs where Additional_Learning_Need_Code='{$ilr->aims[$sa]->A53}'";
					$A53t = trim(DAO::getSingleValue($linklis, $query));

					if($A53!=$A53t)
					{
						return "A53_1[".$sa."]: Invalid additional learning needs code\n";
					}
				}
			}


			private function rule_A54_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A54 = trim($ilr->aims[$sa]->A54);
					if($A54!='' && $A54!='9999999999')
					{
						$first = substr($A54,0,2);
						if ($first!='EE' && $first!='EM' && $first!='GL' && $first!='NE' && $first!='NW' && $first!='SE' && $first!='SW' && $first!='WM' && $first!='YH' && $first!='AB')
							return "A54_1[".$sa."]: Broker contract number must be correct format: Format is RRCCCCCCCC where RR is EE,EM,GL,NE,NW,SE,SW,WM,YH or AB and CCCCCCCC are any alphanumeric characters in the range (A-Z) or (0-9) or 9999999999 (if entered) \n";
						if($this->isAlphaNum(substr($A54,2,1))==false || $this->isAlphaNum(substr($A54,3,1))==false || $this->isAlphaNum(substr($A54,4,1))==false || $this->isAlphaNum(substr($A54,5,1))==false || $this->isAlphaNum(substr($A54,6,1))==false || $this->isAlphaNum(substr($A54,7,1))==false || $this->isAlphaNum(substr($A54,8,1))==false || $this->isAlphaNum(substr($A54,9,1))==false)
							return "A54_1[".$sa."]: Broker contract number must be correct format: Format is RRCCCCCCCC where RR is EE,EM,GL,NE,NW,SE,SW,WM,YH or AB and CCCCCCCC are any alphanumeric characters in the range (A-Z) or (0-9) or 9999999999 (if entered) \n";
					}
				}
			}

			private function rule_A54_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$query = "select Broker_Contract_Number from TtG_Broker_Contracts where Broker_Contract_Number='{$ilr->aims[$sa]->A54}'";
					$A54 = trim(DAO::getSingleValue($linklis, $query));

					if($A54!=trim($ilr->aims[$sa]->A54) && $ilr->aims[$sa]->A54!='9999999999')
					{
						return "A54_2[".$sa."]: Invalid TtG Broker Contract Number \n";
					}
				}
			}

			private function rule_A55_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A56_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A57_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A57 = trim($ilr->aims[$sa]->A57);
					if($A57!='00')
					{
						return "A57_1[".$sa."]: Source of tuition fees must be zeros \n";
					}
				}
			}

			private function rule_A58_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A59_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A60_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


	// Single Field Validation (L Series) 		
			
			private function rule_L01_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$query = "select CAPN from Providers where CAPN='{$ilr->learnerinformation->L01}'";
				$L01 = trim(DAO::getSingleValue($linklis, $query));
		
				if($L01!=trim($ilr->learnerinformation->L01))
				{
					return "L01_2: Invalid provider number\n";
				}
			}


			private function rule_L02_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L02 = trim($ilr->learnerinformation->L02);
				if($L02!='00')
				{
					return "L02_2: Contract type must be 00 \n";
				}
		
			}

			private function rule_L03_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L03 = trim($ilr->learnerinformation->L03);
				if($L03=='')
				{
					return "L03_1: You must enter learner reference number\n";
				}
		
			}
	
			private function rule_L03_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
	
	
			private function rule_L06_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L06 = trim($ilr->learnerinformation->L06);
				if($L06!='00')
				{
					return "L06_1: ESF co-financing data sets must be 00 \n";
				}
		
			}
	
	
			private function rule_L07_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L07 = trim($ilr->learnerinformation->L07);
				if($L07!='00')
				{
					return "L07_1: HE data sets must be 00 \n";
				}
		
			}
	
	
			private function rule_L08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L08 = trim($ilr->learnerinformation->L08);
				if($L08!='Y' && $L08!='N')
				{
					return "L08_1: Deletion must be N or Y \n";
				}
		
			}
	
			private function rule_L09_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L09 = trim($ilr->learnerinformation->L09);
				if($L09=='')
				{
					return "L09_1: Learner surname is mandatory \n";
				}
			}
	
			private function rule_L09_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L09 = trim($ilr->learnerinformation->L09);
				$st="ÑabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ -'";
				for($lp=0;$lp<strlen($L09);$lp++)
				{
					if(strpos($st,substr($L09,$lp,1))==false)
					{
						return "L09_2: Learner surname contains invalid characters \n";
					}
				}
		
			}
	
			private function rule_L10_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L10 = trim($ilr->learnerinformation->L10);
				if($L10=='')
				{
					return "L10_1: Learner forenames is mandatory \n";
				}
		
			}
		
			private function rule_L10_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
	
	
			private function rule_L11_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
	
	
			private function rule_L11_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
			
				try
				{
					$d = new Date($ilr->learnerinformation->L11);
					$start = new Date('01/08/1882');
					$end   = new Date('01/08/2008');
					if($d->getDate() < $start->getDate() || $d->getDate()>$end->getDate())
					{
						return "L11_3: Date of birth must be between 01/08/1882 and 01/08/2008\n";
					}
				}
				catch(Exception $e)
				{
					return "L11_3: Date of birth must be between 01/08/1882 and 01/08/2008\n";
				
				}
		
			}
	
			private function rule_L11_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
	
			private function rule_L12_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				 
				$query = "select Ethnicity_Code from ILR_L12_Ethnicity where Ethnicity_Code='{$ilr->learnerinformation->L12}'";
				$L12 = trim(DAO::getSingleValue($linklis, $query));
		
				if($L12!=trim($ilr->learnerinformation->L12) || $ilr->learnerinformation->L12=='')
				{
					return "L12_2: Invalid ethnicity code\n";
				}
			}
	
			private function rule_L13_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$L13 = trim($ilr->learnerinformation->L13);				if($L13!='M' && $L13!='F')				{					return "L13_2: Invalid character for gender\n";				}			}			
			private function rule_L14_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{		
				$query = "select Difficulty_Disability from ILR_L14_Difficulty_Disability where Difficulty_Disability='{$ilr->learnerinformation->L14}'";				$L14 = trim(DAO::getSingleValue($linklis, $query));			
				if($L14!=trim($ilr->learnerinformation->L14))				{					return "L14_2: Invalid Learning difficulties or disability \n";				}			}	
	
			private function rule_L15_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$query = "select Disability_Code from ILR_L15_Disability where Disability_Code='{$ilr->learnerinformation->L15}'";				$L15 = trim(DAO::getSingleValue($linklis, $query));			
				if($L15!=trim($ilr->learnerinformation->L15))				{					return "L15_2: Invalid disability code \n";				}			}	
	
			private function rule_L16_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$query = "select Difficulty_Code from ILR_L16_Difficulty where Difficulty_Code='{$ilr->learnerinformation->L16}'";				$L16 = trim(DAO::getSingleValue($linklis, $query));			
				if($L16!=trim($ilr->learnerinformation->L16))				{					return "L16_2: Invalid difficulty code \n";				}			}	
	
			private function rule_L17_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L17 = trim($ilr->learnerinformation->L17);				if($L17!='')				{
					$check = substr($L17,(strpos($L17," ")+1),3);					 $first = substr($check,0,1);					 $second = substr($check,1,1);					 $third = substr($check,2,1);				}			
				if($L17!='')					if(ord($first)<48 || ord($first)>57 || ord($second)<65 || ord($second)>90 || ord($third)<65 || ord($third)>90 || $second=='C' || $second=='I' || $second=='K' || $second=='M' || $second=='O' || $second=='V' || $third=='C' || $third=='I' || $third=='K' || $third=='M' || $third=='O' || $third=='V')						if($check!='ZZZ')						{							return "L17_3: Home Postcode: The second part of the postcode must be in the correct format (nXX) where n = 0-9 and XX are capital letters excluding C, I, K, M, O and V,  or be 'ZZZ', or the whole postcode must be spaces \n";						}			}	
	
			private function rule_L17_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L17 = trim($ilr->learnerinformation->L17);				if($L17!='')				{
					$check = substr($L17,0,(strpos($L17," ")));					$first = substr($check,0,1);				}			
				if($L17!='')					if(ord($first)<65 || ord($first)>90 || strlen($check)<2 || strlen($check)>4)						if($check!='Z99')						{							return "L17_4: Invalid home postcode\n";						}			}
	
			private function rule_L18_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L18 = trim($ilr->learnerinformation->L18);				if($L18=='')				{					return "L18_1: Address is mandatory \n";				}			}	
	
			private function rule_L18_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L18 = trim($ilr->learnerinformation->L18);				$st="ôabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-'/.&![]+:;@ ";			
				for($lp=0;$lp<strlen($L18);$lp++)				{					if(strpos($st,substr($L18,$lp,1))==false)					{						return "L18_2: Address Line 1 contains invalid characters \n";					}				}			}	

			private function rule_L19_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L19 = trim($ilr->learnerinformation->L19);				$st="ôabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-'/.&![]+:;@ ";			
				for($lp=0;$lp<strlen($L19);$lp++)				{					if(strpos($st,substr($L19,$lp,1))==false)					{						return "L19_1: Address Line 2 contains invalid characters \n";					}				}			}	
			private function rule_L20_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L20 = trim($ilr->learnerinformation->L20);				$st="ôabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-'/.&![]+:;@ ";		
				for($lp=0;$lp<strlen($L20);$lp++)				{					if(strpos($st,substr($L20,$lp,1))==false)					{						return "L20_1: Address Line 3 contains invalid characters \n";					}				}			}
	
			private function rule_L21_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L21 = trim($ilr->learnerinformation->L21);				$st="ôabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-'/.&![]+:;@ ";			
				for($lp=0;$lp<strlen($L21);$lp++)				{					if(strpos($st,substr($L21,$lp,1))==false)					{						return "L21_1: Address Line 4 contains invalid characters \n";					}				}			}	
			private function rule_L22_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L22 = trim($ilr->learnerinformation->L22);				if($L22!='')				{
					$check = substr($L22,(strpos($L22," ")+1),3);				 	$first = substr($check,0,1);					$second = substr($check,1,1);					$third = substr($check,2,1);				}			
				if($L22!='')			
					if(ord($first)<48 || ord($first)>57 || ord($second)<65 || ord($second)>90 || ord($third)<65 || ord($third)>90 || $second=='C' || $second=='I' || $second=='K' || $second=='M' || $second=='O' || $second=='V' || $third=='C' || $third=='I' || $third=='K' || $third=='M' || $third=='O' || $third=='V')						if($check!='ZZZ')						{							return "L22_3: Current Postcode L22: The second part of the postcode must be in the correct format (nXX) where n = 0-9 and XX are capital letters excluding C, I, K, M, O and V,  or be 'ZZZ', or the whole postcode must be spaces \n";						}			}	
			private function rule_L22_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L22 = trim($ilr->learnerinformation->L22);				if($L22!='')				{
					$check = substr($L22,0,(strpos($L22," ")));					$first = substr($check,0,1);				}			
				if($L22!='')					if(ord($first)<65 || ord($first)>90 || strlen($check)<2 || strlen($check)>4)						if($check!='Z99')						{							return "L22_4: Invalid current postcode\n";						}			}	
	
			private function rule_L23_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$ilr->learnerinformation->L23 = trim($ilr->learnerinformation->L23);				$L23 = trim($ilr->learnerinformation->L23);
				for($lp=0;$lp<strlen($L23);$lp++)				{					if(is_numeric(substr($L23,$lp,1))==false)					{						return "L23_1: Telephone number must contains only digits \n";					}				}			}	
	
			private function rule_L24_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$query = "select Domicile_Code from ILR_L24_Domiciles where Domicile_Code='{$ilr->learnerinformation->L24}'";				$L24 = trim(DAO::getSingleValue($linklis, $query));
				if($L24!=trim($ilr->learnerinformation->L24) || $ilr->learnerinformation->L24=='')				{					return "L24_3: Invalid country of domicile \n";				}			}	
			private function rule_L25_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{
				
				$d = str_pad($ilr->learnerinformation->L25,3,'0',STR_PAD_LEFT);				$query = "select CONCAT(Code,Satellite_Office) from LSC where CONCAT(Code,Satellite_Office)='$d'";				$L25 = trim(DAO::getSingleValue($linklis, $query));			
				if($L25=='000' || $L25=='999' || $L25!=$d)				{						return "L25_2: Invalid LSC number of funding LSC \n";				}			}	

			private function rule_L26_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
		
				$L26 = trim($ilr->learnerinformation->L26);
				if($L26!='' && $L26!='         ')
				{
					if(strlen($L26)!=9)					{						return "L26_1: Invalid insurance number \n";					}				
					$one = substr($L26,0,1);					$two = substr($L26,1,1);					$digi = substr($L26,2,6);					$st='0123456789';					$nine = substr($L26,8,1);	
					if(ord($one)<65 || ord($one)>90 || $one=='D' || $one=='F' || $one=='I' || $one=='Q' || $one=='U' || $one=='V')					{						return "L26_1: The first character of National Insurance no. must be an alphabet other than D, F, I, Q, U and V \n";					}	
					if(ord($two)<65 || ord($two)>90 || $two=='D' || $two=='F' || $two=='I' || $two=='O' || $two=='Q' || $two=='U' || $two=='V')					{						return "L26_1: The second character of National Insurance no. must be an alphabet other than D, F, I, O, Q, U and V \n";					}				
					for($lp=0;$lp<strlen($digi);$lp++)					{						if(strpos($st,substr($digi,$lp,1))==-1)						{							return "L26_1: Characters 3 to 8 of National Insuarnce no. must only be digits \n";						}					}				
					if( ord($nine)<65 || ord($nine)>90 || ($nine!='A' && $nine!='B' && $nine!='C' && $nine!='D' && $nine!=' '))					{						return "L26_1: The character 9 of National Insurance no. must be A, B, C, D or space \n";					}
				}			}	
	
			private function rule_L27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$query = "select Restricted_Use_Code from ILR_L27_Restricted_Uses where Restricted_Use_Code='{$ilr->learnerinformation->L27}'";				$L27 = trim(DAO::getSingleValue($linklis, $query));		
				if($L27!=trim($ilr->learnerinformation->L27))				{					return "L27_1: Invalid restricted use indicator \n";				}			}	
			private function rule_L28a_8(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$query = "select Eligibility_Enhanced_Code from ILR_L28_Eligibil_Enhance_Fnds where ER_Ind='Y' and Eligibility_Enhanced_Code='{$ilr->learnerinformation->L28a}'";
				$L28a = trim(DAO::getSingleValue($linklis, $query));
		
				if($L28a!=trim($ilr->learnerinformation->L28a))
				{
					return "L28a_8: Must exist on the table and be valid for ER \n";
				}
			}
			
			
			private function rule_L28b_8(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$query = "select Eligibility_Enhanced_Code from ILR_L28_Eligibil_Enhance_Fnds where ER_Ind='Y' and Eligibility_Enhanced_Code='{$ilr->learnerinformation->L28b}'";
				$L28b = trim(DAO::getSingleValue($linklis, $query));
		
				if($L28b!=trim($ilr->learnerinformation->L28b))
				{
					return "L28b_8: Must exist on the table and be valid for ER \n";
				}
			}
			
			private function rule_L29_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$L29 = trim($ilr->learnerinformation->L29);				if($L29!='00')				{					return "L29_2: Additional support must be 00 \n";				}			}	
			private function rule_L31_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$L31 = trim($ilr->learnerinformation->L31);				if($L31!='000000')				{					return "L31_4: Additional support cost must be 000000 \n";				}			}	
	
			private function rule_L32_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$L32 = trim($ilr->learnerinformation->L32);				if($L32!='00')				{					return "L32_2: Eligibility for disadvantage uplift must be 00 \n";				}			}	
	
			private function rule_L33_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L33 = trim($ilr->learnerinformation->L33);				if($L33!='0.0000')				{					return "L33_2: Disadvantage uplift factor must be 0.0000 \n";				}			}	

			private function rule_L34a_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{
				$query = "select Learner_Support_Reason_Code from ILR_L34_Learner_Supp_Reasons where ER_Ind='Y' and Learner_Support_Reason_Code='{$ilr->learnerinformation->L34a}'";				$L34a = trim(DAO::getSingleValue($linklis, $query));
				if($L34a!=$ilr->learnerinformation->L34a)				{					return "L34a_6: Learner support reason 1 must exist on the table \n";				}			}
				
			private function rule_L34b_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				$query = "select Learner_Support_Reason_Code from ILR_L34_Learner_Supp_Reasons where ER_Ind='Y' and Learner_Support_Reason_Code='{$ilr->learnerinformation->L34b}'";
				$L34b = trim(DAO::getSingleValue($linklis, $query));

				if($L34b!=$ilr->learnerinformation->L34b)
				{
					return "L34b_6: Learner support reason 2 must exist on the table \n";
				}
			}
				
			private function rule_L34c_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				$query = "select Learner_Support_Reason_Code from ILR_L34_Learner_Supp_Reasons where ER_Ind='Y' and Learner_Support_Reason_Code='{$ilr->learnerinformation->L34c}'";
				$L34c = trim(DAO::getSingleValue($linklis, $query));

				if($L34c!=$ilr->learnerinformation->L34c)
				{
					return "L34c_6: Learner support reason 3 must exist on the table \n";
				}
			}
						private function rule_L34d_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				$query = "select Learner_Support_Reason_Code from ILR_L34_Learner_Supp_Reasons where ER_Ind='Y' and Learner_Support_Reason_Code='{$ilr->learnerinformation->L34d}'";
				$L34d = trim(DAO::getSingleValue($linklis, $query));

				if($L34d!=$ilr->learnerinformation->L34d)
				{
					return "L34d_6: Learner support reason 4 must exist on the table \n";
				}
			}
			
	
			private function rule_L35_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$query = "select count(*) from ILR_L35_Prior_Attainment_Level where Prior_Attainment_Level_Code='{$ilr->learnerinformation->L35}'";				$L35 = trim(DAO::getSingleValue($linklis, $query));
				if($L35==0)				{					return "L35_1: Invalid prior attainment level code  \n";				}			}	
	
			private function rule_L36_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$query = "select Learner_Status_Code from ILR_L36_Learner_Status where Learner_Status_Code='{$ilr->learnerinformation->L36}'";				$L36 = trim(DAO::getSingleValue($linklis, $query));			
				if($ilr->learnerinformation->L36=='' || $L36!=trim($ilr->learnerinformation->L36))				{					return "L36_1: Invalid learner status on last working day before learning  \n";				}			}	
	
			private function rule_L37_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$query = "select Employment_Status_First_Code from ILR_L37_Employ_Status_Firsts where Employment_Status_First_Code='{$ilr->learnerinformation->L37}'";				$L37 = trim(DAO::getSingleValue($linklis, $query));
				if($L37!=trim($ilr->learnerinformation->L37))				{					return "L37_1: Invalid employment status on first day of learning \n";				}			}	
	
			private function rule_L39_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$query = "select Destination_Code from ILR_L39_Destinations where Destination_Code='{$ilr->learnerinformation->L39}'";				$L39 = trim(DAO::getSingleValue($linklis, $query));
				if($L39!=trim($ilr->learnerinformation->L39))				{					return "L39_2: Destination must exist on the table \n";				}			}	
	
			private function rule_L40a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$query = "select National_Learner_Event_Code from ILR_L40_Nat_Learner_Events where National_Learner_Event_Code='{$ilr->learnerinformation->L40a}'";				$L40a = trim(DAO::getSingleValue($linklis, $query));
				if($L40a!=$ilr->learnerinformation->L40a)				{					return "L40a_2: National learner monitoring 1 must exist on table \n";				}			}	
			private function rule_L40b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$query = "select National_Learner_Event_Code from ILR_L40_Nat_Learner_Events where National_Learner_Event_Code='{$ilr->learnerinformation->L40b}'";				$L40b = trim(DAO::getSingleValue($linklis, $query));			
				if($ilr->learnerinformation->L40b=='' || $L40b!=$ilr->learnerinformation->L40b)				{					return "L40b_2: National learner monitoring 2 must exist on table \n";				}			}	
	
			private function rule_L41a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L41a = trim($ilr->learnerinformation->L41a);				if((int)$L41a < 0)				{					return "L41a_1: Local learner monitoring 1 must be greater than or 0 if entered \n";				}			}	
			private function rule_L41b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L41b = trim($ilr->learnerinformation->L41b);				if((int)$L41b < 0)				{					return "L41b_1: Local learner monitoring 2 must be greater than or 0 if entered \n";				}			}	
			private function rule_L42a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L42a = trim($ilr->learnerinformation->L42a);				if(strpos($L42a,'*')!= false || strpos($L42a,'?')!= false  || strpos($L42a,'%')!=false || strpos($L42a,'_')!=false)				{					return "L42a_1: Provider specified learner data 1 may be any printable characters except for *, ?, % or _ symbols \n";				}			}	
			private function rule_L42b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L42b = trim($ilr->learnerinformation->L42b);		
				if(strpos($L42b,'*')!= false || strpos($L42b,'?')!= false  || strpos($L42b,'%')!=false || strpos($L42b,'_')!=false)				{					return "L42b_1: Provider specified learner data 2 may be any printable characters except for *, ?, % or _ symbols \n";				}			}	
	
			private function rule_L44_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L44a = trim($ilr->learnerinformation->L44);		
				if($L44a=='002')				{					return "L44_1: NCS delivery LSC number must not be 002 \n";				}			}	
			private function rule_L44_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$query = "select Code from LSC where Code='{$ilr->learnerinformation->L44}'";
				$L44 = trim(DAO::getSingleValue($linklis, $query));
						
				if($L44!=trim($ilr->learnerinformation->L44) && $L44!='')				{					return "L44_3: Invalid NCS delivery LSC number, must exist on table \n";				}			}	
			
			private function rule_L45_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
			
				if((int)$ilr->learnerinformation->L45 < 1000000000 || (int)$ilr->learnerinformation->L45 > 9999999999)
				{
					return "L45_2: Must be in the format 1000000000 - 9999999999 \n";
				}
			}
			
						private function rule_L46_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$query = "select UKPRN from Providers where UKPRN='{$ilr->learnerinformation->L46}'";				$L46 = trim(DAO::getSingleValue($linklis, $query));			
				if($L46!=trim($ilr->learnerinformation->L46))				{					return "L46_1: Invalid UK Provider reference number \n";				}			}	
	
			private function rule_L47_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$query = "select Current_Emp_Status_Code from ILR_L47_Current_Emp_Status where Current_Emp_Status_Code='{$ilr->learnerinformation->L47}'";				$L47 = trim(DAO::getSingleValue($linklis, $query));		
				if($L47!=trim($ilr->learnerinformation->L47))				{					return "L47_1: Invalid current employment status \n";				}			}	
			
			private function rule_L48_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{			
				$L48s = trim($ilr->learnerinformation->L48);				if($L48s!='00000000' && $L48s!='00/00/0000' && $L48s!='dd/mm/yyyy' && $L48s!='')				{					try					{						$L48 = new Date($ilr->learnerinformation->L48);					}					catch(Exception $e)					{						return "L48_1: Date employment status changed must be a valid date or 00000000 \n";					}				}			}	
			private function rule_L48_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				$L48 = trim($ilr->learnerinformation->L48);				$start = new Date('01/01/2001');				$end   = new Date('01/08/2011');			
				if($L48!='00000000' && $L48!='00/00/0000' && $L48!='dd/mm/yyyy' && $L48!='')				{
					try
					{						$d = new Date($ilr->learnerinformation->L48);
												if($d->getDate()<$start->getDate() || $d->getDate()>$end->getDate())						{							return "L48_2: Date of employment status changed must be between 01/01/2001 and 01/08/2011\n";
						}
					}
					catch(Exception $e)
					{
						return "L48_2: Date employment status changed must be a valid date or 00000000 \n";
					}
				}			}	
			private function rule_L49a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				if(trim($ilr->learnerinformation->L49a)!='00')				{					return "L49a_2: Discretionary learner support type 1 must be 00 \n";				}			}	

			private function rule_L49b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				if(trim($ilr->learnerinformation->L49b)!='00')				{					return "L49b_2: Discretionary learner support type 2 must be 00 \n";				}			}	
	
			private function rule_L49c_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				if(trim($ilr->learnerinformation->L49c)!='00')				{					return "L49c_2: Discretionary learner support type 3 must be 00 \n";				}			}	
				private function rule_L49d_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)			{				if(trim($ilr->learnerinformation->L49d)!='00')				{					return "L49d_2: Discretionary learner support type 4 must be 00 \n";				}			}
			
			
			// Field level validations E Series
			
			private function rule_E01_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A01 = trim($ilr->aims[$sa]->A01);
					$A06 = trim($ilr->aims[$sa]->A06);
					$E01 = trim($ilr->aims[$sa]->E01);
		
					if($A06=='01' && $A01!=$E01)
					{
		
						return "E01_1[".$sa."]: Provider Number in ESF Dataset and Learning Aim must be the same \n";
		
					}
				}
			}

			
			private function rule_E02_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A02 = trim($ilr->aims[$sa]->A02);
					$A06 = trim($ilr->aims[$sa]->A06);
					$E02 = trim($ilr->aims[$sa]->E02);
		
					if($A06=='01' && $A02!=$E02)
					{
		
						return "E02_1[".$sa."]: Contract Type in ESF Dataset and Learning Aim must be the same \n";
		
					}
				}
			}

			private function rule_E06_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E06 = trim($ilr->aims[$sa]->E06);
		
					if($A06=='01' && $E06!='01')
					{
		
						return "E06_1[".$sa."]: E06 must be 01 \n";
		
					}
				}
			}

			private function rule_E07_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E07 = trim($ilr->aims[$sa]->E07);
		
					if($A06=='01' && $E07!='00')
					{
		
						return "E07_1[".$sa."]: HE dataset must be 00 \n";
		
					}
				}
			}

			private function rule_E08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						try
						{
							$E08 = new Date($ilr->aims[$sa]->E08);
						}
						catch(Exception $e)
						{
							return "E08_1[".$sa."]: Invalid date started ESF Co-financing \n";
						}
					}
				}
			}

			// rule_E08_3 has been deleted in 2008-09 changes
/*			private function rule_E08_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$d = new Date('01/08/2007');
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						try
						{
							$E08 = new Date($ilr->aims[$sa]->E08);
							if($E08->getDate()<$d->getDate())
								return "E08_3[".$sa."]: Date started ESF Co-financing must not be before 01/08/2007 \n";
		
						}
						catch(Exception $e)
						{
							return "E08_3[".$sa."]: Invalid date started ESF Co-financing \n";
						}
					}
				}
			}*/


			private function rule_E09_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						try
						{
							$E09 = new Date($ilr->aims[$sa]->E09);
						}
						catch(Exception $e)
						{
							return "E09_1[".$sa."]: Invalid planned end date for ESF Co-financing \n";
						}
					}
				}
		
			}


			// Deleted for 2008-09	
/*			private function rule_E09_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$d = new Date('01/08/2007');
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						try
						{
							$E09 = new Date($ilr->aims[$sa]->E09);
							if($E09->getDate()<$d->getDate())
								return "E09_2[".$sa."]: Planned end date for ESF Co-financing must not be before 01/08/2007 \n";
		
						}
						catch(Exception $e)
						{
							return "E09_2[".$sa."]: Invalid planned end date for ESF Co-financing \n";
						}
					}
				}
			} */


			private function rule_E10_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && $ilr->aims[$sa]->E10!='00000000')
					{
						try
						{
							$E10 = new Date($ilr->aims[$sa]->E10);
						}
						catch(Exception $e)
						{
							return "E10_1[".$sa."]: Invalid date ended ESF Co-financing \n";
						}
					}
				}
			}


			// Deleted rule	
/*			private function rule_E10_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$d = new Date('01/08/2007');
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && $ilr->aims[$sa]->E10!='00000000')
					{
						try
						{
							$E10 = new Date($ilr->aims[$sa]->E10);
							if($E10->getDate()<$d->getDate())
								return "E10_2[".$sa."]: Date ended ESF Co-financing must not be before 01/08/2007 \n";
		
						}
						catch(Exception $e)
						{
							return "E10_2[".$sa."]: Invalid date ended ESF Co-financing \n";
						}
					}
				}
			} */



			private function rule_E11_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						if(trim($ilr->aims[$sa]->E11)!='98')
						{
							return "E11_2[".$sa."]: Invalid Industrial sector of learners employer must be 98\n";
						}
					}
				}
			}


			private function rule_E12_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						 
						$query = "select Employment_Status_ESF_Code from ILR_E12_Employ_Status_ESF where Employment_Status_ESF_Code='{$ilr->aims[$sa]->E12}'";
						$E12 = trim(DAO::getSingleValue($linklis, $query));
		
						if($E12!=trim($ilr->aims[$sa]->E12) || $E12=='05')
						{
							return "E12_1[".$sa."]: Invalid Learners employment status on day before starting ESF project \n";
						}
					}
				}
			}


			private function rule_E13_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						 
						if(trim($ilr->aims[$sa]->E13)!='98')
						{
							return "E13_2[".$sa."]: Invalid Learners employment status  \n";
						}
					}
				}
			}

			private function rule_E14_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						$query = "select Length_Unemployment_ESF_Code from ILR_E14_Length_Unemploy_ESF where Length_Unemployment_ESF_Code='{$ilr->aims[$sa]->E14}'";
						$E14 = trim(DAO::getSingleValue($linklis, $query));
		
						if($E14!=trim($ilr->aims[$sa]->E14))
						{
							return "E14_1[".$sa."]: Invalid Length of unemployment before starting ESF project \n";
						}
					}
				}
			}


			private function rule_E15_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						 
						if(trim($ilr->aims[$sa]->E15)!='98')
						{
							return "E15_2[".$sa."]: Invalid Type and size of learners employer \n";
						}
					}
				}
			}

			private function rule_E16a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16a!=''))
					{
						if(trim($ilr->aims[$sa]->E16a)!='')
						{
							return "E16a_2[".$sa."]: Invalid (1) addressing gender sterotype code, Must be space filled \n";
						}
					}
				}
			}

			private function rule_E16b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16b!=''))
					{
						if(trim($ilr->aims[$sa]->E16b)!='')
						{
							return "E16b_2[".$sa."]: Invalid (2) addressing gender sterotype code, Must be space filled \n";
						}
					}
				}
			}

			private function rule_E16c_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16c!=''))
					{
						if(trim($ilr->aims[$sa]->E16c)!='')
						{
							return "E16c_2[".$sa."]: Invalid (3) addressing gender sterotype code, Must be space filled \n";
						}
					}
				}
			}

			private function rule_E16d_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16d!=''))
					{
						 
						if($ilr->aims[$sa]->E16d!=' ')
						{
							return "E16d_2[".$sa."]: Invalid (4) addressing gender sterotype code, Must be space filled \n";
						}
					}
				}
			}

			private function rule_E16e_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16e!=''))
					{
		
						if($ilr->aims[$sa]->E16e!=' ')
						{
							return "E16e_2[".$sa."]: Invalid (5) addressing gender sterotype code, Must be space filled \n";
						}
					}
				}
			}

			private function rule_E17a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E17a = $ilr->aims[$sa]->E17a;
		
					if($A06=='01' && trim($E17a)!='')
					{
						return "E17a_2[".$sa."]: Main co-financing activity 1 must be space filled \n";
					}
				}
			}

			private function rule_E17b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E17b = $ilr->aims[$sa]->E17b;
		
					if($A06=='01' && trim($E17b)!='')
					{
						return "E17b_2[".$sa."]: Main co-financing activity 2 must be space filled \n";
					}
				}
			}

			private function rule_E17c_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E17c = $ilr->aims[$sa]->E17c;
		
					if($A06=='01' && trim($E17c)!='')
					{
						return "E17c_2[".$sa."]: Main co-financing activity 3 must be space filled \n";
					}
				}
			}

			private function rule_E17d_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E17d = $ilr->aims[$sa]->E17d;
		
					if($A06=='01' && trim($E17d)!='')
					{
						return "E17d_2[".$sa."]: Main co-financing activity 4 must be space filled \n";
					}
				}
			}

			private function rule_E17e_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E17e = $ilr->aims[$sa]->E17e;
		
					if($A06=='01' && trim($E17e)!='')
					{
						return "E17e_2[".$sa."]: Main co-financing activity 5 must be space filled \n";
					}
				}
			}

			private function rule_E18a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E18a!=''))
					{
						return "E18a_2[".$sa."]: Invalid 1 delivery mode code, must be space filled \n";
					}
				}
			}
		
			private function rule_E18b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E18b!=''))
					{
						return "E18b_2[".$sa."]: Invalid 2 delivery mode code, must be space filled \n";
					}
				}
			}
		
			private function rule_E18c_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E18c!=''))
					{
							return "E18c_2[".$sa."]: Invalid 3 delivery mode code, must be space filled \n";
					}
				}
			}
		
			private function rule_E18d_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E18d!=''))
					{
							return "E18d_2[".$sa."]: Invalid 4 delivery mode code, must be space filled \n";
					}
				}
			}

			private function rule_E19a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19a!=''))
					{
						return "E19a_2[".$sa."]: Invalid 1 Support measures to be accessed by learner, must be space filled \n";
					}
				}
			}
		
		
			private function rule_E19b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19b!=''))
					{
							return "E19b_2[".$sa."]: Invalid 2 Support measures to be accessed by learner, must be space filled \n";
					}
				}
			}
		
			private function rule_E19c_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19c!=''))
					{
							return "E19c_2[".$sa."]: Invalid 3 Support measures to be accessed by learner, must be space filled \n";
					}
				}
			}
		
			private function rule_E19d_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19d!=''))
					{
							return "E19d_2[".$sa."]: Invalid 4 Support measures to be accessed by learner, must be space filled \n";
					}
				}
			}
		
			private function rule_E19e_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19e!=''))
					{
						return "E19e_2[".$sa."]: Invalid 5 Support measures to be accessed by learner, must be space filled \n";
					}
				}
			}

			private function rule_E20a_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E20a!='98'))
					{
							return "E20a_2[".$sa."]: Invalid 1 Learner background code, must be 98 \n";
					}
				}
			}

			private function rule_E20b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E20b!='98'))
					{
							return "E20b_2[".$sa."]: Invalid 2 Learner background code, must be 98 \n";
					}
				}
			}

			private function rule_E20c_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E20c!='98'))
					{
							return "E20c_2[".$sa."]: Invalid 3 Learner background code, must be 98 \n";
					}
				}
			}

			private function rule_E21_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E21!='00'))
					{
							return "E21_2[".$sa."]: Invalid disability support measure code, must be 00 \n";
					}
				}
			}

			private function rule_E22_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E22 = trim($ilr->aims[$sa]->E22);
		
					if($A06=='01' && $E22!='' && is_int(substr($E22,5,1))==false && substr($E22,5,1)!='L')
					{
		
						return "E22_3[".$sa."]: Invalid project dossier number \n";
		
					}
				}
			}

			private function rule_E22_5(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E22 = trim($ilr->aims[$sa]->E22);
		
					if($A06=='01' && substr($E22,5,1)=='L')
						if( ((int)substr($E22,0,2)<07 || (int)substr($E22,0,2)>15) || (is_int(substr($E22,2,1))==false || is_int(substr($E22,3,1))==false || is_int(substr($E22,4,1))==false) || (substr($E22,6,2)!='EA' && substr($E22,6,2)!='LN' && substr($E22,6,2)!='NE' && substr($E22,6,2)!='NW' && substr($E22,6,2)!='SE' && substr($E22,6,2)!='SW' && substr($E22,6,2)!='WM' && substr($E22,6,2)!='EM' && substr($E22,6,2)!='YH' && substr($E22,6,2)!='ME' && substr($E22,6,2)!='SY' && substr($E22,6,2)!='CO') || ((int)substr($E22,8,1)!=1 && (int)substr($E22,8,1)!=2 && (int)substr($E22,8,1)!=3 && (int)substr($E22,8,1)!=4 && (int)substr($E22,8,1)!=4 && (int)substr($E22,8,1)!=5 && (int)substr($E22,8,1)!=6))
						{
								return "E22_5[".$sa."]: Invalid project dossier number \n";
						}
				}
			}


			private function rule_E23_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E23 = trim($ilr->aims[$sa]->E23);
		
					if($A06=='01' && ( (int)$E23<0 || (int)$E23>999))
					{
						return "E23_2[".$sa."]: Invalid Local Project Number \n";
					}
				}
			}

			private function rule_E24_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E24 = trim($ilr->aims[$sa]->E24);
					$A55 = trim($ilr->aims[$sa]->A55);
		
					if($A06=='01' && $E24!=$A55)
					{
						return "E24_1[".$sa."]: Unique Leaner Number must be same in both learning aim and ESF Co-financing data set \n";
					}
				}
			}

			private function rule_E25_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E25 = trim($ilr->aims[$sa]->E25);
					$A56 = trim($ilr->aims[$sa]->A56);
		
					if($A06=='01' && $E25!=$A56)
					{
						return "E25_2[".$sa."]: UK Provider reference number must be same in both learning aim and ESF Co-financing data set \n";
					}
				}
			}
			

			// Cross Field Validations 
			private function rule_A04_A09_A27_A35_A60_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$query = "select FFA_TYPE_CODE from LEARNING_AIM where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$ffa_type_code = trim(DAO::getSingleValue($linklad, $query));
					$A04 = trim($ilr->aims[$sa]->A04);
					$A35 = trim($ilr->aims[$sa]->A35);
					$A60 = trim($ilr->aims[$sa]->A60);

					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2008');
						
						if($A04=='30' && ($A35=='1' || $A35=='2') && $A60=='' && $ffa_type_code!='X' && $ffa_type_code!='' && $A27>=$d)
						{
							return "A04_A09_A27_A35_A60_LAD_1[".$sa."]: If the learning aim is completed and is a QCF aim on the LAD the credits achieved field must be entered for from 1 August 2008 \n";
						}
					}
					catch(Exception $e)
					{
							return "A04_A09_A27_A35_A60_LAD_1[".$sa."]: If the learning aim is completed and is a QCF aim on the LAD the credits achieved field must be entered for from 1 August 2008 \n";
					}
				}
			}
			
			private function rule_A04_A09_A27_A59_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$query = "select FFA_TYPE_CODE from LEARNING_AIM where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$ffa_type_code = trim(DAO::getSingleValue($linklad, $query));
					$A04 = trim($ilr->aims[$sa]->A04);
					$A59 = trim($ilr->aims[$sa]->A59);
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2008');
						
						if($A04=='30' && ($A59=='' || (int)$A59==0)&& $ffa_type_code!='X' && $ffa_type_code!='' && $A27->getDate()>=$d->getDate())
						{
							return "A04_A09_A59_LAD_1[".$sa."]: If the learning aim is a QCF aim on the LAD, a planned credit value must be entered into the Planned Credit Value field \n";
						}
					}
					catch(Exception $e)
					{
						return "A04_A09_A59_LAD_1[".$sa."]: If the learning aim is a QCF aim on the LAD, a planned credit value must be entered into the Planned Credit Value field \n";
					}
				}
			}
			
			private function rule_A04_A09_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				$A04 = trim($ilr->programmeaim->A04);
				$A09 = trim($ilr->programmeaim->A09);
				
				$query = "select learning_aim_type_code from LEARNING_AIM where LEARNING_AIM_REF='$A09'";
				$learning_aim_type_code = trim(DAO::getSingleValue($linklad, $query));

				if($A04=='35' && $A09!='ZPROG001' && $learning_aim_type_code!='1442' && $learning_aim_type_code!='1443')
				{
					return "A04_A09_LAD_1[".$sa."]: If learning aim is a programme aim, the learning aim reference code must be ZPROG001 or a Diploma (14-19) qualification or Diploma (14-19) Template/Catalogue aim \n";
				}
			}
			
			private function rule_A04_A10_A15_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->programmeaim->A04);
					$A10 = trim($ilr->programmeaim->A10); 
					$A15 = trim($ilr->programmeaim->A15); 
					
					if($A04=='35' && ($A15=='02' || $A15=='03' || $A15=='10') && $A10!='45')
					{
						return "A04_A10_A15_1[".$sa."]: The programme aim for an apprenticeship, advanced apprenticeship or higher level apprenticeship programme should have a funding stream of employer responsive \n";
					}
				}
			}
			
			private function rule_A04_A10_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04);
					$A10 = trim($ilr->aims[$sa]->A10); 
					$A53 = trim($ilr->aims[$sa]->A53); 
					
					if($A04=='30' && $A53=='' && ($A10=='45' || $A10=='46'))
					{
						return "A04_A10_A53_1[".$sa."]: Additional learning needs data must be entered for all aims that are employer responsive funded \n";
					}
				}
			}
			
			private function rule_A04_A15_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04);
					$A15 = trim($ilr->aims[$sa]->A15); 
					
					if($A04=='35' && $A15=='99')
					{
						return "A04_A15_1[".$sa."]: If the learning aim is a programme aim, the programme type should not be 'none of the above' \n";
					}
				}
			}

			private function rule_A04_A31_L39_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				$L39 = $ilr->learnerinformation->L39;
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A04 = trim($ilr->aims[$sa]->A04);
					$A31 = trim($ilr->aims[$sa]->A31); 
					
					if($A04=='30' && $A31=='00000000' && $L39!='95')
					{
						return "A04_A31_L39_1[".$sa."]: For non programme aims, if the learning actual end date is not entered, then the destination must be continuing \n";
					}
				}
			}
			
			private function rule_A04_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A04) != '35' && trim($ilr->aims[$sa]->A53) == '')
					{
						return "A04_A53_1[".$sa."]: If the learning aim is not a programme aim, the additional learning aim needs field, must be completed \n";
					}
				}
			}


			private function rule_A05_L05_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if((int)trim($ilr->aims[$sa]->A05) > (int)trim($ilr->learnerinformation->L05))
					{
						
						throw new Exception(trim($ilr->aims[$sa]->A05) . '-' . trim($ilr->learnerinformation->L05));
						
						return "A05_L05_1[".$sa."]: The data set sequence must be less than or equal to the number of datasets \n";
					}
				}
			}
			
			
			private function rule_A06_A10_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A10)=='70' && trim($ilr->aims[$sa]->A06)=='00')
					{
						return "A06_A10_1[".$sa."]: If LSC Funding Stream is LSC ESF Co-financed, there must be an ESF data set \n";
					}
				}
			}


			private function rule_A06_A10_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A10)=='99' && trim($ilr->aims[$sa]->A06)=='1')
					{
						return "A06_A10_2[".$sa."]: ESF data must not be submitted if there is no LSC funding for the aim \n";
					}
				}
			}


			private function rule_A09_A10_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A09)=='XESF0001' && trim($ilr->aims[$sa]->A10)!='70')
					{
						return "A09_A10_1[".$sa."]: If the learning aim is XESF0001 the LSC funding stream must be 70 LSC ESF co-financed \n";
					}
				}
			}

			
			private function rule_A09_A10_A15_A26_A27_AD05_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
						$sd = new Date('01/08/2008');
						$ed = new Date('31/07/2009');
						
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
	
		
/* Being commented for now until sorted with LSC			
			private function rule_A09_A10_A15_A26_A27_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
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
					
					$query = "select count(*) from FRAMEWORK_AIMS where LEARNING_AIM_REF='$A09' and FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26'";
					$mr1 = DAO::getSingleValue($linklad, $query);
					
					$query = "select count(*) from FRAMEWORK_CMN_COMPONENTS where FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26' and COMMON_COMPONENT_CODE in (select COMMON_COMPONENT_CODE from LEARNING_AIM where LEARNING_AIM_REF='$A09')";
					$mr2 = DAO::getSingleValue($linklad, $query);
					
					if($A04=='30' && ($A10=='45' || $A10=='46') && $A27>=$sd && $A26!='000' && $mr1==0 && $mr2==0)
					{
						return "A09_A10_A15_A26_A27_LAD_1[".$sa."]: For starts on or after 1 August 2005, if framework code is entered, it must match the framework for that learning aim in the LAD, for ER funded provision \n";
					}
				}
			}
*/			
			private function rule_A09_A10_A15_A26_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A04 = trim($ilr->aims[$sa]->A04);
					$A09 = trim($ilr->aims[$sa]->A09);
					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);
					
					$query = "select Framework_Component_Type_Code from FRAMEWORK_AIMS where LEARNING_AIM_REF='$A09' and FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26'";
					$fctc = DAO::getSingleValue($linklad, $query);
					
					if($A10=='45' && ($A15=='02' || $A15=='03' || $A15=='10') && $fctc=='001')
					{
						return "A09_A10_A15_A26_LAD_1[".$sa."]: If the learning aim does have a framework component type of 001 within this framework, then the aim must be an employer responsive apprenticeship main aim \n";
					}
				}
			}
			
			private function rule_A09_A10_A15_A26_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A04 = trim($ilr->aims[$sa]->A04);
					$A09 = trim($ilr->aims[$sa]->A09);
					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);
					
					$query = "select Framework_Component_Type_Code from FRAMEWORK_AIMS where LEARNING_AIM_REF='$A09' and FRAMEWORK_TYPE_CODE='$A15' and FRAMEWORK_CODE='$A26'";
					$fctc = DAO::getSingleValue($linklad, $query);
					
					if($A10=='46' && ($A15=='02' || $A15=='03' || $A15=='10') && ($fctc!='001' || $fctc==''))
					{
						return $fctc . "A09_A10_A15_A26_LAD_2[".$sa."]: If the learning aim does not have a framework component type of 001 within this framework, then the aim cannnot be an employer responsive apprenticeship main aim (A10=46)   \n";
					}
				}
			}

		
			
			private function rule_A09_A10_A15_A27_A46a_A46b_AD04_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
						
						$query = "select LEARNING_AIM_TYPE_CODE from LEARNING_AIM where LEARNING_AIM_REF='$A09'";
						$latc = DAO::getSingleValue($linklad, $query);
						
						if(($A46a!='083' || $A46b!='083' || $latc!='1437') && ($A10=='45' && $A15=='99' && $AD04<2 && $A27->getDate()>=$sd->getDate()))
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
			
			private function rule_A09_A10_A15_AD05_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
						$sd = new Date('01/08/2008');
						$ed = new Date('31/07/2009');
						
						if($A04=='30' && ($A16=='09' || $A16=='10' || $A16=='00') && ($A20=='9' || $A20=='0') && $A27>=$sd && $A27<=$ed)
							$AD05 = 'Y';
						else
							$AD05 = 'N';
												
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						
						$query = "select LSC_EMP_RESP_STATUS_CODE from LSC_EMPLOYER_ANNUAL_VALUES where LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);
						
						if($A10=='45' && $A15=='99' && $AD05=='Y' && ($data=='2' || $data=='3'))
						{
							return "A09_A10_A15_AD05_LAD_1[".$sa."]: Must be a valid learning aim for this year for a new start for employer responsive funded provision for first-time entrants not on a framework   \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_AD05_LAD_1[".$sa."]: Must be a valid learning aim for this year for a new start for employer responsive funded provision for first-time entrants not on a framework   \n";
					}
				}
			}
			
			
			private function rule_A09_A10_A15_AD05_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
						$sd = new Date('01/08/2008');
						$ed = new Date('31/07/2009');
						
						if($A04=='30' && ($A16=='09' || $A16=='10' || $A16=='00') && ($A20=='9' || $A20=='0') && $A27>=$sd && $A27<=$ed)
							$AD05 = 'Y';
						else
							$AD05 = 'N';
												
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						
						$query = "select LSC_EMP_RESP_STATUS_CODE from LSC_EMPLOYER_ANNUAL_VALUES where LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);
						
						if(($A10=='45' || $A10=='46') && $A15!='99' && $AD05=='Y' && $data=='2')
						{
							return "A09_A10_A15_AD05_LAD_2[".$sa."]: Must be a valid learning aim for this year for a new start for employer responsive funded provision for first-time entrants on a framework \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_A15_AD05_LAD_2[".$sa."]: Must be a valid learning aim for this year for a new start for employer responsive funded provision for first-time entrants on a framework \n";
					}
				}
			}
			
			
			private function rule_A09_A10_AD05_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
						$sd = new Date('01/08/2008');
						$ed = new Date('31/07/2009');
						
						if($A04=='30' && ($A16=='09' || $A16=='10' || $A16=='00') && ($A20=='9' || $A20=='0') && $A27>=$sd && $A27<=$ed)
							$AD05 = 'Y';
						else
							$AD05 = 'N';
												
						$A09 = trim($ilr->aims[$sa]->A09);
						$A10 = trim($ilr->aims[$sa]->A10);
						
						$query = "select NON_LSC_FUNDED_STATUS_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='$A09'";
						$data = DAO::getSingleValue($linklad, $query);
						
						if( ($A10=='70' || $A10=='80' || $A10=='99') && $AD05=='Y' && $data=='2')
						{
							return "A09_A10_AD05_LAD_1[".$sa."]: Must be valid learning aim for this year for a new start for provision which is not learner responsive, ASL or employer responsive funded, for first-time entrants on aims for which Programme Entry Route is not collected \n";
						}
					}
					catch(Exception $e)
					{
						return "A09_A10_AD05_LAD_1[".$sa."]: Must be valid learning aim for this year for a new start for provision which is not learner responsive, ASL or employer responsive funded, for first-time entrants on aims for which Programme Entry Route is not collected \n";
					}
				}
			}
			
			private function rule_A09_A10_E22_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if($ilr->aims[$sa]->A06=='01')
					{
						$E22 = trim($ilr->aims[$sa]->E22);
						$A10 = trim($ilr->aims[$sa]->A10);
						$A09 = trim($ilr->aims[$sa]->A09);
		
						$query = "select learning_aim_type_code from learning_aim where learning_aim_ref='{$ilr->aims[$sa]->A09}'";
						$latc = trim(DAO::getSingleValue($linklad, $query));
		
						if( ($latc=='1438' && substr($E22,5,1)!='L') || ($latc=='1438' && $A10!='80'))
						{
							return "A09_A10_E22_LAD_1[".$sa."]: Learning aims categorised as soft outcomes in the LAD are only allowed for new ESF project or funding stream 80 \n";
						}
					}
				}
			}
			
			
			private function rule_A09_A10_LAD_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$A09 = trim($ilr->aims[$sa]->A09);
					
					$query = "select LSC_EMP_RESP_STATUS_CODE from LSC_EMPLOYER_ANNUAL_VALUES where ACADEMIC_YEAR_CODE='0809' and learning_aim_ref='{$ilr->aims[$sa]->A09}'";
					$data = trim(DAO::getSingleValue($linklad, $query));
					
					if(($A10=='45' || $A10=='46') && $data!='1' && $data!='2' && $data!='3')
					{
						return "A09_A10_LAD_6[".$sa."]: Must exist on Employer responsive Annual Values table for year 2008/09 with valid status (1, 2 or 3) for employer responsive funded aims \n";
					}
				}
			}

			
			private function rule_A09_A10_LAD_7(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$A09 = trim($ilr->aims[$sa]->A09);
					
					$query = "select  NON_LSC_FUNDED_STATUS_CODE from ALL_ANNUAL_VALUES where ACADEMIC_YEAR_CODE='0809' and learning_aim_ref='{$ilr->aims[$sa]->A09}'";
					$data = trim(DAO::getSingleValue($linklad, $query));
					
					if(($A10=='70' || $A10=='80' || $A10=='99') && $data!='1' && $data!='2')
					{
						return "A09_A10_LAD_7[".$sa."]: Must exist on All Annual Values table for year 2008/09 with valid status (1 or 2) for aims which are not learner responsive, employer responsive or ASL funded \n";
					}
				}
			}
			

			
			
/*			private function rule_A09_A10_A15_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A15)=='09' && trim($ilr->aims[$sa]->A10)=='40' && trim($ilr->aims[$sa]->A09)!='XE2E0001')
					{
						return "A09_A10_A15_3[".$sa."]: WBL funded E2E programme must use the E2E learning aim code \n";
					}
				}
			}

			
			
			
			private function rule_A09_A10_A15_A24_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = DAO::getSingleValue($linklad, $query);

					if($flag11!='Y' && trim($ilr->aims[$sa]->A10)=='40' && (trim($ilr->aims[$sa]->A15)=='02' || trim($ilr->aims[$sa]->A15)=='03' || trim($ilr->aims[$sa]->A15)=='04' || trim($ilr->aims[$sa]->A15)=='05' || trim($ilr->aims[$sa]->A15)=='06' || trim($ilr->aims[$sa]->A15)=='07' || trim($ilr->aims[$sa]->A15)=='10') && trim($ilr->aims[$sa]->A24)=='0000')
					{
						return "A09_A10_A15_A24_LAD_1[".$sa."]: Occupation relating to learning aim must be entered for WBL funded Advanced Apprenticeship, Apprenticeship, Higher Apprenticeship, and NVQ main aims. Except for framework completion class codes\n";
					}
				}
			}


			private function rule_A09_A10_A15_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select NOTIONAL_NVQ_LEVEL_CODE from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$nvlc = DAO::getSingleValue($linklad, $query);

					if(trim($ilr->aims[$sa]->A10)=='40' && ((trim($ilr->aims[$sa]->A15)=='04' && trim($nvlc)!='1') || (trim($ilr->aims[$sa]->A15)=='05' && trim($nvlc!='2')) || (trim($ilr->aims[$sa]->A15=='06') && trim($nvlc!='3'))))
					{
							return "A09_A10_A15_LAD_1[".$sa."]: Programme type must be consistent with LAD level for NVQ level 1 to 3 Learning for funded aims\n";
					}
				}
			}

 
			private function rule_A09_A10_A15_A24_LAD_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query1 = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$query2 = "select NOTIONAL_NVQ_LEVEL_CODE from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = DAO::getSingleValue($linklad, $query1);
					$nvlc = DAO::getSingleValue($linklad, $query2);

					if(trim($flag11)!='Y')
					{
						return "A09_A10_A15_A24_LAD_3[".$sa."]: Occupation relating to learning aim must be entered for WBL funded Advanced Apprenticeship, Apprenticeship, Higher Apprenticeship, and NVQ main aims. Except for framework completion class codes \n";
					}
				}
			}



			private function rule_A09_A10_A16_A27_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select WBL_ANNUAL_VALUE_STATUS_CODE from WBL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$avsc = DAO::getSingleValue($linklad, $query);

					$A10 = trim($ilr->aims[$sa]->A10);
					$A16 = trim($ilr->aims[$sa]->A16);
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2007');
						$ed = new Date('31/07/2008');
						if( ($A10=='40' || $A10=='41' || $A10=='42') && ($A16=='09' || $A16='10') && $A27->getDate() >= $sd->getDate() && $A27->getDate()<=$ed->getDate() && trim($avsc)=='2')
						{
								return "A09_A10_A16_A27_LAD_2[".$sa."]: Must be valid learning aim for this year for a new start for WBL-funded provision for first-time entrants \n";
						}
					}
					catch(Exception $e)
					{
								return "A09_A10_A16_A27_LAD_2[".$sa."]: Must be valid learning aim for this year for a new start for WBL-funded provision for first-time entrants \n";

					}
				}
			}

			private function rule_A09_A10_A16_A27_LAD_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select NON_LSC_FUNDED_STATUS_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$nlfsc = DAO::getSingleValue($linklad, $query);

					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A16 = trim($ilr->aims[$sa]->A16);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2007');
						$ed = new Date('31/07/2008');
						if( ($A10=='60' || $A10=='70' || $A10=='80'|| $A10=='99') && ($A16=='09' || $A16=='10'|| $A16=='00') && $A27->getDate() >= $sd->getDate() && $A27->getDate()<=$ed->getDate() && $nlfsc=='2')
						{
								return "A09_A10_A16_A27_LAD_3[".$sa."]: Must be valid learning aim for this year for a new start for provision which is not FE-, ACL- or WBL-funded, for first-time entrants on aims for which Programme Entry Route is not collected \n";
						}
					}
					catch(Exception $e)
					{
								return "A09_A10_A16_A27_LAD_3[".$sa."]: Must be valid learning aim for this year for a new start for provision which is not FE-, ACL- or WBL-funded, for first-time entrants on aims for which Programme Entry Route is not collected \n";
					}
				}
			}

			private function rule_A09_A10_A26_A27_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select SECTOR_FRAMEWORK_CODE from SECTOR_FRAMEWORK_AIMS where LEARNING_AIM_REF='{$ilr->aims[0]->A09}' AND SECTOR_FRAMEWORK_CODE='{$ilr->aims[$sa]->A26}'";
					$sfc = DAO::getSingleValue($linklad, $query);
					$query = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = DAO::getSingleValue($linklad, $query);


					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A26 = trim($ilr->aims[$sa]->A26);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2005');
						$ed = new Date('31/07/2008');
						if( $A27->getDate()>=$sd->getDate() && trim($flag11)!='Y' && ($A10=='40' || $A10=='41') && $A26!='000' && trim($sfc)!=$A26)
						{
								return "A09_A10_A26_A27_LAD_1[".$sa."]: For starts on or after 1 August 2005, if sector framework code is entered, it must match the framework for that learning aim in the LAD for funded NVQs, employer approved schemes and technical certificates\n";
						}
					}
					catch(Exception $e)
					{
							return "A09_A10_A26_A27_LAD_1[".$sa."]: For starts on or after 1 August 2005, if sector framework code is entered, it must match the framework for that learning aim in the LAD for funded NVQs, employer approved schemes and technical certificates\n";
					}
				}
			}

			private function rule_A09_A10_A27_A46a_A46b_LAD_8(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select TTG_ANNUAL_VAL_STATUS_CODE from TTG_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$tavsc = DAO::getSingleValue($linklad, $query);

					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
						$sd = new Date('01/08/2007');
						$ed = new Date('31/07/2008');
						if($A10='60' && $A27->getDate()>=$sd->getDate() && $A27->getDate()<=$ed->getDate() && ($A46a!='63' && $A46b!='63') && trim($tavsc=='2') )
						{
								return "A09_A10_A27_A46a_A46b_LAD_8[".$sa."]: Must be valid learning aim for this year for a new start for TtG-funded provision\n";
						}
					}
					catch(Exception $e)
					{
								return "A09_A10_A27_A46a_A46b_LAD_8[".$sa."]: Must be valid learning aim for this year for a new start for TtG-funded provision\n";
					}
				}
			}

			private function rule_A09_A10_A27_LAD_7(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$query = "select ACL_ANNUAL_VAL_STATUS_CODE from ACL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$aavsc = DAO::getSingleValue($linklad, $query);

					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$sd = new Date('01/08/2007');
						$ed = new Date('31/07/2008');
						if($A10='10' && $A27->getDate()>=$sd->getDate() && $A27->getDate()<=$ed->getDate() && trim($aavsc)=='2')
						{
								return "A09_A10_A27_LAD_7[".$sa."]: Must be valid learning aim for this year for a new start for ACL-funded provision\n";
						}
					}
					catch(Exception $e)
					{
							return "A09_A10_A27_LAD_7[".$sa."]: Must be valid learning aim for this year for a new start for ACL-funded provision\n";
					}
				}
			}

			private function rule_A09_A10_A46a_A46b_LAD_10(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select TTG_ANNUAL_VAL_STATUS_CODE from TTG_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$tavsc = DAO::getSingleValue($linklad, $query);

					$A10 = trim($ilr->aims[$sa]->A10);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);

					if($A10=='60' && $A46a!='63' && $A46b!='63' && (trim($tavsc)!='1' && trim($tavsc)!='2'))
					{
							return "A09_A10_A46a_A46b_LAD_10[".$sa."]: Must exist on Train to Gain Annual Values table for year 2007/08 with valid status (1 or 2) for TtG-funded aims\n";
					}
				}
			}

			private function rule_A09_A10_LAD_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select WBL_ANNUAL_VALUE_STATUS_CODE from WBL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$wavsc = DAO::getSingleValue($linklad, $query);

					$A10 = trim($ilr->aims[$sa]->A10);

					if( ($A10=='40' || $A10=='41' || $A10=='42') && ($wavsc!='1' && $wavsc!='2'))
					{
							return "A09_A10_LAD_6[".$sa."]: Must exist on WBL Annual Values table for year 2007/08 with valid status (1 or 2) for WBL-funded aims\n";
					}
				}
			}

			private function rule_A09_A10_LAD_7(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select NON_LSC_FUNDED_STATUS_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$nlfsc = trim(DAO::getSingleValue($linklad, $query));

					$A10 = trim($ilr->aims[$sa]->A10);

					if( ($A10=='60' || $A10=='70' || $A10=='80' || $A10=='99') &&  ($nlfsc!='1' && $nlfsc!='2'))
					{
							return "A09_A10_LAD_7[".$sa."]: Must exist on All Annual Values table for year 2007/08 with valid status (1 or 2) for aims which are not FE-, WBL- or ACL-funded\n";
					}
				}
			}

			private function rule_A09_A10_LAD_8(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select TECHNICAL_CERTIFICATE from WBL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$tc = trim(DAO::getSingleValue($linklad, $query));

					$A10 = trim($ilr->aims[$sa]->A10);

					if($A10=='41' && ($tc!='1') )
					{
							return "A09_A10_LAD_8[".$sa."]: If funding for a technical certificate is being claimed the learning aim must be a technical certificate\n";
					}
				}
			}

			private function rule_A09_A10_LAD_9(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select KEY_SKILL_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0809'";
					$ksc = trim(DAO::getSingleValue($linklad, $query));

					$A10 = trim($ilr->aims[$sa]->A10);

					if($A10=='42' && ($ksc!='1' && $ksc!='3'))
					{
							return "A09_A10_LAD_9[".$sa."]: If funding for a key skill is being claimed the learning aim must be a key skill \n";
					}
				}
			}

			private function rule_A09_A15_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A09)=='XE2E0001' && trim($ilr->aims[$sa]->A15)!='09')
					{

						return "A09_A15_3[".$sa."]: When an E2E learning aim code of XE2E0001 is used, the programme type must be E2E. \n";

					}
				}
			}


			private function rule_A09_A15_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = trim(DAO::getSingleValue($linklad, $query));

					$A15 = trim($ilr->aims[$sa]->A15);
					if($flag11=='Y' && ($A15!='02' && $A15!='03' && $A15!='10'))
					{
						return "A09_A15_LAD_1[".$sa."]: If a framework completion class code is used then the programme type must be Advanced Apprenticeship, Apprenticeship or Higher Apprenticeship\n";
					}
				}
			}


				private function rule_A09_A32_A46a_A46b_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
				{
					for($sa=0;$sa<=$ilr->subaims;$sa++)
					{
	
						$query = "select Learning_aim_type_code from LEARNING_AIM where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
						$latc = DAO::getSingleValue($linklad, $query);
						
						$A32 = trim($ilr->aims[$sa]->A32);
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
	
						if($A32=='00000' &&  (($A46a=='027' || $A46a=='083') || ($A46b=='027' || $A46b=='083') || $latc=='1437'))
						{
								return "A09_A32_A46a_A46b_LAD_1[".$sa."]: If the learning aim is delivered under the Employability Skills Programme or is the Employability Award the guided learning hours must not be 00000 \n";
						}
					}
				}
*/

				private function rule_A09_A32_A46a_A46b_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
				{
					
					for($sa=0;$sa<=$ilr->subaims;$sa++)
					{

						$query = "select Learning_aim_type_code from LEARNING_AIM where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
						$latc = DAO::getSingleValue($linklad, $query);
	
						$A32 = trim($ilr->aims[$sa]->A32);
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
	
						if($A32!='00000' &&  ( ($A46a!='27' && $A46a!='83') && ($A46b!='27' && $A46b!='83') && $latc!='1437'))
						{
								return "A09_A32_A46a_A46b_LAD_2[".$sa."]: If the learning aim is not delivered under the Employability Skills Programme or is not the Employability Award then the guided learning hours must be zero  00000\n";
						}
					}
				}


				private function rule_A09_A36_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
				{
			
					for($sa=0;$sa<=$ilr->subaims;$sa++)
					{
						$query = "select BASIC_SKILLS_DIAG_TEST from LEARNING_AIM where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
						$data = trim(DAO::getSingleValue($linklad, $query));
						
						$A36 = trim($ilr->aims[$sa]->A36);

						if($data=='Y' && ($A36=='LN' || $A36=='L2' || $A36=='L1' || $A36=='E3' || $A36=='E2' || $A36=='E1'))
						{
							return "A09_A36_LAD_1[".$sa."]: If learning aim is not 'basic skills diagnostic assessment', the learning outcome grade cannot be one of the grades reserved for such aims \n";
						}
					}
				}


				private function rule_A09_A46a_A46b_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
				{
					for($sa=0;$sa<=$ilr->subaims;$sa++)
					{
	
						$query = "select SKILLS_FOR_LIFE_TYPE_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
						$sfltc = trim(DAO::getSingleValue($linklad, $query));
						
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
						if( ($A46a=='030' || $A46b=='030')  && ($sfltc!='1' && $sfltc!='2' && $sfltc!='3' && $sfltc!='5' && $sfltc!='6' && $sfltc!='7' && $sfltc!='8' && $sfltc!='9' && $sfltc!='10'))
						{
							return "A09_A46a_A46b_LAD_1[".$sa."]: If the aim is delivered under the WBL basic skills project or the National Employers Basic Skills project the learning aim must be a basic skill eligible for funding under these projects \n";
						}
					}
				}


				private function rule_A09_A46a_A46b_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
				{
					for($sa=0;$sa<=$ilr->subaims;$sa++)
					{
	
						$query = "select SKILLS_FOR_LIFE_TYPE_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' and academic_year_code='0809'";
						$sfltc = trim(DAO::getSingleValue($linklad, $query));
						
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
						if( ($A46a=='083' || $A46b=='083') && ($sfltc!='01' && $sfltc!='02' && $sfltc!='03' && $sfltc!='05' && $sfltc!='06' && $sfltc!='07' && $sfltc!='08' && $sfltc!='09' && $sfltc!='10'))
						{
							return "A09_A46a_A46b_LAD_2[".$sa."]: If the aim is delivered under the Employability Skills Project the learning aim must be a basic skill eligible for funding under these projects\n";
						}
					}
				}

				private function rule_A09_A46a_A46b_LAD_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
				{
					for($sa=0;$sa<=$ilr->subaims;$sa++)
					{
	
						$query = "select NOTIONAL_NVQ_LEVEL_CODE from LEARNING_AIM where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
						$nvlc = trim(DAO::getSingleValue($linklad, $query));
						
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
						if( ($A46a=='082' || $A46b=='082') && ($nvlc!='3'))
						{
							return "A09_A46a_A46b_LAD_3[".$sa."]: If the learner is coded as learner account then the learning aim must be level 3 \n";
						}
					}
				}

//Warning only

/*				private function rule_A09_L35_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
				{
					for($sa=0;$sa<=$ilr->subaims;$sa++)
					{
	
						$L35 = $ilr->learnerinformation->L35;
						$query = "select count(*) from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' and (LEVEL2_ENTITLEMENT_CAT_CODE='1' OR LEVEL3_ENTITLEMENT_CAT_CODE='1')";
						$v = trim(DAO::getSingleValue($linklad, $query));
						
						if( ($L35=='97' || $L35=='98') && $v>0 )
						{
							return "A09_L35_LAD_1[".$sa."]:  If the learning aim is a full level 2 or full level 3 then the prior attainment should not be not known or other qualification level not known \n";
						}
					}
				}
*/				
				
			private function rule_A10_A15_A16_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A10_A15_A18_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A18 = trim($ilr->aims[$sa]->A18);

					if($A10=='45' && $A15=='99' && ($A18!='22' && $A18!='23' && $A18!='24'))
					{

						return "A10_A15_A18_1[".$sa."]: If the funding stream is employer responsive and learning aim is not part of an apprenticeship then the main delivery method should be set to code 22 or 23 or 24 \n";

					}
				}
			}
			
			private function rule_A10_A15_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			
			private function rule_A10_A15_A27_A54_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A54 = trim($ilr->aims[$sa]->A54);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('01/08/2008');
						if($A10=='45' && $A15=='99' && $A27->getDate()>$ed->getDate() && $A54=='')
						{

							return "A10_A15_A27_A54_1[".$sa."]: If learning aim is employer responsive funded and is not an apprenticeship and started after 1 August 2008  it must have a valid broker contract id or be [not Brokered]  \n";

						}
					}
					catch(Exception $e)
					{
						return "A10_A15_A27_A54_1[".$sa."]: If learning aim is employer responsive funded and is not an apprenticeship and started after 1 August 2008  it must have a valid broker contract id or be [not Brokered]  \n";
					}
				}
			}

			
			private function rule_A10_A15_A27_AD03_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=0;$sa++)
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
						
					if(trim($A44)=='')
						$AD03 = 'T';	

					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('01/08/2008');
						
						if($A10=='45' && $A15=='99' && $A27->getDate()>= $ed->getDate() && $AD03=='T')
						{
							return "A10_A15_A27_AD03_1[".$sa."]: If learning aim is employer responsive funded and is not an apprenticeship and started after 1 August 2008  it must have a valid EDRS number \n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A15_A27_AD03_1[".$sa."]: If learning aim is employer responsive funded and is not an apprenticeship and started after 1 August 2008  it must have a valid EDRS number \n";
					}
				}
			}


			private function rule_A10_A16_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


/*			private function rule_A10_A16_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A16 = trim($ilr->aims[$sa]->A16);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('31/07/2004');
						if( ($A10='41' || $A10=='42') && $A16=='00' && $A27->getDate()>$ed->getDate())
						{

							return "A10_A16_A27_1[".$sa."]: Programme entry route must be entered for subsiduary aims funded as part of a framework for start after 31 July 2004. \n";

						}
					}
					catch(Exception $e)
					{
						return "A10_A16_A27_1[".$sa."]: Programme entry route must be entered for subsiduary aims funded as part of a framework for start after 31 July 2004. \n";

					}

				}
			}


			private function rule_A10_A18_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A18 = trim($ilr->aims[$sa]->A18);

					if($A10=='60' && ($A18!='22' && $A18!='23'))
					{

						return "A10_A18_1[".$sa."]: If the funding stream is Train to Gain then the main delivery method should be set to code 22 or 23 \n";

					}
				}
			}

			private function rule_A10_A18_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A18 = trim($ilr->aims[$sa]->A18);

					if($A10=='60' && $A18!='00')
					{

						return "A10_A18_2[".$sa."]: If the funding stream is not train to gain then the delivery method must be null \n";

					}
				}
			}

*/

			private function rule_A10_A24_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A24 = trim($ilr->aims[$sa]->A24);

					if($A10=='46' && ($A24=='0000' || $A24==''))
					{
						return "A10_A24_2[".$sa."]: Occupation relating to learning aim must be entered for Advanced Apprenticeship, Apprenticeship, Higher Apprenticeship main aims. \n";
					}
				}
			}

			private function rule_A10_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A10_A27_A44(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A44 = trim($ilr->aims[$sa]->A44);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2008');

						if($A10=='46' && $A27->getDate()>$check->getDate() && $A44=='')
						{
							return "A10_A27_A44[".$sa."]: If learning aim is employer responsive funded and is a main aim of an apprenticeship programme and started after 1 August 2008 the employer identifier must be completed\n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A27_A44[".$sa."]: If learning aim is employer responsive funded and is a main aim of an apprenticeship programme and started after 1 August 2008 the employer identifier must be completed\n";
					}
				}
			}
			
/*			private function rule_A10_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2006');

						if($A10=='60' && $A27->getDate()<$check->getDate())
						{

							return "A10_A27_2[".$sa."]: If start date is before 1 August 2006 then the funding stream cannot be train to Gain\n";

						}
					}
					catch(Exception $e)
					{
						return "A10_A27_2[".$sa."]: If start date is before  1 August 2006 then the funding stream cannot be train to Gain\n";

					}
				}
			}
*/

			private function rule_A10_A27_L11_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$L11 = new Date($ilr->learnerinformation->L11);
						$A27->subtractYears(12);
						
						if($A27->getDate()<$L11->getDate() && $A10!='45' && $A10!='46')
						{
							return "A10_A27_L11_2[".$sa."]: Must be more than 12 years old at start for non WBL funded learning aims \n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A27_L11_2[".$sa."]: Must be more than 12 years old at start for non WBL funded learning aims \n";
					}
				}
			}

			private function rule_A10_A27_L11_5(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			
			private function rule_A10_A27_T08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$T08 = new Date(date('d/m/Y')); 
												
						if($A10=='46' && $A27->getDate()>= $T08->getDate())
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
			
			
			private function rule_A10_A31_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A31 = trim($ilr->aims[$sa]->A31);
					$A50 = trim($ilr->aims[$sa]->A50);
					
					if(($A10=='45' || $A10=='46') && $A31=='00000000' && $A50!='96')
					{

						return "A10_A31_A50_1[".$sa."]: If there is no learning actual end date then Reason Learning Ended must be continuing for employer responsive funded aims \n";

					}
				}
			}


			private function rule_A10_E08_E12_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						try
						{
							$A10 = trim($ilr->aims[$sa]->A10);
							$E08 = new Date($ilr->aims[$sa]->E08);
							$E12 = trim($ilr->aims[$sa]->E12);
							$d = new Date('31/07/2004');
		
							if($A10=='70' && $E08->getDate()>$d->getDate() && $E12=='98')
							{
								return "A10_E08_E12_1[".$sa."]: For ESF funded aims which start after 31/07/2004 the employment status on day before starting ESF project must not be not known not provided \n";
							}
						}
						catch(Exception $e)
						{
							return "A10_E08_E12_1[".$sa."]: For ESF funded aims which start after 31/07/2004 the employment status on day before starting ESF project must not be not known not provided \n";
						}
					}
				}
			}

			
			private function rule_A10_E12_E14_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						try
						{
							$A10 = trim($ilr->aims[$sa]->A10);
							$E12 = trim($ilr->aims[$sa]->E12);
							$E14 = trim($ilr->aims[$sa]->E14);
							
							if($A10=='70' && $E12=='04' && $E14=='98')
							{
								return "A10_E12_E14_2[".$sa."]: For ESF funded aims the Length of Unemployment must not be 'Not Known/Not Provided' if Employment Status at start is 'Unemployed' \n";
							}
						}
						catch(Exception $e)
						{
							return "A10_E12_E14_2[".$sa."]: For ESF funded aims the Length of Unemployment must not be 'Not Known/Not Provided' if Employment Status at start is 'Unemployed' \n";
						}
					}
				}
			}
			
			
			private function rule_A10_E22_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$E22 = trim($ilr->aims[$sa]->E22);
		
						if($A10=='70' && $E22=='')
						{
		
							return "A10_E22_1[".$sa."]: If the funding stream is 70 then the project dossier number must be entered \n";
		
						}
					}
				}
			}
		
			private function rule_A10_E23_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$E23 = trim($ilr->aims[$sa]->E23);
		
						if($A10=='70' && $E23=='000')
						{
		
							return "A10_E23_1[".$sa."]: If the funding stream is 70 then the local project number must be entered \n";
		
						}
					}
				}
			}
			
			
			//TODO  A10_L01_L25_ER_LR_1 this rule needs to be coded but at the moment LR and ER ini the rule are not understandable
			
			
/*			private function rule_A10_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);

					if($A10=='60' && ($A46a=='64' || $A46b=='64'))
					{

						return "A10_A46a_A46b_1[".$sa."]: If the learning aim is train to gain badged then it cannot be train to gain funded\n";

					}
				}
			}


			private function rule_A10_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A50 = trim($ilr->aims[$sa]->A50);

					if( ($A10=='40' || $A10=='41' || $A10=='42') && $A50=='00')
					{

						return "A10_A50_1[".$sa."]: Reason learning ended must be completed for all WBL funded aims\n";

					}
				}
			}


			private function rule_A10_A54_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					$A54 = trim($ilr->aims[$sa]->A54);

					if($A10=='60' || ($A46a=='64' || $A46b=='64') && $A54=='')
					{

						return "A10_A54_A46a_A46b_1[".$sa."]: If the funding stream is TtG or the aim is Ttg badged it must have a valid broker contract id or be 'not Brokered'  \n";

					}
				}
			}


			private function rule_A10_L35_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					$L35 = trim($ilr->learnerinformation->L35);

					if($A10=='60' && ($L35=='02' || $L35=='03' || $L35=='04' || $L35=='05') && ($A46a!='63' && $A46b!='63'))
					{

						return "A10_L35_A46a_A46b_1[".$sa."]: If the funding stream is train to gain then the learner prior attainment must not be level 2 or above unless the learning aim is part of the train to gain level 3 pilot  \n";

					}
				}
			}


			private function rule_A10_L35_A46a_A46b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					$L35 = trim($ilr->learnerinformation->L35);

					if($A10=='60' && ($L35=='03' || $L35=='04' || $L35=='05') && ($A46a=='63' || $A64b=='63'))
					{

						return "A10_L35_A46a_A46b_2[".$sa."]: If the funding stream is train to gain and the learning aim is part of the train to gain level 3 pilots then the learner prior attainment must not be level 3 or above  \n";

					}
				}
			}
*/

			private function rule_A14_L11_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
					
						$L11 = new Date($ilr->learnerinformation->L11);
						$A14 = $ilr->aims[$sa]->A14;
						$d = new Date('01/08/1943');
						
						if($L11->getDate()<=$d->getDate() && $A14=='15')
						{
							return "A14_L11_1[".$sa."]: Reason for full funding/co-funding of learning aim must not be Job seeker’s allowance if the learner is 65 or over at the 1st of August of the training year. \n";
						}
					}
					catch(Exception $e)
					{
						return "A14_L11_1[".$sa."]: Reason for full funding/co-funding of learning aim must not be Job seeker’s allowance if the learner is 65 or over at the 1st of August of the training year. \n";
					}
				}
			}

			private function rule_A14_L11_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$L11 = new Date($ilr->learnerinformation->L11);
						$A14 = $ilr->aims[$sa]->A14;
						$d = new Date('01/08/1985');
						
						if($L11->getDate()<=$d->getDate() && $A14=='15')
						{
							return "A14_L11_2[".$sa."]: Reason for full funding/co-funding of learning aim must not be ‘16-18 year old learner’, if the learner is 23 or over at the 1st August of the training year. \n";
						}
					}
					catch(Exception $e)
					{
						return "A14_L11_2[".$sa."]: Reason for full funding/co-funding of learning aim must not be ‘16-18 year old learner’, if the learner is 23 or over at the 1st August of the training year. \n";
					}
				}
			}
			
			private function rule_A15_A16_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A16 = trim($ilr->aims[$sa]->A16);

					if(($A15=='02' || $A15=='03' || $A15=='10') && $A16=='10')
					{

						return "A15_A16_3[".$sa."]: If the programme type is Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship the programme entry route must not be first time entrant on other WBL programme \n";

					}
				}
			}


			private function rule_A15_A16_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A16 = trim($ilr->aims[$sa]->A16);

					if(($A15!='02' && $A15!='03' && $A15!='10') && $A16=='09')
					{

						return "A15_A16_4[".$sa."]: If the programme type is not Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship then the programme entry route must not be first time entrant on Apprenticeship or Advanced Apprenticship \n";

					}
				}
			}


			private function rule_A15_A16_5(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A16 = trim($ilr->aims[$sa]->A16);

					if(($A15!='02' && $A15!='10') && ($A16=='03' || $A16=='15'))
					{

						return "A15_A16_5[".$sa."]: If programme entry route is 'progress to advanced apprenticeship from young apprenticeship' or 'progress to advanced apprenticeship from programme led pathway delivered in FE' the programme type should be advanced apprenticeship or higher level apprenticeship \n";

					}
				}
			}


			private function rule_A15_A16_6(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A16 = trim($ilr->aims[$sa]->A16);

					if($A15!='03' && ($A16=='13' || $A16=='14'))
					{

						return "A15_A16_6[".$sa."]: If programme entry route is 'progress to apprenticeship from young apprenticeship' or 'progress to apprenticeship from programme led pathway delivered in FE' the programme type should be apprenticeship \n";

					}
				}
			}
			
			private function rule_A15_A18_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A18 = trim($ilr->aims[$sa]->A18);

					if( ($A15=='02' || $A15=='03' || $A15=='10') && $A18!='00')
					{
						return "A15_A18_2[".$sa."]: If the learning aim is part of an apprenticeship framework then the delivery method must be null \n";
					}
				}
			}
			

			private function rule_A15_A26_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);

					if($A15!='09' && $A15!='99' && $A26=='000')
					{
						return "A15_A26_3[".$sa."]: Framework code must be entered for all learning aims that are part of an Apprenticeship, Advanced Apprenticeship, Higher level Apprenticeship or diploma programme \n";
					}
				}
			}
			

			private function rule_A15_A26_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);

					if($A15=='99' && $A26!='000' && $A26!='')
					{
						return "A15_A26_4[".$sa."]: Framework code must not be entered if the programme type is 99. \n";
					}
				}
			}
			
			private function rule_A15_A50_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A50 = trim($ilr->aims[$sa]->A50);

					if( ($A15=='02' || $A15=='03' || $A15=='10') && $A50=='00')
					{
						return "A15_A50_2[".$sa."]: If programme type is apprenticeship, advanced apprenticeship or higher apprenticeship the reason learning ended must be completed \n";
					}
				}
			}
			
/*			private function rule_A15_A16_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A15 = trim($ilr->aims[$sa]->A15);
						$A16 = trim($ilr->aims[$sa]->A16);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2007');

						if($A27->getDate()>=$check->getDate() && ($A15=='02' || $A15=='03' || $A15=='10') && $A16=='00')
						{

							return "A15_A16_A27_1[".$sa."]: If the start date is on or after 1 August 2007 and the programme type is Apprenticeship, Advanced apprenticeship or Higher Level Apprenticeship the programme entry route must be entered \n";

						}
					}
					catch(Exception $e)
					{
						return "A15_A16_A27_1[".$sa."]: If the start date is on or after 1 August 2007 and the programme type is Apprenticeship, Advanced apprenticeship or Higher Level Apprenticeship the programme entry route must be entered \n";

					}
				}
			}


			private function rule_A15_A26_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);

					if(($A15!='02' && $A15!='03' && $A15!='10') && $A26!='000')
					{

						return "A15_A26_2[".$sa."]: Sector code must not be entered if the programme type not = Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship \n";

					}
				}
			}


			private function rule_A15_A26_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A15 = trim($ilr->aims[$sa]->A15);
						$A26 = trim($ilr->aims[$sa]->A26);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('31/07/2004');

						if(($A15=='02' || $A15=='03' || $A15=='10') && $A26=='000' && $A27->getDate()>$check->getDate())
						{

							return "A15_A26_A27_1[".$sa."]: Sector code must be entered for subsiduary aims if the programme type is Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship for starts after 31 July 2004\n";

						}
					}
					catch(Exception $e)
					{
						return "A15_A26_A27_1[".$sa."]: Sector code must be entered for subsiduary aims if the programme type is Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship for starts after 31 July 2004\n";

					}
				}
			}


			private function rule_A15_A43_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A43 = trim($ilr->aims[$sa]->A43);

					if($A43!='00000000' && ($A15!='02' && $A15!='03' && $A15!='10'))
					{

						return "A15_A43_1[".$sa."]: Sector Framework Achievement Date must only be entered for programme types Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship \n";

					}
				}
			}


			private function rule_A15_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A50 = trim($ilr->aims[$sa]->A50);

					if( ($A50=='20' || $A50=='21' || $A50=='23' || $A50=='24') && $A15!='09')
					{

						return "A15_A50_1[".$sa."]: To progress from E2E you must be in E2E \n";

					}
				}
			}


			private function rule_A15_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A15 = trim($ilr->aims[$sa]->A15);
					$A53 = trim($ilr->aims[$sa]->A53);

					if( ($A15!='02' && $A15!='03' && $A15!='04' && $A15!='05' && $A15!='06' && $A15!='07' && $A15!='10') && $A53!='00')
					{

						return "A15_A53_1[".$sa."]: If programme type is not Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship or NVQ learning, Additional learning needs data must not be entered.\n";

					}
				}
			}
*/

			private function rule_A16_A27(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
							return "A16_A27[".$sa."]: If the start date is on or after 1 August 2007 then the programme entry route cannot be progress to NVQ level 3 from NVQ level 2 \n";
						}
					}
					catch(Exception $e)
					{
						return "A16_A27[".$sa."]: If the start date is on or after 1 August 2007 then the programme entry route cannot be progress to NVQ level 3 from NVQ level 2 \n";
					}
				}
			}

			private function rule_A16_AD06(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
							return "A16_AD06[".$sa."]: the programme entry type must be not be direct for programmes starting after 1 August 2003 \n";
						}
					}
					catch(Exception $e)
					{
						return "A16_AD06[".$sa."]: the programme entry type must be not be direct for programmes starting after 1 August 2003 \n";
					}
				}
			}
			
			
			private function rule_A23_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A23 = trim($ilr->aims[$sa]->A23);
						$A10 = $ilr->aims[$sa]->A10;
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2008');

						if( ($A10=='45' || $A10=='46') && $A23 == '' && $A27->getDate() >= $d->getDate())
						{
							return "A23_A27_1[".$sa."]: The delivery location postcode must be entered for all employer responsive aims from 1 August 2008 \n";
						}
					}
					catch(Exception $e)
					{
						return "A23_A27_1[".$sa."]: The delivery location postcode must be entered for all employer responsive aims from 1 August 2008 \n";
					}
				}
			}
			
			private function rule_A27_A28_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A27_A28_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A28 = new Date($ilr->aims[$sa]->A28);
						$A27->addYears(20);

						if($A28->getDate()>$A27->getDate())
						{

							return "A27_A28_2[".$sa."]: Planned end date must be less than 20 years after the start date \n";

						}
					}
					catch(Exception $e)
					{
						return "A27_A28_2[".$sa."]: Planned end date must be less than 20 years after the start date \n";

					}
				}
			}
			
			private function rule_A27_A28_A32_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A28 = new Date($ilr->aims[$sa]->A28);
						$A28->addDays(1);
						$A32 = trim($ilr->aims[$sa]->A32);

						if((int)$A32>60 && ((int)$A32 / ( (int)$A28->getDate()  - (int)$A27->getDate() ) / 86400) > 24 )
						{

							return "A27_A28_A32_1[".$sa."]: If the GLH is greater than 60, then the value divided by the number of days of study must not be greater than 24.\n";

						}
					}
					catch(Exception $e)
					{
						return "A27_A28_A32_1[".$sa."]: If the GLH is greater than 60, then the value divided by the number of days of study must not be greater than 24.\n";

					}
				}
			}


			private function rule_A27_A31_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			

			private function rule_A27_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

								return "A27_A40_1[".$sa."]: Achievement date must be greater than or equal to the start date if entered \n";

							}
						}
					}
					catch(Exception $e)
					{
							return "A27_A40_1[".$sa."]: Achievement date must be greater than or equal to the start date if entered \n";
					}
				}
			}

/*
			private function rule_A27_A43_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A43s = trim($ilr->aims[$sa]->A43);

						if($A43s!='00000000')
						{
							$A43d = new Date($ilr->aims[$sa]->A43);
							if($A43d->getDate() <= $A27->getDate())
							{

								return "A27_A43_1[".$sa."]: Sector Framework achievement date must be greater than or equal to the start date if entered \n";

							}
						}
					}
					catch(Exception $e)
					{
							return "A27_A43_1[".$sa."]: Sector Framework achievement date must be greater than or equal to the start date if entered \n";

					}
				}
			}
*/

/*			private function rule_A27_A46a_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

			
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
	
						$query = "select Valid_To from ILR_A46_Nat_Learner_Aims where National_Learner_Aim_Code='{$ilr->aims[$sa]->A46a}' and (LR_Ind='Y' or ER_Ind='Y')";
						$d = new Date(DAO::getSingleValue($linklis, $query));
						$test = DAO::getSingleValue($linklis, $query);
						
						if($A27->getDate()>$d->getDate() && $ilr->aims[$sa]->A46a!='999')
						{
							return "A27_A46a_1[".$sa."]: The learning aim start date must not be after the 'valid to date' in the A46 code table  \n";
						}
					}
					catch(Exception $e)
					{
						return $test . "A27_A46a_1[".$sa."]: The learning aim start date must not be after the 'valid to date' in the A46 code table  \n";
					}
				}
			}
*/
			private function rule_A27_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
	
						$query = "select Valid_To from ILR_A46_Nat_Learner_Aims where National_Learner_Aim_Code='{$ilr->aims[$sa]->A46b}' and (LR_Ind='Y' or ER_Ind='Y')";
						$d = new Date(DAO::getSingleValue($linklis, $query));
						
						if($A27->getDate()>$d->getDate() && $ilr->aims[$sa]->A46b!='999')
						{
							return "A27_A46a_1[".$sa."]: The learning aim start date must not be after the 'valid to date' in the A46 code table  \n";
						}
					}
					catch(Exception $e)
					{
						return "A27_A46b_1[".$sa."]: The learning aim start date must not be after the 'valid to date' in the A46 code table  \n";
					}
				}
			}

			
			private function rule_A27_E08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						try
						{
							$A27 = new Date($ilr->aims[$sa]->A27);
							$E08 = new Date($ilr->aims[$sa]->E08);
		
							if($E08->getDate()<$A27->getDate())
							{
								return "A27_E08_1[".$sa."]: The ESF start date must not be before the learning start date on the aim to which the ESF record is attached \n";
							}
						}
						catch(Exception $e)
						{
							return "A27_E08_1[".$sa."]: The ESF start date must not be before the learning start date on the aim to which the ESF record is attached \n";
						}
					}
				}
			}
			
			
			private function rule_A27_E22_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						try
						{
							$A27 = new Date($ilr->aims[$sa]->A27);
							$E22 = trim($ilr->aims[$sa]->E22);
							$d = new Date('01/01/2008');
		
							if(substr($E22,5,1)=='L' && $A27->getDate()<$d-getDate())
							{
								return "A27_E22_1[".$sa."]: New format of ESF Project Dossier Number is only valid for starts after 1 January 2008 \n";
							}
						}
						catch(Exception $e)
						{
							return "A27_E22_1[".$sa."]: New format of ESF Project Dossier Number is only valid for starts after 1 January 2008 \n";
						}
					}
				}
			}
			
			private function rule_A27_L11_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A27_L14_L15_L16_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$L14 = $ilr->learnerinformation->L14;
						$L15 = $ilr->learnerinformation->L15;
						$L16 = $ilr->learnerinformation->L16;
						$d = new Date('01/08/2008');
						
						if($A27->getDate()>=$d->getDate() && $L14=='1' && $L15=='98' && $L16=='98')
						{
							return "A27_L14_L15_L16_1[".$sa."]: For new starters from 1 August 2008, if 'learner considers himself or herself to have a learning difficulty and/or disability or health problem', then the learning disability and learning difficulty must not be 'no disability' and 'no learning difficulty' \n";
						}
					}
					catch(Exception $e)
					{
						return "A27_L14_L15_L16_1[".$sa."]: For new starters from 1 August 2008, if 'learner considers himself or herself to have a learning difficulty and/or disability or health problem', then the learning disability and learning difficulty must not be 'no disability' and 'no learning difficulty' \n";
					}
				}
			}
			

			private function rule_A31_A34_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A31_A34_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A31_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A31_A40_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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


			private function rule_A31_A50_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			
			private function rule_A31_E10_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						$A31s = trim($ilr->aims[$sa]->A31);
						$E10s = trim($ilr->aims[$sa]->E10);
						
						if($A31s!='00000000' && $E10s =='00000000')
						{
							return "A31_E10_2[".$sa."]: If the learning actual end date is entered, then the date ended ESF co-financing must be entered. \n";
						}
					}
				}
			}
			

			private function rule_A31_T08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A34_A35_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A34_A35_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A35_A36_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A35_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			
			private function rule_A35_A40_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A40 = trim($ilr->aims[$sa]->A40);
					$A35 = trim($ilr->aims[$sa]->A35);

					if($A35=='1' && $A40=='00000000')
					{
						return "A35_A40_2[".$sa."]: If the Learning Outcome is 'achieved' then the Achievement Date must be entered \n";
					}
				}
			}
			
			private function rule_A40_T08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_A46a_A46b_A54_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					$A54 = trim($ilr->aims[$sa]->A54);
					
					if( ($A46a=='064' || $A46a=='100' || $A46b=='064' || $A46b=='100') && $A54=='')
					{
						return "A46a_A46b_A54_1[".$sa."]: If the learning aim was TtG funded or TtG badged prior to 2008/09 it must have a valid broker contract id or be not Brokered  \n";
					}
				}
			}
			
			
			private function rule_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);

					if($A46a!='999' && $A46a==$A46b)
					{
						return "A46a_A46b_1[".$sa."]: National Learning Aim Monitoring values must be different unless they are both 999 \n";
					}
				}
			}

			private function rule_A46a_A46b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_A46a_A46b_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			
			private function rule_AD03_A44_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
										
					if($AD03!='T' && $AD03!=substr($A44,8,1))
					{
						return "AD03_A44_1[".$sa."]: If an EDRS reference number is entered in the Employer identifier field it must be a valid EDRS number \n";
					}
				}
			}
			
			private function rule_AD03_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					// Calculate AD03
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
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
										
					if($AD03=='T' && ($A46a=='064' || $A46b=='064'))
					{
						return "AD03_A46a_A46b_1[".$sa."]: If the learning aim is train to gain badged then an EDRS reference number must be entered in the Employer Identifier field \n";
					}
				}
			}
			
			private function rule_AD03_A46a_A46b_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					// Calculate AD03
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
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
										
					if($AD03=='T' && ($A46a=='100' || $A46b=='100'))
					{
						return "AD03_A46a_A46b_2[".$sa."]: If the learning aim is train to gain badged then an EDRS reference number must be entered in the Employer Identifier field \n";
					}
				}
			}
			
			private function rule_E08_E09_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						try
						{
							$E08 = new Date($ilr->aims[$sa]->E08);
							$E09 = new Date($ilr->aims[$sa]->E09);
		
							if($E09->getDate()<$E08->getDate())
							{
								return "E08_E09_1[".$sa."]: Planned end date must be on or after start date in ESF Co-Financing Dataset\n";
							}
						}
						catch(Exception $e)
						{
							return "E08_E09_1[".$sa."]: Planned end date must be on or after start date in ESF Co-Financing Dataset\n";
						}
					}
				}
			}
		
			private function rule_E08_E10_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					if(trim($ilr->aims[$sa]->A06)=='01')
					{
						try
						{
							$E08 = new Date($ilr->aims[$sa]->E08);
							$E10 = new Date($ilr->aims[$sa]->E10);
		
							if($E10->getDate()<$E08->getDate())
							{
								return "E08_E10_1[".$sa."]: Actual end date must be on or after start date in ESF Co-Financing Dataset\n";
							}
						}
						catch(Exception $e)
						{
							return "E08_E10_1[".$sa."]: Actual end date must be on or after start date in ESF Co-Financing Dataset\n";
						}
					}
				}
			}
			
			private function rule_E12_E14_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E14 = trim($ilr->aims[$sa]->E14);
		
					if($A06=='01' && $E12!='04' && ($E14!='99' && $E14!='98'))
						{
							return "E12_E14_2[".$sa."]: Length of Unemployment must be Not Unemployed or Not Known/not provided if Employment Status at start is not Unemployed  \n";
						}
				}
			}
			
			private function rule_E12_E14_3(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E14 = trim($ilr->aims[$sa]->E14);
		
					if($A06=='01' && $E12=='04' && $E14=='99')
					{
						return "E12_E14_3[".$sa."]: Length of Unemployment must not be Not Unemployed if Employment Status at start is Unemployed. \n";
					}
				}
			}
		
			private function rule_E12_E14_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					$A06 = trim($ilr->aims[$sa]->A06);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E14 = trim($ilr->aims[$sa]->E14);
		
					if($A06=='01' && ($E12=='01' || $E12=='02' || $E12=='03' || $E12=='06') && $E14!='99')
					{
						return "E12_E14_4[".$sa."]:  If employment status before starting is employed, full time education, self employed, economically inactive, then the Length of unemployment before starting ESF project must be 'not unemployed'. \n";
					}
				}
			}
			
/*			private function rule_L05_L08_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L05 = trim($ilr->learnerinformation->L05);
				$L08 = trim($ilr->learnerinformation->L08);
		
				if($L08=='Y' && (int)$L05!=0)
				{
					return "L05_L08_1: Must be no learning aims data sets if the delete flag is set \n";
				}
			}
*/			
			private function rule_L05_L08_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L05 = trim($ilr->learnerinformation->L05);
				$L08 = trim($ilr->learnerinformation->L08);
		
				if( ($L08=='N' || $L08==' ') && (int)$L05==0)
				{
					return "L05_L08_2: Must be at least one learning aim if there is no delete flag \n";
				}
			}
			
			private function rule_L14_L15_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				
				$L14 = trim($ilr->learnerinformation->L14);
				$L15 = trim($ilr->learnerinformation->L15);
		
				if($L14=='9' && $L15!='99')
				{
					return "L14_L15_1: Disability or Health Problem must be set to ‘Not known/information not provided’ if Learning difficulties and/or disabilities is set to ‘No information provided by the learner’.\n";
				}
			}

			private function rule_L14_L15_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L14 = trim($ilr->learnerinformation->L14);
				$L15 = trim($ilr->learnerinformation->L15);
		
				if($L14=='2' && $L15!='98')
				{
					return "L14_L15_2: Disability or Health Problem must be set to ‘No disability’ if Learning difficulties and/or disabilities is set to ‘Learner does not consider himself or herself to have a learning difficulty and/or disability or health problem’.  \n";
				}
			}

			private function rule_L14_L16_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L14 = trim($ilr->learnerinformation->L14);
				$L16 = trim($ilr->learnerinformation->L16);
		
				if($L14=='9' && $L16!='99')
				{
					return "L14_L16_1: Learning Difficulty must be set to ‘Not known/information not provided’ if Learning difficulties and/or disabilities is set to ‘No information provided by the learner’  \n";
				}
			}


			private function rule_L14_L16_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L14 = trim($ilr->learnerinformation->L14);
				$L16 = trim($ilr->learnerinformation->L16);
		
				if($L14=='2' && $L16!='98')
				{
					return "L14_L16_2: Learening Difficulty must be set to ‘No learning difficulty’ if Learning difficulties and/or disabilities is set to ‘Learner does not consider himself or herself to have a learning difficulty and/or disability or health problem. \n";
				}
			}


			private function rule_L17_L24_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L17 = trim($ilr->learnerinformation->L17);
				$L24 = trim($ilr->learnerinformation->L24);
		
				if( ($L24=='XK' || $L24=='XF' || $L24=='IM' || $L24=='XG' || $L24=='XH' || $L24=='XI') && $L17=='')
				{
					return "L17_L24_1: Postcode is mandatory unless country code is not UK \n";
				}
			}

			
			private function rule_L25_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
		
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					$L25 = trim($ilr->learnerinformation->L25);
		
					if(($A46a=='030' || $A46b=='030') && $L25!='002')
					{
						return "L25_A46a_A46b_1[".$sa."]: The Basic Skills Project for National Employers is only available for providers who contract with the National Contracts Service  \n";
					}
				}
			}

			
			private function rule_L25_L44_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L25 = trim($ilr->learnerinformation->L25);
				$L44 = trim($ilr->learnerinformation->L44);
		
				if( ($L25=='002' || $L25=='2') && $L44=='')
				{
					return "L25_L44_1: Delivery locations must be present for NCS contracts \n";
				}
			}
		
		
			private function rule_L25_L44_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L25 = trim($ilr->learnerinformation->L25);
				$L44 = trim($ilr->learnerinformation->L44);
		
				if($L44!='' && $L25!='002')
				{
					return "L25_L44_2: Non NES contracts must not fill in L44 \n";
				}
			}

			
			private function rule_L27_L39_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
				
				$L27 = trim($ilr->learnerinformation->L27);
				$L39 = trim($ilr->learnerinformation->L39);
		
				if($L39=='61' && $L27!='2')
				{
					return "L27_L39_1: If destination code is set to 'death' then the restricted use indicator must be set to 'learner is not to be contacted' \n";
				}
			}


			private function rule_L28a_L28b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L28a = trim($ilr->learnerinformation->L28a);
				$L28b = trim($ilr->learnerinformation->L28b);
		
				if($L28a == $L28b && $L28a!='99')
				{
					return "L28a_L28b_1: Entries for Eligibility for enhanced funding must be different unless they are both 99 \n";
				}
			}
	
			private function rule_L34abcd_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_L34bcd_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L34b = trim($ilr->learnerinformation->L34b);
				$L34c = trim($ilr->learnerinformation->L34c);
				$L34d = trim($ilr->learnerinformation->L34d);
				
				if($L34b != '99' && ($L34b==$L34c || $L34b==$L34d))
				{
					return "L34bcd_1: Learner Support Reasons must all be different unless they are 99 \n";
				}
			}
			
			private function rule_L34cd_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L34c = trim($ilr->learnerinformation->L34c);
				$L34d = trim($ilr->learnerinformation->L34d);
				
				if($L34c != '99' && $L34c==$L34d)
				{
					return "L34cd_1: Learner Support Reasons must all be different unless they are 99 \n";
				}
			}
			
			
			private function rule_L35_A14_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			
			private function rule_L35_A14_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
			

		
			private function rule_L37_L47_L48_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

			private function rule_L40a_L40b_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
			{
		
				$L40a = trim($ilr->learnerinformation->L40a);
				$L40b = trim($ilr->learnerinformation->L40b);
		
				if($L40a==$L40b && $L40a!='99')
				{
					return "L40a_L40b_1: The National Learning Monitoring fields must not contain the same values unless they are both 99 \n";
				}
			}


/*	private function rule_E11_E12_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E11 = trim($ilr->aims[$sa]->E11);
			$E12 = trim($ilr->aims[$sa]->E12);

			if($A06=='01' && ($E12=='02' || $E12=='04' || $E12=='05' || $E12=='06') && $E11!='99')
				{

					return "E11_E12_1[".$sa."]: If employment status before starting is 'full time education', 'unemployed', 'economically inactive' or 'still at school', then the industrial sector of learner's employer must be 'no employer'. \n";

				}
		}
	}

	private function rule_E12_E13_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E12 = trim($ilr->aims[$sa]->E12);
			$E13 = trim($ilr->aims[$sa]->E13);

			if($A06=='01' && $E12=='04' && $E13!='03')
				{

					return "E12_E13_2[".$sa."]: If Employment Status on day before start is unemployed learner's employment status must be unemployed \n";

				}
		}
	}

	private function rule_E12_E13_4(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E12 = trim($ilr->aims[$sa]->E12);
			$E13 = trim($ilr->aims[$sa]->E13);

			if($A06=='01' && $E12=='01' && $E13=='03')
				{

					return "E12_E13_4[".$sa."]: If Employment Status on day before start is employed, learner's employment status cannot be unemployed  \n";

				}
		}
	}

	private function rule_E12_E13_5(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E12 = trim($ilr->aims[$sa]->E12);
			$E13 = trim($ilr->aims[$sa]->E13);

			if($A06=='01' && ($E12=='02' || $E12=='05') && $E13!='98')
				{

					return "E12_E13_5[".$sa."]: If employment status before starting is 'full time education' or 'still at school', then the learner's employment status must be 'not known / not provided'.  \n";

				}
		}
	}



	private function rule_E12_E15_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E12 = trim($ilr->aims[$sa]->E12);
			$E15 = trim($ilr->aims[$sa]->E15);

			if($A06=='01' && ($E12=='02' || $E12=='04' || $E12=='05' || $E12=='06') && $E15!='99')
				{

					return "E12_E15_2[".$sa."]: If employment status before starting is 'full time education', unemployed', 'economically inactive' or 'still at school', then the Type and size of learner's employer must be 'not employed'. \n";

				}
		}
	}


	private function rule_E16abcde_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E16a = trim($ilr->aims[$sa]->E16a);
			$E16b = trim($ilr->aims[$sa]->E16b);
			$E16c = trim($ilr->aims[$sa]->E16c);
			$E16d = trim($ilr->aims[$sa]->E16d);
			$E16e = trim($ilr->aims[$sa]->E16e);

			if($A06=='01' && $E16a!='' && ($E16a==$E16b || $E16a==$E16c || $E16a==$E16d || $E16a==$E16e || $E16b==$E16c || $E16b==$E16d || $E16b==$E16e || $E16c==$E16d || $E16c==$E16e || $E16d==$E16e))
				{

					return "E16abcde_1[".$sa."]: Addressing Gender Stereotyping entries must all be different if entered \n";

				}
		}
	}


	private function rule_E18abcd_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E18a = trim($ilr->aims[$sa]->E18a);
			$E18b = trim($ilr->aims[$sa]->E18b);
			$E18c = trim($ilr->aims[$sa]->E18c);
			$E18d = trim($ilr->aims[$sa]->E18d);

			if($A06=='01' && $E18a!='' && ($E18a==$E18b || $E18a==$E18c || $E18a==$E18d || $E18b==$E18c || $E18b==$E18d || $E18c==$E18d))
				{

					return "E18abcd_1[".$sa."]: Delivery Mode entries must all be different if entered  \n";

				}
		}
	}

	private function rule_E19abcde_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E19a = trim($ilr->aims[$sa]->E19a);
			$E19b = trim($ilr->aims[$sa]->E19b);
			$E19c = trim($ilr->aims[$sa]->E19c);
			$E19d = trim($ilr->aims[$sa]->E19d);
			$E19e = trim($ilr->aims[$sa]->E19e);

			if($A06=='01' && $E19a!='' && ($E19a==$E19b || $E19a==$E19c || $E19a==$E19d || $E19a==$E19e || $E19b==$E19c || $E19b==$E19d || $E19b==$E19e || $E19c==$E19d || $E19c==$E19e || $E19d==$E19e))
				{

					return "E19abcde_1[".$sa."]: Support measures to be accessed by the learner entries must all be different if entered \n";

				}
		}
	}

	private function rule_E20abc_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E20a = trim($ilr->aims[$sa]->E20a);
			$E20b = trim($ilr->aims[$sa]->E20b);
			$E20c = trim($ilr->aims[$sa]->E20c);

			if($A06=='01' && $E20a!='' && ($E20a==$E20b || $E20a==$E20c || $E20b==$E20c))
				{

					return "E20abc_1[".$sa."]: Learner background must all be different if entered \n";

				}
		}
	}



	private function rule_A10_E08_E11_E12_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$E08 = new Date($ilr->aims[$sa]->E08);
					$E11 = trim($ilr->aims[$sa]->E11);
					$E12 = trim($ilr->aims[$sa]->E12);
					$d = new Date('31/07/2004');
					if($A10=='70' && $E08->getDate()>$d->getDate() && ($E12=='01' || $E12=='03') && ($E11=='98' || $E11=='99'))
					{

						return "A10_E08_E11_E12_1[".$sa."]: For ESF funded aims starting after 31/07/2004, if employment status before starting is 'employed' or 'self employed', the industrial sector of learner's employer must not be 'no employer' or 'not known'. \n";

					}
				}
				catch(Exception $e)
				{
						return "A10_E08_E11_E12_1[".$sa."]: For ESF funded aims starting after 31/07/2004, if employment status before starting is 'employed' or 'self employed', the industrial sector of learner's employer must not be 'no employer' or 'not known'. \n";
				}
			}
		}
	}


	private function rule_A10_E08_E11_E12_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$E08 = new Date($ilr->aims[$sa]->E08);
					$E11 = trim($ilr->aims[$sa]->E11);
					$E12 = trim($ilr->aims[$sa]->E12);
					$d = new Date('31/07/2004');

					if($A10!='70' && $E08->getDate()>$d->getDate() && ($E12=='01' || $E12=='03') && $E11=='99')
					{

						return "A10_E08_E11_E12_2[".$sa."]: For ESF match aims starting after 31/07/2004, if employment status before starting is 'employed' or 'self employed', the industrial sector of learner's employer must not be 'no employer' \n";

					}
				}
				catch(Exception $e)
				{
					return "A10_E08_E11_E12_2[".$sa."]: For ESF match aims starting after 31/07/2004, if employment status before starting is 'employed' or 'self employed', the industrial sector of learner's employer must not be 'no employer' \n";
				}
			}
		}
	}


	private function rule_A10_E08_E12_E13_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$E08 = new Date($ilr->aims[$sa]->E08);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E13 = trim($ilr->aims[$sa]->E13);
					$d = new Date('31/07/2004');

					if($A10=='70' && $E08->getDate()>$d->getDate() && ($E12=='01' || $E12=='03') && $E13!='01' && $E13!='02')
					{

						return "A10_E08_E12_E13_1[".$sa."]: For ESF funded aims starting after 31/07/2004, if employment status before starting is 'employed' or 'self employed', the learner's employment status must be ;in secure employment' or 'threatened with redundancy'. \n";

					}
				}
				catch(Exception $e)
				{
					return "A10_E08_E12_E13_1[".$sa."]: For ESF funded aims starting after 31/07/2004, if employment status before starting is 'employed' or 'self employed', the learner's employment status must be ;in secure employment' or 'threatened with redundancy'. \n";
				}
			}
		}
	}

	private function rule_A10_E08_E12_E13_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$E08 = new Date($ilr->aims[$sa]->E08);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E13 = trim($ilr->aims[$sa]->E13);
					$d = new Date('31/07/2004');

					if($A10!='70' && $E08->getDate()>$d->getDate() && $E12=='03' && $E13=='03')
					{

						return "A10_E08_E12_E13_2[".$sa."]: For ESF match aims starting after 31/07/2004, if employment status before starting is 'self employed', the learner's employment status must not be 'unemployed' \n";

					}
				}
				catch(Exception $e)
				{
					return "A10_E08_E12_E13_2[".$sa."]: For ESF match aims starting after 31/07/2004, if employment status before starting is 'self employed', the learner's employment status must not be 'unemployed' \n";
				}
			}
		}
	}


	private function rule_A10_E08_E12_E14_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$E08 = new Date($ilr->aims[$sa]->E08);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E14 = trim($ilr->aims[$sa]->E14);
					$d = new Date('31/07/2004');

					if($A10=='70' && $E08->getDate()>$d->getDate() && $E12=='04' && ($E14=='98' || $E14=='99'))
					{

						return "A10_E08_E12_E14_1[".$sa."]: For ESF funded aims starting after 31/07/2004, if employment status before starting is 'unemployed', then the Length of unemployment before starting ESF project must not be 'not known / not provided' or 'not unemployed'. \n";

					}
				}
				catch(Exception $e)
				{
					return "A10_E08_E12_E14_1[".$sa."]: For ESF funded aims starting after 31/07/2004, if employment status before starting is 'unemployed', then the Length of unemployment before starting ESF project must not be 'not known / not provided' or 'not unemployed'. \n";
				}
			}
		}
	}

	private function rule_A10_E08_E12_E14_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
				$A10 = trim($ilr->aims[$sa]->A10);
				$E08 = new Date($ilr->aims[$sa]->E08);
				$E12 = trim($ilr->aims[$sa]->E12);
				$E14 = trim($ilr->aims[$sa]->E14);
				$d = new Date('31/07/2004');

				if($A10!='70' && $E08->getDate()>$d->getDate() && $E12=='04' && $E14=='99')
				{

					return "A10_E08_E12_E14_2[".$sa."]: For ESF matched aims starting after 31/07/2004, if employment status before starting is 'unemployed', then the Length of unemployment before starting ESF project must not be 'not unemployed'. \n";

				}
				}
				catch(Exception $e)
				{
					return "A10_E08_E12_E14_2[".$sa."]: For ESF matched aims starting after 31/07/2004, if employment status before starting is 'unemployed', then the Length of unemployment before starting ESF project must not be 'not unemployed'. \n";
				}
			}
		}
	}

	private function rule_A10_E08_E12_E15_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$E08 = new Date($ilr->aims[$sa]->E08);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E15 = trim($ilr->aims[$sa]->E15);
					$d = new Date('31/07/2004');

					if($A10=='70' && $E08->getDate()>$d->getDate() && $E12=='01' && ($E15!='01' && $E15!='02' && $E15!='03' && $E15!='04' && $E15!='05' && $E15!='06'))
					{

						return "A10_E08_E12_E15_1[".$sa."]: For ESF funded aims starting after 31/07/2004, if employment status before starting is 'employed', then the Type and size of learner's employer must be 'public sector organisation', 'small/medium enterprise', 'large organisation', 'Micro SME', 'Small SME' or 'Medium SME'. \n";

					}
				}
				catch(Exception $e)
				{
					return "A10_E08_E12_E15_1[".$sa."]: For ESF funded aims starting after 31/07/2004, if employment status before starting is 'employed', then the Type and size of learner's employer must be 'public sector organisation', 'small/medium enterprise', 'large organisation', 'Micro SME', 'Small SME' or 'Medium SME'. \n";
				}
			}
		}
	}


	private function rule_A10_E08_E12_E15_2(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A10 = trim($ilr->aims[$sa]->A10);
					$E08 = new Date($ilr->aims[$sa]->E08);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E15 = trim($ilr->aims[$sa]->E15);
					$d = new Date('31/07/2004');

					if($A10!='70' && $E08->getDate()>$d->getDate() && $E12=='01' && $E15=='99')
					{

						return "A10_E08_E12_E15_2[".$sa."]: For ESF matched aims starting after 31/07/2004, if employment status before starting is 'employed', then the Type and size of learner's employer must not be not employed. \n";

					}
				}
				catch(Exception $e)
				{
					return "A10_E08_E12_E15_2[".$sa."]: For ESF matched aims starting after 31/07/2004, if employment status before starting is 'employed', then the Type and size of learner's employer must not be not employed. \n";
				}
			}
		}
	}


	private function rule_A10_L01_L25_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A10 = trim($ilr->aims[$sa]->A10);
			$L01 = trim($ilr->learnerinformation->L01);
			$L25 = trim($ilr->learnerinformation->L25);
			$capn='';
			 
			$query = "select trim(capn)+trim(lsc) from provider_ttg_factors where trim(capn)+trim(lsc)='{($L01.$L25)}'";
			$capn = trim(DAO::getSingleValue($linklis, $query));

			if($A10=='60' && ($L01.$L25)!=$capn)
			{
				return "A10_L01_L25_1[".$sa."]: For train to gain funded aims there must be a train to gain funding factor for the provider number LSC combination. \n";
			}
		}
	}



	private function rule_A27_E12_E15_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A27 = new Date($ilr->aims[$sa]->A27);
					$E12 = trim($ilr->aims[$sa]->E12);
					$E15 = trim($ilr->aims[$sa]->E15);
					$d = new Date('01/08/2006');

					if($A27->getDate()>=$d->getDate() && $E12=='03' && $E15!='04' && $E15!='05' && $E15!='06')
					{

						return "A27_E12_E15_1[".$sa."]: If the learner's employment status on day before starting ESF project is 'self employed' and the start date is on or after 1 August 2006 the type and size of learner's employer should be micro, small or medium SME \n";

					}
				}
				catch(Exception $e)
				{
					return "A27_E12_E15_1[".$sa."]: If the learner's employment status on day before starting ESF project is 'self employed' and the start date is on or after 1 August 2006 the type and size of learner's employer should be micro, small or medium SME \n";
				}
			}
		}
	}

	private function rule_A27_E15_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A27 = new Date($ilr->aims[$sa]->A27);
					$E15 = trim($ilr->aims[$sa]->E15);
					$d = new Date('01/08/2006');

					if($A27->getDate()>=$d->getDate() && $E15=='02')
					{

						return "A27_E15_1[".$sa."]: Type and size of learner's employer must not be 'Small/Medium enterprise' if the start date is on or after 1 August 2006. \n";

					}
				}
				catch(Exception $e)
				{
					return "A27_E15_1[".$sa."]: Type and size of learner's employer must not be 'Small/Medium enterprise' if the start date is on or after 1 August 2006. \n";
				}
			}
		}
	}

	private function rule_A27_E21_L14_1(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				try
				{
					$A27 = new Date($ilr->aims[$sa]->A27);
					$E21 = trim($ilr->aims[$sa]->E21);
					$L14 = trim($ilr->learnerinformation->L14);
					$d = new Date('31/07/2006');

					if($A27->getDate()>$d->getDate() && ($E21!='' && $E21!='00') && $L14!='1')
					{

						return "A27_E21_L14_1[".$sa."]: For starts after 31 July 2006 if support measures for learners with disabilities are being provided the learner must have a learning difficulty and/or disability and/or health problem \n";

					}
				}
				catch(Exception $e)
				{
					return "A27_E21_L14_1[".$sa."]: For starts after 31 July 2006 if support measures for learners with disabilities are being provided the learner must have a learning difficulty and/or disability and/or health problem \n";
				}
			}
		}
	}
*/

	private function rule_R03(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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
	
	
	private function rule_R04(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if(trim($ilr->aims[$sa]->A06)=='01')
			{
				$A01 = trim($ilr->aims[$sa]->A01);
				$E01 = trim($ilr->aims[$sa]->E01);
				$A03 = trim($ilr->aims[$sa]->A03);
				$E03 = trim($ilr->aims[$sa]->E03);
				if($A01!=$E01 || $A03!=$E03)
				{
						return "R04[".$sa."]: An ESF record must have an associated Aim record (same Provider Number and Learner Reference number and Learning Aim data set sequence number)\n";
				}
			}
		}
	}

	private function rule_R23(PDO $link, PDO $linklad, PDO $linklis, ILR2008 $ilr)
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

public $report = NULL;

}

