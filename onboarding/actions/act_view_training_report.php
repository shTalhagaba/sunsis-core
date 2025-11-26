<?php
class view_training_report implements IAction
{
    public function execute(PDO $link)
    {

        $_SESSION['bc']->index = 0;
        $_SESSION['bc']->add($link, "do.php?_action=view_training_report", "View Training Report");

        $view = $this->buildView($link); /* @var $view View */
        $view->refresh($link, $_REQUEST);

        require_once('tpl_view_training_report.php');
    }

    public function buildView(PDO $link)
    {
        $key = __CLASS__;

        if(!isset($_SESSION[$key]))
        {
            $sql = new SQLStatement("
SELECT DISTINCT
  ob_learners.id AS ob_learner_id,
  tr.`id` AS tr_id,
  ob_learners.`firstnames`,
  ob_learners.`surname`,
  frameworks.`title` AS standard,
  frameworks.programme_code AS standard_code,
  frameworks.`duration_in_months` AS standard_duration,
  DATE_FORMAT(
    tr.`practical_period_start_date`,
    '%d/%m/%Y'
  ) AS practical_period_start_date,
  DATE_FORMAT(
    tr.`practical_period_end_date`,
    '%d/%m/%Y'
  ) AS practical_period_end_date,
  tr.`duration_practical_period`,
  DATE_FORMAT(
    tr.`apprenticeship_start_date`,
    '%d/%m/%Y'
  ) AS apprenticeship_start_date,
  DATE_FORMAT(
    tr.`apprenticeship_end_date_inc_epa`,
    '%d/%m/%Y'
  ) AS apprenticeship_end_date_inc_epa,
  tr.`apprenticeship_duration_inc_epa`,
  ob_learner_skills_analysis.`delivery_plan_hours_ba` AS delivery_hours_before_assessment,
  ob_learner_skills_analysis.`delivery_plan_hours_fa` AS delivery_hours_after_assessment,
  ob_learner_skills_analysis.`max_duration_fa` AS duration_after_assessment,
  ob_learner_skills_analysis.`percentage_fa` AS assessment_percentage,
  ob_learner_skills_analysis.`funding_band_maximum`,
  ob_learner_skills_analysis.`recommended_duration`,
  ob_learner_skills_analysis.`contracted_hours_per_week`,
  ob_learner_skills_analysis.`weeks_to_be_worked_per_year`,
  ob_learner_skills_analysis.`total_contracted_hours_per_year`,
  ob_learner_skills_analysis.`total_contracted_hours_full_apprenticeship`,
  ob_learner_skills_analysis.`total_training_price`,
  ob_learner_skills_analysis.`total_nego_price_fa` AS negotiated_price_after_assessment,
  ob_learner_skills_analysis.`epa_price`,
  ob_learner_skills_analysis.`rationale_by_provider`,
DATE_FORMAT(tr.`learner_sign_date`, '%d/%m/%Y') AS learner_sign_date,
DATE_FORMAT(tr.`emp_sign_date`, '%d/%m/%Y') AS employer_sign_date,
DATE_FORMAT(tr.`tp_sign_date`, '%d/%m/%Y') AS provider_sign_date
FROM
  ob_learners
  INNER JOIN tr
    ON ob_learners.`id` = tr.`ob_learner_id`
  LEFT JOIN frameworks
    ON tr.`framework_id` = frameworks.`id`
  LEFT JOIN ob_learner_skills_analysis
    ON tr.`id` = ob_learner_skills_analysis.`tr_id`

			");

            $view = $_SESSION[$key] = new View();
            $view->setViewName($key);
            $view->setSQL($sql->__toString());

            $f = new TextboxViewFilter('filter_firstnames', "WHERE ob_learners.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE ob_learners.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname (starts with): %s");
            $view->addFilter($f);

            $f = new DropDownViewFilter('filter_employer', DAO::getResultset($link, "SELECT id, legal_name, null, CONCAT('WHERE organisations.id=', id) FROM organisations WHERE organisation_type = '" . Organisation::TYPE_EMPLOYER . "' ORDER BY legal_name"), null, true);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

            $f = new DropDownViewFilter('filter_standard', DAO::getResultset($link, "SELECT id, title, null, CONCAT('WHERE tr.framework_id=', id) FROM frameworks WHERE id IN (SELECT DISTINCT framework_id FROM tr) ORDER BY title"), null, true);
            $f->setDescriptionFormat("Standard: %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_tr_ids', "WHERE tr.id in (%s)", null);
            $f->setDescriptionFormat("TR IDs: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Exclude Archived Learners', null, 'WHERE ob_learners.archive = "N"'),
                1=>array(2, 'Include Archived Learners', null, 'WHERE true'),
                2=>array(3, 'Show Only Archived Learners', null, 'WHERE ob_learners.archive = "Y"'),
            );
            $f = new DropDownViewFilter('filter_archive', $options, 1, false);
            $f->setDescriptionFormat("Archive: %s");
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
                4=>array(300,300,null,null),
                5=>array(400,400,null,null),
                6=>array(500,500,null,null),
                7=>array(0, 'No limit', null, null));
            $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
            $f->setDescriptionFormat("Records per page: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Firstnames, Surname', null, 'ORDER BY firstnames, surname'),
                1=>array(2, 'Surname, Firstnames', null, 'ORDER BY surname, firstnames'));
            $f = new DropDownViewFilter(View::KEY_ORDER_BY, $options, 1, false);
            $f->setDescriptionFormat("Sort by: %s");
            $view->addFilter($f);


        }

        return $_SESSION[$key];
    }

    public function renderView(PDO $link, View $view)
    {
        $st = $link->query($view->getSQL());
        if ($st)
        {
            echo $view->getViewNavigator();
            echo '<div align="center"><table class="table table-bordered table-condensed">';
            echo '<thead><tr class="bg-gray-active">';

            $fields = [];
            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $field = $st->getColumnMeta($i);
                $fields[] = $field['name'];
            }

            $fields = array_diff($fields, ["ob_learner_id", "tr_id"]);

            foreach ($fields as $field)
            {
                echo '<th>' . htmlspecialchars(ucwords(str_replace("_"," ",str_replace("_and_"," & ",$field)))) . '</th>';
            }

            echo '</tr></thead><tbody>';

            $class = '';
            while ($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("do.php?_action=read_training&id={$row['tr_id']}");
                foreach ($fields as $field)
                {
                    if($field == 'rationale_by_provider')
                    {
                        $row[$field] = preg_replace('/ /', '&nbsp;', $row[$field], 10);
                        echo '<td class="small">' . $row[$field] . '</td>';
                    }
                    else
                    {
                        echo '<td>' . htmlspecialchars($row[$field]) . '</td>';
                    }
                }
                echo '</tr>';
            }

            echo '</tbody></table></div>';
            echo $view->getViewNavigator();

        }
    }

    }
?>