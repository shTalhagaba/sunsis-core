<?php
class SlaKpiGenerateReportsLastVisit extends View
{
    public static function get_includes()
    {
        error_reporting(E_ALL^E_NOTICE);
        include_once('act_sla_kpi_reports.php');
    }
    public static function getInstance(PDO $link)
	{

        $key = 'view_'.__CLASS__;

        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode'] != '')
        {
            unset($_SESSION[$key]);
        }
        $from_date = $_REQUEST['from_date'];
        if($from_date != '')
        {
            $start_date_default_value = $from_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_start_date']))
        {
            $start_date_default_value = $_REQUEST[__CLASS__.'_start_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $start_date_default_value = "";
        }

        $to_date = $_REQUEST['to_date'];
        if($to_date != '')
        {
            $end_date_default_value = $to_date;
        }
        elseif(isset($_REQUEST[__CLASS__.'_end_date']))
        {
            $end_date_default_value = $_REQUEST[__CLASS__.'_end_date'];
            //exit($_REQUEST[__CLASS__.'_start_date']);
        }
        else
        {
            $end_date_default_value = "";
        }

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

        //filter training provider
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

        //echo 'session = <pre>';
        //print_r($_SESSION[$key]);exit;
        //echo $_SESSION[$key]->sql;exit;

        if(!isset($_SESSION[$key]))
		{

$sql = "
SELECT DISTINCT
	DATE_FORMAT(tr.start_date, '%d-%m-%Y') as start_date,
	DATE_FORMAT(target_date, '%d-%m-%Y') as planned_end_date,
	frameworks.first_review as frequency,
	frameworks.review_frequency as subsequent,
	assessor_review.comments as assessment_status,
	meeting_dates.all_dates,
	tr.id AS tr_id,
	courses.title as course_title,
	tr.contract_id,
	tr.l03,
	CONCAT(tr.surname, ' ', tr.firstnames) AS learner_name,
    concat(acs.firstnames,' ',acs.surname) as apprentice_coordinator,
	DATE_FORMAT(assessment_date_subquery.assessment_date, '%d-%m-%Y')  as last_review_date,
	employers.legal_name AS employer,
    providers.legal_name AS training_provider,
	IF(CONCAT(assessorsng.firstnames,' ',assessorsng.surname) IS NOT NULL, CONCAT(assessorsng.firstnames,' ',assessorsng.surname), CONCAT(assessors.firstnames,' ',assessors.surname)) AS assessor,
	groups.title as groups,
	NULL as next_review_date,
	NULL as missed_reviews,
	contracts.title as contract,
    CURDATE() as today

FROM
	tr
	LEFT JOIN organisations AS employers	ON tr.employer_id = employers.id
	LEFT JOIN organisations AS providers ON tr.provider_id = providers.id
	LEFT JOIN users ON users.username = tr.username
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN group_members ON group_members.tr_id = tr.id
	LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
	LEFT JOIN courses ON courses.id = courses_tr.course_id
	LEFT JOIN frameworks ON frameworks.id = courses.framework_id
	LEFT JOIN groups ON groups.courses_id = courses.id AND group_members.groups_id = groups.id
	LEFT JOIN users AS assessors ON groups.assessor = assessors.id
	LEFT JOIN users AS verifiers ON groups.verifier = verifiers.id
	LEFT JOIN course_qualifications_dates ON course_qualifications_dates.course_id = courses.id
	LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND assessor_review.id = (SELECT MAX(id) FROM assessor_review WHERE tr_id = tr.id AND `assessor_review`.`meeting_date` IS NOT NULL)
	LEFT JOIN contracts ON contracts.id = tr.contract_id
	LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
	LEFT JOIN users AS verifiersng ON verifiersng.id = tr.verifier
	LEFT JOIN users as acs on acs.id = tr.programme
	LEFT OUTER JOIN (
		SELECT
			assessor_review.tr_id,
			MAX(meeting_date) AS `assessment_date`
		FROM
			assessor_review
		GROUP BY
			assessor_review.tr_id
	) AS `assessment_date_subquery`
		ON `assessment_date_subquery`.tr_id = tr.id

    LEFT OUTER JOIN (
        SELECT
            tr_id,
            GROUP_CONCAT(meeting_date) as all_dates
        FROM assessor_review
            group by assessor_review.tr_id
    ) AS `meeting_dates` on `meeting_dates`.tr_id = tr.id


WHERE status_code = 1 and assessment_date_subquery.assessment_date != '' ".$where."
ORDER BY tr.surname";

        $view = $_SESSION[$key] = new SlaKpiGenerateReportsLastVisit();
        $view->setSQL($sql);


			// Add view filters
			$options = array(
                //0=>array(5,5,null,null),
                0=>array(10,10,null,null),
				1=>array(20,20,null,null),
				2=>array(50,50,null,null),
				3=>array(100,100,null,null),
				4=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 10, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

            // Sort by Qualification title
			/*$options = array(
				0=>array(1, 'internaltitle (asc)', null, 'ORDER BY sq.internaltitle'),
				1=>array(2, 'internaltitle (desc)', null, 'ORDER BY sq.internaltitle DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);*/

            //start_date filter
            $format = "WHERE assessment_date_subquery.assessment_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, $start_date_default_value);
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

            //end_date filter
			$format = "WHERE assessment_date_subquery.assessment_date <= '%s'";
			$f = new DateViewFilter('end_date', $format, $end_date_default_value);
			$f->setDescriptionFormat("To end date: %s");
			$view->addFilter($f);

            //Assessor filter
            $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=3 order by CONCAT(firstnames,' ',surname)";

			$f = new DropDownViewFilter('filter_assessor', $options, $assessor_default_value, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

            //Contract filter
			$options = "SELECT id, title, null, CONCAT('WHERE contracts.id=',id) FROM contracts where active =  1 order by title";
			$f = new DropDownViewFilter('filter_contract', $options, $contract_default_value, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);

            //Employer filter
            $options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" order by legal_name';
			$f = new DropDownViewFilter('filter_employer', $options, $employer_default_value, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

            // Training provider Filter
			$options = "SELECT o.id, legal_name, null, CONCAT('WHERE tr.provider_id=',o.id) FROM organisations o inner join tr on o.id = tr.provider_id group by tr.provider_id order by legal_name";
			$f = new DropDownViewFilter('filter_training_provider', $options, $training_provider_default_value, true);
			$f->setDescriptionFormat("Training Provider %s");
			$view->addFilter($f);

		}
		return $_SESSION[$key];
	}


   	public function render(PDO $link)
	{
        self::get_includes();

        $obj_sla_kpi_reports = new sla_kpi_reports();
        $stu_quali_dtls_arr = array();

	//	pre($this->getSQL());
		//@var $result pdo_result
        //echo '<pre>';
        //print_r($this->getFilterValue('filter_drilldown'));exit;
        $from_date1 = $this->getFilterValue('start_date');

        //echo "from_date1 = ".$from_date1;exit;
        if($from_date1 != '')
        {
            $from_date1 = str_replace('/', '-', $from_date1);
            $from_date = date("Y-m-d",strtotime("$from_date1"));
        }
        else
        {
            $from_date = "";
        }

        $to_date1 = $this->getFilterValue('end_date');

        if($to_date1 != '')
        {
            $to_date1 = str_replace('/', '-', $to_date1);
            $to_date = date("Y-m-d",strtotime("$to_date1"));
        }
        else
        {
            $to_date = "";
        }
        //echo 'from_date1 = '.$from_date1.' to_date1 = '.$to_date1.'<br>';
        //echo 'from_date = '.$from_date.' to_date = '.$to_date.'<br>';
        //pre($this->getSQL());
        //echo $this->getSQL();//exit;
        //exit;
		$st = $link->query($this->getSQL());

        //echo 'st = <pre>';
        //print_r($st);exit;

        $error_msg = '<div align="center"><h1>Sorry, no data found !</h1></div>';
        $rows_exist="false";

		if($st)
		{
            $table_head = $this->getViewNavigator();
			$table_head .= '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6" style="width:1230px;">';

			$table_body = '<tbody>';

            if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
            {
            	   $table_head .= '
            	<thead>
            	<tr>
            		<th>Learner Name</th>
            		<th>Status</th>
            		<th>Member Number</th>
            		<th>Qualification</th>
            		<th>Start date</th>
            		<th>Planned end date</th>
            		<th>Group Code</th>
            		<th>Assessor</th>
            		<th>Last Review Date</th>
            		<th>Next Review Date</th>
            		<th>Employer</th>
                    <th>Training Provider</th>
            		<th>Contract</th>
                    <th>Days</th>
            	</tr>
            	</thead>';
            }
            else
            {
            	   $table_head .= '
            	<thead>
            	<tr>
            		<th>Learner Name</th>
            		<th>Course Title</th>
            		<th>Start date</th>
            		<th>Planned end date</th>
            		<th>Group</th>
            		<th>Assessor</th>
            		<th>Last Review Date</th>
            		<th>Next Review Date</th>
            		<th>Employer</th>
                    <th>Training Provider</th>
            		<th>Contract</th>
                    <th>Days</th>
            	</tr>
            	</thead>';
            }


            while($row = $st->fetch())
			{
                $rows_exist="true";

                $last_review_date = $row['last_review_date'];
                $cnv_last_review_date = date('Y-m-d',strtotime($last_review_date));
                //echo 'from_date = '.$from_date.' today = '.$today.'<br>';
                if($from_date == "")
                {
                    $from_date = $row['today'];
                }
                $diff = abs(strtotime($from_date) - strtotime($cnv_last_review_date));

               /* $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));*/
                $days = floor($diff/(60*60*24));


				/*$d = strtotime($row['next_review_date']);
				$c = strtotime(date("Y-m-d"));
				$color='blue';
				if ( $d < $c ) { $color='red' ; }*/
				//if ( $d > $c ) { $color='blue' ; }

				$contract = $row['contract'];
				if ( preg_match("/LSC/i",$row['contract']) ) { $contract = "LSC"; }
				if ( preg_match("/Scottish/i",$row['contract'] ) ) { $contract = "Scottish"; }

				$table_body .= HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id'] . '&amp;contract=' . $row['contract_id']);
				$table_body .= '<tr style="font-size:8pt">';
				$table_body .= '<td align="left"><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . '&amp;contract=' . $row['contract_id']. ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';

				if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
				{
					if($row['target']>=0 || $row['percentage_completed']>=0)
						if($row['percentage_completed']<$row['target'])
							$table_body .= "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
						else
							$table_body .= "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
					else
							$table_body .= "<td align='center' style='border-right-style: solid;'> <img src=\"/images/notstarted.gif\" border=\"0\" alt=\"\" /></td>";

					$table_body .= '<td align="left">' . HTML::cell($row['member_number']) . '</td>';
				}


				if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
					if($row['qualification']=="500/2154/0")
						$table_body .= '<td align="left">' . HTML::cell("BIT") . '</td>';
					elseif($row['qualification']=='100/3955/7')
						$table_body .= '<td align="left">' . HTML::cell("PMO") . '</td>';
					elseif($row['qualification']=='100/4214/3')
						$table_body .= '<td align="left">' . HTML::cell("IT Users") . '</td>';
					elseif($row['qualification']=='500/3841/2')
						$table_body .= '<td align="left">' . HTML::cell("Leadership") . '</td>';
					elseif($row['qualification']=='500/7384/9')
						$table_body .= '<td align="left">' . HTML::cell("Environment") . '</td>';
					else
						$table_body .= '<td align="left">' . HTML::cell($row['qualification']) . '</td>';
				else
					$table_body .= '<td align="left">' . HTML::cell($row['course_title']) . '</td>';

				$table_body .= '<td align="left">' . HTML::cell(Date::toMedium($row['start_date'])) . '</td>';

				$cd = new Date(date('Y-m-d'));
				$pd = new Date($row['planned_end_date']);

				if($cd->getDate()>$pd->getDate())
					$table_body .= '<td align="left"><span>' . HTML::cell(Date::toMedium($row['planned_end_date'])) . '</span></td>';
				else
					$table_body .= '<td align="left">' . HTML::cell(Date::toMedium($row['planned_end_date'])) . '</td>';

				if(DB_NAME=='am_tmuk' || DB_NAME=='ams')
					$table_body .= '<td align="left">' . HTML::cell($row['group_code']) . '</td>';
				else
					$table_body .= '<td align="left">' . HTML::cell($row['groups']) . '</td>';

				$table_body .= '<td align="left">' . HTML::cell($row['assessor']) . '</td>';
				$table_body .= '<td align="center">' . HTML::cell(Date::toMedium($row['last_review_date'])) . '</td>';
				$table_body .= "<td align='center'><span style='color:$color'>" . HTML::cell(Date::toMedium($row['next_review_date'])) . '</span></td>';
				$table_body .= '<td align="left">' . HTML::cell($row['employer']) . '</td>';
                $table_body .= '<td align="left">' . HTML::cell($row['training_provider']) . '</td>';
				$table_body .= '<td align="left">' . HTML::cell($contract) . '</td>';
                $table_body .= '<td align="left">' . HTML::cell($days).'</td>';
				$table_body .= '</tr>';
			}


            if($rows_exist == "true")
            {
                //echo $data_table;
                echo $table_head;
                echo $table_body;
    			echo '</tbody></table></div>';
    			echo $this->getViewNavigator();
            }
            else
            {
                echo $error_msg;
            }
        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }
}
?>