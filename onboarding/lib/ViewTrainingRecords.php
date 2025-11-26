<?php
class ViewTrainingRecords extends View
{

    public static function getInstance(PDO $link)
    {
        $key = 'view_Ob_'.__CLASS__;

	$where = "";
	$extraFields = "";
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
	            $extraFields = "
                    (SELECT otj_planner_signatures.learner_sign FROM otj_planner_signatures WHERE tr_id = ob_tr.`id` LIMIT 1 ) AS otj_learner_sign,
                    (SELECT otj_planner_signatures.employer_sign FROM otj_planner_signatures WHERE tr_id = ob_tr.`id` LIMIT 1 ) AS otj_employer_sign,
                    (SELECT otj_planner_signatures.provider_sign FROM otj_planner_signatures WHERE tr_id = ob_tr.`id` LIMIT 1 ) AS otj_provider_sign,  
                ";
        }

        if(!isset($_SESSION[$key]))
        {
            // Create new view object
            $sql = <<<SQL
SELECT DISTINCT
  ob_tr.id AS system_id,
  ob_tr.`ob_learner_id`,
  ob_learners.`firstnames`,
  ob_learners.`surname`,
  CASE ob_tr.status_code
   WHEN 1 THEN 'In Progress'
   WHEN 2 THEN 'Completed'
   WHEN 3 THEN 'Archived'
   WHEN 4 THEN 'Converted'
   WHEN 5 THEN 'Not Progressed'
   WHEN 6 THEN 'Change of Employer'
   ELSE ''
  END AS status,
  DATE_FORMAT(ob_learners.dob, '%d/%m/%Y') AS dob,
  ob_learners.uln,
  ((DATE_FORMAT(ob_tr.`practical_period_start_date`,'%Y') - DATE_FORMAT(ob_learners.dob,'%Y')) - (DATE_FORMAT(ob_tr.`practical_period_start_date`,'00-%m-%d') < DATE_FORMAT(ob_learners.dob,'00-%m-%d'))) AS age_at_start,
  ob_tr.`type_of_funding`,
  ob_learners.`home_address_line_1`,
  ob_learners.`home_postcode`,
  employers.`legal_name` AS employer,
  frameworks.`title` AS standard,
  DATE_FORMAT(ob_tr.`practical_period_start_date`, '%d/%m/%Y') AS practical_period_start_date,
  DATE_FORMAT(ob_tr.`practical_period_start_date`, '%M %Y') AS _start_month_year,
  DATE_FORMAT(ob_tr.`practical_period_end_date`, '%d/%m/%Y') AS practical_period_end_date,
  #(SELECT emp_sign FROM employer_agreement_schedules WHERE tr_id = ob_tr.id ORDER BY id DESC LIMIT 1) AS initial_contract_emp_sign,
  #(SELECT tp_sign FROM employer_agreement_schedules WHERE tr_id = ob_tr.id ORDER BY id DESC LIMIT 1) AS initial_contract_tp_sign,
  employer_agreement_schedules.emp_sign AS initial_contract_emp_sign,
  employer_agreement_schedules.tp_sign AS initial_contract_tp_sign,
  (SELECT ob_learner_pre_iag_form.learner_sign FROM ob_learner_pre_iag_form WHERE tr_id = ob_tr.`id` ) AS pre_iag_learner_sign,
  (SELECT ob_learner_pre_iag_form.provider_sign FROM ob_learner_pre_iag_form WHERE tr_id = ob_tr.`id` ) AS pre_iag_provider_sign,
  (SELECT ob_learner_learning_style.learner_sign FROM ob_learner_learning_style WHERE tr_id = ob_tr.`id` ) AS learn_style_learner_sign,
  (SELECT ob_learner_writing_assessment.learner_sign FROM ob_learner_writing_assessment WHERE tr_id = ob_tr.`id` ) AS writing_asmt_learner_sign,
  (SELECT ob_learner_writing_assessment.provider_sign FROM ob_learner_writing_assessment WHERE tr_id = ob_tr.`id` ) AS writing_asmt_provider_sign,
  #(SELECT ob_learner_skills_analysis.learner_sign FROM ob_learner_skills_analysis WHERE tr_id = ob_tr.`id` ORDER BY id DESC LIMIT 1 ) AS sk_learner_sign,
  #(SELECT ob_learner_skills_analysis.employer_sign FROM ob_learner_skills_analysis WHERE tr_id = ob_tr.`id` ORDER BY id DESC LIMIT 1 ) AS sk_employer_sign,
  #(SELECT ob_learner_skills_analysis.provider_sign FROM ob_learner_skills_analysis WHERE tr_id = ob_tr.`id` ORDER BY id DESC LIMIT 1 ) AS sk_provider_sign,
  $extraFields
  ob_learner_skills_analysis.learner_sign AS sk_learner_sign,
  ob_learner_skills_analysis.employer_sign AS sk_employer_sign,
  ob_learner_skills_analysis.provider_sign AS sk_provider_sign,
  ob_tr.`learner_sign` AS app_agr_learner_sign,
  ob_tr.`emp_sign` AS app_agr_emp_sign,
  ob_tr.`tp_sign` AS onboarding_provider_sign,
  ob_learner_fdil.`learner_sign` AS fdil_learner_sign,
  ob_learner_fdil.`tutor_sign` AS fdil_tutor_sign
FROM
  ob_tr INNER JOIN ob_learners ON ob_tr.`ob_learner_id` = ob_learners.`id`
  LEFT JOIN organisations AS employers ON ob_tr.`employer_id` = employers.`id`
  LEFT JOIN locations ON ob_tr.`employer_location_id` = locations.`id`
  LEFT JOIN frameworks ON ob_tr.`framework_id` = frameworks.`id`
  LEFT JOIN ob_learner_skills_analysis ON ob_tr.`id` = ob_learner_skills_analysis.tr_id
  LEFT JOIN ob_learner_fdil ON ob_tr.`id` = ob_learner_fdil.tr_id
  #LEFT JOIN employer_agreement_schedules ON ob_tr.`id` = employer_agreement_schedules.tr_id 
  LEFT JOIN (SELECT m1.* FROM employer_agreement_schedules m1 LEFT JOIN employer_agreement_schedules m2 ON (m1.tr_id = m2.tr_id AND m1.id < m2.id) WHERE m2.id IS NULL) AS employer_agreement_schedules
  ON ob_tr.`id` = employer_agreement_schedules.tr_id 
$where    
;
SQL;

            $view = new ViewTrainingRecords();
            $view->setViewName($key);
            $view = $_SESSION[$key] = $view;
            $view->setSQL($sql);

            $f = new TextboxViewFilter('filter_firstnames', "WHERE ob_learners.firstnames LIKE '%s%%'", null);
            $f->setDescriptionFormat("First Name (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_surname', "WHERE ob_learners.surname LIKE '%s%%'", null);
            $f->setDescriptionFormat("Surname (starts with): %s");
            $view->addFilter($f);

            $f = new TextboxViewFilter('filter_system_id', "WHERE ob_tr.id IN (%s)", null);
            $f->setDescriptionFormat("Training Records: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'In Progress', null, 'WHERE ob_tr.status_code = "'.TrainingRecord::STATUS_IN_PROGRESS.'"'),
                1=>array(2, 'Completed', null, 'WHERE ob_tr.status_code = "'.TrainingRecord::STATUS_COMPLETED.'"'),
                2=>array(3, 'Archived', null, 'WHERE ob_tr.status_code = "'.TrainingRecord::STATUS_ARCHIVED.'"'),
                3=>array(4, 'Converted', null, 'WHERE ob_tr.status_code = "'.TrainingRecord::STATUS_CONVERTED.'"'),
                4=>array(5, 'Not Progressed', null, 'WHERE ob_tr.status_code = "'.TrainingRecord::STATUS_NOT_PROGRESSED.'"'),
            );
            $f = new DropDownViewFilter('filter_status', $options, TrainingRecord::STATUS_IN_PROGRESS);
            $f->setDescriptionFormat("Status: %s");
            $view->addFilter($f);

	        $i = 0;
            foreach(self::$additionalCheckboxFilters AS $field => $value)
            {
                ++$i;
                $options = [
                    0 => [1, $value, null, "HAVING {$field} IS NOT NULL"],
                    1 => [2, $value, null, "HAVING {$field} IS NULL"],
                ];
                $f = new DropDownViewFilter('filter_chk'.$i, $options);
                $f->setDescriptionFormat("Chk {$i}: %s");
                $view->addFilter($f);
            }

            $options = array(
                0=>array(1, 'Skills analysis not signed by learner', null, 'WHERE ob_tr.id IN (SELECT tr_id FROM ob_learner_skills_analysis WHERE signed_by_learner = 0 AND signed_by_provider = 0)'),
                1=>array(2, 'Skills analysis signed by learner and not by provider', null, 'WHERE ob_tr.id IN (SELECT tr_id FROM ob_learner_skills_analysis WHERE signed_by_learner = 1 AND signed_by_provider = 0)'),
                2=>array(3, 'Onboarding not signed by learner', null, 'WHERE ob_tr.learner_sign IS NULL'),
                3=>array(4, 'Onboarding signed by learner and not by employer', null, 'WHERE ob_tr.learner_sign IS NOT NULL AND ob_tr.emp_sign IS NULL'),
                4=>array(5, 'Onboarding signed by learner and employer but not by provider', null, 'WHERE ob_tr.learner_sign IS NOT NULL AND ob_tr.emp_sign IS NOT NULL AND ob_tr.tp_sign IS NULL'),
                5=>array(6, 'Onboarding learners converted and moved to Main Sunesis', null, 'WHERE ob_tr.status_code = ' . TrainingRecord::STATUS_CONVERTED . ' AND ob_tr.sunesis_tr_id != 0'),
            );
            $f = new DropDownViewFilter('filter_stats', $options);
            $f->setDescriptionFormat("Stats: %s");
            $view->addFilter($f);

            $options = array(
                0=>array(1, 'Levy', null, 'WHERE ob_tr.type_of_funding = "Levy"'),
                1=>array(2, 'Non-Levy', null, 'WHERE ob_tr.type_of_funding = "Non-Levy"'),
                2=>array(3, 'Levy Gifted', null, 'WHERE ob_tr.type_of_funding = "Levy Gifted"'),
            );
            $f = new DropDownViewFilter('filter_type_of_funding', $options);
            $f->setDescriptionFormat("Type of Funding: %s");
            $view->addFilter($f);

            $options = array(
                array('10', '10 Community Learning', null, 'WHERE frameworks.fund_model = "10"'),
                array('11', '11 Tailored Learning', null, 'WHERE frameworks.fund_model = "11"'),
                array('25', '25 16-19 EFA', null, 'WHERE frameworks.fund_model = "25"'),
                array('35', '35 Adult Skills', null, 'WHERE frameworks.fund_model = "35"'),
                array('36', '36 Apprenticeships (from 1 May 2017)', null, 'WHERE frameworks.fund_model = "36"'),
                array('37', '37 Skills Bootcamp', null, 'WHERE frameworks.fund_model = "37"'),
                array('38', '38 Adult Skills Fund', null, 'WHERE frameworks.fund_model = "38"'),
                array('70', '70 ESF', null, 'WHERE frameworks.fund_model = "70"'),
                array('81', '81 Other SFA', null, 'WHERE frameworks.fund_model = "81"'),
                array('82', '82 Other EFA', null, 'WHERE frameworks.fund_model = "82"'),
                array('99', '99 Non-funded', null, 'WHERE frameworks.fund_model = "99"')
            );	
            $f = new DropDownViewFilter('filter_funding_model', $options);
            $f->setDescriptionFormat("Funding Model: %s");
            $view->addFilter($f);

            $options = array(
                array('991', 'Learner Loan', null, 'WHERE frameworks.fund_model_extra = "991"'),
                array('992', 'Commercial', null, 'WHERE frameworks.fund_model_extra = "992"'),
            );
            $f = new DropDownViewFilter('filter_fund_model_extra', $options);
            $f->setDescriptionFormat("Fund Model Specific: %s");
            $view->addFilter($f);

            $options = DAO::getResultset($link, "SELECT DISTINCT frameworks.id, frameworks.title, null, CONCAT('WHERE ob_tr.framework_id=', frameworks.id) FROM frameworks LEFT JOIN ob_tr ON frameworks.id = ob_tr.framework_id ORDER BY frameworks.title");
            $f = new DropDownViewFilter('filter_standard', $options);
            $f->setDescriptionFormat("Standard: %s");
            $view->addFilter($f);

            $options = DAO::getResultset($link, "SELECT DISTINCT organisations.id, organisations.legal_name, null, CONCAT('WHERE ob_tr.employer_id=', organisations.id) FROM organisations LEFT JOIN ob_tr ON organisations.id = ob_tr.employer_id ORDER BY organisations.legal_name");
            $f = new DropDownViewFilter('filter_employer', $options);
            $f->setDescriptionFormat("Employer: %s");
            $view->addFilter($f);

            $format = "WHERE ob_tr.practical_period_start_date >= '%s'";
            $f = new DateViewFilter('from_practical_period_start_date', $format, '');
            $f->setDescriptionFormat("From practical period start date: %s");
            $view->addFilter($f);
            $format = "WHERE ob_tr.practical_period_start_date <= '%s'";
            $f = new DateViewFilter('to_practical_period_start_date', $format, '');
            $f->setDescriptionFormat("To practical period start date: %s");
            $view->addFilter($f);

            $format = "WHERE ob_tr.practical_period_end_date >= '%s'";
            $f = new DateViewFilter('from_practical_period_end_date', $format, '');
            $f->setDescriptionFormat("From practical period end date: %s");
            $view->addFilter($f);
            $format = "WHERE ob_tr.practical_period_end_date <= '%s'";
            $f = new DateViewFilter('to_practical_period_end_date', $format, '');
            $f->setDescriptionFormat("To practical period end date: %s");
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
            echo '<div class="center"><table class="table table-bordered" id="tblTrainings">';
            $columns = array();

            for($i = 0; $i < $st->columnCount(); $i++)
            {
                $column = $st->getColumnMeta($i);
                if(!in_array($column['name'], $this->excludeColumns))// && !array_key_exists($column['name'], self::$additionalCheckboxFilters))
                    $columns[] = $column['name'];
            }
	    	if(DB_NAME != "am_ela")
                {
		    $columns = array_diff( $columns, array_keys(self::$additionalCheckboxFilters) );
                }

            echo '<thead class="bg-green-gradient"><tr>';
            foreach($columns AS $column)
            {
                echo ( DB_NAME == "am_ela" && array_key_exists($column, self::$additionalCheckboxFilters) ) ?  
                    '<th class="topRow">' . self::$additionalCheckboxFilters[$column] . '</th>' : 
                    '<th class="topRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
            }
            echo '</tr></thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("do.php?_action=read_training&id={$row['system_id']}");
                foreach($columns as $col)
                {
                    if( DB_NAME == "am_ela" && array_key_exists($col, self::$additionalCheckboxFilters) )
                    {
                        echo '<td align="center">';
                        echo $row[$col] != '' ? '<i class="fa fa-check fa-lg text-green"></i>' : '<i class="fa fa-remove fa-lg text-red"></i>';
                        echo '</td>';
                    }
                    else
                    {
                        echo '<td>' . ((isset($row[$col]))?(($row[$col]=='')?'&nbsp':$row[$col]):'&nbsp') . '</td>';
                    }
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

    public function exportToCSV(PDO $link, $columns)
	{
		$statement = $this->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());

        $columns = array();
        for($i = 0; $i < $st->columnCount(); $i++)
        {
            $column = $st->getColumnMeta($i);
            if(!in_array($column['name'], $this->excludeColumns))
                $columns[] = $column['name'];
            if(DB_NAME != "am_ela")
            {
                $columns = array_diff( $columns, array_keys(self::$additionalCheckboxFilters) );
            }    
        }

		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename="' . $this->getViewName() . '.csv"');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}

			if($row = $st->fetch(PDO::FETCH_ASSOC))
			{
				$line = '';
				foreach($row as $field=>$value)
				{
					if(in_array($field, $columns))
					{
						if(strlen($line) > 0)
						{
							$line .= ',';
						}
						$line .= ( DB_NAME == "am_ela" && array_key_exists($field, self::$additionalCheckboxFilters) ) ?
                            '"' . str_replace('"', '""', self::$additionalCheckboxFilters[$field]) . '"' : 
                            '"' . str_replace('"', '""', $field) . '"';
					}
				}
				echo $line . "\r\n";

				do
				{
					$line = '';

					foreach($row as $field=>$value)	{
						if(in_array($field, $columns)) {
                            if(strlen($line) > 0)
                            {
                                $line .= ',';
                            }
                            $line .= ( DB_NAME == "am_ela" && array_key_exists($field, self::$additionalCheckboxFilters) && $value != '' ) ?
                                '"Yes"' : 
                                '"' . str_replace('"', '""', $value) . '"';
						}
					}
					echo $line."\r\n";
				} while($row = $st->fetch(PDO::FETCH_ASSOC));
			}
		}
		else
		{
			throw new DatabaseException($link, $statement->__toString());
		}
	}

    private $excludeColumns = [
        'ob_learner_id',
        'tr_id',
        'archive',
    ];

    private static $additionalCheckboxFilters = [
        "initial_contract_emp_sign" => "Initial Contract - Emp. Sign",
        "initial_contract_tp_sign" => "Initial Contract - Prov. Sign",
        "pre_iag_learner_sign" => "Pre IAG - Lear. Sign",
        "pre_iag_provider_sign" => "Pre IAG - Prov. Sign",
        "learn_style_learner_sign" => "Learn Style - Lear. Sign",
        "writing_asmt_learner_sign" => "Writing Asmt. - Lear. Sign",
        "writing_asmt_provider_sign" => "Writing Asmt. - Prov. Sign",
        "sk_learner_sign" => "Skills Scan - Lear. Sign",
        "sk_employer_sign" => "Skills Scan - Emp. Sign",
        "sk_provider_sign" => "Skills Scan - Prov. Sign",
        "app_agr_learner_sign" => "App. Agr. - Lear. Sign",
        "app_agr_emp_sign" => "App. Agr. - Emp. Sign",
        "onboarding_provider_sign" => "Onboarding - Prov. Sign",
        "fdil_learner_sign" => "FDIL - Lear. Sign",
        "fdil_tutor_sign" => "FDIL - Tutor Sign",
        "otj_learner_sign" => "OTJ - Learner Sign",
        "otj_employer_sign" => "OTJ - Employer Sign",
        "otj_provider_sign" => "OTJ - Provider Sign",
    ];	

}
?>