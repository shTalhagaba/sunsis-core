<?php
class view_fs_report implements IAction
{
    public function execute(PDO $link)
    {
        $subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

        $view = VoltView::getViewFromSession('ViewFSReport', 'ViewFSReport'); /* @var $view VoltView */
        if(is_null($view))
        {
            $view = $_SESSION['ViewFSReport'] = $this->buildView($link);
        }
        $view->refresh($_REQUEST, $link);

        if($subaction == 'export_csv')
        {
            $this->export_csv($link, $view);
            exit;
        }

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_fs_report", "View FS Report");

        include_once('tpl_view_fs_report.php');
    }

    private function buildView(PDO $link)
    {

    $sql = new SQLStatement("
    SELECT 
    tr.id as tr_id
    ,CONCAT(users.firstnames, ' ', users.surname) AS learner_name 
    ,contracts.title AS contract
    ,(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.assessor) AS assessor
    ,(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.tutor) AS functional_skills_tutor
    ,(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.verifier) AS iqa
    ,'' AS team_leader_name
    ,tr.target_date AS apprenticeship_planned_end_date
    ,student_qualifications.start_date AS aim_start_date
    ,student_qualifications.end_date AS aim_planned_end_date
    ,'In-learning' AS learner_status
    ,'' AS target_progress
    ,'' AS actual_progress
    ,student_qualifications.title as functional_skill_title
    ,IF(tr.target_date > CURDATE(), 'Funded','Unfunded') AS learner_funding_status
    ,IF(student_qualifications.title REGEXP 'English', literacy_other, numeracy_other) AS initial_assessment_score
    ,student_qualifications.level
    ,IF(qual_exempt = 1, 'Exempt',IF(actual_end_date IS NULL, 'In-learning', 'Achieved')) AS aim_status
    FROM 
    student_qualifications 
    LEFT JOIN tr ON tr.id = student_qualifications.tr_id
    LEFT JOIN users ON users.username = tr.username
    LEFT JOIN contracts ON contracts.id = tr.contract_id
    WHERE 
    (student_qualifications.title LIKE '%English%' OR student_qualifications.title LIKE '%Maths%')
    AND tr.status_code = 1;
    ");

        $view = new VoltView('ViewFSReport', $sql->__toString());

        $options = array(
            0=>array(0, 'Show all', null, null),
            1=>array(1, 'Yes', null, 'where fs_opt_in = "Yes"'),
            2=>array(2, 'No', null, 'where fs_opt_in = "No"'));
        $f = new VoltDropDownViewFilter('filter_fs_opt_in', $options, 0, false);
        $f->setDescriptionFormat("FS Opt-In Status: %s");
        $view->addFilter($f);

        /*$f = new VoltTextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%%%s%%'", null);
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
        $view->addFilter($f);*/

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
            echo '<th>Learner name</th><th>Contract</th><th>Assessor</th><th>Functional Skills Tutor</th><th>IQA</th><th>Team Leader Name</th><th>Apprenticeship Planned End Date</th><th>Aim Start Date</th><th>Aim Planned End Date</th><th>Learner Status</th>';
            echo '<th>Target Progress</th><th>Actual Progress</th><th>Functional Skill Title</th><th>Learner Funding Status</th><th>Initial Assessment Score</th><th>Level</th><th>Aim Status</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id='.$row['tr_id']);
                echo '<td>'.$row['learner_name'].'</td>';
                echo '<td>'.$row['contract'].'</td>';
                echo '<td>'.$row['assessor'].'</td>';
                echo '<td>'.$row['functional_skills_tutor'].'</td>';
                echo '<td>'.$row['iqa'].'</td>';
                echo '<td>'.$row['team_leader_name'].'</td>';
                echo '<td>'.Date::toShort($row['apprenticeship_planned_end_date']).'</td>';
                echo '<td>'.Date::toShort($row['aim_start_date']).'</td>';
                echo '<td>'.Date::toShort($row['aim_planned_end_date']).'</td>';
                echo '<td>'.$row['learner_status'].'</td>';
                echo '<td>'.$row['target_progress'].'</td>';
                echo '<td>'.$row['actual_progress'].'</td>';
                echo '<td>'.$row['functional_skill_title'].'</td>';
                echo '<td>'.$row['learner_funding_status'].'</td>';
                echo '<td>'.$row['initial_assessment_score'].'</td>';
                echo '<td>'.$row['level'].'</td>';
                echo '<td>'.$row['aim_status'].'</td>';
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
            header('Content-Disposition: attachment; filename=Functional Skills Report.csv');
            if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
            {
                header('Pragma: public');
                header('Cache-Control: max-age=0');
            }

            echo "Learner name,Contract,Assessor,Functional Skills Tutor,IQA,Team Leader Name,Apprenticeship Planned End Date,Aim Start Date,Aim Planned End Date,Learner Status,Target Progress,Actual Progress,Functional Skill Title,Learner Funding Status,Initial Assessment Score,Level,AimStatus";
            echo "\n";
            while($row = $st->fetch(DAO::FETCH_ASSOC))
            {
                echo HTML::csvSafe($row['learner_name']) . ",";
                echo HTML::csvSafe($row['contract']) . ",";
                echo HTML::csvSafe($row['assessor']) . ",";
                echo HTML::csvSafe($row['functional_skills_tutor']) . ",";
                echo HTML::csvSafe($row['iqa']) . ",";
                echo HTML::csvSafe($row['team_leader_name']) . ",";
                echo Date::toShort($row['apprenticeship_planned_end_date']) . ",";
                echo Date::toShort($row['aim_start_date']) . ",";
                echo Date::toShort($row['aim_planned_end_date']) . ",";
                echo HTML::csvSafe($row['learner_status']) . ",";
                echo HTML::csvSafe($row['target_progress']) . ",";
                echo HTML::csvSafe($row['actual_progress']) . ",";
                echo HTML::csvSafe($row['functional_skill_title']) . ",";
                echo HTML::csvSafe($row['learner_funding_status']) . ",";
                echo HTML::csvSafe($row['initial_assessment_score']) . ",";
                echo HTML::csvSafe($row['level']) . ",";
                echo HTML::csvSafe($row['aim_status']) . ",";
                echo "\n";
            }
        }
        else
        {
            throw new DatabaseException($link, $view->getSQLStatement()->__toString());
        }
    }
}