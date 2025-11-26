<?php
class ViewOTJReport extends View
{

    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            // Create new view object
            $where = '';

            $sql = <<<HEREDOC
SELECT tr_id,tr.uln, CONCAT(firstnames,' ', surname) AS learner_name, l03 AS LearnRefNumber, `date`, description, `time_from`, time_to, TIME_FORMAT(TIMEDIFF(time_to,time_from),"%H") as duration, comments FROM otj
LEFT JOIN lookup_otj_types ON lookup_otj_types.`id` = otj.`type`
LEFT JOIN tr ON tr.id = otj.`tr_id`
HEREDOC;

            $view = $_SESSION[$key] = new ViewOTJReport();
            $view->setSQL($sql);


            // Add view filters
            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE tr.assessor=',char(39),id,char(39),' or tr.assessor=' , char(39),id, char(39)) FROM users where type=" . User::TYPE_ASSESSOR . " ORDER BY users.firstnames";
            $f = new DropDownViewFilter('filter_assessor', $options, null, true);
            $f->setDescriptionFormat("Assessor: %s");
            $view->addFilter($f);

            $options = "SELECT DISTINCT groups.`assessor`, CONCAT(users.`firstnames`, ' ' , users.`surname`), null, CONCAT('WHERE groups.assessor=', CHAR(39), groups.`assessor`, CHAR(39)) FROM groups INNER JOIN users ON groups.`assessor` = users.id ORDER BY users.`firstnames`;";
            $f = new DropDownViewFilter('filter_group_assessor', $options, null, true);
            $f->setDescriptionFormat("Group Assessor: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('surname', "WHERE tr.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname: %s");
            $view->addFilter($f);

            /*
                         * re: Updated to use lookup_programme_type table #21814
                         */
            $options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type order by description asc ";
            $f = new DropDownViewFilter('filter_programme_type', $options, null, true);
            $f->setDescriptionFormat("Programme Type: %s");
            $view->addFilter($f);

            // Start Date Filter
            $format = "WHERE tr.start_date >= '%s'";
            $f = new DateViewFilter('start_date_start', $format, '');
            $f->setDescriptionFormat("From start date: %s");
            $view->addFilter($f);

            $format = "WHERE tr.start_date <= '%s'";
            $f = new DateViewFilter('start_date_end', $format, '');
            $f->setDescriptionFormat("To start date: %s");
            $view->addFilter($f);

            // Planned end date Filter
            $format = "WHERE tr.target_date >= '%s'";
            $f = new DateViewFilter('end_date_start', $format, '');
            $f->setDescriptionFormat("From end date: %s");
            $view->addFilter($f);

            $format = "WHERE tr.target_date <= '%s'";
            $f = new DateViewFilter('end_date_end', $format, '');
            $f->setDescriptionFormat("To end date: %s");
            $view->addFilter($f);

            // Actual end date Filter
            $format = "WHERE tr.closure_date >= '%s'";
            $f = new DateViewFilter('actual_end_date_start', $format, '');
            $f->setDescriptionFormat("From actual end date: %s");
            $view->addFilter($f);

            $format = "WHERE tr.closure_date <= '%s'";
            $f = new DateViewFilter('actual_end_date_end', $format, '');
            $f->setDescriptionFormat("To actual end date: %s");
            $view->addFilter($f);

            // Compliance Date Filter
            $format = "WHERE date >= '%s'";
            $f = new DateViewFilter('event_date_start', $format, '');
            $f->setDescriptionFormat("From event date: %s");
            $view->addFilter($f);

            $format = "WHERE date <= '%s'";
            $f = new DateViewFilter('event_date_end', $format, '');
            $f->setDescriptionFormat("To event date: %s");
            $view->addFilter($f);


            // Add view filters
            $options = array(
                0=>array(0, 'Show all', null, null),
                1=>array(1, '1. The learner is continuing or intending to continue', null, 'WHERE tr.status_code=1'),
                2=>array(2, '2. The learner has completed the learning activity', null, 'WHERE tr.status_code=2'),
                3=>array(3, '3. The learner has withdrawn from learning', null, 'WHERE tr.status_code=3'),
                4=>array(4, '4. The learner has transferred to a new learning provider', null, 'WHERE tr.status_code = 4'),
                5=>array(5, '5. Changes in learning within the same programme', null, 'WHERE tr.status_code = 5'),
                6=>array(6, '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
                7=>array(7, '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
            $f = new DropDownViewFilter('filter_record_status', $options, 1, false);
            $f->setDescriptionFormat("Show: %s");
            $view->addFilter($f);

            $options = "SELECT distinct description, description, null, CONCAT('WHERE otj.type=',char(39),id,char(39)) FROM lookup_otj_types";
            $f = new DropDownViewFilter('filter_otj_type', $options, null, true);
            $f->setDescriptionFormat("OTJ Type: %s");
            $view->addFilter($f);

            $options = "SELECT distinct id, title, null, CONCAT('WHERE tr.contract_id=',char(39),id,char(39)) FROM contracts";
            $f = new DropDownViewFilter('filter_contract', $options, null, true);
            $f->setDescriptionFormat("Contract: %s");
            $view->addFilter($f);

//			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE events_template.course_id=',id) FROM courses order by courses.title";
            $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE events_template.course_id=',id) FROM courses order by courses.title";
            $f = new DropDownViewFilter('filter_course', $options, null, true);
            $f->setDescriptionFormat("Course: %s");
            $view->addFilter($f);


            // Provider Filter
            if($_SESSION['user']->type == User::TYPE_MANAGER)
                $options = 'SELECT id, legal_name, null, CONCAT("WHERE providers.id=",id) FROM organisations WHERE organisation_type LIKE "%3%" AND organisations.id = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE providers.id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
            $f = new DropDownViewFilter('filter_provider', $options, null, true);
            $f->setDescriptionFormat("Training Provider: %s");
            $view->addFilter($f);

            // Employer Filter
            if($_SESSION['user']->type == User::TYPE_MANAGER)
                $options = 'SELECT id, legal_name, null, CONCAT("WHERE employers.id=",id) FROM organisations WHERE organisation_type LIKE "%2%" AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
            else
                $options = "SELECT id, legal_name, null, CONCAT('WHERE  employers.id=',id) FROM organisations WHERE organisation_type like '%2%' order by legal_name";
            $f = new DropDownViewFilter('filter_employer', $options, null, true);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

            // ULN Filter
            $f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
            $f->setDescriptionFormat("Learner ULN: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
            $f->setDescriptionFormat("L03: %s");
            $view->addFilter($f);


        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        /* @var $result pdo_result */
        $st = $link->query($this->getSQL());
        if($st)
        {
            //pre($this->getSQL());
            echo $this->getViewNavigator();
            echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo <<<HEREDOC
	<thead>
		<tr>
			<th>Learner Name</th>
			<th>LearnRefNumber</th>
			<th>ULN</th>
            <th>OTJ date</th>
			<th>OTJ description</th>
			<th>Start Time</th>
			<th>End Time</th>
			<th>Duration</th>
			<th>Comments</th>
		</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo '<tr style="font-size:8pt">';
                echo '<td align="left"><a href=do.php?_action=read_training_record&amp;id='. $row['tr_id'] . ' ><span style="color: black"> ' . HTML::cell($row['learner_name']) . '</span></a></td>';
                echo '<td align="left">' . HTML::cell($row['LearnRefNumber']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['uln']) . '</td>';
                echo '<td align="left">' . HTML::cell(Date::toMedium($row['date'])) . '</td>';
                echo '<td align="left">' . HTML::cell($row['description']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['time_from']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['time_to']) . '</td>';
                echo '<td align="left">' . HTML::cell($row['duration']. ' hours') . '</td>';
                echo '<td align="left">' . HTML::cell($row['comments']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div align="center">';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}
?>