<?php
class ViewObLearners extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_'.__CLASS__;

	$where = "";
        if(DB_NAME == "am_ela")
        {
            if($_SESSION['user']->learners_caseload == 0)
            {
                $where = "";
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_FRONTLINE)
            {
                $where = "WHERE ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_FRONTLINE . "'";
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_LINKS_TRAINING)
            {
                $where = "WHERE ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_LINKS_TRAINING . "'";
            }
	    elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_NEW_ACCESS)
            {
                $where = "WHERE ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_NEW_ACCESS . "'";
            }
            elseif($_SESSION['user']->learners_caseload == OnboardingLearner::CASELOAD_INTERNAL_ELA)
            {
                $where = "WHERE ob_learners.`caseload_org_id` = '" . OnboardingLearner::CASELOAD_INTERNAL_ELA . "'";
            }
        }

        if(!isset($_SESSION[$key]))
        {
            // Create new view object
            $sql = <<<SQL
SELECT DISTINCT 
  `id` AS system_id,
  `firstnames`,
  `surname`,
  CASE `gender`
    WHEN 'F' THEN 'Female'
    WHEN 'M' THEN 'Male'
    WHEN 'U' THEN 'Unknown'
    WHEN 'W' THEN 'Witheld'
  END AS gender,
  DATE_FORMAT(`dob`, '%d/%m/%Y') AS dob,
  ob_learners.uln,
  `home_address_line_1`,
  `home_postcode`,
  `home_mobile` AS mobile,
  `home_email` AS email,
  `home_telephone` AS telephone,
  `work_email`,
  (SELECT frameworks.title FROM frameworks INNER JOIN ob_tr ON frameworks.id = ob_tr.framework_id WHERE ob_tr.ob_learner_id = ob_learners.id ORDER BY ob_tr.id DESC LIMIT 1 ) AS recently_enrolled_to,
  (SELECT CONCAT(users.`firstnames`, ' ', users.`surname`) FROM users WHERE users.id = ob_learners.`created_by`) AS `created_by`,
  DATE_FORMAT(`created`, '%d/%m/%Y %H:%i:%s') AS created_at
FROM
	ob_learners 
$where    
;
SQL;

            $view = $_SESSION[$key] = new ViewObLearners();
            $view->setSQL($sql);

            $f = new TextboxViewFilter('filter_firstnames', "WHERE ob_learners.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE ob_learners.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname (starts with): %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Exclude Archived Learners', null, 'WHERE ob_learners.archive = "N"'),
                1=>array(2, 'Include Archived Learners', null, 'WHERE true'),
                2=>array(3, 'Show Only Archived Learners', null, 'WHERE ob_learners.archive = "Y"'),
            );
            $f = new DropDownViewFilter('filter_archive', $options, 1, false);
            $f->setDescriptionFormat("Archive: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Female', null, 'WHERE ob_learners.gender = "F"'),
                1=>array(2, 'Male', null, 'WHERE ob_learners.gender = "M"'),
                2=>array(3, 'Unknown', null, 'WHERE ob_learners.gender = "U"'),
                3=>array(4, 'Withold', null, 'WHERE ob_learners.gender = "W"'),
            );
            $f = new DropDownViewFilter('filter_gender', $options, null, true);
            $f->setDescriptionFormat("Gender: %s");
            $view->addFilter($f);

            $options = DAO::getResultset($link, "SELECT DISTINCT organisations.id, organisations.legal_name, null, CONCAT('WHERE ob_learners.employer_id=', organisations.id) FROM organisations ORDER BY organisations.legal_name");
            $f = new DropDownViewFilter('filter_employer', $options);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

	    $options = DAO::getResultset($link, "
                SELECT 
                    users.id, CONCAT(users.`firstnames`, ' ', users.`surname`), null, CONCAT('WHERE ob_learners.created_by=', users.id) 
                FROM 
                    users 
                WHERE 
                    users.id IN (SELECT DISTINCT created_by FROM ob_learners) 
                ORDER BY 
                    users.`firstnames`;");
            $f = new DropDownViewFilter('filter_created_by', $options);
            $f->setDescriptionFormat("Created By: %s");
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
                1=>array(1, 'Learner Surname', null, 'ORDER BY ob_learners.`surname` ASC'),
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
            $columns = array();
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                $columns[] = $column['name'];
            }
            echo '<thead class="bg-green-gradient"><tr>';
            foreach($columns AS $column)
            {
                echo '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
            }
            echo '</tr></thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("do.php?_action=view_ob_learner&id={$row['system_id']}");
                foreach($columns as $col)
                {
                    echo '<td>' . ((isset($row[$col]))?(($row[$col]=='')?'&nbsp':$row[$col]):'&nbsp') . '</td>';
                }
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