<?php
class success_rates implements IAction
{
    public function execute(PDO $link)
    {
        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=success_rates", 'Success Rates');

        set_time_limit(0);
        ini_set('memory_limit','8192M');

        $output = isset($_REQUEST['output'])?$_REQUEST['output']:'';

        // Loop through all the contracts starting with the most recent
        $current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");
        $start_year = $current_contract_year;
        //	$link->query("truncate success_rates");

        $this->createTempTable($link);
        $values = '';
        $counter = 0;
        $data = array();
        $ukprn = '';
        if(DB_NAME!='am_de' && DB_NAME!='ams')
        {
            for($year = $current_contract_year; $year>= ($current_contract_year-10); $year--)
            {
                if($_SESSION['user']->isAdmin() OR $_SESSION['user']->type==12)
                {
                    $sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.is_active = 1 and contracts.funding_body = 2 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) AND funding_type=1 and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year) and tr_id not in (select tr_id from qar_archive);";
                }
                else
                {
                    $org_id = $_SESSION['user']->employer_id;
                    $ukprn = DAO::getSingleValue($link, "select ukprn from organisations where id = '$org_id'");
                    if(DB_NAME=="am_lead")
                        $sql = "SELECT * FROM ilr INNER JOIN tr training_records ON (ilr.tr_id = training_records.id AND training_records.provider_id = $org_id) INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.is_active=1 and contracts.funding_body = 2 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) AND funding_type=1 and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year) and tr_id not in (select tr_id from qar_archive);";
                    else
                        $sql = "SELECT * FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE ilr.is_active=1 and contracts.funding_body = 2 and submission = (SELECT MAX(submission) FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year = $year) and locate('$ukprn',ilr)>0 AND funding_type=1 and contract_year = '$year' and tr_id not in (SELECT tr_id FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE contract_year > $year) and tr_id not in (select tr_id from qar_archive);";
                }

                $st = $link->query($sql);
                if($st)
                {
                    while($row = $st->fetch())
                    {
                        if($row['contract_year']<2012)
                        {
                            $ilr = Ilr2011::loadFromXML($row['ilr']);
                            $tr_id = $row['tr_id'];
                            $submission = $row['submission'];
                            $l03 = $row['L03'];
                            $contract_id = $row['contract_id'];
                            $p_prog_status = -1;

                            if($ilr->learnerinformation->L08!="Y")
                            {
                                if(($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="" && $ilr->programmeaim->A15!="0"))
                                {
                                    $programme_type = "Apprenticeship";
                                    $start_date = Date::toMySQL($ilr->programmeaim->A27);
                                    $end_date = Date::toMySQL($ilr->programmeaim->A28);

                                    // Age Band Calculation
                                    if($ilr->learnerinformation->L11!='00/00/0000' && $ilr->learnerinformation->L11!='00000000')
                                    {
                                        $dob = $ilr->learnerinformation->L11;
                                        $dob = Date::toMySQL($dob);
                                        $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                    }
                                    else
                                    {
                                        $age = '';
                                    }
                                    if($age<=18)
                                        $age_band = "16-18";
                                    elseif($age<=23)
                                        $age_band = "19-23";
                                    elseif($age>=24)
                                        $age_band = "24+";
                                    else
                                        $age_band = "Unknown";

                                    if($ilr->programmeaim->A31!='00000000' && $ilr->programmeaim->A31!='00/00/0000' && $ilr->programmeaim->A31!='')
                                        $actual_date = Date::toMySQL($ilr->programmeaim->A31);
                                    else
                                        $actual_date = "0000-00-00";

                                    $comp_status = $ilr->programmeaim->A34;
                                    $outcome = $ilr->programmeaim->A35;
                                    if($ilr->programmeaim->A40!='00000000' && $ilr->programmeaim->A40!='00/00/0000' && $ilr->programmeaim->A40!='')
                                        $achievement_date = Date::toMySQL($ilr->programmeaim->A40);
                                    else
                                        $achievement_date = "0000-00-00";

                                    $level = $ilr->programmeaim->A15;


                                    // Calculation for p_prog_status for apprenticeship only
                                    if($ilr->programmeaim->A15=='2' || $ilr->programmeaim->A15=='3' || $ilr->programmeaim->A15=='10')
                                    {
                                        $p_prog_status = 7;
                                        if($actual_date=='0000-00-00')
                                            $p_prog_status = 0;
                                        if($achievement_date!='' && $achievement_date!='0000-00-00')
                                            $p_prog_status = 1;
                                        if($actual_date!='0000-00-00' && ($ilr->programmeaim->A35==4 || $ilr->programmeaim->A35==5) && $achievement_date!='0000-00-00')
                                            $p_prog_status = 3;
                                        if($ilr->aims[0]->A40!='00000000' && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
                                            $p_prog_status = 4;
                                        if($ilr->aims[0]->A40!='00000000' && $actual_date=='0000-00-00')
                                            $p_prog_status = 5;
                                        if($ilr->aims[0]->A40=='00000000' && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
                                            $p_prog_status = 6;
                                        if($ilr->programmeaim->A34==3)
                                            $p_prog_status = 13;
                                        if($ilr->programmeaim->A34==4 || $ilr->programmeaim->A34==5)
                                            $p_prog_status = 8;
                                        if($ilr->programmeaim->A50==2)
                                            $p_prog_status = 9;
                                        if($ilr->programmeaim->A50==7)
                                            $p_prog_status = 10;
                                        if($ilr->programmeaim->A34==6)
                                            $p_prog_status = 11;
                                        if(($ilr->programmeaim->A40!='00000000' || $ilr->programmeaim->A40!='')&& $ilr->programmeaim->A34==6)
                                            $p_prog_status = 12;

                                    }

                                    $a23 = $ilr->programmeaim->A23;

                                    $local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
                                    if($local_authority=='')
                                    {
                                        $postcode = str_replace(" ","",$a23);
                                        $page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
                                        $local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
                                        $local_authority = str_replace("<strong>District</strong>","",$local_authority);
                                        $local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
                                        $local_authority = @str_replace("City Council","",$local_authority);
                                        $local_authority = @str_replace("District","",$local_authority);
                                        $local_authority = @str_replace("Council","",$local_authority);
                                        $local_authority = @str_replace("Borough","",$local_authority);
                                        if($local_authority=="")
                                            $local_authority="Not Found";
                                        $local_authority = str_replace("'","\'",$local_authority);
                                        DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
                                    }
                                    $local_authority = str_replace("'","\'",$local_authority);

                                    $a26 = $ilr->programmeaim->A26;
                                    $a09 = $ilr->aims[0]->A09;

                                    $ukprn = $ilr->aims[0]->A22;
                                    if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
                                    {
                                        $provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
                                    }
                                    else
                                    {
                                        $provider = '';
                                    }


                                    $ethnicity = $ilr->learnerinformation->L12;
                                    $lldd = $ilr->learnerinformation->L14;
                                    $gender = $ilr->learnerinformation->L13;
                                    if($ilr->programmeaim->A16=='08' or $ilr->programmeaim->A16=='11')
                                        $restart=1;
                                    else
                                        $restart=0;

                                    $d = array();
                                    $d['l03'] = $l03;
                                    $d['tr_id'] = $tr_id;
                                    $d['programme_type'] = $programme_type;
                                    $d['start_date'] = $start_date;
                                    $d['planned_end_date'] = $end_date;
                                    $d['actual_end_date'] = $actual_date;
                                    $d['achievement_date'] = $achievement_date;
                                    $d['expected'] = 0;
                                    $d['actual'] = 0;
                                    $d['completion_status'] = $comp_status;
                                    $d['outcome'] = $outcome;
                                    $d['hybrid'] = 0;
                                    $d['p_prog_status'] = $p_prog_status;
                                    $d['contract_id'] = $contract_id;
                                    $d['submission'] = $submission;
                                    $d['level'] = $level;
                                    $d['age_band'] = $age_band;
                                    $d['a09'] = $a09;
                                    $d['local_authority'] = $local_authority;
                                    $d['region'] = $a23;
                                    $d['postcode'] = $a23;
                                    $d['sfc'] = $a26;
                                    $d['ssa1'] = '';
                                    $d['ssa2'] = '';
                                    //$d['glh'] = $glh;
                                    $d['employer'] = '';
                                    $d['assessor'] = '';
                                    $d['provider'] = $provider;
                                    $d['contractor'] = '';
                                    $d['ethnicity']	= $ethnicity;
                                    $d['lldd']	= $lldd;
                                    $d['gender']	= $gender;
                                    $d['restart']	= $restart;
                                    $d['dob']	= $dob;
                                    $d['FworkCode']	= $a26;
                                    $d['ethnicity_code'] = $ilr->learnerinformation->L12;
                                    $d['funding_provision'] = $row['funding_provision'];

                                    $data[] = $d;

                                    //$values .= "('$l03',$tr_id,'$programme_type','$start_date','$end_date', '$actual_date','$achievement_date' , 0, 0, 0, $p_prog_status, $contract_id, '$submission', '$level','$age_band','$a09','$local_authority','$a23','$a23','$a26','$ssa1','$ssa2','$employer','$assessor','$provider','$contractor','$ethnicity'),";
                                }
                                else
                                {

                                    for($a = 0; $a<=$ilr->subaims; $a++)
                                    {
                                        // Calclation of A_TTGAIN

                                        if( ($ilr->aims[$a]->A10=='45' || $ilr->aims[$a]->A10=='46' || $ilr->aims[$a]->A10=='60') && ($ilr->aims[$a]->A15!='2' && $ilr->aims[$a]->A15!='3' && $ilr->aims[$a]->A15!='10') && ($ilr->aims[$a]->A46a!='83' && $ilr->aims[$a]->A46b!='83'))
                                        {

                                            // Age Band Calculation
                                            if(($ilr->aims[$a]->A18=='24' || $ilr->aims[$a]->A18=='23' || $ilr->aims[$a]->A18=='22') && $ilr->aims[$a]->A46a!='125')
                                                $programme_type = "Workplace";
                                            elseif($ilr->aims[$a]->A18=='1' || $ilr->aims[$a]->A46a=='125')
                                                $programme_type = "Classroom";
                                            else
                                                $programme_type = "Unknown";
                                            $start_date = Date::toMySQL($ilr->aims[$a]->A27);
                                            $end_date = Date::toMySQL($ilr->aims[$a]->A28);

                                            if($ilr->learnerinformation->L11!='00/00/0000')
                                            {
                                                $dob = $ilr->learnerinformation->L11;
                                                $dob = Date::toMySQL($dob);
                                                $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                            }
                                            else
                                            {
                                                $age = '';
                                            }
                                            if($age<=18)
                                                $age_band = "16-18";
                                            elseif($age<=23)
                                                $age_band = "19-23";
                                            elseif($age>=24)
                                                $age_band = "24+";
                                            else
                                                $age = "Unknown";

                                            if($ilr->aims[$a]->A31!='00000000' && $ilr->aims[$a]->A31!='00/00/0000' && $ilr->aims[$a]->A31!='')
                                                $actual_date = Date::toMySQL($ilr->aims[$a]->A31);
                                            else
                                                $actual_date = "0000-00-00";

                                            $comp_status = $ilr->aims[$a]->A34;
                                            $outcome = $ilr->aims[$a]->A35;

                                            if($ilr->aims[$a]->A40!='00000000' && $ilr->aims[$a]->A40!='00/00/0000' && $ilr->aims[$a]->A40!='')
                                                $achievement_date = Date::toMySQL($ilr->aims[$a]->A40);
                                            else
                                                $achievement_date = "0000-00-00";

                                            $level = $ilr->aims[$a]->A15;
                                            $a09 = $ilr->aims[$a]->A09;

                                            // Calculation for p_prog_status for apprenticeship only
                                            $p_prog_status = 7;
                                            if($actual_date=='0000-00-00')
                                                $p_prog_status =0;
                                            if($achievement_date!='0000-00-00')
                                                $p_prog_status = 1;
                                            if($actual_date!='0000-00-00' && ($ilr->aims[$a]->A35==4 || $ilr->aims[$a]->A35==5) && $achievement_date=='0000-00-00')
                                                $p_prog_status = 3;
                                            if($actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
                                                $p_prog_status = 6;
                                            if($ilr->aims[$a]->A34==3)
                                                $p_prog_status = 13;
                                            if($ilr->aims[$a]->A34==4 || $ilr->aims[$a]->A34==5)
                                                $p_prog_status = 8;
                                            if($ilr->aims[$a]->A50==2)
                                                $p_prog_status = 9;
                                            if($ilr->aims[$a]->A50==7)
                                                $p_prog_status = 10;
                                            if($ilr->aims[$a]->A34==6)
                                                $p_prog_status = 11;

                                            $a23 = trim($ilr->aims[0]->A23);

                                            if(strlen($a23)>8)
                                                pre("Postcode " . $a23 . " is not correct");

                                            $local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
                                            if($local_authority=='')
                                            {
                                                $postcode = str_replace(" ","",$a23);
                                                $page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
                                                $local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
                                                $local_authority = str_replace("<strong>District</strong>","",$local_authority);
                                                $local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
                                                $local_authority = @str_replace("City Council","",$local_authority);
                                                $local_authority = @str_replace("District","",$local_authority);
                                                $local_authority = @str_replace("Council","",$local_authority);
                                                $local_authority = @str_replace("Borough","",$local_authority);
                                                if($local_authority=='')
                                                    $local_authority="Not Found";
                                                $local_authority = str_replace("'","\'",$local_authority);
                                                DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
                                            }
                                            $local_authority = str_replace("'","\'",$local_authority);

                                            $a09 = $ilr->aims[0]->A09;
                                            $a26 = $ilr->aims[0]->A26;


                                            $ukprn = $ilr->aims[$a]->A22;
                                            if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
                                            {
                                                $provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
                                            }
                                            else
                                            {
                                                $provider = '';
                                            }

                                            $provider = addslashes($provider);
                                            $ethnicity = $ilr->learnerinformation->L12;
                                            $lldd = $ilr->learnerinformation->L14;
                                            $gender = $ilr->learnerinformation->L13;
                                            if($ilr->aims[$a]->A16=='08' or $ilr->aims[$a]->A16=='11')
                                                $restart=1;
                                            else
                                                $restart=0;

                                            $d = array();
                                            $d['l03'] = $l03;
                                            $d['tr_id'] = $tr_id;
                                            $d['programme_type'] = $programme_type;
                                            $d['start_date'] = $start_date;
                                            $d['planned_end_date'] = $end_date;
                                            $d['actual_end_date'] = $actual_date;
                                            $d['achievement_date'] = $achievement_date;
                                            $d['expected'] = 0;
                                            $d['actual'] = 0;
                                            $d['completion_status'] = $comp_status;
                                            $d['outcome'] = $outcome;
                                            $d['hybrid'] = 0;
                                            $d['p_prog_status'] = $p_prog_status;
                                            $d['contract_id'] = $contract_id;
                                            $d['submission'] = $submission;
                                            $d['level'] = $level;
                                            $d['age_band'] = $age_band;
                                            $d['a09'] = $a09;
                                            $d['local_authority'] = $local_authority;
                                            $d['region'] = $a23;
                                            $d['postcode'] = $a23;
                                            $d['sfc'] = $a26;
                                            $d['ssa1'] = '';
                                            $d['ssa2'] = '';
                                            //$d['glh'] = $glh;
                                            $d['employer'] = '';
                                            $d['assessor'] = '';
                                            $d['provider'] = $provider;
                                            $d['contractor'] = '';
                                            $d['ethnicity']	= $ethnicity;
                                            $d['ethnicity_code']	= $ethnicity;
                                            $d['lldd']	= $lldd;
                                            $d['restart']	= $restart;
                                            $d['gender'] = $gender;
                                            $d['dob']	= $dob;
                                            $d['FworkCode']	= $a26;
                                            $d['ethnicity_code'] = $ilr->learnerinformation->L12;
                                            $d['funding_provision'] = $row['funding_provision'];
                                            $data[] = $d;


                                        }
                                    }
                                }

                                $counter++;
                            }
                        }
                        elseif($row['contract_year']>=2012)
                        {
                            $ilr = Ilr2016::loadFromXML($row['ilr']);
                            $tr_id = $row['tr_id'];
                            $submission = $row['submission'];
                            $l03 = $row['L03'];
                            $contract_id = $row['contract_id'];
                            $p_prog_status = -1;

                            foreach($ilr->LearningDelivery as $delivery)
                            {
                                $WithdrawReason = "" . $delivery->WithdrawReason;
                                if($delivery->AimType==1 && $delivery->ProgType!='99' && $delivery->ProgType!='24' && ("".$delivery->ProgType)!='' && $WithdrawReason!='40' && $delivery->FundModel!='99')
                                {
                                    if($delivery->ProgType=='24')
                                        $programme_type = "Traineeship";
                                    else
                                        $programme_type = "Apprenticeship";
                                    $a26 = "".$delivery->FworkCode;
                                    $StdCode = "".$delivery->StdCode;
                                    $PwayCode = "".$delivery->PwayCode;
                                    $EmpOutcome = "".$delivery->EmpOutcome;
                                    $start_date = Date::toMySQL("".$delivery->LearnStartDate);
                                    $end_date = Date::toMySQL("".$delivery->LearnPlanEndDate);
                                    if(("".$ilr->DateOfBirth)!='00/00/0000' && ("".$ilr->DateOfBirth)!='00000000')
                                    {
                                        $dob = "".$ilr->DateOfBirth;
                                        $dob = Date::toMySQL($dob);
                                        $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                    }
                                    else
                                    {
                                        $age = '';
                                    }
                                    // Age Band Calculation
                                    if($age<=18)
                                        $age_band = "16-18";
                                    elseif($age<=23)
                                        $age_band = "19-23";
                                    elseif($age>=24)
                                        $age_band = "24+";
                                    else
                                        $age_band = "Unknown";

                                    $LearnActEndDate = "" . $delivery->LearnActEndDate;
                                    if($LearnActEndDate!='00000000' && $LearnActEndDate!='00/00/0000' && $LearnActEndDate!='')
                                        $actual_date = Date::toMySQL($LearnActEndDate);
                                    else
                                        $actual_date = "0000-00-00";

                                    $comp_status = $delivery->CompStatus;
                                    $outcome = $delivery->Outcome;
                                    $AchDate = "" . $delivery->AchDate;
                                    if($AchDate!='00000000' && $AchDate!='00/00/0000' && $AchDate!='')
                                        $achievement_date = Date::toMySQL($AchDate);
                                    else
                                        $achievement_date = "0000-00-00";

                                    $level = "".$delivery->ProgType;

                                    // Calculation for p_prog_status for apprenticeship only
                                    if($delivery->ProgType=='2' || $delivery->ProgType=='3' || $delivery->ProgType=='10' || $delivery->ProgType=='20' || $delivery->ProgType=='24' || $delivery->ProgType=='25')
                                    {
                                        $p_prog_status = 7;
                                        if($actual_date=='0000-00-00')
                                            $p_prog_status = 0;
                                        if($achievement_date!='' && $achievement_date!='0000-00-00')
                                            $p_prog_status = 1;
                                        if($actual_date!='0000-00-00' && ($delivery->Outcome=='4' || $delivery->Outcome=='5') && $achievement_date!='0000-00-00')
                                            $p_prog_status = 3;
                                        if($delivery->CompStatus=='2' && $delivery->Outcome=='1')
                                            $p_prog_status = 1;
                                        if($achievement_date!='0000-00-00' && $actual_date=='0000-00-00')
                                            $p_prog_status = 5;
                                        if($achievement_date!='0000-00-00' && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
                                            $p_prog_status = 6;
                                        if($delivery->CompStatus=='3')
                                            $p_prog_status = 13;
                                        if($delivery->CompStatus==4 || $delivery->CompStatus==5)
                                            $p_prog_status = 8;
                                        if($delivery->WithdrawReason==2)
                                            $p_prog_status = 9;
                                        if($delivery->WithdrawReason==7)
                                            $p_prog_status = 10;
                                        if($delivery->CompStatus==6)
                                            $p_prog_status = 11;
                                        if( ($delivery->AchDate!='00000000' || $delivery->AchDate!='') && $delivery->CompStatus==6)
                                            $p_prog_status = 12;
                                    }
                                    $a23 = "" . $delivery->DelLocPostCode;
                                    $local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
                                    if($local_authority=='')
                                    {
                                        $postcode = str_replace(" ","",$a23);
                                        $page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
                                        $local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
                                        $local_authority = str_replace("<strong>District</strong>","",$local_authority);
                                        $local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
                                        $local_authority = @str_replace("City Council","",$local_authority);
                                        $local_authority = @str_replace("District","",$local_authority);
                                        $local_authority = @str_replace("Council","",$local_authority);
                                        $local_authority = @str_replace("Borough","",$local_authority);
                                        if($local_authority=="")
                                            $local_authority="Not Found";
                                        $local_authority = str_replace("'","\'",$local_authority);
                                        DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
                                    }
                                    $local_authority = str_replace("'","\'",$local_authority);

                                    $a09 = '';
                                    foreach($ilr->LearningDelivery as $d)
                                    {
                                        $a09 = "".$d->LearnAimRef;
                                        $count = DAO::getSingleValue($link, "
                                        SELECT COUNT(*) FROM lars201718.Core_LARS_FrameworkAims WHERE LearnAimRef = '$a09' AND (FrameworkComponentType='3' || FrameworkComponentType='1' || FrameworkComponentType='2');");
                                        if($count>0)
                                        {
                                            $ukprn = "".$d->PartnerUKPRN;
                                            break;
                                        }
                                    }
                                    //if($a09!='')
                                    //{
                                    //		$ssa1 = DAO::getSingleValue($link, "SELECT CONCAT(lad200910.SSA_TIER1_CODES.SSA_TIER1_CODE,' ',lad200910.SSA_TIER1_CODES.SSA_TIER1_DESC) FROM lad200910.SSA_TIER1_CODES INNER JOIN lad200910.ALL_ANNUAL_VALUES ON lad200910.ALL_ANNUAL_VALUES.SSA_TIER1_CODE = lad200910.SSA_TIER1_CODES.SSA_TIER1_CODE WHERE ALL_ANNUAL_VALUES.LEARNING_AIM_REF = '$a09';");
                                    //			$ssa2 = DAO::getSingleValue($link, "SELECT CONCAT(lad200910.SSA_TIER2_CODES.SSA_TIER2_CODE,' ',lad200910.SSA_TIER2_CODES.SSA_TIER2_DESC) FROM lad200910.SSA_TIER2_CODES INNER JOIN lad200910.ALL_ANNUAL_VALUES ON lad200910.ALL_ANNUAL_VALUES.SSA_TIER2_CODE = lad200910.SSA_TIER2_CODES.SSA_TIER2_CODE WHERE lad200910.ALL_ANNUAL_VALUES.LEARNING_AIM_REF = '$a09'");
                                    //		}

                                    if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
                                    {
                                        $provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
                                    }
                                    else
                                    {
                                        $provider = '';
                                    }

                                    $provider = addslashes($provider);
                                    $ethnicity = "".$ilr->Ethnicity;
                                    $lldd = "".$ilr->LLDDHealthProb;
                                    $gender = "".$ilr->Sex;

                                    $restart = 0;
                                    foreach($delivery->LearningDeliveryFAM as $ldf)
                                    {
                                        if($ldf->LearnDelFAMType=='RES' and $ldf->LearnDelFAMCode='1')
                                            $restart = 1;
                                    }
                                    $d = array();
                                    $d['l03'] = $l03;
                                    $d['tr_id'] = $tr_id;
                                    $d['programme_type'] = $programme_type;
                                    $d['start_date'] = $start_date;
                                    $d['planned_end_date'] = $end_date;
                                    $d['actual_end_date'] = $actual_date;
                                    $d['achievement_date'] = $achievement_date;
                                    $d['expected'] = 0;
                                    $d['actual'] = 0;
                                    $d['completion_status'] = $comp_status;
                                    $d['outcome'] = $outcome;
                                    $d['hybrid'] = 0;
                                    $d['p_prog_status'] = $p_prog_status;
                                    $d['contract_id'] = $contract_id;
                                    $d['submission'] = $submission;
                                    $d['level'] = $level;
                                    $d['age_band'] = $age_band;
                                    $d['a09'] = $a09;
                                    $d['local_authority'] = $local_authority;
                                    $d['region'] = $a23;
                                    $d['postcode'] = $a23;
                                    $d['sfc'] = $a26;
                                    $d['ssa1'] = '';
                                    $d['ssa2'] = '';
                                    $d['restart'] = $restart;
                                    $d['employer'] = '';
                                    $d['assessor'] = '';
                                    $d['provider'] = $provider;
                                    $d['contractor'] = '';
                                    $d['ethnicity']	= $ethnicity;
                                    $d['ethnicity_code']	= $ethnicity;
                                    $d['lldd']	= $lldd;
                                    $d['gender']	= $gender;
                                    $d['programme'] = $StdCode;
                                    $d['dob']	= $dob;
                                    $d['StdCode']	= $StdCode;
                                    $d['FworkCode']	= $a26;
                                    $d['PwayCode']	= $PwayCode;
                                    $d['funding_provision'] = isset($row['funding_provision'])?$row['funding_provision']:1;
                                    if($programme_type=='Traineeship' && ($EmpOutcome=='1' || $EmpOutcome=='2'))
                                    {
                                        // Exclude these traineeships
                                    }
                                    else
                                    {
                                        $data[] = $d;
                                    }
                                }
                                else
                                {
                                    if($delivery->AimType!='1' && $delivery->ProgType!='25' && $delivery->ProgType!='25' && $delivery->ProgType!='2' && $delivery->ProgType!='3' && $delivery->ProgType!='20' && $delivery->ProgType!='21' && $delivery->ProgType!='22'&& $delivery->ProgType!='23' && $delivery->StdCode=='')
                                    {
                                        if(DB_NAME=='am_baltic' && $delivery->FundModel=='99')
                                            continue;
                                        if($row['contract_year']<2013)
                                        {
                                            $ldm = '';
                                            foreach($delivery->LearningDeliveryFAM as $ldf)
                                            {
                                                if($ldf->LearnDelFAMType=='LDM')
                                                    if($ldf->LearnDelFAMCode=='125')
                                                        $ldm = 'Classroom';
                                            }

                                            if($delivery->ProgType=='24')
                                                $programme_type = "Traineeship";
                                            elseif($ldm=='Classroom')
                                                $programme_type = "Classroom";
                                            else
                                                $programme_type = "Workplace";
                                        }
                                        else
                                        {
                                            $ldm = '';
                                            foreach($delivery->LearningDeliveryFAM as $ldf)
                                            {
                                                if($ldf->LearnDelFAMType=='WPL')
                                                    if($ldf->LearnDelFAMCode=='1')
                                                        $ldm = 'Workplace';
                                            }

                                            if($delivery->ProgType=='24')
                                                $programme_type = "Traineeship";
                                            elseif($ldm=='Workplace')
                                                $programme_type = "Workplace";
                                            else
                                                $programme_type = "Classroom";

                                        }

                                        $start_date = Date::toMySQL($delivery->LearnStartDate);
                                        $end_date = Date::toMySQL($delivery->LearnPlanEndDate);

                                        if($ilr->DateOfBirth!='00/00/0000')
                                        {
                                            $dob = "".$ilr->DateOfBirth;
                                            $dob = Date::toMySQL($dob);
                                            $age = DAO::getSingleValue($link, "SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS('$start_date')-TO_DAYS('$dob')), '%Y')+0");
                                        }
                                        else
                                        {
                                            $age = '';
                                        }
                                        if($age<=18)
                                            $age_band = "16-18";
                                        elseif($age<=23)
                                            $age_band = "19-23";
                                        elseif($age>=24)
                                            $age_band = "24+";
                                        else
                                            $age = "Unknown";

                                        $LearnActEndDate = "".$delivery->LearnActEndDate;
                                        if($LearnActEndDate!='00000000' && $LearnActEndDate!='00/00/0000' && $LearnActEndDate!='')
                                            $actual_date = Date::toMySQL($LearnActEndDate);
                                        else
                                            $actual_date = "0000-00-00";

                                        $comp_status = $delivery->CompStatus;
                                        $outcome = $delivery->Outcome;

                                        $AchDate = "" . $delivery->AchDate;
                                        if($AchDate!='00000000' && $AchDate!='00/00/0000' && $AchDate!='')
                                            $achievement_date = Date::toMySQL($AchDate);
                                        else
                                            $achievement_date = "0000-00-00";

                                        $level = "".$delivery->ProgType;
                                        $a09 = "".$delivery->LearnAimRef;
                                        $Outcome = "".$delivery->Outcome;
                                        // Calculation for p_prog_status for apprenticeship only
                                        $p_prog_status = -1;
                                        if($actual_date=='0000-00-00')
                                            $p_prog_status =0;
                                        if($Outcome=='1')
                                            $p_prog_status = 1;
                                        if($delivery->CompStatus==3)
                                            $p_prog_status = 13;
                                        if($delivery->CompStatus==4 || $delivery->CompStatus==5)
                                            $p_prog_status = 8;
                                        if($delivery->WithdrawReason==2)
                                            $p_prog_status = 9;
                                        if($delivery->WithdrawReason==7)
                                            $p_prog_status = 10;
                                        if($delivery->CompStatus==6)
                                            $p_prog_status = 11;

                                        $a23 = trim($delivery->DelLocPostCode);
                                        $local_authority = DAO::getSingleValue($link, "select local_authority from central.lookup_postcode_la where postcode='$a23' limit 0,1");
                                        if($local_authority=='')
                                        {
                                            $postcode = str_replace(" ","",$a23);
                                            $page = @file_get_contents("http://www.uk-postcodes.com/postcode/".$postcode);
                                            $local_authority = substr($page,strpos($page,"<strong>District</strong>"),(strpos($page,"<strong>Ward</strong>")-strpos($page,"<strong>District</strong>")));
                                            $local_authority = str_replace("<strong>District</strong>","",$local_authority);
                                            $local_authority = @substr($local_authority,strpos($local_authority,">")+1,(strpos($local_authority,"<",2)-strpos($local_authority,">")-1));
                                            $local_authority = @str_replace("City Council","",$local_authority);
                                            $local_authority = @str_replace("District","",$local_authority);
                                            $local_authority = @str_replace("Council","",$local_authority);
                                            $local_authority = @str_replace("Borough","",$local_authority);
                                            if($local_authority=='')
                                                $local_authority="Not Found";
                                            $local_authority = str_replace("'","\'",$local_authority);
                                            DAO::execute($link, "insert into central.lookup_postcode_la (postcode, local_authority) values('$a23', '$local_authority')");
                                        }
                                        $local_authority = str_replace("'","\'",$local_authority);

                                        $ukprn = "".$delivery->PartnerUKPRN;
                                        if($ukprn!='' && $ukprn!='00000000' && $ukprn!='        ')
                                        {
                                            $provider = DAO::getSingleValue($link, "select legal_name from organisations where ukprn = '$ukprn'");
                                        }
                                        else
                                        {
                                            $provider = '';
                                        }

                                        $provider = addslashes($provider);
                                        $ethnicity = $ilr->Ethnicity;
                                        $lldd = $ilr->LLDDHealthProb;
                                        $gender = $ilr->Sex;
                                        $restart = 0;
                                        foreach($delivery->LearningDeliveryFAM as $ldf)
                                        {
                                            if($ldf->LearnDelFAMType=='RES' and $ldf->LearnDelFAMCode='1')
                                                $restart = 1;
                                        }

                                        $d = array();
                                        $d['l03'] = $l03;
                                        $d['tr_id'] = $tr_id;
                                        $d['programme_type'] = $programme_type;
                                        $d['start_date'] = $start_date;
                                        $d['planned_end_date'] = $end_date;
                                        $d['actual_end_date'] = $actual_date;
                                        $d['achievement_date'] = $achievement_date;
                                        $d['expected'] = 0;
                                        $d['actual'] = 0;
                                        $d['completion_status'] = $comp_status;
                                        $d['outcome'] = $outcome;
                                        $d['hybrid'] = 0;
                                        $d['p_prog_status'] = $p_prog_status;
                                        $d['contract_id'] = $contract_id;
                                        $d['submission'] = $submission;
                                        $d['level'] = $level;
                                        $d['age_band'] = $age_band;
                                        $d['a09'] = $a09;
                                        $d['local_authority'] = $local_authority;
                                        $d['region'] = $a23;
                                        $d['postcode'] = $a23;
                                        $d['sfc'] = '';
                                        $d['ssa1'] = '';
                                        $d['ssa2'] = '';
                                        $d['restart'] = $restart;
                                        $d['employer'] = '';
                                        $d['assessor'] = '';
                                        $d['provider'] = $provider;
                                        $d['contractor'] = '';
                                        $d['ethnicity']	= $ethnicity;
                                        $d['ethnicity_code']	= $ethnicity;
                                        $d['aim_type'] = '';
                                        $d['lldd'] = $lldd;
                                        $d['gender'] = $gender;
                                        $d['dob']	= $dob;
                                        $d['funding_provision'] = isset($row['funding_provision'])?$row['funding_provision']:1;
                                        $data[] = $d;

                                    }
                                }
                            }
                            $counter++;
                        }
                    }
                }
            }
			DAO::multipleRowInsert($link, "success_rates", $data);

            // Remaining fields
            DAO::execute($link, "UPDATE success_rates
INNER JOIN lars201718.Core_LARS_Standard AS lars ON lars.StandardCode = success_rates.`StdCode`
INNER JOIN lars201718.CoreReference_LARS_SectorSubjectAreaTier1_Lookup AS lookup ON lookup.SectorSubjectAreaTier1 = lars.SectorSubjectAreaTier1
SET success_rates.`ssa1` = CONCAT(lookup.SectorSubjectAreaTier1,'-',lookup.SectorSubjectAreaTier1Desc)
WHERE StdCode IS NOT NULL;");

            DAO::execute($link, "UPDATE success_rates
INNER JOIN lars201718.Core_LARS_Framework AS lars ON lars.FworkCode = success_rates.FworkCode AND lars.ProgType=success_rates.`level`
INNER JOIN lars201718.CoreReference_LARS_SectorSubjectAreaTier1_Lookup AS lookup ON lookup.SectorSubjectAreaTier1 = lars.SectorSubjectAreaTier1
SET success_rates.`ssa1` = CONCAT(lookup.SectorSubjectAreaTier1,'-',lookup.SectorSubjectAreaTier1Desc)
WHERE success_rates.FworkCode IS NOT NULL;");

            DAO::execute($link, "UPDATE success_rates
INNER JOIN lars201718.Core_LARS_Standard AS lars ON lars.StandardCode = success_rates.`StdCode`
INNER JOIN lars201718.CoreReference_LARS_SectorSubjectAreaTier2_Lookup AS lookup ON lookup.SectorSubjectAreaTier2 = lars.SectorSubjectAreaTier2
SET success_rates.`ssa2` = CONCAT(lookup.SectorSubjectAreaTier2,'-',lookup.SectorSubjectAreaTier2Desc)
WHERE StdCode IS NOT NULL;");


            DAO::execute($link, "UPDATE success_rates
INNER JOIN lars201718.Core_LARS_Framework AS lars ON lars.FworkCode = success_rates.FworkCode AND lars.ProgType=success_rates.`level`
INNER JOIN lars201718.CoreReference_LARS_SectorSubjectAreaTier2_Lookup AS lookup ON lookup.SectorSubjectAreaTier2 = lars.SectorSubjectAreaTier2
SET success_rates.`ssa2` = CONCAT(lookup.SectorSubjectAreaTier2,'-',lookup.SectorSubjectAreaTier2Desc)
WHERE success_rates.FworkCode IS NOT NULL;");


            if(DB_NAME=="am_baltic")
            {
                DAO::execute($link, "UPDATE success_rates
                  LEFT JOIN tr ON tr.id = success_rates.`tr_id`
                  LEFT JOIN courses_tr ON tr.id = courses_tr.`tr_id`
                  LEFT JOIN (
                            SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, inductee_type, induction.`resourcer`,
                  inductees.`employer_type`
                  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
                  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
                SET success_rates.learner_type = induction_fields.inductee_type, success_rates.employer_type = induction_fields.employer_type;");
            }

            DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier2_codes on ssa_tier2_codes.SSA_TIER2_CODE = all_annual_values.SSA_TIER2_CODE set ssa2 = CONCAT(lad201213.ssa_tier2_codes.SSA_TIER2_CODE,' ',lad201213.ssa_tier2_codes.SSA_TIER2_DESC)");
            DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.employer_id set employer = organisations.legal_name");
            DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id set provider = organisations.legal_name where provider='' or provider is NULL");
            if(DB_NAME=='am_lead')
            {
                DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id set provider = organisations.legal_name");
            }
            DAO::execute($link, "update success_rates INNER JOIN contracts on contracts.id = success_rates.contract_id INNER JOIN organisations on organisations.id = contracts.contract_holder set contractor = organisations.legal_name");

            if(DB_NAME=='am_siemens' or DB_NAME=='am_siemens_demo')
            {
                DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN users on users.id = tr.programme set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname)");
                DAO::execute($link, "update qar_archive INNER JOIN tr on tr.id = qar_archive.tr_id INNER JOIN users on users.id = tr.programme set qar_archive.assessor = CONCAT(users.firstnames, ' ', users.surname)");
            }
            else
            {
                DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN users on users.id = tr.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname)");
                DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN group_members on group_members.tr_id = tr.id INNER JOIN groups on group_members.groups_id = groups.id INNER JOIN users on users.id = groups.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname) where success_rates.assessor is NULL or success_rates.assessor=''");
            }

            DAO::execute($link, "DELETE FROM success_rates WHERE (p_prog_status = 13 or p_prog_status=6 or p_prog_status=9 or p_prog_status=-1 or p_prog_status=8)  AND DATE_ADD(start_date, INTERVAL 41 DAY)>actual_end_date and restart = 0 and programme_type!='Classroom' and programme_type!='Traineeship';");
            DAO::execute($link, "DELETE FROM success_rates WHERE p_prog_status = 8 OR p_prog_status=12;");
            DAO::execute($link, "DELETE FROM success_rates WHERE ((DATE_ADD(start_date, INTERVAL 167 DAY)<=planned_end_date AND DATE_ADD(start_date, INTERVAL 41 DAY)>=actual_end_date) OR (DATE_ADD(start_date, INTERVAL 166 DAY)>planned_end_date AND DATE_ADD(start_date, INTERVAL 13 DAY)>=actual_end_date)) AND programme_type!='Apprenticeship' AND completion_status = 3;");
            DAO::execute($link, "DELETE FROM success_rates WHERE a09 = 'ZWRKX001' AND programme_type!='Apprenticeship'");
            //DAO::execute($link, "DELETE FROM success_rates WHERE actual_end_date is not null and LAST_DAY(actual_end_date)<>DAY(actual_end_date) AND MONTH(actual_end_date)=MONTH(start_date) AND YEAR(actual_end_date)=YEAR(start_date);");


            // Traineeship Exclusion
            DAO::execute($link, "DELETE FROM success_rates WHERE programme_type = 'Traineeship' AND completion_status!=1 AND outcome !=1 AND tr_id = (SELECT tr_id FROM destinations WHERE tr_id = success_rates.`tr_id` AND ((outcome_type='EDU' AND outcome_code=2) OR (outcome_type='EMP' AND outcome_code IN (1,3,4))) AND outcome_start_date>=success_rates.actual_end_date LIMIT 0,1)");

            //pre($link->errorInfo());
            DAO::execute($link, "UPDATE success_rates SET actual = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.actual_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.actual_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2'), expected = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.planned_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.planned_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2');");
            DAO::execute($link, "UPDATE success_rates SET actual = (SELECT contract_year FROM central.lookup_submission_dates WHERE IF(success_rates.achievement_date is not null, GREATEST(success_rates.actual_end_date,success_rates.achievement_date),success_rates.actual_end_date) >= central.lookup_submission_dates.census_start_date AND IF(success_rates.achievement_date is not null, GREATEST(success_rates.actual_end_date,success_rates.achievement_date), success_rates.actual_end_date) <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2'), expected = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.planned_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.planned_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2') where success_rates.programme_type='Apprenticeship';");
            DAO::execute($link, "update success_rates set ethnicity = (select Ethnicity_Desc from lis201112.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) UNION select Ethnicity_Desc from lis201011.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) limit 0,1);");

            DAO::execute($link, "update success_rates INNER JOIN lad201213.frameworks on frameworks.FRAMEWORK_CODE = success_rates.sfc set sfc = frameworks.FRAMEWORK_DESC where success_rates.programme is null");

            // For Standards
            DAO::execute($link, "update success_rates INNER JOIN lars201718.Core_LARS_Standard AS f ON f.StandardCode = success_rates.programme SET sfc = f.StandardName where success_rates.programme is not null");
            DAO::execute($link, "UPDATE success_rates INNER JOIN lars201718.Core_LARS_Standard AS s ON s.StandardCode = success_rates.programme INNER JOIN lars201718.CoreReference_LARS_SectorSubjectAreaTier1_Lookup AS l ON l.SectorSubjectAreaTier1 = s.SectorSubjectAreaTier1 SET success_rates.ssa1 = CONCAT(success_rates.programme,' - ',l.SectorSubjectAreaTier1Desc) WHERE programme IS NOT NULL;");
            DAO::execute($link, "UPDATE success_rates INNER JOIN lars201718.Core_LARS_Standard AS s ON s.StandardCode = success_rates.programme INNER JOIN lars201718.CoreReference_LARS_SectorSubjectAreaTier2_Lookup AS l ON l.SectorSubjectAreaTier2 = s.SectorSubjectAreaTier2 SET success_rates.ssa2 = CONCAT(success_rates.programme,' - ',l.SectorSubjectAreaTier2Desc) WHERE programme IS NOT NULL;");


            DAO::execute($link, "update success_rates set sfc = LEFT(sfc,POSITION('-' IN sfc)-1) where POSITION('-' in sfc)<>0");
            DAO::execute($link, "update success_rates LEFT JOIN lad201213.learning_aim on learning_aim.LEARNING_AIM_REF = success_rates.a09 LEFT JOIN lad201213.learning_aim_types on learning_aim_types.LEARNING_AIM_TYPE_CODE = learning_aim.LEARNING_AIM_TYPE_CODE set aim_type = LEARNING_AIM_TYPE_DESC");
            DAO::execute($link, "update success_rates set ssa1 = sfc where ssa1='X Not Applicable'");
            DAO::execute($link, "update success_rates set ssa1 = replace(ssa1,\"'\",\"\")");
            DAO::execute($link, "update success_rates set hybrid = expected where actual <= expected and hybrid = 0");
            DAO::execute($link, "update success_rates set hybrid = actual where expected <= actual and hybrid = 0");



            DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'QCF Units' and programme_type = 'Classroom'");
            DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'Employability Award' and programme_type = 'Classroom'");
            DAO::execute($link, "DELETE FROM success_rates WHERE a09 = 'ZESF0001' and programme_type = 'Classroom'");
            DAO::execute($link, "DELETE FROM success_rates WHERE a09 = 'XESF0001' and programme_type = 'Classroom'");
            DAO::execute($link, "DELETE FROM success_rates WHERE a09 = 'Z0007836'");
            DAO::execute($link, "DELETE FROM success_rates WHERE a09 = 'Z0007837'");
            //DAO::execute($link, "DELETE FROM success_rates WHERE start_date = actual_end_date and DATE_ADD(start_date, INTERVAL 13 DAY)>planned_end_date and completion_status = 3 and outcome = 3");

            DAO::execute($link, "UPDATE success_rates INNER JOIN qualifications ON REPLACE(qualifications.id,'/','') = success_rates.a09 SET success_rates.level = qualifications.level WHERE programme_type!='Apprenticeship';");

            DAO::execute($link, "update success_rates set lldd = 'LDD - Yes' WHERE lldd = '1'");
            DAO::execute($link, "update success_rates set lldd = 'LDD - No' WHERE lldd = '2'");
            DAO::execute($link, "update success_rates set lldd = 'LDD - Unknown' WHERE lldd != 'LDD - Yes' and lldd != 'LDD - No'");

            DAO::execute($link, "UPDATE success_rates SET provider_id = (SELECT provider_id FROM tr WHERE tr.id = success_rates.`tr_id`)");
            DAO::execute($link, "UPDATE success_rates SET employer_id = (SELECT employer_id FROM tr WHERE tr.id = success_rates.`tr_id`)");
            DAO::execute($link, "UPDATE success_rates SET contract_year = IF(MONTH(start_date)>=8,YEAR(start_date),YEAR(start_date)-1);");


            DAO::execute($link, "UPDATE success_rates2
            INNER JOIN lars201718.Core_LARS_Standard ON success_rates2.StdCode = lars201718.Core_LARS_Standard.StandardCode
            SET success_rates2.sfc = CONCAT(StdCode,' - ',StandardName) WHERE success_rates2.StdCode IS NOT NULL;");

            DAO::execute($link, "UPDATE success_rates2
            SET ssa1 = \"06 - Information and Communication Technology\"
            WHERE ssa1 IN (\"06 - Information and Communication Technology
            \",\"06.00Information and Communication Technology\",\"06.00Information and Communication Technology
            \",\"6.00-Information and Communication Technology\")");

            DAO::execute($link, "UPDATE success_rates2
            SET ssa1 = \"15.00-Business, Administration and Law\"
            WHERE ssa1 IN (\"15.00-Business, Administration and Law\",\"15.00Business, Administration and Law
            \",\"15 - Business, Administration and Law
            \");");


            DAO::execute($link, "drop table success_rates2");
            DAO::execute($link, "create table IF NOT EXISTS success_rates2 select * from success_rates");

            IF(DB_NAME=='am_crackerjack')
            {
                DAO::execute($link, "UPDATE success_rates2  
                INNER JOIN lars201718.`Core_LARS_Standard` AS lars ON lars.StandardCode = success_rates2.StdCode
                SET `level` = CONCAT(\"Std \",NotionalEndLevel)
                WHERE success_rates2.level = 25;
                ");
            }

        }
        else
        {
            DAO::execute($link, "insert into success_rates select * from success_rates2");
        }



        // Update archive
        //DAO::execute($link, "insert into qar_archive select * from success_rates2");
        DAO::execute($link, "insert into success_rates select * from qar_archive");
        DAO::execute($link, "insert into success_rates2 select * from qar_archive");


        $table = array();
        $table2 = array();
        $table3 = array();
        // Note: The UNION query below does not work with temporary tables (MySQL cannot "reopen" a temporary table),
        //       so it has been rewritten as two queries which are then joined together, sorted and made DISTINCT in PHP.
        //$years = DAO::getSingleColumn($link, "SELECT expected FROM success_rates UNION SELECT actual FROM success_rates WHERE expected IS NOT NULL AND actual IS NOT NULL order by expected");
        $years_expected = DAO::getSingleColumn($link, "SELECT distinct expected FROM success_rates WHERE expected IS NOT NULL");
        $years_actual = DAO::getSingleColumn($link, "SELECT distinct actual FROM success_rates WHERE actual IS NOT NULL");
        $years = array_merge($years_expected, $years_actual);
        $years = array_unique($years, SORT_STRING);
        sort($years);
        foreach($years as $y)
        {
            $table[$y][NULL] = 0;
            $table2[$y][NULL] = 0;
            $table3[$y][NULL] = 0;
        }


        // Calculate Table for overall cohort table
        $sql = "SELECT * FROM success_rates where programme_type='Apprenticeship' order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table[$row['expected']][$row['actual']]))
                    $table[$row['expected']][$row['actual']]++;
                else
                    $table[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year = array();
            foreach($table as $key => $expected)
            {
                //if($key!='')
                $year[] = $key;
            }

            foreach($table as $key => $expected)
            {
                foreach($year as $y)
                {
                    if(!isset($table[$key][$y]))
                        $table[$key][$y] = 0;
                }
            }
        }


        // Calculate Table for overall achievers
        $sql = "SELECT * FROM success_rates where programme_type='Apprenticeship' and p_prog_status=1 order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table2[$row['expected']][$row['actual']]))
                    $table2[$row['expected']][$row['actual']]++;
                else
                    $table2[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year2 = array();
            foreach($table2 as $key => $expected)
            {
                $year2[] = $key;
            }
            foreach($table2 as $key => $expected)
            {
                foreach($year2 as $y)
                {
                    if(!isset($table2[$key][$y]))
                        $table2[$key][$y] = 0;
                }
            }
        }

        // Calculate Table for Timely achievers
        $sql = "SELECT * FROM success_rates where programme_type='Apprenticeship' and p_prog_status=1 and DATEDIFF(actual_end_date, planned_end_date)<=90 order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table3[$row['expected']][$row['actual']]))
                    $table3[$row['expected']][$row['actual']]++;
                else
                    $table3[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year3 = array();
            foreach($table3 as $key => $expected)
            {
                $year3[] = $key;
            }
            foreach($table3 as $key => $expected)
            {
                foreach($year3 as $y)
                {
                    if(!isset($table3[$key][$y]))
                        $table3[$key][$y] = 0;
                }
            }
        }

        // Calculate Table for overall cohort table
        $table4 = array();
        $sql = "SELECT * FROM success_rates where programme_type='Classroom' order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table4[$row['expected']][$row['actual']]))
                    $table4[$row['expected']][$row['actual']]++;
                else
                    $table4[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year4 = array();
            foreach($table4 as $key => $expected)
            {
                $year4[] = $key;
            }
            foreach($table4 as $key => $expected)
            {
                foreach($year4 as $y)
                {
                    if(!isset($table4[$key][$y]))
                        $table4[$key][$y] = 0;
                }
            }
        }

        // Calculate Table for overall achievers
        $table5 = array();
        $sql = "SELECT * FROM success_rates where programme_type='Classroom' and p_prog_status=1 order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table5[$row['expected']][$row['actual']]))
                    $table5[$row['expected']][$row['actual']]++;
                else
                    $table5[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year5 = array();
            foreach($table5 as $key => $expected)
            {
                $year5[] = $key;
            }
            foreach($table5 as $key => $expected)
            {
                foreach($year5 as $y)
                {
                    if(!isset($table5[$key][$y]))
                        $table5[$key][$y] = 0;
                }
            }
        }


        // Calculate Table for Timely achievers
        $table6 = array();
        $sql = "SELECT * FROM success_rates where programme_type='Classroom' and p_prog_status=1 and DATEDIFF(actual_end_date, planned_end_date)<=90 order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table6[$row['expected']][$row['actual']]))
                    $table6[$row['expected']][$row['actual']]++;
                else
                    $table6[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year6 = array();
            foreach($table6 as $key => $expected)
            {
                $year6[] = $key;
            }
            foreach($table6 as $key => $expected)
            {
                foreach($year6 as $y)
                {
                    if(!isset($table6[$key][$y]))
                        $table6[$key][$y] = 0;
                }
            }
        }

        // Calculate Table for overall cohort table
        $table7 = array();
        $sql = "SELECT * FROM success_rates where programme_type='Workplace' order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table7[$row['expected']][$row['actual']]))
                    $table7[$row['expected']][$row['actual']]++;
                else
                    $table7[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year7 = array();
            foreach($table7 as $key => $expected)
            {
                $year7[] = $key;
            }
            foreach($table7 as $key => $expected)
            {
                foreach($year7 as $y)
                {
                    if(!isset($table7[$key][$y]))
                        $table7[$key][$y] = 0;
                }
            }
        }

        // Calculate Table for overall achievers
        $table8 = array();
        $sql = "SELECT * FROM success_rates where programme_type='Workplace' and p_prog_status=1 order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table8[$row['expected']][$row['actual']]))
                    $table8[$row['expected']][$row['actual']]++;
                else
                    $table8[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year8 = array();
            foreach($table8 as $key => $expected)
            {
                $year8[] = $key;
            }
            foreach($table8 as $key => $expected)
            {
                foreach($year8 as $y)
                {
                    if(!isset($table8[$key][$y]))
                        $table8[$key][$y] = 0;
                }
            }
        }


        // Calculate Table for Timely achievers
        $table9 = array();
        $sql = "SELECT * FROM success_rates where programme_type='Workplace' and p_prog_status=1 and DATEDIFF(actual_end_date, planned_end_date)<=90 order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($table9[$row['expected']][$row['actual']]))
                    $table9[$row['expected']][$row['actual']]++;
                else
                    $table9[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $year9 = array();
            foreach($table9 as $key => $expected)
            {
                $year9[] = $key;
            }
            foreach($table9 as $key => $expected)
            {
                foreach($year9 as $y)
                {
                    if(!isset($table9[$key][$y]))
                        $table9[$key][$y] = 0;
                }
            }
        }


        // Calculate Table for overall cohort table
        $tabletrainee = array();
        $sql = "SELECT * FROM success_rates where programme_type='Traineeship' order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($tabletrainee[$row['expected']][$row['actual']]))
                    $tabletrainee[$row['expected']][$row['actual']]++;
                else
                    $tabletrainee[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $yeartrainee = array();
            foreach($tabletrainee as $key => $expected)
            {
                $yeartrainee[] = $key;
            }
            foreach($tabletrainee as $key => $expected)
            {
                foreach($yeartrainee as $y)
                {
                    if(!isset($tabletrainee[$key][$y]))
                        $tabletrainee[$key][$y] = 0;
                }
            }
        }

        // Calculate Table for overall achievers
        $tabletraineeoverall = array();
        $sql = "SELECT * FROM success_rates where programme_type='Traineeship' and p_prog_status=1 order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($tabletraineeoverall[$row['expected']][$row['actual']]))
                    $tabletraineeoverall[$row['expected']][$row['actual']]++;
                else
                    $tabletraineeoverall[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $yeartraineeoverall = array();
            foreach($tabletraineeoverall as $key => $expected)
            {
                $yeartraineeoverall[] = $key;
            }
            foreach($tabletraineeoverall as $key => $expected)
            {
                foreach($yeartraineeoverall as $y)
                {
                    if(!isset($tabletraineeoverall[$key][$y]))
                        $tabletraineeoverall[$key][$y] = 0;
                }
            }
        }


        // Calculate Table for Timely achievers
        $tabletraineetimely = array();
        $sql = "SELECT * FROM success_rates where programme_type='Traineeship' and p_prog_status=1 and DATEDIFF(actual_end_date, planned_end_date)<=90 order by expected,actual";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                if(isset($tabletraineetimely[$row['expected']][$row['actual']]))
                    $tabletraineetimely[$row['expected']][$row['actual']]++;
                else
                    $tabletraineetimely[$row['expected']][$row['actual']] = 1;
            }

            // Creating the table by adding blank cells
            $yeartraineetimely = array();
            foreach($tabletraineetimely as $key => $expected)
            {
                $yeartraineetimely[] = $key;
            }
            foreach($tabletraineetimely as $key => $expected)
            {
                foreach($yeartraineetimely as $y)
                {
                    if(!isset($tabletraineetimely[$key][$y]))
                        $tabletraineetimely[$key][$y] = 0;
                }
            }
        }



        DAO::execute($link, "UPDATE success_rates LEFT JOIN central.lookup_la_gor ON success_rates.local_authority = central.lookup_la_gor.local_authority SET success_rates.region = central.lookup_la_gor.government_region;");
        DAO::execute($link, "UPDATE success_rates set sfc = 'Business and Administration' where sfc = 'Business Administration'");

        DAO::execute($link, "CREATE TEMPORARY TABLE success_rates_bcs select * from success_rates");
        DAO::execute($link, "update success_rates_bcs set actual_end_date = planned_end_date, achievement_date = planned_end_date, actual = expected, hybrid = expected, p_prog_status = 1 where p_prog_status = 0");


        if($output=='XLS')
        {

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename="Success_Rates.CSV"');

            // Internet Explorer requires two extra headers when downloading files over HTTPS
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

            echo "L03,Programme Type,Start Date,Planned End Date,Actual End Date,Achievement Date,Completion Status, Outcome,Expected Year, Actual Year, Hybrid Year, Submission, Level, Age Band, Learning Aim Reference, Local Authority,Region, Postcode, SFC, SSA1, SSA2, Employer, Assessor, Provider, Contractor, Ethnicity, Status, Aim Type";
            echo "\r\n";

            $sqlxl = "select * from success_rates";
            $stxl = $link->query($sqlxl);
            if($stxl)
            {
                while($rowxl = $stxl->fetch())
                {
                    echo '="' . $rowxl['l03'] . '"';
                    echo ',"' . $rowxl['programme_type'] . '"';
                    echo ',"' . $rowxl['start_date'] . '"';
                    echo ',"' . $rowxl['planned_end_date'] . '"';
                    echo ',"' . $rowxl['actual_end_date'] . '"';
                    echo ',"' . $rowxl['achievement_date'] . '"';
                    echo ',"' . $rowxl['completion_status'] . '"';
                    echo ',"' . $rowxl['outcome'] . '"';
                    echo ',"' . $rowxl['expected'] . '"';
                    echo ',"' . $rowxl['actual'] . '"';
                    echo ',"' . $rowxl['hybrid'] . '"';
                    echo ',"' . $rowxl['submission'] . '"';
                    echo ',"' . $rowxl['level'] . '"';
                    echo ',"' . $rowxl['age_band'] . '"';
                    echo ',"' . $rowxl['a09'] . '"';
                    echo ',"' . $rowxl['local_authority'] . '"';
                    echo ',"' . $rowxl['region'] . '"';
                    echo ',"' . $rowxl['postcode'] . '"';
                    echo ',"' . $rowxl['sfc'] . '"';
                    echo ',"' . $rowxl['ssa1'] . '"';
                    echo ',"' . $rowxl['ssa2'] . '"';
                    echo ',"' . $rowxl['employer'] . '"';
                    echo ',"' . $rowxl['assessor'] . '"';
                    echo ',"' . $rowxl['provider'] . '"';
                    echo ',"' . $rowxl['contractor'] . '"';
                    echo ',"' . $rowxl['ethnicity'] . '"';
                    echo ',"' . $rowxl['p_prog_status'] . '"';
                    echo ',"' . $rowxl['aim_type'] . '"';
                    echo "\r\n";
                }
            }
        }
        else
        {
            $d = date('d/m/Y h:i:s a', time());
            DAO::execute($link, "update configuration set value = '$d' where entity = 'QAR'");
			if(DB_NAME != 'am_crackerjack')
	            http_redirect("do.php?_action=qar");
        }

        /*
        // This report shows any out of sync learners in regard to their ILR actual end dates and their training record closure dates
        $report = '';
        $sql = "SELECT * from tr where status_code = 2;";
        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                $tr_id = $row['id'];
                $l03 = $row['l03'];
                $ilr = DAO::getSingleValue($link, "SELECT ilr FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $tr_id ORDER BY contract_year DESC, submission DESC LIMIT 0,1;");
                $submission = DAO::getSingleValue($link, "SELECT submission FROM ilr INNER JOIN contracts ON contracts.id = ilr.contract_id WHERE tr_id = $tr_id ORDER BY contract_year DESC, submission DESC LIMIT 0,1;");

                $ilr = Ilr2009::loadFromXML($ilr);

                if($ilr->programmeaim->A10=="70" || ($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!=""))
                {
                    $actual_end_date = $ilr->programmeaim->A31;
                }
                else
                {
                    $actual_end_date = $ilr->aims[0]->A31;
                }

                $closure_date = Date::toMedium($row['closure_date']);
                if($actual_end_date!='00000000')
                    $actual_end_date = Date::toMedium($actual_end_date);
                if($actual_end_date!=$closure_date)
                    $report .= $submission . " " . $l03 . " " . $tr_id . " " . $actual_end_date . " " . $closure_date . "\n";
            }
        }

        pre($report);
*/
		include('tpl_success_rates.php');
    }


    public function createTempTable(PDO $link)
    {
		if(DB_NAME == "am_baltic")
			DAO::execute($link, "SET max_heap_table_size = 367001600");
        $sql = <<<HEREDOC
CREATE TEMPORARY TABLE `success_rates` (
  `l03` varchar(12) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `programme_type` varchar(15) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  `expected` int(11) DEFAULT NULL,
  `actual` int(11) DEFAULT NULL,
  `completion_status` int(11) DEFAULT NULL,
  `outcome` int(11) DEFAULT NULL,
  `hybrid` int(11) DEFAULT NULL,
  `p_prog_status` int(11) DEFAULT NULL,
  `contract_id` int(11) DEFAULT NULL,
  `submission` varchar(3) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `age_band` varchar(20) DEFAULT NULL,
  `a09` varchar(8) DEFAULT NULL,
  `local_authority` varchar(50) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `sfc` varchar(100) DEFAULT NULL,
  `ssa1` varchar(100) DEFAULT NULL,
  `ssa2` varchar(100) DEFAULT NULL,
  `employer` varchar(100) DEFAULT NULL,
  `assessor` varchar(100) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `contractor` varchar(100) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `aim_type` varchar(50) DEFAULT NULL,
  `lldd` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `programme` varchar(70) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `StdCode` int(11) default null,
  `FworkCode` int(11) default null,
  `PwayCode` int(11) default null,
  `data_error` int(11) default null,
  `year_left` int(11) default null,
  `provider_id` int(11) default null,
  `employer_id` int(11) default null,
  `ethnicity_code` tinyint(4) default null,
  `contract_year` varchar(4) default null,
  `funding_provision` tinyint(1) default null,
  `restart` varchar(1) default null,
  `at_risk` tinyint(4) default null,
  `learner_type` varchar(50) default null,
  `employer_type` varchar(50) default null,
  KEY `prog` (`programme_type`,`expected`,`actual`),
  INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band), index(employer), index(assessor), index(provider), index(contractor)
) ENGINE 'MEMORY'
HEREDOC;
        DAO::execute($link, $sql);
		if(DB_NAME == "am_baltic")
			DAO::execute($link, "SET max_heap_table_size = @@max_heap_table_size");
    }


    public function getOverallAchievers($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 AND programme_type = '$programme_type' $where;");
    }


    public function getOverallAchieversExport($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        $st = $link->query("SELECT tr_id FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 AND programme_type = '$programme_type' $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }


    public function getOverallLeaver($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='' , $assessor = '', $provider='', $contractor='', $ethnicity= '', $framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND programme_type = '$programme_type' $where;");
    }

    public function getOverallLeaverExport($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='' , $assessor = '', $provider='', $contractor='', $ethnicity= '', $framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        $st = $link->query("SELECT tr_id FROM success_rates WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND programme_type = '$programme_type' $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }


    public function getTimelyAchievers($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90 AND programme_type = '$programme_type' $where;");
    }


    public function getTimelyAchieversExport($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        $st = $link->query("SELECT tr_id FROM success_rates WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90 AND programme_type = '$programme_type' $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }


    public function getTimelyLeaver($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE expected = $year AND programme_type = '$programme_type' $where;");
    }

    public function getTimelyLeaverInYear($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates WHERE expected = $year AND programme_type = '$programme_type' and actual_end_date is not null $where;");
    }

    public function getTimelyLeaverExport($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        $st = $link->query("SELECT tr_id FROM success_rates WHERE expected = $year AND programme_type = '$programme_type' $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }

    public function getTimelyLeaverExportInYear($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='',$programme='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';
        if($programme=='All programmes')
            $programme = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";
        if($programme!='')
            $where .= " and programme='$programme'";

        $st = $link->query("SELECT tr_id FROM success_rates WHERE expected = $year AND programme_type = '$programme_type' and actual_end_date is not null $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }


    public function getOverallAchieversBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_bcs WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 AND programme_type = '$programme_type' $where;");
    }


    public function getOverallAchieversExportBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        $st = $link->query("SELECT tr_id FROM success_rates_bcs WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND p_prog_status = 1 AND programme_type = '$programme_type' $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }


    public function getOverallLeaverBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='' , $assessor = '', $provider='', $contractor='', $ethnicity= '', $framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_bcs WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND programme_type = '$programme_type' $where;");
    }

    public function getOverallLeaverExportBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='' , $assessor = '', $provider='', $contractor='', $ethnicity= '', $framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        $st = $link->query("SELECT tr_id FROM success_rates_bcs WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) AND programme_type = '$programme_type' $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }


    public function getTimelyAchieversBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_bcs WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90 AND programme_type = '$programme_type' $where;");
    }


    public function getTimelyAchieversExportBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        $st = $link->query("SELECT tr_id FROM success_rates_bcs WHERE expected = $year AND p_prog_status = 1 and DATEDIFF(actual_end_date, planned_end_date)<=90 AND programme_type = '$programme_type' $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }


    public function getTimelyLeaverBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='', $framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_bcs WHERE expected = $year AND programme_type = '$programme_type' $where;");
    }

    public function getTimelyLeaverInYearBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        return DAO::getSingleValue($link, "SELECT count(*) FROM success_rates_bcs WHERE expected = $year AND programme_type = '$programme_type' and actual_end_date is not null $where;");
    }

    public function getTimelyLeaverExportBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        $st = $link->query("SELECT tr_id FROM success_rates_bcs WHERE expected = $year AND programme_type = '$programme_type' $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }

    public function getTimelyLeaverExportInYearBCS($link, $year, $programme_type, $age_band, $level, $region='', $ssa='', $sfc='', $employer='', $assessor = '', $provider='', $contractor='', $ethnicity='',$framework='',$lldd='',$gender='')
    {
        if($region=='All regions')
            $region = '';
        if($employer=='All employers')
            $employer = '';
        if($assessor=='All assessors')
            $assessor = '';
        if($provider=='All providers')
            $provider = '';
        if($contractor=='All contractors')
            $contractor = '';
        if($ethnicity=='All ethnicities')
            $ethnicity = '';

        $where = '';
        $sfc = addslashes($sfc);
        $framework = addslashes($framework);
        if($level != '')
            $where .= " and level = '$level'";
        if($age_band != '')
            $where .= " and age_band = '$age_band'";
        if($region!='')
            $where .= " and region='$region'";
        if($ssa!='')
            $where .= " and concat(ssa1,'<br>',ssa2)='$ssa'";
        if($sfc!='')
            $where .= " and ssa2='$sfc'";
        if($employer!='')
            $where .= " and employer='$employer'";
        if($assessor!='')
            $where .= " and assessor='$assessor'";
        if($provider!='')
            $where .= " and provider='$provider'";
        if($contractor!='')
            $where .= " and contractor='$contractor'";
        if($ethnicity!='')
            $where .= " and ethnicity='$ethnicity'";
        if($framework!='')
            $where .= " and sfc='$framework'";
        if($lldd!='')
            $where .= " and lldd='$lldd'";
        if($gender!='')
            $where .= " and gender='$gender'";

        $st = $link->query("SELECT tr_id FROM success_rates_bcs WHERE expected = $year AND programme_type = '$programme_type' and actual_end_date is not null $where;");
        if($st)
        {
            $data = Array();
            while($row = $st->fetch())
            {
                $data[] = $row['tr_id'];
            }
            $data2 = implode(",",$data);
        }
        return array(sizeof($data),$data2);
    }

    public function array2xml($array, $xml = false)
    {
        if($xml === false){
            $xml = new SimpleXMLElement('<root/>');
        }
        foreach($array as $key => $value){
            if(is_array($value)){
                array2xml($value, $xml->addChild($key));
            }else{
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }

    public function convertProgrammeTypeToDesc($programme_type)
    {
        $progTypeDesc = '';
        if($programme_type=='2')
            $progTypeDesc = "Advanced Apprenticeship";
        elseif($programme_type=='3')
            $progTypeDesc = "Apprenticeship";
        elseif($programme_type=='20')
            $progTypeDesc = "Higher Apprenticeship";
        else
            $progTypeDesc = "Programme Type " . $programme_type;

        return $progTypeDesc;
    }
}
?>
