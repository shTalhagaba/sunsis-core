<?php
class SlaKpiGenerateReports extends View
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

        //filter contract
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

        //drill down
        $drill_down_by =  $_REQUEST['drill_down_by'];
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

        if(!isset($_SESSION[$key]))
		{
        // Create new view object
        if($filter_drilldown_default_value == 'employer')
        {
            $sql ="SELECT sq.internaltitle, o.legal_name AS employer_name, count( tr.id ) AS learner_count
                    FROM organisations o
                    INNER JOIN tr ON o.id = tr.employer_id
                    INNER JOIN student_qualifications sq ON tr.id = sq.tr_id
                    WHERE sq.achievement_date !=''
                    GROUP BY o.id, sq.internaltitle ";
        }
        elseif($filter_drilldown_default_value == 'training_provider')
        {
            $sql ="SELECT sq.internaltitle, o.legal_name AS training_provider_name, count( tr.id ) AS learner_count
                    FROM organisations o
                    INNER JOIN tr ON o.id = tr.provider_id
                    INNER JOIN student_qualifications sq ON tr.id = sq.tr_id
                    WHERE sq.achievement_date !=''
                    GROUP BY o.id, sq.internaltitle ";
        }
        elseif($filter_drilldown_default_value == 'contract')
        {
            $sql ="SELECT sq.internaltitle, c.title AS contractor_name, count( tr.id ) AS learner_count
                    FROM contracts c
                    INNER JOIN tr ON c.id = tr.contract_id
                    INNER JOIN student_qualifications sq ON tr.id = sq.tr_id
                    WHERE sq.achievement_date !=''
                    GROUP BY c.id, sq.internaltitle ";
        }
        elseif($filter_drilldown_default_value == 'assessor')
        {
            $sql ="SELECT sq.internaltitle, concat( u.firstnames, u.surname ) AS assessor_name, count( tr.id ) AS learner_count
                    FROM users u
                    INNER JOIN tr ON u.id = tr.assessor
                    INNER JOIN student_qualifications sq ON tr.id = sq.tr_id
                    WHERE sq.achievement_date !=''
                    GROUP BY tr.assessor, sq.internaltitle ";//assessor_name
        }
        else
        {
            $sql ="SELECT sq.internaltitle, count( tr.id ) AS learner_count
                    FROM student_qualifications sq
                    INNER JOIN tr ON tr.id = sq.tr_id
                    WHERE sq.achievement_date !=''
                    GROUP BY sq.internaltitle ";
        }

        $view = $_SESSION[$key] = new SlaKpiGenerateReports();
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
			$options = array(
				0=>array(1, 'internaltitle (asc)', null, 'ORDER BY sq.internaltitle'),
				1=>array(2, 'internaltitle (desc)', null, 'ORDER BY sq.internaltitle DESC'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);

            //start_date filter
            $format = "WHERE sq.achievement_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, $start_date_default_value);
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

            //end_date filter
			$format = "WHERE sq.achievement_date <= '%s'";
			$f = new DateViewFilter('end_date', $format, $end_date_default_value);
			$f->setDescriptionFormat("To end date: %s");
			$view->addFilter($f);


	        // Employer Filter
			$options = "SELECT o.id, legal_name, null, CONCAT('WHERE tr.employer_id=',o.id) FROM organisations o inner join tr on o.id = tr.employer_id group by tr.employer_id order by legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, $employer_default_value, true);
			$f->setDescriptionFormat("Employer %s");
			$view->addFilter($f);

            // Training provider Filter
			$options = "SELECT o.id, legal_name, null, CONCAT('WHERE tr.provider_id=',o.id) FROM organisations o inner join tr on o.id = tr.provider_id group by tr.provider_id order by legal_name";
			$f = new DropDownViewFilter('filter_training_provider', $options, $training_provider_default_value, true);
			$f->setDescriptionFormat("Training Provider %s");
			$view->addFilter($f);

            //Assessor filter
            $options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.assessor=' , char(39),id, char(39)) FROM users where type=3 order by CONCAT(firstnames,' ',surname)";

			$f = new DropDownViewFilter('filter_assessor', $options, $assessor_default_value, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

            //Contractor filter
			$options = "SELECT id, title, null, CONCAT('WHERE tr.contract_id=',id) FROM contracts where active =  1 order by title";
			$f = new DropDownViewFilter('filter_contract', $options, $contract_default_value, true);
			$f->setDescriptionFormat("Contract: %s");
			$view->addFilter($f);


            //drill_down filter
            $options = array(
				0=>array('none', 'None', null, null),
				1=>array('employer', 'Employer', null, null),
				2=>array('contract', 'Contract', null, null),
                3=>array('training_provider', 'Training Provider', null, null),
                4=>array('assessor', 'Asssessor', null, null)
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

        $from_date1 = $this->getFilterValue('start_date');

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


        $where_condition = "";
        if($from_date != '' && $to_date != '')
        {
            $where_condition = " and achievement_date >='".$from_date."' and achievement_date <='".$to_date."'";
            $stu_quali_dtls_arr = $obj_sla_kpi_reports->get_student_qualification_details($link,$mode="get_distinct_titles_from_achievement_date_range",$idarr=array($from_date,$to_date));

        }
        else
        {
            $stu_quali_dtls_arr = $obj_sla_kpi_reports->get_student_qualification_details($link,$mode="get_distinct_titles",$idarr=array());
        }

        $drill_down_by = $this->getFilterValue('filter_drilldown');
        if($drill_down_by == "none")
        {
            $drill_down_by="";
        }

		$st = $link->query($this->getSQL());

        $error_msg = "<h1>Sorry, no data found !</h1>";
        $rows_exist="false";

		if($st)
		{
            $table_head = $this->getViewNavigator();
			$table_head .= '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6" style="width:1230px;">';

			$table_body = '<tbody>';


            if($drill_down_by == "employer")
            {
                $drilldown_name = "Drilldown by Employers";
                $drilldown_title = "Employers";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $qualification_title = $row['internaltitle'];
                    $employer_name = $row['employer_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$qualification_title.'</td>
                        <td>'.$employer_name.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            else if($drill_down_by == "training_provider")
            {
                $drilldown_name = "Drilldown by Training Providers";
                $drilldown_title = "Training Providers";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $qualification_title = $row['internaltitle'];
                    $training_provider_name = $row['training_provider_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$qualification_title.'</td>
                        <td>'.$training_provider_name.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            else if($drill_down_by == "contract")
            {
                $drilldown_name = "Drilldown by Contractors";
                $drilldown_title = "Contractors";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $qualification_title = $row['internaltitle'];
                    $contractor_name = $row['contractor_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$qualification_title.'</td>
                        <td>'.$contractor_name.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else if($drill_down_by == "assessor")
            {
                $drilldown_name = "Drilldown by Assessors";
                $drilldown_title = "Assessors";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $qualification_title = $row['internaltitle'];
                    $assessor_name = $row['assessor_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$qualification_title.'</td>
                        <td>'.$assessor_name.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }
            }
            else
            {
                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $qualification_title = $row['internaltitle'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$qualification_title.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }


            if($drill_down_by == "")
            {
                $table_head .= '<thead>
                <tr>
                    <th>Qualification Title</th>
                    <th>No. of Achievers</th>
                </tr>';
            }
            else
            {
                $table_head .= '<thead>
                <tr>
                    <th>Qualification Title</th>
                    <th>'.$drilldown_title.'</th>
                    <th>No. of Achievers</th>
                </tr>';
            }

            if($rows_exist == "true")
            {
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