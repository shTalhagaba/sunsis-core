<?php
class SlaKpiGenerateReportsOverallSuccess extends View
{
    public static function get_includes()
    {
        include_once('act_sla_kpi_reports.php');
        require_once('./lib/KPI_classes.php');
    }

    public static function createTempTable(PDO $link)
    {
        $sql = "
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
                  KEY `prog` (`programme_type`,`expected`,`actual`),
                  INDEX(ssa1), INDEX(ssa2), index(programme_type), index(age_band)
                ) ENGINE 'MEMORY'
                ";
        DAO::execute($link, $sql);
    }

    public static function getInstance(PDO $link)
	{
        /*
        //filter gender
        $gender = $_REQUEST['gender'];
        if($gender != '')
        {
            $gender_default_value = $gender;
        }
        elseif(isset($_REQUEST[__CLASS__.'_gender']))
        {
            $gender_default_value = $_REQUEST[__CLASS__.'_gender'];
        }
        else
        {
            $gender_default_value = "";
        }

        //filter course
        $course = $_REQUEST['course'];
        if($course != '')
        {
            $course_default_value = $course;
        }
        elseif(isset($_REQUEST[__CLASS__.'_course']))
        {
            $course_default_value = $_REQUEST[__CLASS__.'_course'];
        }
        else
        {
            $course_default_value = "";
        }

        //filter framework
        $framework = $_REQUEST['framework'];
        if($framework != '')
        {
            $framework_default_value = $framework;
        }
        elseif(isset($_REQUEST[__CLASS__.'_framework']))
        {
            $framework_default_value = $_REQUEST[__CLASS__.'_framework'];
        }
        else
        {
            $framework_default_value = "";
        }

        //filter valid
        $valid = $_REQUEST['valid'];
        if($valid != '')
        {
            $valid_default_value = $valid;
        }
        elseif(isset($_REQUEST[__CLASS__.'_valid']))
        {
            $valid_default_value = $_REQUEST[__CLASS__.'_valid'];
        }
        else
        {
            $valid_default_value = "all";
        }

        //filter active
        $active = $_REQUEST['active'];
        if($active != '')
        {
            $active_default_value = $active;
        }
        elseif(isset($_REQUEST[__CLASS__.'_active']))
        {
            $active_default_value = $_REQUEST[__CLASS__.'_active'];
        }
        else
        {
            $active_default_value = "all";
        }


        //filter contract_year
        $contract_year = $_REQUEST['contract_year'];
        if($contract_year != '')
        {
            $contract_year_default_value = $contract_year;
        }
        elseif(isset($_REQUEST[__CLASS__.'_contract_year']))
        {
            $contract_year_default_value = $_REQUEST[__CLASS__.'_contract_year'];
        }
        else
        {
            $contract_year_default_value = "";
        }

        //filter submission
        $submission = $_REQUEST['submission'];
        if($submission != '')
        {
            $submission_default_value = $submission;
        }
        elseif(isset($_REQUEST[__CLASS__.'_submission']))
        {
            $submission_default_value = $_REQUEST[__CLASS__.'_submission'];
        }
        else
        {
            //$submission_val = DAO::getSingleValue($link, "SELECT id FROM lookup_er_submissions WHERE description = (SELECT MAX(submission) FROM ilr)");
            $submission_val = DAO::getSingleValue($link, "SELECT MAX(submission) FROM ilr");
            //exit($submission_val);
            $submission_default_value = $submission_val;
        }

*/

        error_reporting(E_ALL^E_NOTICE);
        $key = 'view_'.__CLASS__;
        //echo 'key = '.$key;exit;
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode'] != '')
        {
            unset($_SESSION[$key]);
        }//unset($_SESSION[$key]);


        //filter assessor
        $assessor = $_REQUEST['assessor'];
        if($assessor != '')
        {
            $assessor_default_value = $assessor;
        }
        elseif(isset($_REQUEST[__CLASS__.'_assessor']))
        {
            $assessor_default_value = $_REQUEST[__CLASS__.'_assessor'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $assessor_default_value = "";
        }

        //filter contract
        $contract = $_REQUEST['contract'];
        if($contract != '')
        {
            $contract_default_value = $contract;
        }
        elseif(isset($_REQUEST[__CLASS__.'_contract']))
        {
            $contract_default_value = $_REQUEST[__CLASS__.'_contract'];
        }
        else
        {
            $contract_default_value = "";
        }

        //filter employer
        $employer = $_REQUEST['employer'];
        if($employer != '')
        {
            $employer_default_value = $employer;
        }
        elseif(isset($_REQUEST[__CLASS__.'_employer']))
        {
            $employer_default_value = $_REQUEST[__CLASS__.'_employer'];
        }
        else
        {
            $employer_default_value = "";
        }

        //filter provider
        $training_provider = $_REQUEST['training_provider'];
        if($training_provider != '')
        {
            $training_provider_default_value = $training_provider;
        }
        elseif(isset($_REQUEST[__CLASS__.'_training_provider']))
        {
            $training_provider_default_value = $_REQUEST[__CLASS__.'_training_provider'];
        }
        else
        {
            $training_provider_default_value = "";
        }



        //filter age band
        $age_band = $_REQUEST['age_band'];
        if($age_band != '')
        {
            $age_band_default_value = $age_band;
        }
        elseif(isset($_REQUEST[__CLASS__.'_age_band']))
        {
            $age_band_default_value = $_REQUEST[__CLASS__.'_age_band'];
        }
        else
        {
            $age_band_default_value = "";
        }


        //filter program type or level
        $programme_type = $_REQUEST['programme_type'];
        if($programme_type != '')
        {
            $programme_type_default_value = $programme_type;
        }
        elseif(isset($_REQUEST[__CLASS__.'_programme_type']))
        {
            $programme_type_default_value = $_REQUEST[__CLASS__.'_programme_type'];
        }
        else
        {
            $programme_type_default_value = "";
        }


        //filter sector subject area (ssa)
        $ssa = $_REQUEST['ssa'];
        if($ssa != '')
        {
            $ssa_default_value = $ssa;
        }
        elseif(isset($_REQUEST[__CLASS__.'_ssa']))
        {
            $ssa_default_value = $_REQUEST[__CLASS__.'_ssa'];
        }
        else
        {
            $ssa_default_value = "";
        }


        //filter ethnicity
        $ethnicity = $_REQUEST['ethnicity'];
        if($ethnicity != '')
        {
            $ethnicity_default_value = $ethnicity;
        }
        elseif(isset($_REQUEST[__CLASS__.'_ethnicity']))
        {
            $ethnicity_default_value = $_REQUEST[__CLASS__.'_ethnicity'];
        }
        else
        {
            $ethnicity_default_value = "";
        }


        //drill down
        $drill_down_by =  $_REQUEST['drill_down_by'];
        //echo "drill_down_by = ".$drill_down_by;
        if($drill_down_by != '')
        {
            $filter_drilldown_default_value = $drill_down_by;
        }
        elseif(isset($_REQUEST[__CLASS__.'_filter_drilldown']))
        {
            $filter_drilldown_default_value = $_REQUEST[__CLASS__.'_filter_drilldown'];
        }
        else
        {
            $filter_drilldown_default_value = "none";
        }


        if(isset($_REQUEST[__CLASS__.'_filter_drilldown']))
        {
            unset($_SESSION[$key]);
        }
        //echo 'session = <pre>';
        //print_r($_SESSION[$key]);exit;
        //echo $_SESSION[$key]->sql;exit;

        if(!isset($_SESSION[$key]))
		{
            // Create new view object


            /*//filter gender
            $options = 'SELECT DISTINCT gender, gender, null, CONCAT("WHERE tr.gender=",char(39),gender,char(39)) FROM tr';
			$f = new DropDownViewFilter('filter_gender', $options, $gender_default_value, true);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

            //Filter course
            $options = 'SELECT DISTINCT id, title, null, CONCAT("WHERE courses.id=",id) FROM courses order by title';
			$f = new DropDownViewFilter('filter_course', $options, $course_default_value, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);

            //Filter framework
            $options = 'SELECT DISTINCT id, title, null, CONCAT("WHERE student_frameworks.id=",id) FROM student_frameworks order by title';
			$f = new DropDownViewFilter('filter_framework', $options, $framework_default_value, true);
			$f->setDescriptionFormat("Framework: %s");
			$view->addFilter($f);


            ////Filter valid
            $options = array(
                0=>array('all', 'All', null, ' where ilr.is_valid = "1" OR ilr.is_valid != "1"'),
                1=>array('valid', 'Valid', null, ' where ilr.is_valid = "1"'),
                2=>array('invalid', 'Invalid', null, ' where ilr.is_valid != "1"')
            );
            $f = new DropDownViewFilter('filter_valid', $options, $valid_default_value, false);
            $f->setDescriptionFormat("Validity: %s");
            $view->addFilter($f);


            ////Filter active
            $options = array(
                0=>array('all', 'All', null, ' where ilr.is_active = "1" OR ilr.is_active != "1"'),
                1=>array('active', 'Active', null, ' where ilr.is_active = "1"'),
                2=>array('inactive', 'Not Active', null, ' where ilr.is_active != "1"')
            );
            $f = new DropDownViewFilter('filter_active', $options, $active_default_value, false);
            $f->setDescriptionFormat("Active: %s");
            $view->addFilter($f);


            ///// Filter Contracts year
			$options = "SELECT DISTINCT(contract_year), contract_year, null, CONCAT('WHERE c.contract_year=',contract_year) FROM contracts ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_contract_year', $options, $contract_year_default_value, false);
			$f->setDescriptionFormat("Contract year: %s");
            $view->addFilter($f);

            //// Filter submission
			$options = "SELECT description, description, null, CONCAT('WHERE ilr.submission=',quote(description)) FROM lookup_er_submissions";
			$f = new DropDownViewFilter('filter_submission', $options, $submission_default_value, false);
			$f->setDescriptionFormat("Submission: %s");
			$view->addFilter($f);
            */


            $view = $_SESSION[$key] = new SlaKpiGenerateReportsOverallSuccess();


            //Assessor filter
            $options = "SELECT CONCAT(firstnames,' ',surname), CONCAT(firstnames,' ',surname), null, null FROM users where type=3 order by CONCAT(firstnames,' ',surname)";

			$f = new DropDownViewFilter('filter_assessor', $options, $assessor_default_value, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

            //Contractor filter
			$options = "SELECT id, title, null, null FROM contracts where active = 1 order by title";
			$f = new DropDownViewFilter('filter_contract', $options, $contract_default_value, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

            // Employer Filter
			$options = "SELECT o.id, legal_name, null, null FROM organisations o inner join tr on o.id = tr.employer_id group by tr.employer_id order by legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, $employer_default_value, true);
			$f->setDescriptionFormat("Employer %s");
			$view->addFilter($f);

            // Training provider Filter
			$options = "SELECT o.id, legal_name, null, null FROM organisations o inner join tr on o.id = tr.provider_id group by tr.provider_id order by legal_name";
			$f = new DropDownViewFilter('filter_training_provider', $options, $training_provider_default_value, true);
			$f->setDescriptionFormat("Training Provider %s");
			$view->addFilter($f);


            ////Filter age_band
            $options = array(
                0=>array('16-18', '16-18', null, null),
                1=>array('19-24', '19-24', null, null),
                2=>array('25+', '25+', null, null),
                3=>array('Unknown', 'Unknown', null, null)
            );
            $f = new DropDownViewFilter('filter_age_band', $options, $age_band_default_value, true);
            $f->setDescriptionFormat("Age band: %s");
            $view->addFilter($f);


            ////Filter programme_type
            $options = array(
                0=>array('Apprenticeship', 'Apprenticeship', null, null),
                1=>array('Workplace', 'Workplace', null, null),
                2=>array('Classroom', 'Classroom', null, null),
                3=>array('Unknown', 'Unknown', null, null)
            );
            $f = new DropDownViewFilter('filter_programme_type', $options, $programme_type_default_value, true);
            $f->setDescriptionFormat("Programme type: %s");
            $view->addFilter($f);


            // Filter ssa
			//$options = "SELECT CONCAT(SSA_TIER1_CODE,' ',SSA_TIER1_DESC), CONCAT(SSA_TIER1_CODE,' ',SSA_TIER1_DESC), null, null FROM lad201213.ssa_tier1_codes order by SSA_TIER1_CODE asc";
			$options = "SELECT CONCAT(SSA_TIER2_CODE,' ',SSA_TIER2_DESC), CONCAT(SSA_TIER2_CODE,' ',SSA_TIER2_DESC), null, null FROM lad201213.ssa_tier2_codes order by SSA_TIER2_CODE asc";
			$f = new DropDownViewFilter('filter_ssa', $options, $ssa_default_value, true);
			$f->setDescriptionFormat("Sector subject area %s");
            $view->addFilter($f);

            // Filter Ethnicity
            $options = "SELECT Ethnicity_Desc, Ethnicity_Desc AS description, NULL , NULL
                        FROM lis201112.ilr_l12_ethnicity
                        UNION SELECT Ethnicity_Desc, Ethnicity_Desc, NULL , NULL
                        FROM lis201011.ilr_l12_ethnicity
                        ORDER BY description ASC";
            $f = new DropDownViewFilter('filter_ethnicity', $options, $ethnicity_default_value, true);
			$f->setDescriptionFormat("Ethnicity %s");
            $view->addFilter($f);


            //drill_down filter
            $options = array(
                /*
                5=>array('gender', 'Gender', null, null),
                6=>array('course', 'Course', null, null),
                8=>array('area_of_learning', 'Area of Learning', null, null),
                9=>array('frameworks', 'Frameworks', null, null),
                */
                0=>array('none', 'None', null, null),
                1=>array('assessor', 'Asssessor', null, null),
                2=>array('contract', 'Contract', null, null),
				3=>array('employer', 'Employer', null, null),
                4=>array('training_provider', 'Training Provider', null, null),
                5=>array('age_band', 'Age band', null, null),
                6=>array('programme_type', 'Programme Type', null, null),
                7=>array('ssa', 'Sector subject area', null, null),
                8=>array('ethnicity', 'Ethnicity', null, null),
                9=>array('region', 'Government Office Region', null, null),
            );

			$f = new DropDownViewFilter('filter_drilldown', $options, $filter_drilldown_default_value, false);
			$f->setDescriptionFormat("Drilldown by : %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}


   	public function render(PDO $link)
	{
        self::get_includes();

        $obj_sla_kpi_reports = new sla_kpi_reports();
        $stu_quali_dtls_arr = array();


        // Loop through all the contracts starting with the most recent
    	$current_contract_year = DAO::getSingleValue($link, "SELECT contract_year FROM contracts ORDER BY contract_year DESC LIMIT 0,1;");
    	//	$link->query("truncate success_rates");
		$this->createTempTable($link);
		$values = '';
		$counter = 0;
		$data = array();

        $and_condition="";


        $contract_id = $this->getFilterValue('filter_contract');
        //echo "<br>contract_id = ".$contract_id."<br>";//exit('done');
        if($contract_id){$and_condition .= " AND ilr.contract_id='".$contract_id."' ";}

        $employer_id = $this->getFilterValue('filter_employer');
        //echo "<br>employer_id = ".$employer_id."<br>";//exit('done');
        if($employer_id){$and_condition .= " AND tr.employer_id='".$employer_id."' ";}

        $training_provider_id = $this->getFilterValue('filter_training_provider');
        //echo "<br>training_provider_id = ".$training_provider_id."<br>";//exit('done');
        if($training_provider_id){$and_condition .= " AND tr.provider_id='".$training_provider_id."' ";}


        /*$gender = $this->getFilterValue('filter_gender');

        //$contract_year = $this->getFilterValue('filter_contract_year');
        //echo "<br>contract_year = ".$contract_year."<br>";//exit('done');
        */



		for($year = $current_contract_year; $year>= ($current_contract_year-4); $year--)
		{
            $sql = "SELECT ilr.*, contracts.* FROM ilr
                    	INNER JOIN contracts ON contracts.id = ilr.contract_id
                    	LEFT JOIN tr ON tr.id = ilr.tr_id
                    	LEFT JOIN organisations AS employers ON employers.id = tr.employer_id
                    	LEFT JOIN organisations AS providers ON providers.id = tr.provider_id
                    	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
                    WHERE ilr.is_active =1
                    AND contracts.funding_body =2
                    AND submission = (
                    	SELECT MAX( submission )
                    	FROM ilr
                    	INNER JOIN contracts ON contracts.id = ilr.contract_id
                    	WHERE contract_year =$year
                    )
                    AND funding_type =1
                    AND contract_year = '$year' ".$and_condition;
                //pr($sql);
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
    								elseif($age<=24)
    									$age_band = "19-24";
    								elseif($age>=25)
    									$age_band = "25+";
    								else
    									$age_band = "Unknown";

    								if($ilr->programmeaim->A31!='00000000' && $ilr->programmeaim->A31!='00/00/0000' && $ilr->programmeaim->A31!='')
    									$actual_date = Date::toMySQL($ilr->programmeaim->A31);
    								else
    									$actual_date = "0000-00-00";

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
    										elseif($age<=24)
    											$age_band = "19-24";
    										elseif($age>=25)
    											$age_band = "25+";
    										else
    											$age = "Unknown";

    										if($ilr->aims[$a]->A31!='00000000' && $ilr->aims[$a]->A31!='00/00/0000' && $ilr->aims[$a]->A31!='')
    											$actual_date = Date::toMySQL($ilr->aims[$a]->A31);
    										else
    											$actual_date = "0000-00-00";

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

    										$provider = addslashes((string)$provider);
    										$ethnicity = $ilr->learnerinformation->L12;

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
    										$data[] = $d;


    									}
    								}
    							}

    							$counter++;
    						}
    					}
    					else
    					{
    						$ilr = Ilr2012::loadFromXML($row['ilr']);
    						$tr_id = $row['tr_id'];
    						$submission = $row['submission'];
    						$l03 = $row['L03'];
    						$contract_id = $row['contract_id'];
    						$p_prog_status = -1;

    						foreach($ilr->LearningDelivery as $delivery)
    						{
    							if($delivery->AimType==1 && $delivery->ProgType!='99')
    							{
    								$programme_type = "Apprenticeship";
    								$a26 = "".$delivery->FworkCode;
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
    								elseif($age<=24)
    									$age_band = "19-24";
    								elseif($age>=25)
    									$age_band = "25+";
    								else
    									$age_band = "Unknown";

    								if($delivery->LearnActEndDate!='00000000' && $delivery->LearnActEndDate!='00/00/0000' && $delivery->LearnActEndDate!='')
    									$actual_date = Date::toMySQL($delivery->LearnActEndDate);
    								else
    									$actual_date = "0000-00-00";

    								if($delivery->AchDate!='00000000' && $delivery->AchDate!='00/00/0000' && $delivery->AchDate!='')
    									$achievement_date = Date::toMySQL($delivery->AchDate);
    								else
    									$achievement_date = "0000-00-00";

    								$level = "".$delivery->ProgType;

    								// Calculation for p_prog_status for apprenticeship only
    								if($delivery->ProgType=='2' || $delivery->ProgType=='3' || $delivery->ProgType=='10')
    								{
    									$p_prog_status = 7;
    									if($actual_date=='0000-00-00')
    										$p_prog_status = 0;
    									if($achievement_date!='' && $achievement_date!='0000-00-00')
    										$p_prog_status = 1;
    									if($actual_date!='0000-00-00' && ($delivery->Outcome=='4' || $delivery->Outcome=='5') && $achievement_date!='0000-00-00')
    										$p_prog_status = 3;
    									if($achievement_date && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
    										$p_prog_status = 4;
    									if($achievement_date && $actual_date=='0000-00-00')
    										$p_prog_status = 5;
    									if($achievement_date && $actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
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
    									if($d->AimType==1 || $d->AimType==4)
    									{
    										$a09 = "".$d->LearnAimRef;
    										$ukprn = "".$d->PartnerUKPRN;
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

    								$provider = addslashes((string)$provider);
    								$ethnicity = "".$ilr->Ethnicity;
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
    								$data[] = $d;
    							}
    							else
    							{
    								if($delivery->AimType==4 && $delivery->FundModel!='99')
    								{
                                        $ldm = '';
                                        foreach($delivery->LearningDeliveryFAM as $ldf)
                                        {
                                            if($ldf->LearnDelFAMType=='LDM')
                                                if($ldf->LearnDelFAMCode=='125')
                                                    $ldm = 'Classroom';
                                        }

                                        if($ldm=='Classroom')
                                            $programme_type = "Classroom";
                                        elseif($delivery->MainDelMeth=='24' || $delivery->MainDelMeth=='23' || $delivery->MainDelMeth=='22')
                                            $programme_type = "Workplace";
                                        else
                                            $programme_type = "Unknown";

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
    									elseif($age<=24)
    										$age_band = "19-24";
    									elseif($age>=25)
    										$age_band = "25+";
    									else
    										$age = "Unknown";

    									if($delivery->LearnActEndDate!='00000000' && $delivery->LearnActEndDate!='00/00/0000' && $delivery->LearnActEndDate!='')
    										$actual_date = Date::toMySQL($delivery->LearnActEndDate);
    									else
    										$actual_date = "0000-00-00";

    									if($delivery->AchDate!='00000000' && $delivery->AchDate!='00/00/0000' && $delivery->AchDate!='')
    										$achievement_date = Date::toMySQL($delivery->AchDate);
    									else
    										$achievement_date = "0000-00-00";

    									$level = "".$delivery->ProgType;
    									$a09 = "".$delivery->LearnAimRef;
    									// Calculation for p_prog_status for apprenticeship only
    									$p_prog_status = 7;
    									if($actual_date=='0000-00-00')
    										$p_prog_status =0;
    									if($achievement_date!='0000-00-00')
    										$p_prog_status = 1;
    									if($actual_date!='0000-00-00' && ($delivery->Outcome==4 || $delivery->Outcome==5) && $achievement_date=='0000-00-00')
    										$p_prog_status = 3;
    									if($actual_date!='0000-00-00' && $achievement_date=='0000-00-00')
    										$p_prog_status = 6;
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

    									$provider = addslashes((string)$provider);
    									$ethnicity = $ilr->Ethnicity;

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
    									//$d['glh'] = $glh;
    									$d['employer'] = '';
    									$d['assessor'] = '';
    									$d['provider'] = $provider;
    									$d['contractor'] = '';
    									$d['ethnicity']	= $ethnicity;
                                        $d['aim_type'] = '';
    									$data[] = $d;

    								}
    							}
    						}
    						$counter++;
    					}
    				}
    			}
                else
                {
                    throw new DatabaseException($link, $sql);
                }
            }

            //pr($data);

    		DAO::multipleRowInsert($link, "success_rates", $data);

    		// Remaining fields
    		DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier1_codes on ssa_tier1_codes.SSA_TIER1_CODE = all_annual_values.SSA_TIER1_CODE set ssa1 = CONCAT(lad201213.ssa_tier1_codes.SSA_TIER1_CODE,' ',lad201213.ssa_tier1_codes.SSA_TIER1_DESC)");
    		DAO::execute($link, "update success_rates INNER JOIN lad201213.all_annual_values on all_annual_values.LEARNING_AIM_REF = success_rates.a09 INNER JOIN lad201213.ssa_tier2_codes on ssa_tier2_codes.SSA_TIER2_CODE = all_annual_values.SSA_TIER2_CODE set ssa2 = CONCAT(lad201213.ssa_tier2_codes.SSA_TIER2_CODE,' ',lad201213.ssa_tier2_codes.SSA_TIER2_DESC)");
    		DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.employer_id set employer = organisations.legal_name");
    		DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id set provider = organisations.legal_name where provider='' or provider is NULL");
            if(DB_NAME=='am_lead')
            {
                DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN organisations on organisations.id = tr.provider_id set provider = organisations.legal_name");
            }

            //changed from contract_holders to contracts
            //DAO::execute($link, "update success_rates INNER JOIN contracts on contracts.id = success_rates.contract_id INNER JOIN organisations on organisations.id = contracts.contract_holder set contractor = organisations.legal_name");
            DAO::execute($link, "update success_rates INNER JOIN contracts on contracts.id = success_rates.contract_id set contractor = contracts.title");


            DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN users on users.id = tr.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname)");
    		DAO::execute($link, "update success_rates INNER JOIN tr on tr.id = success_rates.tr_id INNER JOIN group_members on group_members.tr_id = tr.id INNER JOIN groups on group_members.groups_id = groups.id INNER JOIN users on users.id = groups.assessor set success_rates.assessor = CONCAT(users.firstnames, ' ', users.surname) where success_rates.assessor is NULL or success_rates.assessor=''");

    		DAO::execute($link, "DELETE FROM success_rates WHERE (p_prog_status = 13 or p_prog_status=6 or p_prog_status=-1 or p_prog_status=8)  AND DATE_ADD(start_date, INTERVAL 42 DAY)>actual_end_date and programme_type!='Classroom';");
    		DAO::execute($link, "DELETE FROM success_rates WHERE p_prog_status = 8 OR p_prog_status=12;");

    		//pre($link->errorInfo());
    		DAO::execute($link, "UPDATE success_rates SET actual = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.actual_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.actual_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2'), expected = (SELECT contract_year FROM central.lookup_submission_dates WHERE success_rates.planned_end_date >= central.lookup_submission_dates.census_start_date AND success_rates.planned_end_date <= central.lookup_submission_dates.census_end_date and central.lookup_submission_dates.contract_type = '2');");
    		DAO::execute($link, "update success_rates set ethnicity = (select Ethnicity_Desc from lis201112.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) UNION select Ethnicity_Desc from lis201011.ilr_l12_ethnicity where TRIM(Ethnicity_Code)=trim(success_rates.ethnicity) limit 0,1);");
    		DAO::execute($link, "update success_rates INNER JOIN lad201213.frameworks on frameworks.FRAMEWORK_CODE = success_rates.sfc set sfc = frameworks.FRAMEWORK_DESC");
    		DAO::execute($link, "update success_rates set sfc = LEFT(sfc,POSITION('-' IN sfc)-1)");
            DAO::execute($link, "update success_rates LEFT JOIN lad201213.learning_aim on learning_aim.LEARNING_AIM_REF = success_rates.a09 LEFT JOIN lad201213.learning_aim_types on learning_aim_types.LEARNING_AIM_TYPE_CODE = learning_aim.LEARNING_AIM_TYPE_CODE set aim_type = LEARNING_AIM_TYPE_DESC");
            DAO::execute($link, "update success_rates set ssa1 = sfc where ssa1='X Not Applicable'");
            DAO::execute($link, "update success_rates set ssa1 = replace(ssa1,\"'\",\"\")");

DAO::execute($link, "UPDATE success_rates LEFT JOIN central.lookup_la_gor ON success_rates.local_authority = central.lookup_la_gor.local_authority SET success_rates.region = central.lookup_la_gor.government_region;");


            DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'QCF Units' and programme_type = 'Classroom'");
            DAO::execute($link, "DELETE FROM success_rates WHERE aim_type = 'Employability Award' and programme_type = 'Classroom'");



            $filter_conditions = "";

            $filter_assessor_val = $this->getFilterValue('filter_assessor');
            //echo "<br>filter_assessor_val = ".$filter_assessor_val."<br>";//exit('done');
            if($filter_assessor_val != "")
            {
                $filter_conditions .= " AND assessor = '".$filter_assessor_val."'";
            }

            $filter_age_band_val = $this->getFilterValue('filter_age_band');
            //echo "<br>filter_age_band_val = ".$filter_age_band_val."<br>";//exit('done');
            if($filter_age_band_val != "")
            {
                $filter_conditions .= " AND age_band = '".$filter_age_band_val."'";
            }

            $filter_programme_type_val = $this->getFilterValue('filter_programme_type');
            //echo "<br>filter_programme_type_val = ".$filter_programme_type_val."<br>";//exit('done');
            if($filter_programme_type_val != "")
            {
                $filter_conditions .= " AND programme_type = '".$filter_programme_type_val."'";
            }

            $filter_ssa_val = $this->getFilterValue('filter_ssa');
            //echo "<br>filter_ssa_val = ".$filter_ssa_val."<br>";//exit('done');
            if($filter_ssa_val != "")
            {
                $filter_conditions .= " AND ssa2 = '".$filter_ssa_val."'";
            }

            $filter_ethnicity_val = $this->getFilterValue('filter_ethnicity');
            //echo "<br>filter_ethnicity_val = ".$filter_ethnicity_val."<br>";//exit('done');
            if($filter_ethnicity_val != "")
            {
                $filter_conditions .= " AND ethnicity = '".$filter_ethnicity_val."'";
            }




            $drill_down_by = $this->getFilterValue('filter_drilldown');
            //echo "<br>drill_down_by = ".$drill_down_by."<br>";//exit('done');

            $group_by="";

            if($drill_down_by == "none")
            {
                $drilldown_name="Drilldown by Contract year";
                $drilldown_title = "Contract year";
                $drilldown_col_key = "";

                //$group_by = "";
            }

            else if($drill_down_by == "assessor")
            {
                $drilldown_name = "Drilldown by Assessors";
                $drilldown_title = "Assessors";
                $drilldown_col_key = "assessor";

                $group_by = "assessor";
            }

            else if($drill_down_by == "contract")
            {
                $drilldown_name = "Drilldown by Contracts";
                $drilldown_title = "Contracts";
                $drilldown_col_key = "contractor";

                $group_by = "contract_id";
            }

            elseif($drill_down_by == "employer")
            {
                $drilldown_name = "Drilldown by Employers";
                $drilldown_title = "Employers";
                $drilldown_col_key = "employer";

                $group_by = "employer";
            }

            else if($drill_down_by == "training_provider")
            {
                $drilldown_name = "Drilldown by Training Providers";
                $drilldown_title = "Training Providers";
                $drilldown_col_key = "provider";

                $group_by = "provider";
            }

            else if($drill_down_by == "age_band")
            {
                $drilldown_name = "Drilldown by Age band";
                $drilldown_title = "Age band";
                $drilldown_col_key = "age_band";

                $group_by = "age_band";
            }

            else if($drill_down_by == "programme_type")
            {
                $drilldown_name = "Drilldown by Programme type";
                $drilldown_title = "Programme Type";
                $drilldown_col_key = "programme_type";

                $group_by = "programme_type";
            }

            else if($drill_down_by == "ssa")
            {
                $drilldown_name = "Drilldown by Sector Subject Area";
                $drilldown_title = "Drilldown by Sector Subject Area";
                //$drilldown_col_key = "CONCAT( ssa1, '<br>', ssa2 ) AS ssa";
                $drilldown_col_key = "ssa2";

                $group_by = "ssa2";
            }

            else if($drill_down_by == "ethnicity")
            {
                $drilldown_name = "Drilldown by Ethnicity";
                $drilldown_title = "Ethnicity";
                $drilldown_col_key = "ethnicity";

                $group_by = "ethnicity";
            }

            else if($drill_down_by == "region")
            {
                $drilldown_name = "Drilldown by Government Office Region";
                $drilldown_title = "Government Office Region";
                $drilldown_col_key = "region";

                $group_by = "region";
            }

            if($group_by != "")
            {
                $group_by = " GROUP BY ".$group_by;
            }
            $drilldown_column = "";
            if($drilldown_col_key != '')
            {
                $drilldown_column = $drilldown_col_key.", ";
            }

            //for($year = $current_contract_year; $year>= ($current_contract_year-4); $year--)
            $years_expected = DAO::getSingleColumn($link, "SELECT distinct expected FROM success_rates WHERE expected IS NOT NULL");
    		$years_actual = DAO::getSingleColumn($link, "SELECT distinct actual FROM success_rates WHERE actual IS NOT NULL");
    		$years = array_merge($years_expected, $years_actual);
    		$years = array_unique($years, SORT_STRING);
    		sort($years);

            $report_type = $_REQUEST['report'];



            $arr_all_vals_drilldown_col = array();
            $achievers_arr = array();
            $leavers_arr = array();

            foreach($years as $year)
		    {
                ///The main difference between overall success and timely success depends on the below condition
                if($report_type == "overall_success")
                {
                    $where_cond = " WHERE ((expected = $year AND actual<= $year) OR (expected <= $year AND actual = $year)) ";
                }
                else if($report_type == "timely_success")
                {
                    $where_cond = " WHERE expected = $year AND DATEDIFF(actual_end_date, planned_end_date)<=90 ";
                }



                ///fetch achievers
                $achievers_query = "SELECT ".$drilldown_column." count(tr_id) as achievers FROM success_rates ".$where_cond." AND p_prog_status = 1 ".$filter_conditions." ".$group_by;
                //echo 'achievers_query = <br>';
                //pr($achievers_query);
                $st = $link->query($achievers_query);

                if(! $st) throw new DatabaseException($link, $achievers_query);
                while($row = $st->fetch())
                {
                    if($drill_down_by == "none")
                    {
                        $achievers_arr[$year] = $row['achievers'];
                    }
                    else
                    {
                        $achievers_arr[$year][$row[$drilldown_col_key]] = $row['achievers'];
                    }

                    array_push($arr_all_vals_drilldown_col, $row[$drilldown_col_key]);
                }



                ///fetch leavers
                $leavers_query = "SELECT ".$drilldown_column." count(tr_id) as leavers FROM success_rates ".$where_cond." ".$filter_conditions." ".$group_by;
                //echo 'leavers_query = <br>';
                //pr($leavers_query);
                $st = $link->query($leavers_query);

                if(! $st) throw new DatabaseException($link, $leavers_query);
                while($row = $st->fetch())
                {
                    if($drill_down_by == "none")
                    {
                        $leavers_arr[$year] = $row['leavers'];
                    }
                    else
                    {
                        $leavers_arr[$year][$row[$drilldown_col_key]] = $row['leavers'];
                    }

                    array_push($arr_all_vals_drilldown_col, $row[$drilldown_col_key]);
                }
            }

            //echo 'achievers_arr =<br>';pr($achievers_arr);
            //echo 'leavers_arr =<br>';pr($leavers_arr);

            if($drill_down_by != "none")
            {
                $arr_all_vals_drilldown_col = array_unique($arr_all_vals_drilldown_col, SORT_STRING);
        		sort($arr_all_vals_drilldown_col);
                //echo 'arr_all_vals_drilldown_col =<br>';pr($arr_all_vals_drilldown_col);
            }


            $final_arr = array();
            $cntr=0;
            foreach($years as $year)
		    {
                if($drill_down_by == "none" && isset($achievers_arr[$year]))
                {
                    $final_arr[$cntr]['Year'] = Date::getFiscal($year);

                    $cnt_achievers = 0;

                    if(isset($achievers_arr[$year]))
                    {
                        $cnt_achievers = $achievers_arr[$year];
                    }
                    $final_arr[$cntr]['Achievers'] = $cnt_achievers;


                    $cnt_leavers = 0;

                    if(isset($leavers_arr[$year]))
                    {
                        $cnt_leavers = $leavers_arr[$year];
                    }
                    $final_arr[$cntr]['Leavers'] = $cnt_leavers;


                    $success_rate = "";

                    if($cnt_leavers != 0)//so that we do not get divide by zero error
                    {
                        $success_rate_val = ($cnt_achievers/$cnt_leavers)*100;
                        $success_rate = sprintf("%.2f",$success_rate_val)."%";

                        if($success_rate_val >= 53)
                        {
                            $success_rate = '<font style="background-color: green">'.$success_rate.'</font>';
                        }
                        else
                        {
                            $success_rate = '<font style="background-color: red">'.$success_rate.'</font>';
                        }
                    }
                    $final_arr[$cntr]['Success rate'] = $success_rate;
                    $cntr++;
                }
                else
                {
                    foreach($arr_all_vals_drilldown_col as $drilldown_col_val)
                    {
                        $final_arr[$cntr]['Year'] = Date::getFiscal($year);

                        $final_arr[$cntr][$drilldown_title] = $drilldown_col_val;

                        $cnt_achievers = 0;

                        if(isset($achievers_arr[$year]) && isset($achievers_arr[$year][$drilldown_col_val]))
                        {
                            $cnt_achievers = $achievers_arr[$year][$drilldown_col_val];
                        }
                        $final_arr[$cntr]['Achievers'] = $cnt_achievers;



                        $cnt_leavers = 0;

                        if(isset($leavers_arr[$year]) && isset($leavers_arr[$year][$drilldown_col_val]))
                        {
                            $cnt_leavers = $leavers_arr[$year][$drilldown_col_val];
                        }
                        $final_arr[$cntr]['Leavers'] = $cnt_leavers;


                        $success_rate = "";

                        if($cnt_leavers != 0)//so that we do not get divide by zero error
                        {
                            $success_rate_val = ($cnt_achievers/$cnt_leavers)*100;
                            $success_rate = sprintf("%.2f",$success_rate_val)."%";

                            if($success_rate_val >= 53)
                            {
                                $success_rate = '<font style="background-color: green">'.$success_rate.'</font>';
                            }
                            else
                            {
                                $success_rate = '<font style="background-color: red">'.$success_rate.'</font>';
                            }
                        }
                        $final_arr[$cntr]['Success rate'] = $success_rate;
                        $cntr++;
                    }
                }
            }
            //echo 'final_arr =<br>';pr($final_arr);

            ///This section removes the data from the final array where achievers = 0 and leavers = 0
            if(count($final_arr) > 0)
            {
                $countr=0;
                foreach($final_arr as $arr)
                {
                    if($arr['Achievers'] == 0 && $arr['Leavers'] == 0 )
                    {
                        unset($final_arr[$countr]);
                    }
                    $countr++;
                }
                $final_arr = array_values($final_arr);//as the keys have been unsetted this function resets the keys of the array
            }


            //pre($final_arr);
            if(isset($final_arr) && count($final_arr) > 0)
            {
                $report_apps_by_aol = new DataMatrix(array_keys($final_arr[0]), $final_arr, false);
                $report_apps_by_aol->addTotalColumns(array('Achievers', 'Leavers'));

                echo '<div align="center" style="margin-top:50px;">';
                //echo '<h4>Contract year : '.$contract_year.'</h4>';
				echo '<h3>'.$drilldown_name.'</h3>';

                echo $report_apps_by_aol->to('HTML');
                echo '</div>';
            }
            else
            {
                echo $error_msg = "<h1 style='text-align: center;'>Sorry, no data found !</h1>";
            }

    }
}
?>