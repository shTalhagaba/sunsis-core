<?php
class HomePageLearnersList extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            // Create new view object
            $sql = <<<SQL
SELECT
    ob_learners.id,
	organisations.legal_name AS employer,
    ob_learners.firstnames,
    ob_learners.surname,
    DATE_FORMAT(ob_learners.dob, '%d/%m/%Y') AS date_of_birth,
    ob_learners.home_postcode AS postcode,
    ob_learners.home_email AS email,
    frameworks.title AS standard,
    tr.status_code,
    DATE_FORMAT(tr.practical_period_start_date, '%d/%m/%Y') AS practical_period_start_date,
    DATE_FORMAT(tr.practical_period_end_date, '%d/%m/%Y') AS practical_period_end_date,
    IF(employer_agreement_schedules.`tp_sign` IS NOT NULL, 'Yes', 'No') AS schedule1_signed_by_provider,
    IF(employer_agreement_schedules.`emp_sign` IS NOT NULL, 'Yes', 'No') AS schedule1_signed_by_employer,
    IF(ob_learner_skills_analysis.`signed_by_learner` = 1, 'Yes', 'No') AS assessment_signed_by_learner,
    IF(ob_learner_skills_analysis.`signed_by_provider` = 1, 'Yes', 'No') AS assessment_signed_by_provider,
    IF(ob_learner_skills_analysis.`is_eligible_after_ss` = 'Y', 'Yes', 'No') AS is_eligible_after_ss,
    IF(tr.learner_sign IS NOT NULL, 'Yes', 'No') AS ob_form_signed_by_learner,
    IF(tr.emp_sign IS NOT NULL, 'Yes', 'No') AS ob_form_signed_by_employer,
    IF(tr.tp_sign IS NOT NULL, 'Yes', 'No') AS ob_form_signed_by_provider,
    IF(tr.personality_test IS NOT NULL, 'Yes', 'No') AS first_learning_activity_done,
    tr.id AS tr_id,
    tr.provider_id
FROM
	ob_learners
	LEFT JOIN tr ON ob_learners.id = tr.ob_learner_id 
	LEFT JOIN ob_learner_skills_analysis ON tr.`id` = ob_learner_skills_analysis.`tr_id`
	LEFT JOIN organisations ON ob_learners.`employer_id` = organisations.`id`
	LEFT JOIN frameworks ON tr.`framework_id` = frameworks.`id`
	LEFT JOIN employer_agreement_schedules ON (employer_agreement_schedules.tr_id = tr.`id` AND employer_agreement_schedules.`employer_id` = tr.`employer_id`)
 ;
SQL;

            $view = $_SESSION[$key] = new HomePageLearnersList();
            $view->setSQL($sql);

            $f = new TextboxViewFilter('filter_firstnames', "WHERE ob_learners.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE ob_learners.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
            $f->setDescriptionFormat("TR IDs: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(20,20,null,null),
                1=>array(50,50,null,null),
                2=>array(100,100,null,null),
                3=>array(200,200,null,null),
                4=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(0, 'Learner Firstname', null, 'ORDER BY ob_learners.`firstnames` ASC'),
                1=>array(1, 'Employer, Learner Firstname', null, 'ORDER BY organisations.`legal_name`, ob_learners.`firstnames`')
            );
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 0, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div class="center"><table class="table table-bordered">';
            echo <<<HEREDOC
	<thead>
	<tr>
		<th class="bottomRow">Firstnames</th>
		<th class="bottomRow">Surname</th>
		<th class="bottomRow">Date of Birth</th>
		<th class="bottomRow">Postcode</th>
		<th class="bottomRow">Email</th>
		<th class="bottomRow">Standard</th>
		<th class="bottomRow">Prac. Period Start</th>
		<th class="bottomRow">Prac. Period End</th>
		<th class="bottomRow">Employer</th>
		<th class="bottomRow">Sch. Signed by Provider</th>
		<th class="bottomRow">Sch. Signed by Employer</th>
		<th class="bottomRow">Assessment Signed by Learner</th>
		<th class="bottomRow">Assessment Signed by Provider</th>
		<th class="bottomRow" title="learner's eligibility after skills assessment">Learner Eligible after SA</th>
		<th class="bottomRow">Onboarding Signed by Learner</th>
		<th class="bottomRow">Onboarding Signed by Employer</th>
		<th class="bottomRow">Onboarding Signed by Provider</th>
		<th class="bottomRow">1st Learn. Activity Done</th>
	</tr>
	</thead>
HEREDOC;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("do.php?_action=read_training&id={$row['tr_id']}");
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';
                echo '<td>' . Date::toShort($row['date_of_birth']) . '</td>';
                echo '<td>' . HTML::cell($row['postcode']) . '</td>';
                echo '<td>' . HTML::cell($row['email']) . '</td>';
                echo '<td>' . HTML::cell($row['standard']) . '</td>';
                echo '<td>' . HTML::cell($row['practical_period_start_date']) . '</td>';
                echo '<td>' . HTML::cell($row['practical_period_end_date']) . '</td>';
                echo '<td>' . HTML::cell($row['employer']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['schedule1_signed_by_provider']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['schedule1_signed_by_employer']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['assessment_signed_by_learner']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['assessment_signed_by_provider']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['is_eligible_after_ss']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['ob_form_signed_by_learner']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['ob_form_signed_by_employer']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['ob_form_signed_by_provider']) . '</td>';
                echo '<td align="center">' . HTML::cell($row['first_learning_activity_done']) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }

    public function renderForCaseloading(PDO $link)
    {
        $st = $link->query($this->getSQL());
        if($st)
        {
            echo $this->getViewNavigator();
            echo '<div class="table-responsive resultset"><table id="tblObLearners" class="table table-bordered">';
            echo <<<HEREDOC
	<thead>
	<tr>
		<th class="bottomRow">Employer</th>
		<th class="bottomRow">Provider</th>
		<th class="bottomRow">Firstnames</th>
		<th class="bottomRow">Surname</th>
		<th class="bottomRow">Postcode</th>
		<th class="bottomRow">Email</th>
		<th class="bottomRow">Standard</th>
		<th class="bottomRow" style="width: 25%;">Select Trainer</th>
	</tr>
	</thead>
HEREDOC;
            echo '<tbody>';
            if(!$_SESSION['user']->isAdmin())
                $ddlTrainers = DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.employer_id = '{$_SESSION['user']->employer_id}' AND users.type = 3 ORDER BY firstnames");
            while($row = $st->fetch())
            {
                if($_SESSION['user']->isAdmin())
                    $ddlTrainers = DAO::getResultset($link, "SELECT users.id, CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE users.employer_id = '{$row['provider_id']}' AND users.type = 3 ORDER BY firstnames");

                echo '<tr id="ob_learner_id_' . $row['tr_id'].'">';
                echo '<td>' . HTML::cell($row['employer']) . '</td>';
                echo '<td>' . DAO::getSingleValue($link, "SELECT legal_name FROM organisations WHERE id = '{$row['provider_id']}'") . '</td>';
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';
                echo '<td>' . HTML::cell($row['postcode']) . '</td>';
                echo '<td>' . HTML::cell($row['email']) . '</td>';
                echo '<td>' . HTML::cell($row['standard']) . '</td>';
                echo '<td>';
                echo HTML::selectChosen('trainers_for_' . $row['tr_id'], $ddlTrainers, [], false, false, true, 2);
                echo '<br>';
                echo '<span class="btn btn-xs btn-success btnAssignTrainers" title="Save trainers for ' . $row['firstnames'] . ' ' . $row['surname'] . '"><i class="fa fa-save"></i></span>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</tbody></table><p><br></p></div>';
            echo $this->getViewNavigator();

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }

}
?>