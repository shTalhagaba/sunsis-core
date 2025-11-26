<?php
class ValidateILR0708
{
	public function validate(PDO $link, ILR0708 $ilr)
	{
		$class = new ReflectionClass(__CLASS__);
		$methods = $class->getMethods();

		$report = '';

	
		$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis0708;port=".DB_PORT, DB_USER, DB_PASSWORD);
		$linklad = new PDO("mysql:host=".DB_HOST.";dbname=lad20070821;port=".DB_PORT, DB_USER, DB_PASSWORD);
				
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

			private function rule_A01_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=(int)$ilr->learnerinformation->subaims;$sa++)
				{

					if(trim($ilr->learnerinformation->L01)!=trim($ilr->aims[$sa]->A01))

					{

						return "A01_1[".$sa."]: The provider number in the aim and learner must match ";

					}
				}
			}

			private function rule_A02_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A02)!='12' && trim($ilr->aims[$sa]->A02)!='00')
					{

						return "A02_2[".$sa."]: Contract type must be 12 (if entered) ";

					}
				}
			}

			private function rule_A05_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if((int)trim($ilr->aims[$sa]->A05)<1 || (int)trim($ilr->aims[$sa]->A05)>98)
					{

						return "A05_1[".$sa."]: Learning aim data set sequence must be numeric and between 01 and 98 ";

					}
				}
			}

			private function rule_A07_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A07)!='00')
					{

						return "A07_2[".$sa."]: HE data sets must be 00 ";

					}
				}
			}

			private function rule_A08_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A08)!='2')
					{

						return "A08_3[".$sa."]: Data set format must be 2 ";

					}
				}
			}


			private function rule_A09_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A10_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					if($A10!='40' && $A10!='41' && $A10!='42' && $A10!='60' && $A10!='70' && $A10!='80' && $A10!='99')
					{

						return "A10_4[".$sa."]: LSC funding stream must be 40, 41, 42, 60, 70, 80 or 99 ";

					}
				}
			}

			private function rule_A11a_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A11b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A12a_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A12a = trim($ilr->aims[$sa]->A12a);
					if($A12a!='000')
					{

						return "A12a_2[".$sa."]: Implied rate of LSC funding for historic ESF (1) must be 000 ";

					}
				}
			}

			private function rule_A12b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A12b = trim($ilr->aims[$sa]->A12b);
					if($A12b!='000')
					{

						return "A12b_2[".$sa."]: Implied rate of LSC funding for historic ESF (1) must be 000 ";

					}
				}
			}


			private function rule_A13_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A14_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A14 = trim($ilr->aims[$sa]->A14);
					if($A14!='00')
					{

						return "A14_2[".$sa."]: Reason for partial or full non-payment of tuition fees must be 00 ";

					}
				}
			}

			private function rule_A15_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select programme_type_code from ilr_A15_programme_types where programme_type_code='{$ilr->aims[0]->A15}'";
					$A15 = trim(DAO::getSingleValue($linklis, $query));

					if($A15!=trim($ilr->aims[$sa]->A15))
					{
						return "A15_1[".$sa."]: Invalid Programme Type ";
					}
				}
			}

			private function rule_A16_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select programme_route_code from ilr_A16_programme_routes where programme_route_code='{$ilr->aims[0]->A16}'";
					$A16 = DAO::getSingleValue($linklis, $query);

					$A16 = str_pad($A16,2,'0',STR_PAD_LEFT);

					if($A16!='00' && $A16!=$ilr->aims[$sa]->A16)
					{
						return "A16_3[".$sa."]: Invalid Programme Route Code ";
					}
				}
			}

			private function rule_A17_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A18_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A18 = trim($ilr->aims[$sa]->A18);
					if($A18!='' && $A18!='22' && $A18!='23')
					{

						return "A18_3[".$sa."]: Main delivery method must be 22 or 23 if entered ";

					}
				}
			}

			private function rule_A19_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A20_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A21_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A22_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A22 = $ilr->aims[$sa]->A22;
					if($A22!='      ')
					{

						return "A22_1[".$sa."]: Franchising delivery provider number must be spaces\n";

					}
				}
			}



			private function rule_A23_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A23 = trim($ilr->aims[$sa]->A23);
					if($A23!='')
					{$check = substr($A23,(strpos($A23," ")+1),3);
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

			private function rule_A23_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A24_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select SOC2000_CODE_CODE from SOC2000_CODES where SOC2000_CODE_CODE='{$ilr->aims[$sa]->A24}'";
					$A24 = trim(DAO::getSingleValue($linklad, $query));

					if($A24!='' && $A24!=trim($ilr->aims[$sa]->A24))
					{
						return "A24_1[".$sa."]: Occupation relating to learner aim is not exist on the SOC 2000 Codes table \n";
					}
				}
			}


			private function rule_A26_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select sector_framework_code from sector_frameworks where sector_framework_code='{$ilr->aims[0]->A26}'";
					$A26 = trim(DAO::getSingleValue($linklad, $query));

					if($A26!='' && $A26!=trim($ilr->aims[$sa]->A26))
					{
						return "A26_1[".$sa."]: Sector framework code is not valid \n";
					}
				}
			}


			private function rule_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A27_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/1997');
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

			private function rule_A27_6(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					try
					{
						$d = new Date($ilr->aims[$sa]->A27);
						$check = new Date('01/08/2017');
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

			private function rule_A28_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A28_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A31_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$d = trim($ilr->aims[$sa]->A31);
					if($d!='00000000' && $d!='dd/mm/yyyy')
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


			private function rule_A31_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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
								return "A31_2[".$sa."]: Learn ing actual end date must be on or before current date";
							}
					}
				}
			}


			private function rule_A32_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A34_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select completion_status_code from ilr_A34_completion_status where completion_status_code='{$ilr->aims[$sa]->A34}'";
					$A34 = trim(DAO::getSingleValue($linklis, $query));

					if($A34!=trim($ilr->aims[$sa]->A34))
					{
						return "A34_3[".$sa."]: Invalid Completion Status Code \n";
					}
				}
			}


			private function rule_A35_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select Learning_outcome_code from ilr_A35_learning_outcomes where learning_outcome_code='{$ilr->aims[$sa]->A35}'";
					$A35 = trim(DAO::getSingleValue($linklis, $query));

					if($A35!=trim($ilr->aims[$sa]->A35))
					{
						return "A35_1[".$sa."]: Invalid Learning outcome code \n";
					}
				}
			}


			private function rule_A36_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A36 = $ilr->aims[$sa]->A36;

					if($A36!='   ')
					{

						return "A36_2[".$sa."]: Learning outcome grade must be spaces\n";

					}
				}
			}

			private function rule_A37_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A38_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A39_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A39 = $ilr->aims[$sa]->A39;

					if($A39!='0')
					{

						return "A39_2[".$sa."]: Eligibility for achievement funding \n";

					}
				}
			}

			private function rule_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$d = trim($ilr->aims[$sa]->A40);
					if($d!='00000000' && $d!='dd/mm/yyyy')
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


			private function rule_A40_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$check = new Date(date('d/m/Y'));
					$s = trim($ilr->aims[$sa]->A40);
					if($s!='00000000' && $s!='dd/mm/yyyy')
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


			private function rule_A43_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A43s = trim($ilr->aims[$sa]->A43);
					if($A43s!='00000000' && $A43s!='dd/mm/yyyy')
					{
							try
							{
								$A43 = new Date($ilr->aims[$sa]->A43);
							}
							catch(Exception $e)
							{
								return "A43_1[".$sa."]: Sector framework achievement date must be a valid date or 00000000 \n";
							}

					}
				}
			}


			private function rule_A44_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A45_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A45_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A46a_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46a = trim($ilr->aims[$sa]->A46a);
					$a=$A46a;
					if($a!='17' && $a!='21' && $a!='26' && $a!='27' && $a!='28' && $a!='29' && $a!='30' && $a!='32' && $a!='33' && $a!='36' && $a!='37' && $a!='38' && $a!='46' && $a!='47' && $a!='48' && $a!='49' && $a!='50' && $a!='51' && $a!='52' && $a!='53' && $a!='54' && $a!='55' && $a!='56' && $a!='57' && $a!='58' && $a!='59' && $a!='60' && $a!='62' && $a!='63' && $a!='66' && $a!='67' && $a!='68' && $a!='69' && $a!='70' && $a!='71' && $a!='72' && $a!='73' && $a!='74' && $a!='75' && $a!='76' && $a!='77' && $a!='78'  && $a!='79'  && $a!='80' && $a!='81' && $a!='82' && $a!='83' && $a!='84' && $a!='85' && $a!='87' && $a!='88' && $a!='89' && $a!='90' && $a!='91' && $a!='92' && $a!='93' && $a!='94' && $a!='95' && $a!='96' && $a!='97' && $a!='98' && $a!='99')
					{

						return "A46a_2[".$sa."]: National learning aim monitoring 1 Must be 17,21, 26-30,32-33,36-38, 46-60, 62-63, 66-85, 87-99 \n";

					}
				}
			}

			private function rule_A46b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46b = trim($ilr->aims[$sa]->A46b);
					$A=$A46b;
					if($A!='17' && $A!='21' && $A!='26' && $A!='27' && $A!='28' && $A!='29' && $A!='30' && $A!='32' && $A!='33' && $A!='36' && $A!='37' && $A!='38' && $A!='46' && $A!='47' && $A!='48' && $A!='49' && $A!='50' && $A!='51' && $A!='52' && $A!='53' && $A!='54' && $A!='55' && $A!='56' && $A!='57' && $A!='58' && $A!='59' && $A!='60' && $A!='62' && $A!='63' && $A!='66' && $A!='67' && $A!='68' && $A!='69' && $A!='70' && $A!='71' && $A!='72' && $A!='73' && $A!='74' && $A!='75' && $A!='76' && $A!='77' && $A!='78'  && $A!='79'  && $A!='80' && $A!='81' && $A!='82' && $A!='83' && $A!='84' && $A!='85' && $A!='87' && $A!='88' && $A!='89' && $A!='90' && $A!='91' && $A!='92' && $A!='93' && $A!='94' && $A!='95' && $A!='96' && $A!='97' && $A!='98' && $A!='99')
					{

						return "A46b_2[".$sa."]: National learning aim monitoring 2 Must be 17,21, 26-30,32-33,36-38, 46-60, 62-63, 66-85, 87-99 \n";

					}
				}
			}

			private function rule_A47a_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A47b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A48a_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A48b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A49_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select project_code from ilr_A49_project_codes where project_code='{$ilr->aims[$sa]->A49}'";
					$A49 = trim(DAO::getSingleValue($linklis, $query));

					if($A49!=trim($ilr->aims[$sa]->A49))
					{
						return "A49_3[".$sa."]: Invalid project code \n";
					}
				}
			}

			private function rule_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select reason_learning_ended_code from ilr_A50_reason_learning_ended where reason_learning_ended_code='{$ilr->aims[$sa]->A50}'";
					$A50 = trim(DAO::getSingleValue($linklis, $query));

					if($A50!=trim($ilr->aims[$sa]->A50))
					{
						return "A50_1[".$sa."]: Invalid reason learning ended \n";
					}
				}
			}


			private function rule_A51a_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A52_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A52 = trim($ilr->aims[$sa]->A52);

					if($A52!='00000')
					{
						return "A52_1[".$sa."]: Distance learning funding must be zeros\n";
					}
				}
			}


			private function rule_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select additional_learning_need_code from ilr_A53_add_learning_needs where additional_learning_need_code='{$ilr->aims[$sa]->A53}'";
					$A53 = trim(DAO::getSingleValue($linklis, $query));

					if($A53!=trim($ilr->aims[$sa]->A53))
					{
						return "A53_1[".$sa."]: Invalid additional learning needs code\n";
					}
				}
			}


			private function rule_A54_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A54 = trim($ilr->aims[$sa]->A54);
					if($A54!='')
					{
						$first = substr($A54,0,2);
						if ($first!='EE' && $first!='EM' && $first!='GL' && $first!='NE' && $first!='NW' && $first!='SE' && $first!='SW' && $first!='WM' && $first!='YH' && $first!='AB')
							return "Broker contract number must be correct format: Format is RRCCCCCCCC where RR is EE,EM,GL,NE,NW,SE,SW,WM,YH or AB and CCCCCCCC are any alphanumeric characters in the range (A-Z) or (0-9) or 9999999999 (if entered) \n";
						if(isAlphaNum(substr($A54,2,1))==false || isAlphaNum(substr($A54,3,1))==false || isAlphaNum(substr($A54,4,1))==false || isAlphaNum(substr($A54,5,1))==false || isAlphaNum(substr($A54,6,1))==false || isAlphaNum(substr($A54,7,1))==false || isAlphaNum(substr($A54,8,1))==false || isAlphaNum(substr($A54,9,1))==false)
							return "A54_1[".$sa."]: Broker contract number must be correct format: Format is RRCCCCCCCC where RR is EE,EM,GL,NE,NW,SE,SW,WM,YH or AB and CCCCCCCC are any alphanumeric characters in the range (A-Z) or (0-9) or 9999999999 (if entered) \n";
					}
				}
			}

			private function rule_A54_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select broker_contract_number from ttg_broker_contracts where broker_contract_number='{$ilr->aims[$sa]->A54}'";
					$A54 = trim(DAO::getSingleValue($linklis, $query));

					if($A54!=trim($ilr->aims[$sa]->A54))
					{
						return "A54_2[".$sa."]: Invalid TtG Broker Contract Number \n";
					}
				}
			}

			private function rule_A55_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A55 = trim($ilr->aims[$sa]->A55);
					$L45 = trim($ilr->learnerinformation->L45);
					if($A55!='' && $L45!='' && $A55!=$L45)
					{
						return "A55_1[".$sa."]: Unique learner number in learner information and aim information must be the same \n";
					}
				}
			}

			private function rule_A56_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A57_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A02_A10(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A10)=='60' && trim($ilr->aims[$sa]->A02)!='00')
					{

						return "A02_A10[".$sa."]: If the learning aim is TtG funded, the allocation number must be set to 00 \n";

					}
				}
			}


			private function rule_A05_L05(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if((int)trim($ilr->aims[$sa]->A05) > (int)trim($ilr->learnerinformation->L05))
					{

						return "A05_L05[".$sa."]: The data set sequence must be less than or equal to the number of datasets \n";

					}
				}
			}


			private function rule_A06_A10_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A10)=='70' && trim($ilr->aims[$sa]->A06)=='00')
					{

						return "A06_A10_1[".$sa."]: If LSC Funding Stream is LSC ESF Co-financed, there must be an ESF data set \n";

					}
				}
			}


			private function rule_A06_A10_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A10)=='99' && trim($ilr->aims[$sa]->A06)=='01')
					{

						return "A06_A10_2[".$sa."]: ESF data must not be submitted if there is no LSC funding for the aim \n";

					}
				}
			}


			private function rule_A09_A10_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A09)=='XESF0001' && trim($ilr->aims[$sa]->A10)!='70')
					{

						return "A09_A10_1[".$sa."]: If the learning aim is XESF0001 the LSC funding stream must be 70 LSC ESF co-financed \n";

					}
				}
			}

			private function rule_A09_A10_A15_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A15)=='09' && trim($ilr->aims[$sa]->A10)=='40' && trim($ilr->aims[$sa]->A09)!='XE2E0001')
					{

						return "A09_A10_A15_3[".$sa."]: WBL funded E2E programme must use the E2E learning aim code \n";

					}
				}
			}


			private function rule_A09_A10_A15_A24_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A09_A10_A15_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

 /*
			private function rule_A09_A10_A15_A24_LAD_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

*/

			private function rule_A09_A10_A16_A27_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select WBL_ANNUAL_VALUE_STATUS_CODE from WBL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
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

			private function rule_A09_A10_A16_A27_LAD_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select NON_LSC_FUNDED_STATUS_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
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

			private function rule_A09_A10_A26_A27_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A09_A10_A27_A46a_A46b_LAD_8(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select TTG_ANNUAL_VAL_STATUS_CODE from TTG_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
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

			private function rule_A09_A10_A27_LAD_7(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select ACL_ANNUAL_VAL_STATUS_CODE from ACL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
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

			private function rule_A09_A10_A46a_A46b_LAD_10(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select TTG_ANNUAL_VAL_STATUS_CODE from TTG_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
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

			private function rule_A09_A10_LAD_6(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select WBL_ANNUAL_VALUE_STATUS_CODE from WBL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
					$wavsc = DAO::getSingleValue($linklad, $query);

					$A10 = trim($ilr->aims[$sa]->A10);

					if( ($A10=='40' || $A10=='41' || $A10=='42') && ($wavsc!='1' && $wavsc!='2'))
					{
							return "A09_A10_LAD_6[".$sa."]: Must exist on WBL Annual Values table for year 2007/08 with valid status (1 or 2) for WBL-funded aims\n";
					}
				}
			}

			private function rule_A09_A10_LAD_7(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select NON_LSC_FUNDED_STATUS_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
					$nlfsc = trim(DAO::getSingleValue($linklad, $query));

					$A10 = trim($ilr->aims[$sa]->A10);

					if( ($A10=='60' || $A10=='70' || $A10=='80' || $A10=='99') &&  ($nlfsc!='1' && $nlfsc!='2'))
					{
							return "A09_A10_LAD_7[".$sa."]: Must exist on All Annual Values table for year 2007/08 with valid status (1 or 2) for aims which are not FE-, WBL- or ACL-funded\n";
					}
				}
			}

			private function rule_A09_A10_LAD_8(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select TECHNICAL_CERTIFICATE from WBL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
					$tc = trim(DAO::getSingleValue($linklad, $query));

					$A10 = trim($ilr->aims[$sa]->A10);

					if($A10=='41' && ($tc!='1') )
					{
							return "A09_A10_LAD_8[".$sa."]: If funding for a technical certificate is being claimed the learning aim must be a technical certificate\n";
					}
				}
			}

			private function rule_A09_A10_LAD_9(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select KEY_SKILL_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' AND ACADEMIC_YEAR_CODE='0708'";
					$ksc = trim(DAO::getSingleValue($linklad, $query));

					$A10 = trim($ilr->aims[$sa]->A10);

					if($A10=='42' && ($ksc!='1' && $ksc!='3'))
					{
							return "A09_A10_LAD_9[".$sa."]: If funding for a key skill is being claimed the learning aim must be a key skill \n";
					}
				}
			}

			private function rule_A09_A15_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A09)=='XE2E0001' && trim($ilr->aims[$sa]->A15)!='09')
					{

						return "A09_A15_3[".$sa."]: When an E2E learning aim code of XE2E0001 is used, the programme type must be E2E. \n";

					}
				}
			}


			private function rule_A09_A15_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A09_A32_A46a_A46b_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select Learning_aim_type_code from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$latc = DAO::getSingleValue($linklad, $query);

					$A32 = trim($ilr->aims[$sa]->A32);
					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);

					if($A32=='00000' &&  ( ($A46a=='27' || $A46a=='83') || ($A46b=='27' || $A46b=='83') || $latc=='1437'))
					{
							return "A09_A32_A46a_A46b_LAD_1[".$sa."]: If the learning aim is delivered under the Employability Skills Programme or is the Employability Award the guided learning hours must not be 00000\n";
					}
				}
			}


			private function rule_A09_A32_A46a_A46b_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select Learning_aim_type_code from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
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

			private function rule_A09_A35_A40_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = trim(DAO::getSingleValue($linklad, $query));

					$A35 = trim($ilr->aims[$sa]->A35);
					$A40 = trim($ilr->aims[$sa]->A40);
					if($A35=='1' && ($A40=='00000000' || $A40=='dd/mm/yyyy') && $flag11!='Y')
					{
						return "A09_A35_A40_LAD_1[".$sa."]: If the Learning Outcome is 'achieved' then the Achievement Date must be entered for all learning aims except for sector framework class codes\n";
					}
				}
			}

			private function rule_A09_A35_A43_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = trim(DAO::getSingleValue($linklad, $query));

					$A35 = trim($ilr->aims[$sa]->A35);
					$A43 = trim($ilr->aims[$sa]->A43);
					if($flag11=='Y' && $A35=='1' && ($A43=='00000000' || $A43=='dd/mm/yyyy'))
					{
						return "A09_A35_A43_LAD_1[".$sa."]: If the learning outcome is 'achieved' then the Sector framework acheivement date must be entered for sector framework class codes  \n";
					}
				}
			}


			private function rule_A09_A35_A43_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = trim(DAO::getSingleValue($linklad, $query));

					$A35 = trim($ilr->aims[$sa]->A35);
					$A43 = trim($ilr->aims[$sa]->A43);
					if($flag11=='Y' && $A35!='1' && $A43!='00000000' && $A43!='dd/mm/yyyy')
					{
						return "A09_A35_A43_LAD_2[".$sa."]: If the Sector framework Achievement Date is entered then the Learning Outcome must be 'achieved' for sector framework class codes \n";
					}
				}
			}


			private function rule_A09_A40_A43_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = trim(DAO::getSingleValue($linklad, $query));

					$A40 = trim($ilr->aims[$sa]->A40);
					$A43 = trim($ilr->aims[$sa]->A43);
					if($flag11!='Y' && ($A43!='00000000' && $A43!='dd/mm/yyyy') && ($A40=='00000000' && $A40=='dd/mm/yyyy'))
					{
						return "A09_A40_A43_LAD_1[".$sa."]: If the Sector Framework Achievement Date is entered, the Achievement Date must be entered except for framework completion class codes \n";
					}
				}
			}


			private function rule_A09_A40_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select FLAG11 from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$flag11 = trim(DAO::getSingleValue($linklad, $query));

					$A40 = trim($ilr->aims[$sa]->A40);
					if($flag11=='Y' && $A40!='00000000' && $A40!='dd/mm/yyyy')
					{
						return "A09_A40_LAD_1[".$sa."]: Achievement date must be null for sector framework class codes \n";
					}
				}
			}


			private function rule_A09_A46a_A46b_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select SKILLS_FOR_LIFE_TYPE_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' and academic_year_code='0708'";
					$sfltc = trim(DAO::getSingleValue($linklad, $query));

					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					if( ($A46a=='27' || $A46b=='27' || $A46a=='30' || $A46b=='30') && ($sfltc!='01' && $sfltc!='02' && $sfltc!='03' && $sfltc!='05' && $sfltc!='06' && $sfltc!='07' && $sfltc!='08' && $sfltc!='09' && $sfltc!='10'))
					{
						return "A09_A46a_A46b_LAD_1[".$sa."]: If the aim is delivered under the WBL basic skills project or the National Employers Basic Skills project the learning aim must be a basic skill eligible for funding under these projects \n";
					}
				}
			}


			private function rule_A09_A46a_A46b_LAD_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select SKILLS_FOR_LIFE_TYPE_CODE from ALL_ANNUAL_VALUES where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}' and academic_year_code='0708'";
					$sfltc = trim(DAO::getSingleValue($linklad, $query));

					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					if( ($A46a=='83' || $A46b=='83') && ($sfltc!='01' && $sfltc!='02' && $sfltc!='03' && $sfltc!='05' && $sfltc!='06' && $sfltc!='07' && $sfltc!='08' && $sfltc!='09' && $sfltc!='10'))
					{
						return "A09_A46a_A46b_LAD_2[".$sa."]: If the aim is delivered under the Employability Skills Project the learning aim must be a basic skill eligible for funding under these projects\n";
					}
				}
			}

			private function rule_A09_A46a_A46b_LAD_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					 
					 

					$query = "select NOTIONAL_NVQ_LEVEL_CODE from learning_aim where LEARNING_AIM_REF='{$ilr->aims[$sa]->A09}'";
					$nvlc = trim(DAO::getSingleValue($linklad, $query));

					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);
					if( ($A46a=='82' || $A46b=='82') && ($nvlc!='3'))
					{
						return "A09_A46a_A46b_LAD_3[".$sa."]: If the learner is coded as learner account then the learning aim must be level 3 \n";
					}
				}
			}


			private function rule_A10_A15_A16_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					if(trim($ilr->aims[$sa]->A10)=='40' && trim($ilr->aims[$sa]->A16)=='04' && trim($ilr->aims[$sa]->A15)!='06')
					{

						return "A10_A15_A16_2[".$sa."]: For WBL funded main aims, If the Programme Entry Route is 'Resume NVQ3 after NVQ2' the Programme Type must be 'NVQ3' \n";

					}
				}
			}

			private function rule_A10_A15_A16_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A16 = trim($ilr->aims[$sa]->A16);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('31/07/2006');
						if($A10=='40' && ($A15=='04' || $A15=='05' || $A15=='06') && ($A16=='01' || $A16=='03' || $A16=='04' || $A16=='06' || $A16=='09' || $A16=='10') && $A27->getDate()>$ed->getDate())
						{

							return "A10_A15_A16_A27_1[".$sa."]: WBL Learners cannot be funded for an NVQ level 1, 2 or 3 programme if they start after 31 July 2006 \n";

						}
					}
					catch(Exception $e)
					{
							return "A10_A15_A16_A27_1[".$sa."]: WBL Learners cannot be funded for an NVQ level 1, 2 or 3 programme if they start after 31 July 2006 \n";
					}
				}
			}

			private function rule_A10_A15_A26_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A26 = trim($ilr->aims[$sa]->A26);

					if($A10=='40' && ($A15=='02' || $A15=='03' || $A15=='10') && $A26=='000')
					{

						return "A10_A15_A26_1[".$sa."]: For WBL funded main aims, Sector code must be entered if the programme type = Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship\n";

					}
				}
			}

			private function rule_A10_A15_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('31/07/2004');
						if($A10=='40' && $A15=='99' && $A27->getDate()>$ed->getDate())
						{
							return "A10_A15_A27_1[".$sa."]: A main aim funded as part of a WBL programme must not have a programme type of 'none of the above' for starts after 31 July 2004\n";
						}
					}
					catch(Exception $e)
					{
						return "A10_A15_A27_1[".$sa."]: A main aim funded as part of a WBL programme must not have a programme type of 'none of the above' for starts after 31 July 2004\n";
					}
				}
			}

			private function rule_A10_A15_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A15 = trim($ilr->aims[$sa]->A15);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$ed = new Date('31/07/2004');
						if( ($A10=='41' || $A10=='42') && ($A15!='02' && $A15!='03' && $A15!='10') && $A27->getDate()>$ed->getDate())
						{

							return "A10_A15_A27_2[".$sa."]: A technical certificate or key skill funded as part of a framework must have a programme type of Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship for starts after 31 July 2004\n";

						}
					}
					catch(Exception $e)
					{
						return "A10_A15_A27_2[".$sa."]: A technical certificate or key skill funded as part of a framework must have a programme type of Apprenticeship, Advanced Apprenticeship or Higher level Apprenticeship for starts after 31 July 2004\n";
					}
				}
			}


			private function rule_A10_A15_A37_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A37 = trim($ilr->aims[$sa]->A37);

					if($A10=='40' && $A15=='09' && $A37!='00' )
					{

						return "A10_A15_A37_1[".$sa."]: Number of units must not be entered for WBL funded E2E main aims.\n";

					}
				}
			}


			private function rule_A10_A15_A38_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A38 = trim($ilr->aims[$sa]->A38);

					if($A10=='40' && $A15=='09' && $A38!='00' )
					{

						return "A10_A15_A38_1[".$sa."]: Number of units to achieve full qualification must not be entered for WBL funded E2E main aims. \n";

					}
				}
			}


			private function rule_A10_A15_A43_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A43 = trim($ilr->aims[$sa]->A43);

					if($A10!='40' && $A43!='00000000' && ($A15!='02' && $A15!='03' && $A15!='10'))
					{

						return "A10_A15_A43_1[".$sa."]: Sector Framework Achievement Date must only be entered for the main aim for programme types Apprenticeship, Advanced Apprenticeship, Higher level Apprenticeship\n";

					}
				}
			}

			private function rule_A10_A15_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A15 = trim($ilr->aims[$sa]->A15);
					$A53 = trim($ilr->aims[$sa]->A53);

					if($A53=='' && ($A10=='40' || $A10=='42') && $A15!='09')
					{

						return "A10_A15_A53_1[".$sa."]: Additional learning needs data must be entered for aims that are WBL funded aims unless they are entry to employment. \n";

					}
				}
			}


			private function rule_A10_A16_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A16 = trim($ilr->aims[$sa]->A16);

					if($A10=='40' && ($A16=='00' || $A16==''))
					{
						return "A10_A16_1[".$sa."]: Programme entry route must be entered for WBL funded main aims. \n";
					}
				}
			}


			private function rule_A10_A16_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A10_A18_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A10_A18_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A10_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A10 = trim($ilr->aims[$sa]->A10);
						$A27 = new Date($ilr->aims[$sa]->A27);
						$check = new Date(date('d/m/Y'));

						if($A10=='40' && $A27->getDate()>$check->getDate())
						{

							return "A10_A27_1[".$sa."]: Start date must be on or before today's date for WBL funded main aims \n";

						}
					}
					catch(Exception $e)
					{
						return "A10_A27_1[".$sa."]: Start date must be on or before today's date for WBL funded main aims \n";

					}
				}
			}


			private function rule_A10_A27_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A10_A31_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A10 = trim($ilr->aims[$sa]->A10);
					$A31 = trim($ilr->aims[$sa]->A31);
					$A50 = trim($ilr->aims[$sa]->A50);

					if(($A10=='40' || $A10=='41' || $A10=='42') && $A31=='00000000' && $A50!='96')
					{

						return "A10_A31_A50_1[".$sa."]: If there is no learning actual end date then Reason Learning Ended must be continuing for WBL funded aims \n";

					}
				}
			}


			private function rule_A10_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A10_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A10_A54_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A10_L35_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A10_L35_A46a_A46b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A16_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A16_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A16_5(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A16_6(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A16_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A26_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A26_A27_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A43_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A50_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A15_A53_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A16_A27(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A27_A28_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A27_A28_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A27_A28_A32_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A27_A31_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A31s = trim($ilr->aims[$sa]->A31);

						if($A31s!='00000000' && $A31s!='dd/mm/yyyy')
						{
							$A31d = new Date($ilr->aims[$sa]->A31);
							if($A31d->getDate() < $A27->getDate())
							{

								return "A27_A31_1[".$sa."]: If present, the learning actual end date to be on or after start date \n";

							}
						}
					}
					catch(Exception $e)
					{
							return "A27_A31_1[".$sa."]: If present, the learning actual end date to be on or after start date \n";

					}
				}
			}



			private function rule_A27_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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
							if($A40d->getDate() <= $A27->getDate())
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


			private function rule_A27_A43_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A27_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$A46a = trim($ilr->aims[$sa]->A46a);
						$A46b = trim($ilr->aims[$sa]->A46b);
						$check = new Date('01/08/2007');

						if($A27->getDate()>=$check->getDate() && ($A46a=='17' || $A46a=='19' || $A46a=='27' || $A46a=='32' || $A46a=='39' || $A46a=='40' || $A46a=='41' || $A46a=='42' || $A46a=='43' || $A46a=='44' || $A46a=='45' || $A46a=='66' || $A46a=='67' || $A46a=='68' || $A46b=='17' || $A46b=='19' || $A46b=='27' || $A46b=='32' || $A46b=='39' || $A46b=='40' || $A46b=='41' || $A46b=='42' || $A46b=='43' || $A46b=='44' || $A46b=='45' || $A46b=='66' || $A46b=='67' || $A46b=='68'))
						{

							return "A27_A46a_A46b_1[".$sa."]: If start date is on or after 1 August 2007 then codes 17, 19, 27, 32, 39-45, 66, 67, 68 are not valid for use in A46a or A46b\n";

						}
					}
					catch(Exception $e)
					{
						return "A27_A46a_A46b_1[".$sa."]: If start date is on or after 1 August 2007 then codes 17, 19, 27, 32, 39-45, 66, 67, 68 are not valid for use in A46a or A46b\n";

					}
				}
			}


			private function rule_A27_L11_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A27_L11_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$L11 = new Date($ilr->learnerinformation->L11);
						$L11->addYears(12);
						$A10 = trim($ilr->aims[$sa]->A10);

						if($L11->getDate() > $A27->getDate() && ($A10!='40' && $A10!='41' && $A10!='42'))
						{

							return "A27_L11_2[".$sa."]: Must be more than 12 years old at start for non WBL funded learning aims \n";

						}
					}
					catch(Exception $e)
					{
						return "A27_L11_2[".$sa."]: Must be more than 12 years old at start for non WBL funded learning aims \n";

					}
				}
			}


			private function rule_A27_L24_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{
				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{
					try
					{
						$A27 = new Date($ilr->aims[$sa]->A27);
						$d = new Date('01/08/2007');
						 
						 

						$query = "select domicile_code from ilr_l24_domiciles where domicile_code='{$ilr->learnerinformation->L24}'";
						$L24 = trim(DAO::getSingleValue($linklis, $query));

						if($A27->getDate() >= $d->getDate() && $L24=='')
						{

							return "A27_A24_1[".$sa."]: If start date is on or after 1 August 2007 then the country of domicile code must be available for new starters \n";

						}
					}
					catch(Exception $e)
					{

							return "A27_A24_1[".$sa."]: If start date is on or after 1 August 2007 then the country of domicile code must be available for new starters \n";

					}
				}
			}



			private function rule_A31_A34_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A31 = trim($ilr->aims[$sa]->A31);
					$A34 = trim($ilr->aims[$sa]->A34);

					if($A34=='1' && $A31!='00000000' && $A31!='dd/mm/yyyy')
					{

						return "A31_A34_1[".$sa."]: Completion status must not be ‘the learner is continuing or intending to continue the learning activities leading to the learning aim’ if the Learning actual end date is completed.\n";

					}
				}
			}


			private function rule_A31_A34_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A31 = trim($ilr->aims[$sa]->A31);
					$A34 = trim($ilr->aims[$sa]->A34);

					if($A34!='1' && ($A31=='00000000' || $A31=='dd/mm/yyyy'))
					{

						return "A31_A34_2[".$sa."]: Completion status must be ‘the learner is continuing or intending to continue the learning activities leading to the learning aim’ if the Learning actual end date is not completed..\n";

					}
				}
			}


			private function rule_A31_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A31_A40_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A31_A50_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A31_L39_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A31 = trim($ilr->aims[$sa]->A31);
					$L39 = trim($ilr->learnerinformation->L39);

					if(($A31=='00000000' || $A31=='dd/mm/yyyy') && $L39!='95')
					{

						return "A31_L39_1[".$sa."]: If the learning actual end date is not entered, then the destination must be continuing\n";

					}
				}
			}


			private function rule_A34_A35_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

			private function rule_A34_A35_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A35_A40_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


			private function rule_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);

					if($A46a!='99' && $A46a==$A46b)
					{

						return "A46a_A46b_1[".$sa."]: National Learning Aim Monitoring values must be different unless they are both 99 \n";

					}
				}
			}


			private function rule_A46a_A46b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
			{

				for($sa=0;$sa<=$ilr->subaims;$sa++)
				{

					$A46a = trim($ilr->aims[$sa]->A46a);
					$A46b = trim($ilr->aims[$sa]->A46b);

					if($A46a=='99' && $A46b!='99')
					{

						return "A46a_A46b_2[".$sa."]: A46b should not be used if A46a is set to 99 \n";

					}
				}
			}


	private function rule_L01_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select CAPN from providers where CAPN='{$ilr->learnerinformation->L01}'";
		$L01 = trim(DAO::getSingleValue($linklis, $query));

		if($L01!=trim($ilr->learnerinformation->L01))
		{
			return "L01_2: Invalid provider number\n";
		}
	}



	private function rule_L02_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L02 = trim($ilr->learnerinformation->L02);
		if($L02!='00')
		{
			return "L02_2: Contract type must be 00 \n";
		}

	}

	private function rule_L03_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L03 = trim($ilr->learnerinformation->L03);
		if($L03=='')
		{
			return "L03_1: You must enter learner reference number\n";
		}

	}

	private function rule_L03_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_L06_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L06 = trim($ilr->learnerinformation->L06);
		if($L06!='00')
		{
			return "L06_1: ESF co-financing data sets must be 00 \n";
		}

	}


	private function rule_L07_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L07 = trim($ilr->learnerinformation->L07);
		if($L07!='00')
		{
			return "L07_1: HE data sets must be 00 \n";
		}

	}


	private function rule_L08_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L08 = trim($ilr->learnerinformation->L08);
		if($L08!='Y' && $L08!='N')
		{
			return "L08_1: Deletion must be N or Y \n";
		}

	}

	private function rule_L09_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L09 = trim($ilr->learnerinformation->L09);
		if($L09=='')
		{
			return "L09_1: Learner surname is mandatory \n";
		}

	}

	private function rule_L09_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_L10_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L10 = trim($ilr->learnerinformation->L10);
		if($L10=='')
		{
			return "L10_1: Learner forenames is mandatory \n";
		}

	}

	private function rule_L10_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L10 = trim($ilr->learnerinformation->L10);
		$st="⌡♪ÑabcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ -'";
		for($lp=0;$lp<strlen($L10);$lp++)
		{
			if(strpos($st,substr($L10,$lp,1))==false)
			{
				throw new Exception($L10."-".$lp);
				return "L10_2: Learner forenames contains invalid characters \n";
			}
		}

	}


	private function rule_L11_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_L11_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$d = new Date($ilr->learnerinformation->L11);
		$start = new Date('01/08/1981');
		$end   = new Date('01/08/2007');

		if($d->getDate() < $start->getDate() || $d->getDate()>$end->getDate())
		{
			return "L11_3: Date of birth must be between 01/08/1981 and 01/08/2007\n";

		}

	}



	private function rule_L11_6(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_L12_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select ethnicity_code from ilr_l12_ethnicity where ethnicity_code='{$ilr->learnerinformation->L12}'";
		$L12 = trim(DAO::getSingleValue($linklis, $query));

		if($L12!=trim($ilr->learnerinformation->L12))
		{
			return "L12_2: Invalid ethnicity code\n";
		}
	}

	private function rule_L13_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L13 = trim($ilr->learnerinformation->L13);

		if($L13!='M' && $L13!='F')
		{

			return "L13_2: Invalid character for gender\n";

		}

	}

	private function rule_L14_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select difficulty_disability from ilr_l14_difficulty_disability where difficulty_disability='{$ilr->learnerinformation->L14}'";
		$L14 = trim(DAO::getSingleValue($linklis, $query));

		if($L14!=trim($ilr->learnerinformation->L14))
		{
			return "L14_2: Invalid Learning difficulties or disability \n";
		}
	}

	private function rule_L15_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select disability_code from ilr_l15_disability where disability_code='{$ilr->learnerinformation->L15}'";
		$L15 = trim(DAO::getSingleValue($linklis, $query));

		if($L15!=trim($ilr->learnerinformation->L15))
		{
			return "L15_2: Invalid disability code \n";
		}
	}

	private function rule_L16_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select difficulty_code from ilr_l16_difficulty where difficulty_code='{$ilr->learnerinformation->L16}'";
		$L16 = trim(DAO::getSingleValue($linklis, $query));

		if($L16!=trim($ilr->learnerinformation->L16))
		{
			return "L16_2: Invalid difficulty code \n";
		}
	}


	private function rule_L17_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L17 = trim($ilr->learnerinformation->L17);
		if($L17!='')
		{$check = substr($L17,(strpos($L17," ")+1),3);
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

	private function rule_L17_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L17 = trim($ilr->learnerinformation->L17);
		if($L17!='')
		{$check = substr($L17,0,(strpos($L17," ")));
		 $first = substr($check,0,1);
		}
		if($L17!='')
			if(ord($first)<65 || ord($first)>90 || strlen($check)<2 || strlen($check)>4)
				if($check!='Z99')
		{

			return "L17_4: Invalid home postcode\n";

		}

	}

	private function rule_L18_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L18 = trim($ilr->learnerinformation->L18);
		if($L18=='')
		{
			return "L18_1: Address is mandatory \n";
		}

	}


	private function rule_L18_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_L19_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_L20_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_L21_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_L22_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L22 = trim($ilr->learnerinformation->L22);
		if($L22!='')
		{$check = substr($L22,(strpos($L22," ")+1),3);
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

	private function rule_L22_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L22 = trim($ilr->learnerinformation->L22);
		if($L22!='')
		{$check = substr($L22,0,(strpos($L22," ")));
		 $first = substr($check,0,1);
		}
		if($L22!='')
			if(ord($first)<65 || ord($first)>90 || strlen($check)<2 || strlen($check)>4)
				if($check!='Z99')
		{

			return "L22_4: Invalid current postcode\n";

		}

	}

	private function rule_L23_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$ilr->learnerinformation->L23 = trim($ilr->learnerinformation->L23);
		$L23 = trim($ilr->learnerinformation->L23);
		$st="ô0123456789";
		for($lp=0;$lp<strlen($L23);$lp++)
		{
			if(strpos($st,substr($L23,$lp,1))==false)
			{
				return "L23_1: Telephone number must contains only digits \n";
			}
		}
	}


	private function rule_L24_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select domicile_code from ilr_l24_domiciles where domicile_code='{$ilr->learnerinformation->L24}'";
		$L24 = trim(DAO::getSingleValue($linklis, $query));

		if($L24!=trim($ilr->learnerinformation->L24))
		{
			return "L24_1: Invalid country of domicile \n";
		}
	}


	private function rule_L25_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select concat(code,satellite_office) from lsc where concat(code,satellite_office)='{$ilr->learnerinformation->L25}'";
		$L25 = trim(DAO::getSingleValue($linklis, $query));

		if($L25=='000' || $L25!=trim($ilr->learnerinformation->L25))
		{
			return "L25_2: Invalid LSC number of funding LSC \n";
		}
	}


	private function rule_L26_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L26 = trim($ilr->learnerinformation->L26);
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


	private function rule_L27_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select restricted_use_code from ilr_l27_restricted_uses where restricted_use_code='{$ilr->learnerinformation->L27}'";
		$L27 = trim(DAO::getSingleValue($linklis, $query));

		if($L27!=trim($ilr->learnerinformation->L27))
		{
			return "L27_1: Invalid restricted use indicator \n";
		}
	}


	private function rule_L28a_7(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select eligibility_enhanced_code from ilr_l28_eligibil_enhance_fnds where eligibility_enhanced_code='{$ilr->learnerinformation->L28a}'";
		$L28a = trim(DAO::getSingleValue($linklis, $query));

		if($L28a!=trim($ilr->learnerinformation->L28a))
		{
			return "L28a_7: Invalid eligibility for enhanced funding 1 \n";
		}
	}

	private function rule_L28b_7(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select eligibility_enhanced_code from ilr_l28_eligibil_enhance_fnds where eligibility_enhanced_code='{$ilr->learnerinformation->L28b}'";
		$L28b = trim(DAO::getSingleValue($linklis, $query));

		if($L28b!=trim($ilr->learnerinformation->L28b))
		{
			return "L28b_7: Invalid eligibility for enhanced funding 2 \n";
		}
	}

	private function rule_L29_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L29 = trim($ilr->learnerinformation->L29);
		if($L29!='00')
			{
				return "L29_2: Additional support must be 00 \n";
			}
	}


	private function rule_L31_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L31 = trim($ilr->learnerinformation->L31);
		if($L31!='000000')
			{
				return "L31_4: Additional support cost must be 000000 \n";
			}
	}


	private function rule_L32_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L32 = trim($ilr->learnerinformation->L32);
		if($L32!='000000')
			{
				return "L32_2: Additional support cost must be 000000 \n";
			}
	}


	private function rule_L33_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L33 = trim($ilr->learnerinformation->L33);
		if($L33!='0.0000')
			{
				return "L33_2: Disadvantage uplift factor must be 0.0000 \n";
			}
	}


	private function rule_L34a_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L34a = trim($ilr->learnerinformation->L34a);
		if($L34a!='01' && $L34a!='25' && $L34a!='41' && $L34a!='99')
			{
				return "L34a_2: Learner support reason 1 must be 01, 25, 41 or 99 \n";
			}
	}


	private function rule_L34b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L34b = trim($ilr->learnerinformation->L34b);
		if($L34b!='01' && $L34b!='25' && $L34b!='41' && $L34b!='99')
			{
				return "L34b_2: Learner support reason 2 must be 01, 25, 41 or 99 \n";
			}
	}


	private function rule_L34c_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L34c = trim($ilr->learnerinformation->L34c);
		if($L34c!='01' && $L34c!='25' && $L34c!='41' && $L34c!='99')
			{
				return "L34c_2: Learner support reason 3 must be 01, 25, 41 or 99 \n";
			}
	}


	private function rule_L34d_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L34d = trim($ilr->learnerinformation->L34d);
		if($L34d!='01' && $L34d!='25' && $L34d!='41' && $L34d!='99')
			{
				return "L34d_2: Learner support reason 4 must be 01, 25, 41 or 99 \n";
			}
	}


	private function rule_L35_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select prior_attainment_level_code from ilr_l35_prior_attainment_level where prior_attainment_level_code='{$ilr->learnerinformation->L35}'";
		$L35 = trim(DAO::getSingleValue($linklis, $query));

		if($L35!=trim($ilr->learnerinformation->L35))
		{
			return "L35_1: Invalid prior attainment level code  \n";
		}
	}


	private function rule_L36_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select learner_status_code from ilr_l36_learner_status where learner_status_code='{$ilr->learnerinformation->L36}'";
		$L36 = trim(DAO::getSingleValue($linklis, $query));

		if($L36!=trim($ilr->learnerinformation->L36))
		{
			return "L36_1: Invalid learner status on last working day before learning  \n";
		}
	}


	private function rule_L37_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select employment_status_first_code from ilr_l37_employ_status_firsts where employment_status_first_code='{$ilr->learnerinformation->L37}'";
		$L37 = trim(DAO::getSingleValue($linklis, $query));

		if($L37!=trim($ilr->learnerinformation->L37))
		{
			return "L37_1: Invalid employment status on first day of learning \n";
		}
	}

	private function rule_L39_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select destination_code from ilr_l39_destinations where destination_code='{$ilr->learnerinformation->L39}'";
		$L39 = trim(DAO::getSingleValue($linklis, $query));

		if($L39!=trim($ilr->learnerinformation->L39))
		{
			return "L39_1: Invalid destination \n";
		}
	}

	private function rule_L40a_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L40a = trim($ilr->learnerinformation->L40a);
		if($L40a!='12' && $L40a!='13' && $L40a!='14' && $L40a!='15' && $L40a!='16' && $L40a!='17' && $L40a!='18' && $L40a!='19' && $L40a!='99')
			{
				return "L40a_2: National learner monitoring 1 must be 12-19 or 99 \n";
			}
	}


	private function rule_L40b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L40b = trim($ilr->learnerinformation->L40b);
		if($L40b!='12' && $L40b!='13' && $L40b!='14' && $L40b!='15' && $L40b!='16' && $L40b!='17' && $L40b!='18' && $L40b!='19' && $L40b!='99')
			{
				return "L40b_2: National learner monitoring 2 must be 12-19 or 99 \n";
			}
	}


	private function rule_L41a_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L41a = trim($ilr->learnerinformation->L41a);
		if((int)$L41a < 0)
			{
				return "L41a_1: Local learner monitoring 1 must be greater than or 0 if entered \n";
			}
	}


	private function rule_L41b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L41b = trim($ilr->learnerinformation->L41b);
		if((int)$L41b < 0)
			{
				return "L41b_1: Local learner monitoring 2 must be greater than or 0 if entered \n";
			}
	}


	private function rule_L42a_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L42a = trim($ilr->learnerinformation->L42a);

		if(strpos($L42a,'*')!= false || strpos($L42a,'?')!= false  || strpos($L42a,'%')!=false || strpos($L42a,'_')!=false)
		{
			return "L42a_1: Provider specified learner data 1 may be any printable characters except for *, ?, % or _ symbols \n";
		}

	}

	private function rule_L42b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L42b = trim($ilr->learnerinformation->L42b);

		if(strpos($L42b,'*')!= false || strpos($L42b,'?')!= false  || strpos($L42b,'%')!=false || strpos($L42b,'_')!=false)
		{
			return "L42b_1: Provider specified learner data 2 may be any printable characters except for *, ?, % or _ symbols \n";
		}

	}


	private function rule_L44_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L44a = trim($ilr->learnerinformation->L44);

		if($L44a=='002')
		{
			return "L44_1: NCS delivery LSC number must not be 002 \n";
		}

	}

	private function rule_L44_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select concat(code,satellite_office) from lsc where concat(code,satellite_office)='{$ilr->learnerinformation->L44}'";
		$L44 = trim(DAO::getSingleValue($linklis, $query));

		if($L44!=trim($ilr->learnerinformation->L44))
		{
			return "L44_3: Invalid NCS delivery LSC number \n";
		}
	}


	private function rule_L45_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L45a = trim($ilr->learnerinformation->L45);

		if($L45a!='0000000000' && ( (float)$L45a<1000000000 || (float)$L45a>9999999999))
		{
			return "L45_1: Unique learner number must be in the format 1000000000 - 9999999999 (if entered) \n";
		}

	}


	private function rule_L46_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select ukprn from providers where ukprn='{$ilr->learnerinformation->L46}'";
		$L46 = trim(DAO::getSingleValue($linklis, $query));

		if($L46!=trim($ilr->learnerinformation->L46))
		{
			return "L46_1: Invalid UK Provider reference number \n";
		}
	}


	private function rule_L47_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		 
		 

		$query = "select employment_status_first_code from ilr_l37_employ_status_firsts where employment_status_first_code='{$ilr->learnerinformation->L47}'";
		$L47 = trim(DAO::getSingleValue($linklis, $query));

		if($L47!=trim($ilr->learnerinformation->L47))
		{
			return "L47_1: Invalid current employment status \n";
		}
	}

	private function rule_L48_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L48s = trim($ilr->learnerinformation->L48);
		if($L48s!='00000000')
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



	private function rule_L48_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$L48 = trim($ilr->learnerinformation->L48);
		$start = new Date('01/01/2000');
		$end   = new Date('01/08/2010');

		if($L48!='00000000')
		{
			$d = new Date($ilr->learnerinformation->L48);
			if($d->getDate()<$start->getDate() || $d->getDate()>$end->getDate())
				{

					return "L48_2: Date of employment status changed must be between 01/01/2000 and 01/08/2010\n";

				}
		}
	}





	private function rule_L05_L08_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L05 = trim($ilr->learnerinformation->L05);
		$L08 = trim($ilr->learnerinformation->L08);

		if($L08=='Y' && (int)$L05!=0)
		{

			return "L05_L08_1: Must be no learning aims data sets if the delete flag is set \n";

		}

	}

	private function rule_L05_L08_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L05 = trim($ilr->learnerinformation->L05);
		$L08 = trim($ilr->learnerinformation->L08);

		if( ($L08=='N' || $L08==' ') && (int)$L05==0)
		{

			return "L05_L08_2: Must be at least one learning aim if there is no delete flag \n";

		}

	}

	private function rule_L14_L15_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L14 = trim($ilr->learnerinformation->L14);
		$L15 = trim($ilr->learnerinformation->L15);

		if($L14=='9' && $L15!='99')
		{

			return "L14_L15_1: Disability or Health Problem must be set to ‘Not known/information not provided’ if Learning difficulties and/or disabilities is set to ‘No information provided by the learner’.\n";

		}

	}

	private function rule_L14_L15_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L14 = trim($ilr->learnerinformation->L14);
		$L15 = trim($ilr->learnerinformation->L15);

		if($L14=='2' && $L15!='98')
		{

			return "L14_L15_2: Disability or Health Problem must be set to ‘No disability’ if Learning difficulties and/or disabilities is set to ‘Learner does not consider himself or herself to have a learning difficulty and/or disability or health problem’.  \n";

		}

	}

	private function rule_L14_L16_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L14 = trim($ilr->learnerinformation->L14);
		$L16 = trim($ilr->learnerinformation->L16);

		if($L14=='9' && $L16!='99')
		{

			return "L14_L16_1: Learning Difficulty must be set to ‘Not known/information not provided’ if Learning difficulties and/or disabilities is set to ‘No information provided by the learner’  \n";

		}

	}


	private function rule_L14_L16_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L14 = trim($ilr->learnerinformation->L14);
		$L16 = trim($ilr->learnerinformation->L16);

		if($L14=='2' && $L16!='98')
		{

			return "L14_L16_2: Learening Difficulty must be set to ‘No learning difficulty’ if Learning difficulties and/or disabilities is set to ‘Learner does not consider himself or herself to have a learning difficulty and/or disability or health problem. \n";

		}

	}


	private function rule_L17_L24_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L17 = trim($ilr->learnerinformation->L17);
		$L24 = trim($ilr->learnerinformation->L24);

		if( ($L24=='199' || $L24=='399' || $L24=='999' || $L24=='099' || $L24=='299' || $L24=='599') && $L17=='')
		{

			return "L17_L24_1: Postcode is mandatory unless country code is not UK \n";

		}

	}


	private function rule_L25_A46a_A46b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{

			$A46a = trim($ilr->aims[$sa]->A46a);
			$A46b = trim($ilr->aims[$sa]->A46b);
			$L25 = trim($ilr->learnerinformation->L25);

			if(($A46a=='30' || $A46b=='30') && $L25!='002')
			{

				return "L25_A46a_A46b_1[".$sa."]: The Basic Skills Project for National Employers is only available for providers who contract with the National Contracts Service  \n";

			}
		}
	}


	private function rule_L25_L44_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L25 = trim($ilr->learnerinformation->L25);
		$L44 = trim($ilr->learnerinformation->L44);

		if($L25=='002' && $L44=='')
		{

			return "L25_L44_1: Delivery locations must be present for NCS contracts \n";

		}

	}


	private function rule_L25_L44_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L25 = trim($ilr->learnerinformation->L25);
		$L44 = trim($ilr->learnerinformation->L44);

		if($L44!='' && $L25!='002')
		{

			return "L25_L44_2: Non NES contracts must not fill in L44 \n";

		}

	}


	private function rule_L27_L39_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L27 = trim($ilr->learnerinformation->L27);
		$L39 = trim($ilr->learnerinformation->L39);

		if($L39=='61' && $L27!='2')
		{

			return "L27_L39_1: If destination code is set to 'death' then the restricted use indicator must be set to 'learner is not to be contacted' \n";

		}

	}


	private function rule_L28a_L28b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L28a = trim($ilr->learnerinformation->L28a);
		$L28b = trim($ilr->learnerinformation->L28b);

		if($L28a == $L28b && ($L28a!='99' && $L28a!='00'))
		{

			return "L28a_L28b_2: Entries for Eligibility for enhanced funding must be different unless they are both 99 or null \n";

		}

	}


	private function rule_L34a_L34b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L34a = trim($ilr->learnerinformation->L34a);
		$L34b = trim($ilr->learnerinformation->L34b);

		if($L34a != '99' && $L34a==$L34b)
		{

			return "L34a_L34b_1: Learner Support Reasons must all be different unless they are 99 \n";

		}

	}

	private function rule_L34a_L34c_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L34a = trim($ilr->learnerinformation->L34a);
		$L34c = trim($ilr->learnerinformation->L34c);

		if($L34a != '99' && $L34a==$L34c)
		{

			return "L34a_L34c_1: Learner Support Reasons must all be different unless they are 99 \n";

		}

	}


	private function rule_L34a_L34d_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L34a = trim($ilr->learnerinformation->L34a);
		$L34d = trim($ilr->learnerinformation->L34d);

		if($L34a != '99' && $L34a==$L34d)
		{

			return "L34a_L34d_1: Learner Support Reasons must all be different unless they are 99 \n";

		}

	}


	private function rule_L34b_L34c_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L34b = trim($ilr->learnerinformation->L34b);
		$L34c = trim($ilr->learnerinformation->L34c);

		if($L34b != '99' && $L34b==$L34c)
		{

			return "L34b_L34c_1: Learner Support Reasons must all be different unless they are 99 \n";

		}

	}


	private function rule_L34b_L34d_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L34b = trim($ilr->learnerinformation->L34b);
		$L34d = trim($ilr->learnerinformation->L34d);

		if($L34b != '99' && $L34b==$L34d)
		{

			return "L34b_L34d_1: Learner Support Reasons must all be different unless they are 99 \n";

		}

	}


	private function rule_L34c_L34d_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L34c = trim($ilr->learnerinformation->L34c);
		$L34d = trim($ilr->learnerinformation->L34d);

		if($L34c != '99' && $L34c==$L34d)
		{

			return "L34c_L34d_1: Learner Support Reasons must all be different unless they are 99 \n";

		}

	}


	private function rule_L37_L47_L48_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_L40a_L40b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		$L40a = trim($ilr->learnerinformation->L40a);
		$L40b = trim($ilr->learnerinformation->L40b);

		if($L40a==$L40b && $L40a!='99')
		{

			return "L40a_L40b_1: The National Learning Monitoring fields must not contain the same values unless they are both 99 \n";

		}

	}


	private function rule_E01_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E02_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E06_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E07_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E08_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_E08_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$d = new Date('01/09/2001');
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01')
			{
				try
				{
					$E08 = new Date($ilr->aims[$sa]->E08);
					if($E08->getDate()<$d->getDate())
						return "E08_3[".$sa."]: Date started ESF Co-financing must not be before 01/09/2001 \n";

				}
				catch(Exception $e)
				{
					return "E08_3[".$sa."]: Invalid date started ESF Co-financing \n";
				}
			}
		}
	}

	private function rule_E09_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_E09_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$d = new Date('01/09/2001');
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01')
			{
				try
				{
					$E09 = new Date($ilr->aims[$sa]->E09);
					if($E09->getDate()<$d->getDate())
						return "E09_2[".$sa."]: Planned end date for ESF Co-financing must not be before 01/09/2001 \n";

				}
				catch(Exception $e)
				{
					return "E09_2[".$sa."]: Invalid planned end date for ESF Co-financing \n";
				}
			}
		}
	}


	private function rule_E10_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_E10_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		$d = new Date('01/09/2001');
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && $ilr->aims[$sa]->E10!='00000000')
			{
				try
				{
					$E10 = new Date($ilr->aims[$sa]->E10);
					if($E10->getDate()<$d->getDate())
						return "E10_2[".$sa."]: Date ended ESF Co-financing must not be before 01/09/2001 \n";

				}
				catch(Exception $e)
				{
					return "E10_2[".$sa."]: Invalid date ended ESF Co-financing \n";
				}
			}
		}
	}



	private function rule_E11_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01')
			{
				 
				 

				$query = "select Industrial_Sector_Code from ilr_E11_Industrial_Sectors where Industrial_Sector_Code='{$ilr->aims[$sa]->E11}'";
				$E11 = trim(DAO::getSingleValue($linklis, $query));

				if($E11!=trim($ilr->aims[$sa]->E11))
				{
					return "E11_1[".$sa."]: Invalid Industrial sector of learners employer \n";
				}
			}
		}
	}


	private function rule_E12_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01')
			{
				 
				 

				$query = "select Employment_status_esf_code from ilr_E12_employ_status_esf where Employment_status_esf_Code='{$ilr->aims[$sa]->E12}'";
				$E12 = trim(DAO::getSingleValue($linklis, $query));

				if($E12!=trim($ilr->aims[$sa]->E12))
				{
					return "E12_1[".$sa."]: Invalid Learners employment status on day before starting ESF project \n";
				}
			}
		}
	}


	private function rule_E13_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01')
			{
				 
				 

				$query = "select Learner_employment_status_code from ilr_E13_learner_employ_status where Learner_employment_status_Code='{$ilr->aims[$sa]->E13}'";
				$E13 = trim(DAO::getSingleValue($linklis, $query));

				if($E13!=trim($ilr->aims[$sa]->E13))
				{
					return "E13_1[".$sa."]: Invalid Learners employment status  \n";
				}
			}
		}
	}

	private function rule_E14_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01')
			{
				 
				 

				$query = "select Length_unemployment_esf_code from ilr_E14_length_unemploy_esf where Length_unemployment_esf_code='{$ilr->aims[$sa]->E14}'";
				$E14 = trim(DAO::getSingleValue($linklis, $query));

				if($E14!=trim($ilr->aims[$sa]->E14))
				{
					return "E14_1[".$sa."]: Invalid Length of unemployment before starting ESF project \n";
				}
			}
		}
	}


	private function rule_E15_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01')
			{
				 
				 

				$query = "select employer_type_code from ilr_E15_employer_types where employer_type_code='{$ilr->aims[$sa]->E15}'";
				$E15 = trim(DAO::getSingleValue($linklis, $query));

				if($E15!=trim($ilr->aims[$sa]->E15))
				{
					return "E15_1[".$sa."]: Invalid Type and size of learners employer \n";
				}
			}
		}
	}

	private function rule_E16a_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16a!=''))
			{
				 
				 

				$query = "select gender_stereotype_code from ilr_E16_gender_stereotypes where gender_stereotype_code='{$ilr->aims[$sa]->E16a}'";
				$E16a = trim(DAO::getSingleValue($linklis, $query));

				if($E16a!=trim($ilr->aims[$sa]->E16a))
				{
					return "E16a_1[".$sa."]: Invalid 1 addressing gender sterotype code \n";
				}
			}
		}
	}

	private function rule_E16b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16b!=''))
			{
				 
				 

				$query = "select gender_stereotype_code from ilr_E16_gender_stereotypes where gender_stereotype_code='{$ilr->aims[$sa]->E16b}'";
				$E16b = trim(DAO::getSingleValue($linklis, $query));

				if($E16b!=trim($ilr->aims[$sa]->E16b))
				{
					return "E16b_1[".$sa."]: Invalid 2 addressing gender sterotype code \n";
				}
			}
		}
	}

	private function rule_E16c_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16c!=''))
			{
				 
				 

				$query = "select gender_stereotype_code from ilr_E16_gender_stereotypes where gender_stereotype_code='{$ilr->aims[$sa]->E16c}'";
				$E16c = trim(DAO::getSingleValue($linklis, $query));

				if($E16c!=trim($ilr->aims[$sa]->E16c))
				{
					return "E16c_1[".$sa."]: Invalid 3 addressing gender sterotype code \n";
				}
			}
		}
	}

	private function rule_E16d_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16d!=''))
			{
				 
				 

				$query = "select gender_stereotype_code from ilr_E16_gender_stereotypes where gender_stereotype_code='{$ilr->aims[$sa]->E16d}'";
				$E16d = trim(DAO::getSingleValue($linklis, $query));

				if($E16d!=trim($ilr->aims[$sa]->E16d))
				{
					return "E16d_1[".$sa."]: Invalid 4 addressing gender sterotype code \n";
				}
			}
		}
	}

	private function rule_E16e_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E16e!=''))
			{
				 
				 

				$query = "select gender_stereotype_code from ilr_E16_gender_stereotypes where gender_stereotype_code='{$ilr->aims[$sa]->E16e}'";
				$E16e = trim(DAO::getSingleValue($linklis, $query));

				if($E16e!=trim($ilr->aims[$sa]->E16e))
				{
					return "E16e_1[".$sa."]: Invalid 5 addressing gender sterotype code \n";
				}
			}
		}
	}

	private function rule_E17a_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E17a = $ilr->aims[$sa]->E17a;

			if($A06=='01' && $E17a!=' ')
			{

				return "E17a_2[".$sa."]: Main co-financing activity 1 must be space filled \n";

			}
		}
	}

	private function rule_E17b_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E17b = $ilr->aims[$sa]->E17b;

			if($A06=='01' && $E17b!=' ')
			{

				return "E17b_2[".$sa."]: Main co-financing activity 2 must be space filled \n";

			}
		}
	}

	private function rule_E17c_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E17c = $ilr->aims[$sa]->E17c;

			if($A06=='01' && $E17c!=' ')
			{

				return "E17c_2[".$sa."]: Main co-financing activity 3 must be space filled \n";

			}
		}
	}

	private function rule_E17d_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E17d = $ilr->aims[$sa]->E17d;

			if($A06=='01' && $E17d!=' ')
			{

				return "E17d_2[".$sa."]: Main co-financing activity 4 must be space filled \n";

			}
		}
	}

	private function rule_E17e_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E17e = $ilr->aims[$sa]->E17e;

			if($A06=='01' && $E17e!=' ')
			{

				return "E17e_2[".$sa."]: Main co-financing activity 5 must be space filled \n";

			}
		}
	}

	private function rule_E18a_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E18a!=''))
			{
				 
				 

				$query = "select Delivery_mode_code from ilr_E18_esf_delivery_modes where delivery_mode_code='{$ilr->aims[$sa]->E18a}'";
				$E18a = trim(DAO::getSingleValue($linklis, $query));

				if($E18a!=trim($ilr->aims[$sa]->E18a))
				{
					return "E18a_1[".$sa."]: Invalid 1 delivery mode code \n";
				}
			}
		}
	}

	private function rule_E18b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E18b!=''))
			{
				 
				 

				$query = "select Delivery_mode_code from ilr_E18_esf_delivery_modes where delivery_mode_code='{$ilr->aims[$sa]->E18b}'";
				$E18b = trim(DAO::getSingleValue($linklis, $query));

				if($E18b!=trim($ilr->aims[$sa]->E18b))
				{
					return "E18b_1[".$sa."]: Invalid 2 delivery mode code \n";
				}
			}
		}
	}

	private function rule_E18c_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E18c!=''))
			{
				 
				 

				$query = "select Delivery_mode_code from ilr_E18_esf_delivery_modes where delivery_mode_code='{$ilr->aims[$sa]->E18c}'";
				$E18c = trim(DAO::getSingleValue($linklis, $query));

				if($E18c!=trim($ilr->aims[$sa]->E18c))
				{
					return "E18c_1[".$sa."]: Invalid 3 delivery mode code \n";
				}
			}
		}
	}

	private function rule_E18d_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E18d!=''))
			{
				 
				 

				$query = "select Delivery_mode_code from ilr_E18_esf_delivery_modes where delivery_mode_code='{$ilr->aims[$sa]->E18d}'";
				$E18d = trim(DAO::getSingleValue($linklis, $query));

				if($E18d!=trim($ilr->aims[$sa]->E18d))
				{
					return "E18d_1[".$sa."]: Invalid 4 delivery mode code \n";
				}
			}
		}
	}

	private function rule_E19a_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19a!=''))
			{
				 
				 

				$query = "select support_measures_code from ilr_E19_esf_supp_measures where support_measures_code='{$ilr->aims[$sa]->E19a}'";
				$E19a = trim(DAO::getSingleValue($linklis, $query));

				if($E19a!=trim($ilr->aims[$sa]->E19a))
				{
					return "E19a_1[".$sa."]: Invalid 1 Support measures to be accessed by learner \n";
				}
			}
		}
	}


	private function rule_E19b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19b!=''))
			{
				 
				 

				$query = "select support_measures_code from ilr_E19_esf_supp_measures where support_measures_code='{$ilr->aims[$sa]->E19b}'";
				$E19b = trim(DAO::getSingleValue($linklis, $query));

				if($E19b!=trim($ilr->aims[$sa]->E19b))
				{
					return "E19b_1[".$sa."]: Invalid 2 Support measures to be accessed by learner \n";
				}
			}
		}
	}

	private function rule_E19c_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19c!=''))
			{
				 
				 

				$query = "select support_measures_code from ilr_E19_esf_supp_measures where support_measures_code='{$ilr->aims[$sa]->E19c}'";
				$E19c = trim(DAO::getSingleValue($linklis, $query));

				if($E19c!=trim($ilr->aims[$sa]->E19c))
				{
					return "E19c_1[".$sa."]: Invalid 3 Support measures to be accessed by learner \n";
				}
			}
		}
	}

	private function rule_E19d_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19d!=''))
			{
				 
				 

				$query = "select support_measures_code from ilr_E19_esf_supp_measures where support_measures_code='{$ilr->aims[$sa]->E19d}'";
				$E19d = trim(DAO::getSingleValue($linklis, $query));

				if($E19d!=trim($ilr->aims[$sa]->E19d))
				{
					return "E19d_1[".$sa."]: Invalid 4 Support measures to be accessed by learner \n";
				}
			}
		}
	}

	private function rule_E19e_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E19e!=''))
			{
				 
				 

				$query = "select support_measures_code from ilr_E19_esf_supp_measures where support_measures_code='{$ilr->aims[$sa]->E19e}'";
				$E19e = trim(DAO::getSingleValue($linklis, $query));

				if($E19e!=trim($ilr->aims[$sa]->E19e))
				{
					return "E19e_1[".$sa."]: Invalid 5 Support measures to be accessed by learner \n";
				}
			}
		}
	}

	private function rule_E20a_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E20a!=''))
			{
				 
				 

				$query = "select Learner_background_code from ilr_E20_learner_backgrounds where Learner_background_code='{$ilr->aims[$sa]->E20a}'";
				$E20a = trim(DAO::getSingleValue($linklis, $query));

				if($E20a!=trim($ilr->aims[$sa]->E20a))
				{
					return "E20a_1[".$sa."]: Invalid 1 Learner background code \n";
				}
			}
		}
	}

	private function rule_E20b_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E20b!=''))
			{
				 
				 

				$query = "select Learner_background_code from ilr_E20_learner_backgrounds where Learner_background_code='{$ilr->aims[$sa]->E20b}'";
				$E20b = trim(DAO::getSingleValue($linklis, $query));

				if($E20b!=trim($ilr->aims[$sa]->E20b))
				{
					return "E20b_1[".$sa."]: Invalid 2 Learner background code \n";
				}
			}
		}
	}

	private function rule_E20c_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E20c!=''))
			{
				 
				 

				$query = "select Learner_background_code from ilr_E20_learner_backgrounds where Learner_background_code='{$ilr->aims[$sa]->E20c}'";
				$E20c = trim(DAO::getSingleValue($linklis, $query));

				if($E20c!=trim($ilr->aims[$sa]->E20c))
				{
					return "E20c_1[".$sa."]: Invalid 3 Learner background code \n";
				}
			}
		}
	}

	private function rule_E21_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01' && trim($ilr->aims[$sa]->E21!=''))
			{
				 
				 

				$query = "select Disability_supp_measure_code from ilr_E21_disability_supp_meas where disability_supp_measure_code='{$ilr->aims[$sa]->E21}'";
				$E21 = trim(DAO::getSingleValue($linklis, $query));

				if($E21!=trim($ilr->aims[$sa]->E21))
				{
					return "E21_1[".$sa."]: Invalid disability support measure code \n";
				}
			}
		}
	}

	private function rule_E22_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E22_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E22 = trim($ilr->aims[$sa]->E22);

			if($A06=='01' && is_int(substr($E22,5,1)))
				if( (substr($E22,0,2)!='00' && substr($E22,0,2)!='01' && substr($E22,0,2)!='02' && substr($E22,0,2)!='03' && substr($E22,0,2)!='04' && substr($E22,0,2)!='05' && substr($E22,0,2)!='06' && substr($E22,0,2)!='07') || (isDigit(substr($E22,2,1))==false || isDigit(substr($E22,3,1))==false || isDigit(substr($E22,4,1))==false) || (substr($E22,6,2)!='EA' && substr($E22,6,2)!='LN' && substr($E22,6,2)!='NE' && substr($E22,6,2)!='NW' && substr($E22,6,2)!='SE' && substr($E22,6,2)!='SW' && substr($E22,6,2)!='WM' && substr($E22,6,2)!='EM' && substr($E22,6,2)!='YH' && substr($E22,6,2)!='ME') || ( (int)substr($E22,8,1)!=1 && (int)substr($E22,8,1)!=2 && (int)substr($E22,8,1)!=3) && $E22!='999999WM3')
					{

						return "E22_4[".$sa."]: Invalid project dossier number \n";

					}
		}
	}

	private function rule_E22_5(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_E23_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E24_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E25_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E25 = trim($ilr->aims[$sa]->E25);
			$A56 = trim($ilr->aims[$sa]->A56);

			if($A06=='01' && $E25!=$A56)
				{

					return "E25_1[".$sa."]: UK Provider reference number must be same in both learning aim and ESF Co-financing data set \n";

				}
		}
	}

	private function rule_E08_E09_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E08_E10_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E11_E12_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E12_E13_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E12_E13_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E12_E13_5(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E12_E14_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E12_E14_3(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E12_E14_4(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{

		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			$A06 = trim($ilr->aims[$sa]->A06);
			$E12 = trim($ilr->aims[$sa]->E12);
			$E14 = trim($ilr->aims[$sa]->E14);

			if($A06=='01' && ($E12=='01' || $E12=='02' || $E12=='03' || $E12=='05' || $E12=='06') && $E14!='99')
				{

					return "E12_E14_4[".$sa."]: If employment status before starting is 'employed','full time education','self employed', 'economically inactive' or 'still at school, then the Length of unemployment before starting ESF project must be 'not unemployed'. \n";

				}
		}
	}

	private function rule_E12_E15_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_E16abcde_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_E18abcd_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E19abcde_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_E20abc_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A09_A10_E22_LAD_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
	{
		for($sa=0;$sa<=$ilr->subaims;$sa++)
		{
			if($ilr->aims[$sa]->A06=='01')
			{
				$E22 = trim($ilr->aims[$sa]->E22);
				$A10 = trim($ilr->aims[$sa]->A10);
				 
				 

				$query = "select learning_aim_type_code from learning_aim where learning_aim_ref='{$ilr->aims[$sa]->A09}'";
				$latc = trim(DAO::getSingleValue($linklad, $query));

				if( ($latc=='1438' && substr($E22,5,1)!='L') || ($latc=='1438' && $A10!='80'))
				{
					return "A09_A10_E22_LAD_1[".$sa."]: Learning aims categorised as soft outcomes in the LAD are only allowed for new ESF project or funding stream 80 \n";
				}
			}
		}
	}


	private function rule_A10_E08_E11_E12_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_A10_E08_E11_E12_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A10_E08_E12_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A10_E08_E12_E13_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A10_E08_E12_E13_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_A10_E08_E12_E14_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A10_E08_E12_E14_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A10_E08_E12_E15_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_A10_E08_E12_E15_2(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A10_E22_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A10_E23_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A10_L01_L25_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A27_E08_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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


	private function rule_A27_E12_E15_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A27_E15_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A27_E21_L14_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_A27_E22_1(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	private function rule_R03(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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
	
	
	private function rule_R04(PDO $link, PDO $linklad, PDO $linklis, ILR0708 $ilr)
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

	


	public static function isAlphaNum($ch)
	{
		if(isDigit($ch) || isAlpha($ch))
			return true;
		else
			return false;
	}




	private function dummy($rubbish)
	{
		echo "<p>dummy()</p>";
	}

public $report = NULL;

}

