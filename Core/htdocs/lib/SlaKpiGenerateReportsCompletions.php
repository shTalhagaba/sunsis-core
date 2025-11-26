<?php
class SlaKpiGenerateReportsCompletions extends View
{
    public static function get_includes()
    {
        include_once('act_sla_kpi_reports.php');
    }
    public static function getInstance(PDO $link)
	{
        error_reporting(E_ALL^E_NOTICE);
        $key = 'view_'.__CLASS__;
        //echo 'key = '.$key;exit;
        if(isset($_REQUEST['page_mode']) && $_REQUEST['page_mode'] != '')
        {
            unset($_SESSION[$key]);
        }

        //filter from date
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

        //filter to date
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
        //echo 'innnnnnnn';
        //echo "filter_drilldown_default_value = ".$filter_drilldown_default_value;
        if($filter_drilldown_default_value == 'quarter' || $filter_drilldown_default_value == 'month' || $filter_drilldown_default_value == 'week')
        {
            $group_by ="";
            if($filter_drilldown_default_value == "quarter")
            {
                $group_by = " GROUP BY year, year_and_quarter ORDER BY year, year_and_quarter ";
            }
            else if($filter_drilldown_default_value == "month")
            {
                $group_by = " GROUP BY year, year_and_month ORDER BY year, month ";
            }
            else if($filter_drilldown_default_value == "week")
            {
                $group_by = " GROUP BY year, week ORDER BY year, week ";
            }

            $sql="SELECT
                    count( tr.id ) AS learner_count,
                    tr.closure_date,
                    year( tr.closure_date ) AS year,
                    month( tr.closure_date ) AS month,
                    monthname( tr.closure_date ) AS month_name,
                    concat( monthname( tr.closure_date ) , '-', year( tr.closure_date ) ) AS monthname_year,
                    concat( year( tr.closure_date ) , '-', month( tr.closure_date ) ) AS year_and_month,
                    concat( year( tr.closure_date ) , '-', quarter( tr.closure_date ) ) AS year_and_quarter,
                    quarter( tr.closure_date ) AS quarter,
                    CASE quarter( tr.closure_date )
                    WHEN 1
                    THEN 'Jan-Mar'
                    WHEN 2
                    THEN 'Apr-Jun'
                    WHEN 3
                    THEN 'Jul-Sep'
                    WHEN 4
                    THEN 'Oct-Dec'
                    END AS quarter_name,
                    weekofyear( tr.closure_date ) AS week
                    FROM tr
                    WHERE tr.status_code = 2
                    ".$group_by;
        }
        elseif($filter_drilldown_default_value == 'employer')
        {
            $sql ="SELECT year( tr.closure_date ) AS year, count( tr.id ) AS learner_count, tr.employer_id, o.legal_name AS employer_name
                    FROM tr
                    LEFT JOIN organisations o ON tr.employer_id = o.id
                    WHERE tr.status_code = 2
                    GROUP BY year, tr.employer_id
                    ORDER BY year, employer_name";
        }
        elseif($filter_drilldown_default_value == 'training_provider')
        {
            $sql ="SELECT year( tr.closure_date ) AS year, count( tr.id ) AS learner_count, tr.provider_id, o.legal_name AS training_provider_name
                    FROM tr
                    LEFT JOIN organisations o ON tr.provider_id = o.id
                    WHERE tr.status_code = 2
                    GROUP BY year, tr.provider_id
                    ORDER BY year, training_provider_name";
        }
        elseif($filter_drilldown_default_value == 'contract')
        {
            $sql ="SELECT year( tr.closure_date ) AS year, count( tr.id ) AS learner_count, tr.contract_id, c.title AS contract_name
                    FROM tr
                    LEFT JOIN contracts c ON tr.contract_id = c.id
                    WHERE tr.status_code = 2
                    GROUP BY year, tr.contract_id
                    ORDER BY year, contract_name";
        }
        elseif($filter_drilldown_default_value == 'assessor')
        {
            $sql ="SELECT year(tr.closure_date) as year, count( tr.id ) AS learner_count,
                    tr.assessor,
                    concat( u.firstnames, u.surname ) AS assessor_name
                    FROM tr
                    LEFT JOIN users u ON tr.assessor = u.id
                    WHERE concat( u.firstnames, u.surname ) IS NOT NULL and tr.assessor != '' AND tr.status_code = 2
                    GROUP BY year, tr.assessor
                    ORDER BY year, assessor_name";
        }
        else
        {
            $sql ="SELECT year( tr.closure_date ) AS year, count( tr.id ) AS learner_count
                    FROM tr
                    WHERE tr.status_code = 2
                    GROUP BY year
                    ORDER BY year";
        }

        //echo "sql = ".$sql;
        $view = $_SESSION[$key] = new SlaKpiGenerateReportsCompletions();
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
		   	//$options = array(
//				0=>array(1, 'internaltitle (asc)', null, 'ORDER BY sq.internaltitle'),
//				1=>array(2, 'internaltitle (desc)', null, 'ORDER BY sq.internaltitle DESC'));
//			$f = new DropDownViewFilter('order_by', $options, 1, false);
//			$f->setDescriptionFormat("Sort by: %s");
//			$view->addFilter($f);

            //start_date filter
            $format = "WHERE tr.closure_date >= '%s'";
			$f = new DateViewFilter('start_date', $format, $start_date_default_value);
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

            //end_date filter
			$format = "WHERE tr.closure_date <= '%s'";
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
                1=>array('quarter', 'Quarter', null, null),
                2=>array('month', 'Month', null, null),
                3=>array('week', 'Week', null, null),
				4=>array('employer', 'Employer', null, null),
				5=>array('contract', 'Contract', null, null),
                6=>array('training_provider', 'Training Provider', null, null),
                7=>array('assessor', 'Asssessor', null, null)
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




        $drill_down_by = $this->getFilterValue('filter_drilldown');
        //echo "<br>drill_down_by = ".$drill_down_by."<br>";
        if($drill_down_by == "none")
        {
            $drill_down_by="";
        }

        //echo 'sql = '.$this->getSQL();//exit;
		$st = $link->query($this->getSQL());

        //echo 'st = <pre>';
        //print_r($st);exit;

        $error_msg = "<h1>Sorry, no data found !</h1>";
        $rows_exist="false";

		if($st)
		{
            $table_head = $this->getViewNavigator();
			$table_head .= '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6" style="width:1230px;">';

			$table_body = '<tbody>';

            if($drill_down_by == "quarter")
            {
                $drilldown_name = "Drilldown by Quarter";
                $drilldown_title = "Quarter";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $quarter = $row['quarter_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$quarter.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            elseif($drill_down_by == "month")
            {
                $drilldown_name = "Drilldown by Month";
                $drilldown_title = "Month";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $month = $row['month_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$month.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            elseif($drill_down_by == "week")
            {
                $drilldown_name = "Drilldown by Week";
                $drilldown_title = "Week";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $week = $row['week'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$week.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }
            elseif($drill_down_by == "employer")
            {
                $drilldown_name = "Drilldown by Employers";
                $drilldown_title = "Employers";

                while($row = $st->fetch())
    			{
                    $rows_exist="true";
                    $year = $row['year'];
                    $employer_name = $row['employer_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
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
                    $year = $row['year'];
                    $training_provider_name = $row['training_provider_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
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
                    $year = $row['year'];
                    $contractor_name = $row['contract_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
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
                    $year = $row['year'];
                    $assessor_name = $row['assessor_name'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
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
                    $year = $row['year'];
                    $learner_count = $row['learner_count'];

                    $table_body .='
                    <tr>
                        <td>'.$year.'</td>
                        <td>'.$learner_count.'</td>
                    </tr>';
                }

            }


            if($drill_down_by == "")
            {
                $table_head .= '<thead>
                <tr>
                    <th>Year</th>
                    <th>Completions</th>
                </tr>';
            }
            else
            {
                $table_head .= '<thead>
                <tr>
                    <th>Year</th>
                    <th>'.$drilldown_title.'</th>
                    <th>Completions</th>
                </tr>';
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