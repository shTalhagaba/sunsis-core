<?php
class ViewReportFirstLearningActivity extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

        if(!isset($_SESSION[$key]))
        {
            // Create new view object
            $sql = <<<SQL
SELECT DISTINCT 
    ob_learners.id,
    ob_learners.uln AS unique_learner_number,
    ob_learners.ebs_id,
    ob_learners.firstnames,
    ob_learners.surname,
    DATE_FORMAT(ob_learners.dob, '%d/%m/%Y') AS date_of_birth,
    DATE_FORMAT(tr.personality_test_saved_at, '%d/%m/%Y %H:%i:%s') AS first_learning_activity_saved_at,
    tr.personality_test AS first_learning_activity,
    tr.id AS tr_id
FROM
	ob_learners
	LEFT JOIN tr ON ob_learners.id = tr.ob_learner_id
WHERE
    tr.personality_test IS NOT NULL 
 ;
SQL;

            $view = $_SESSION[$key] = new ViewReportFirstLearningActivity();
            $view->setSQL($sql);

            // Add view filters
            $f = new TextboxViewFilter('filter_firstnames', "WHERE ob_learners.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE ob_learners.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname (starts with): %s");
            $view->addFilter($f);

            if($_SESSION['user']->type == User::TYPE_MANAGER) // program manager
            {
                $options = DAO::getResultset($link, "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=', id) FROM organisations WHERE organisations.id = '{$_SESSION['user']->employer_id}' ORDER BY legal_name");
                $f = new DropDownViewFilter('filter_provider', $options, $_SESSION['user']->employer_id, false);
            }
            elseif($_SESSION['user']->type == User::TYPE_ASSESSOR) // program manager
            {
                $options = DAO::getResultset($link, "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=', id) FROM organisations WHERE organisations.id = '{$_SESSION['user']->employer_id}' ORDER BY legal_name");
                $f = new DropDownViewFilter('filter_provider', $options, $_SESSION['user']->employer_id, false);
            }
            else
            {
                $options = DAO::getResultset($link, "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=', id) FROM organisations WHERE organisation_type = '" . Organisation::TYPE_TRAINING_PROVIDER . "' ORDER BY legal_name");
                $f = new DropDownViewFilter('filter_provider', $options, null, true);
            }
            $f->setDescriptionFormat("Provider: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
            $f->setDescriptionFormat("TR IDs: %s");
            $view->addFilter($f);

            $trainer_type = User::TYPE_ASSESSOR;
            $sql = <<<SQL
SELECT 
    users.id, CONCAT(firstnames, ' ', surname), organisations.legal_name, CONCAT("WHERE FIND_IN_SET(", users.id, ","," tr.trainers)")
FROM
    users INNER JOIN organisations ON users.employer_id = organisations.id
WHERE
    users.type = '{$trainer_type}';
SQL;
            $options = DAO::getResultset($link, $sql);
            $f = new DropDownViewFilter('filter_trainer', $options, '', true);
            $f->setDescriptionFormat("Trainer: %s");
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
                0=>array(0, 'Learner Firstname', null, 'ORDER BY ob_learners.`firstnames` ASC')
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
		<th>Unique Learner Number</th>
		<th>Ebs Id</th>
		<th>Firstnames</th>
		<th>Surname</th>
		<th>Date of Birth</th>
		<th>First Learning Activity Saved At</th>
		<th>First Learning Activity</th>
	</tr>
	</thead>
HEREDOC;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo !is_null($row['tr_id']) ?
                    HTML::viewrow_opening_tag("do.php?_action=read_training&id={$row['tr_id']}") :
                    HTML::viewrow_opening_tag("do.php?_action=edit_ob_learner_without_tr&id={$row['id']}");
                echo '<td>' . HTML::cell($row['unique_learner_number']) . '</td>';
                echo '<td>' . HTML::cell($row['ebs_id']) . '</td>';
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';
                echo '<td>' . Date::toShort($row['date_of_birth']) . '</td>';
                echo '<td>' . HTML::cell($row['first_learning_activity_saved_at']) . '</td>';
                echo '<td>' . HTML::cell($row['first_learning_activity']) . '</td>';
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


}
?>