<?php
class ViewReadUploads extends View
{

    public static function getInstance($link, $_REQUEST)
    {
        $time = $_REQUEST['time'];

        $key = 'view_'.__CLASS__.$time;

        if(!isset($_SESSION[$key]))
        {
            if($_SESSION['user']->isAdmin())
            {
                $sql = <<<HEREDOC
select * from exg where DATE_FORMAT(upload_time,'%d %M %Y %H:%i:%s') = '$time';
HEREDOC;
            }

            $view = $_SESSION[$key] = new ViewReadUploads();
            $view->setSQL($sql);

            // Add view filters
            /*$options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Funding Body (asc), Contract Type (asc)', null, 'ORDER BY title desc, funding_body, contracts.contract_type'),
                1=>array(2, 'Funding Body (desc), Contract Type (desc)', null, 'ORDER BY title , funding_body DESC, contracts.contract_type DESC'),
                2=>array(3, 'Contract Year (desc)', null, 'ORDER BY contracts.contract_year desc, title'));
            $f = new DropDownViewFilter('order_by', $options, 3, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'All Contracts', null, null),
                1=>array(2, 'Active Contracts', null, 'where  contracts.active=1'),
                2=>array(3, 'Inactive Contracts', null, 'where contracts.active<>1'));
            $f = new DropDownViewFilter('by_active', $options, 2, false);
            $f->setDescriptionFormat("Active: %s");
            $view->addFilter($f);

            $options = 'SELECT id, description, null, CONCAT("WHERE lookup_contract_locations.id=",id) FROM lookup_contract_locations';
            $f = new DropDownViewFilter('filter_location', $options, null, true);
            $f->setDescriptionFormat("Contract Location: %s");
            $view->addFilter($f);
*/
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        //pre($this->getSQL());
        if($st)
        {
            //echo $this->getViewNavigator();
            echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';

            echo '<thead><tr>';
            if(DB_NAME=='am_doncaster' || DB_NAME=='am_donc_demo' || DB_NAME=='am_siemens')
            {
                echo '<th>ID</th><th>Status</th><th>Learner Reference Number</th><th>Enrolment No</th><th>ULN</th><th>Firstname</th>';
                echo '<th>Middlename</th><th>Surname</th><th>NI Number</th><th>Prior Attainment</th><th>Ethnicity</th>';
                echo '<th>Nationality</th><th>Health Problems</th><th>Learning Difficulty</th><th>Disability</th><th>Mobile</th>';
                echo '<th>Telephone</th><th>Address Line 1</th><th>Address Line 2</th><th>Address Line 3</th><th>Address Line 4</th>';
                echo '<th>Learner Postcode</th><th>Learning Aim Reference</th><th>Learning Aim Title</th><th>LearnStartDate</th><th>PlannedEndDate</th>';
                echo '<th>Actual End Date</th><th>Completion Status</th><th>Outcome</th><th>Achievement Date</th><th>Course Code</th>';
                echo '<th>Framework Code</th><th>Assessor</th><th>EDRS</th><th>Curriculum</th><th>Employer Name</th>';
                echo '<th>Emp Address 1</th><th>Emp Address 2</th><th>Emp Address 3</th><th>Emp Address 4</th><th>Town</th>';
                echo '<th>Emp Postcode</th><th>Emp Telephone</th><th>HS Expiry</th><th>Contact Name</th><th>Last Visit</th>';
                echo '<th>HS Status</th><th>Risk Status</th><th>Pre Employment</th><th>Gender</th><th>Provider</th>';
                echo '<th>Destination</th><th>Upload Time</th><th>Filename</th>';
            }
            else
            {
                if(DB_NAME=='am_exg')
                {
                    echo '<th>Id</th>';
                }
                echo '<th>Status</th><th>EDRS</th><th>Firstnames</th><th>Surname</th><th>Job Role</th><th>Gender</th><th>Ethnicity</th><th>DOB</th><th>NI</th><th>ULN</th><th>Domicile</th><th>Prior Attainment</th><th>Health Problems</th><th>Disability</th><th>Learning Difficulty</th>';
                if(DB_NAME=='am_exg')
                {
                    echo '<th>LSR</th><th>EmpStatus</th><th>LOU</th><th>SEI</th><th>EII</th><th>BSI</th><th>Destination</th>';
                }
                echo '<th>Address Line 1</th><th>Address Line 2</th><th>Address Line 3</th><th>Address Line 4</th><th>Postcode</th><th>Telephone</th><th>Mobile</th><th>Fax</th><th>Email</th>';
                if(DB_NAME=='am_exg')
                {
                    echo '<th>Centre</th><th>Learning Aim</th><th>Start Date</th><th>Planned End Date</th><th>Full</th><th>ContOrg</th><th>FundModel</th><th>MainDelMeth</th><th>SOF</th><th>LDM</th><th>Actual End Date</th><th>CompStatus</th><th>Outcome</th><th>Withdraw Reason</th><th>Achievement Date</th><th>ActualProgRoute</th><th>GLH</th></tr></thead>';
                }
            }
            echo '<tbody>';
            while($row = $st->fetch())
            {
                //echo HTML::viewrow_opening_tag('/do.php?_action=read_uploads&time=' . $row['upload_time']);
                if($row['status']!="Successfully processed")
                    $style = ' style="background-color: #B19CD9;"';
                else
                    $style = '';
                echo '<tr>';
                if(DB_NAME=='am_exg')
                {
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['internal_id']) . '</td>';
                }
                if(DB_NAME=='am_donc_demo' || DB_NAME=='am_doncaster' || DB_NAME=='am_siemens')
                {
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['id']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['status']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['learnrefnumber']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['enrolment']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['uln']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['firstnames']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['middlename']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['surname']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['ninumber']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['priorattain']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['ethnicity']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['nationality']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['healthproblems']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['learningdifficulty']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['disability']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['mobile']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['telephone']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['add1']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['add2']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['add3']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['add4']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['postcode']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['learnaimref']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['title']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['learnstartdate']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['plannedenddate']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['actualenddate']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['compstatus']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['outcome']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['achievementdate']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['coursecode']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['fworkcode']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['assessor']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['edrs']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['curriculum']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['employer']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['empadd1']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['empadd2']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['empadd3']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['empadd4']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['town']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['emppostcode']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['emptel']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['hsexpiry']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['contactname']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['lastvisit']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['hsstatus']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['risk']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['preemployment']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['gender']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['provider']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['destination']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['upload_time']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['filename']) . '</td>';
                }
                else
                {
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['status']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['edrs']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['firstnames']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['surname']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['job_role']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['gender']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['ethnicity']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['dob']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['ni']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['uln']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['domicile']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['prior_attain']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['health_prob']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['disability']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['learning_difficulty']) . '</td>';

                    if(DB_NAME=='am_exg')
                    {
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['lsr']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['employment_status']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['lou']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['sei']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['eii']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['bsi']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['destination']) . '</td>';
                    }

                    echo '<td align="left"' . $style . '>' . HTML::cell($row['add1']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['add2']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['add3']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['add4']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['postcode']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['telephone']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['mobile']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['fax']) . '</td>';
                    echo '<td align="left"' . $style . '>' . HTML::cell($row['email']) . '</td>';

                    if(DB_NAME=='am_exg')
                    {
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['centre']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['learning_aim']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['start_date']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['planned_end_date']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['full_indicator']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['cont_org']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['fund_model']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['main_del_meth']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['sof']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['ldm']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['actual_end_date']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['comp_status']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['outcome']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['withdraw_reason']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['achievement_date']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['actual_prog_route']) . '</td>';
                        echo '<td align="left"' . $style . '>' . HTML::cell($row['glh']) . '</td>';
                    }
                }
                echo '</tr>';
            }

            echo '</tbody></table></div align="center">';
            //echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
    }

}
?>