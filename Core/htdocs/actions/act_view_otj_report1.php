<?php
class view_otj_report1 implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        $view = VoltView::getViewFromSession('ViewOtjReport', 'ViewOtjReport'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewOtjReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        if($subaction == 'export_csv')
        {
            $this->export_csv($link, $view);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_otj_report1", "View OTJ Report");

        include_once('tpl_view_otj_report1.php');
    }

    private function buildView(PDO $link)
    {

if(DB_NAME=='am_ela')
{
    $sql = new SQLStatement("
SELECT
    tr.id AS tr_id,
    tr.uln as uln,
    tr.firstnames,
    tr.surname,
    employers.legal_name AS employer,
    tr.start_date,
    tr.target_date,
    tr.closure_date,
    contracts.title AS contract,
    CONCAT(assessors.firstnames, ' ', assessors.surname) AS assessor,
    student_frameworks.`title` AS framework,
    planned_otj AS otj_hours_due,
	actual_hours AS otj_hours_actual,
    if(planned_otj >= actual_hours, 'On Track', 'Behind') AS otj_progress
FROM
    users LEFT JOIN tr ON users.username = tr.username
    LEFT JOIN onefile_otj ON onefile_otj.onefile_learner_id = tr.onefile_id
    LEFT JOIN student_frameworks ON tr.id = student_frameworks.tr_id
    LEFT JOIN frameworks ON student_frameworks.id = frameworks.id
    LEFT JOIN contracts ON tr.contract_id = contracts.id
    LEFT JOIN organisations employers ON tr.employer_id = employers.id
    LEFT JOIN users assessors ON tr.assessor = assessors.id
WHERE
    tr.id IS NOT NULL
ORDER BY
    tr.firstnames
		");
}
else
{
    $sql = new SQLStatement("
SELECT
    tr.id AS tr_id,
    tr.uln as uln,
    tr.firstnames,
    tr.surname,
    employers.legal_name AS employer,
    tr.start_date,
    tr.target_date,
    tr.closure_date,
    contracts.title AS contract,
    CONCAT(assessors.firstnames, ' ', assessors.surname) AS assessor,
    student_frameworks.`title` AS framework,
    IF(tr.otj_hours IS NULL, frameworks.`otj_hours`, tr.otj_hours) AS otj_hours_due,
	(SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = tr.id) AS otj_hours_actual,
	IF
	(
		tr.`otj_hours` = 0, '',
		IF
		(
			(SELECT SUM(duration_hours)*60 + SUM(duration_minutes) FROM otj WHERE tr_id = tr.id) >=
			( tr.`otj_hours`/(timestampdiff(MONTH, tr.`start_date`, tr.`target_date`)) * timestampdiff(MONTH, tr.start_date, CURDATE()))
			,
			'On Track','Behind'
		)
	) AS otj_progress
FROM
    users LEFT JOIN tr ON users.username = tr.username
    LEFT JOIN student_frameworks ON tr.id = student_frameworks.tr_id
    LEFT JOIN frameworks ON student_frameworks.id = frameworks.id
    LEFT JOIN contracts ON tr.contract_id = contracts.id
    LEFT JOIN organisations employers ON tr.employer_id = employers.id
    LEFT JOIN users assessors ON tr.assessor = assessors.id
WHERE
    tr.id IS NOT NULL
ORDER BY
    tr.firstnames
		");

}
        if($_SESSION['user']->isAdmin() || in_array($_SESSION['user']->type, [12, 15, 7]))
        {
            // do nothing
        }
        elseif($_SESSION['user']->isOrgAdmin() || in_array($_SESSION['user']->type, [8, 13, 14]))
        {
            $emp = $_SESSION['user']->employer_id;
            $username = $_SESSION['user']->username;
            $sql->setClause("WHERE (tr.provider_id= '{$_SESSION['user']->employer_id}' OR tr.employer_id='{$_SESSION['user']->employer_id}' OR users.who_created = '{$_SESSION['user']->username}' OR users.who_created IN (SELECT username FROM users WHERE type = 8 AND employer_id = '{$_SESSION['user']->employer_id}'))");
        }
        elseif($_SESSION['user']->type == 2)
        {
            $sql->setClause("WHERE tr.tutor = '{$_SESSION['user']->id}' ");
        }
        elseif($_SESSION['user']->type == 3)
        {
            $sql->setClause("WHERE tr.assessor = '{$_SESSION['user']->id}' ");
        }
        elseif($_SESSION['user']->type == 4)
        {
            $sql->setClause("WHERE tr.verifier = '{$_SESSION['user']->id}' ");
        }
        else
        {
            $sql->setClause("WHERE tr.employer_id = '{$_SESSION['user']->employer_id}' ");
        }


        $view = new VoltView('ViewOtjReport', $sql->__toString());

        $f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%%%s%%'", null);
        $f->setDescriptionFormat("Firstnames: %s");
        $view->addFilter($f);

        $f = new VoltTextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%%%s%%'", null);
        $f->setDescriptionFormat("Surname: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT contracts.id, contracts.title, null, CONCAT('WHERE tr.contract_id=',contracts.id) FROM contracts ORDER BY contracts.title";
        $f = new VoltDropDownViewFilter('filter_contract', $options, '', true);
        $f->setDescriptionFormat("Contract: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT users.id, CONCAT(firstnames, ' ', surname), null, CONCAT('WHERE tr.assessor=',users.id) FROM users WHERE users.type = 3 ORDER BY firstnames";
        $f = new VoltDropDownViewFilter('filter_assessor', $options, '', true);
        $f->setDescriptionFormat("Assessor: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT id, title, null, CONCAT('WHERE student_frameworks.id=',id) FROM student_frameworks ORDER BY title";
        $f = new VoltDropDownViewFilter('filter_framework', $options, '', true);
        $f->setDescriptionFormat("Framework: %s");
        $view->addFilter($f);

        $options = "SELECT DISTINCT organisations.id, organisations.legal_name, null, CONCAT('WHERE tr.employer_id=',organisations.id) FROM organisations WHERE organisations.organisation_type = 2 ORDER BY organisations.legal_name";
        $f = new VoltDropDownViewFilter('filter_employer', $options, '', true);
        $f->setDescriptionFormat("Contract: %s");
        $view->addFilter($f);

        $options = array(
            0 => array('1', 'Continuing', null, 'WHERE tr.status_code = "1"'),
            1 => array('2', 'Completed', null, 'WHERE tr.status_code = "2"'),
            2 => array('3', 'Withdrawn', null, 'WHERE tr.status_code = "3"'),
            3 => array('4', 'Temp. Withdrawn', null, 'WHERE tr.status_code = "6"')
        );
        $f = new VoltDropDownViewFilter('filter_status_code', $options, 1, true);
        $f->setDescriptionFormat("Status: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'On Track', null, 'HAVING otj_progress = "On Track"'),
            2=>array(2, 'Behind', null, 'HAVING otj_progress = "Behind"'));
        $f = new VoltDropDownViewFilter('filter_otj_progress', $options, 0, false);
        $f->setDescriptionFormat("OTJ Progress: %s");
        $view->addFilter($f);

        $options = array(
            0=>array(20,20,null,null),
            1=>array(50,50,null,null),
            2=>array(100,100,null,null),
            3=>array(200,200,null,null),
            4=>array(300,300,null,null),
            5=>array(400,400,null,null),
            6=>array(500,500,null,null),
            7=>array(0, 'No limit', null, null));
        $f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
        $f->setDescriptionFormat("Records per page: %s");
        $view->addFilter($f);

        return $view;
    }

    private function renderView(PDO $link, VoltView $view)
    {
        //pr($view->getSQLStatement()->__toString());
        $st = $link->query($view->getSQLStatement()->__toString());
        if($st)
        {
            echo $view->getViewNavigatorExtra('', $view->getViewName());
            echo '<div align="center" ><table id="tblOtjReport" class="table table-bordered table-condensed">';
            echo '<thead class="bg-gray"><tr>';
            if(DB_NAME=='am_lead')
                echo '<th>Firstnames</th><th>Surname</th><th>ULN</th><th>Employer</th><th>Coach</th><th>Framework</th><th>Contract</th><th>Start Date</th><th>Planned End Date</th><th>Completed Date</th>';
            else
                echo '<th>Firstnames</th><th>Surname</th><th>ULN</th><th>Employer</th><th>Assessor</th><th>Framework</th><th>Contract</th><th>Start Date</th><th>Planned End Date</th><th>Completed Date</th>';
            echo '<th>Otj Hours Due</th><th>Otj Hours Actual</th><th>Otj Progress</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id='.$row['tr_id']);
                echo '<td>'.$row['firstnames'].'</td>';
                echo '<td>'.$row['surname'].'</td>';
                echo '<td>'.$row['uln'].'</td>';
                echo '<td>'.$row['employer'].'</td>';
                echo '<td>'.$row['assessor'].'</td>';
                echo '<td>'.$row['framework'].'</td>';
                echo '<td>'.$row['contract'].'</td>';
                echo '<td>'.Date::toShort($row['start_date']).'</td>';
                echo '<td>'.Date::toShort($row['target_date']).'</td>';
                echo '<td>'.Date::toShort($row['closure_date']).'</td>';
                $otj_minutes_due = $row['otj_hours_due'] == '' ? 0 : $row['otj_hours_due']*60;
                echo '<td>' . str_replace(' ', '&nbsp;', ViewTrainingRecords::convertToHoursMins($otj_minutes_due, '%02d hours %02d minutes')) . '</td>';
                $otj_minutes_actual = $row['otj_hours_actual'] == '' ? 0 : $row['otj_hours_actual'];
                if(in_array(DB_NAME, ["am_city_skills", "am_demo"]))
                    $otj_minutes_actual = $row['otj_hours_actual'] + ViewTrainingRecords::calculateAttendanceMinutes($link, $row['tr_id']);
                echo '<td>' . str_replace(' ', '&nbsp;', ViewTrainingRecords::convertToHoursMins($otj_minutes_actual, '%02d hours %02d minutes')) . '</td>';
                echo $row['otj_progress'] == 'On Track' ? '<td class="text-green">'.$row['otj_progress'].'</td>' : '<td class="text-red">'.$row['otj_progress'].'</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div><p><br></p>';
            echo $view->getViewNavigatorExtra('', $view->getViewName());
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }

    private function export_csv(PDO $link, VoltView $view)
    {
        $statement = $view->getSQLStatement();
        $statement->removeClause('limit');
        $st = $link->query($statement->__toString());
        if($st)
        {

            header("Content-Type: application/vnd.ms-excel");
            header('Content-Disposition: attachment; filename=Otj Report.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }
            echo "Firstnames,Surname,ULN,Assessor/ Coach, Employer,Contract,Start Date,Planned End Date,Completed Date,Otj Hours Due,Otj Hours Actual,Otj Progress";
            echo "\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::csvSafe($row['firstnames']) . ",";
                echo HTML::csvSafe($row['surname']) . ",";
                echo HTML::csvSafe($row['uln']) . ",";
                echo HTML::csvSafe($row['assessor']) . ",";
                echo HTML::csvSafe($row['employer']) . ",";
                echo HTML::csvSafe($row['contract']) . ",";
                echo Date::toShort($row['start_date']) . ",";
                echo Date::toShort($row['target_date']) . ",";
                echo Date::toShort($row['closure_date']) . ",";
                $otj_minutes_due = $row['otj_hours_due'] == '' ? 0 : $row['otj_hours_due']*60;
                echo ViewTrainingRecords::convertToHoursMins($otj_minutes_due, '%02d hours %02d minutes') . ',';
                $otj_minutes_actual = $row['otj_hours_actual'] == '' ? 0 : $row['otj_hours_actual'];
                if(in_array(DB_NAME, ["am_city_skills", "am_demo"]))
                    $otj_minutes_actual = $row['otj_hours_actual'] + ViewTrainingRecords::calculateAttendanceMinutes($link, $row['tr_id']);
                echo ViewTrainingRecords::convertToHoursMins($otj_minutes_actual, '%02d hours %02d minutes') . ',';
                echo HTML::csvSafe($row['otj_progress']) . ",";
                echo "\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }
}