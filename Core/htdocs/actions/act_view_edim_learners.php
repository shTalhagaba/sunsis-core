<?php
class view_edim_learners implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		$view = VoltView::getViewFromSession('ViewEdimLearners', 'ViewEdimLearners'); /* @var $view VoltView */
		if(is_null($view))
		{
			$view = $_SESSION['ViewEdimLearners'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		$_SESSION['bc']->add($link, "do.php?_action=view_edim_learners", "View Learners");

		if($subaction == 'export_csv')
		{
			$this->export_csv($link, $view);
			exit;
		}

		include_once('tpl_view_edim_learners.php');
	}

	private function buildView(PDO $link)
	{
		$sql = new SQLStatement("
SELECT
  learners.id AS learner_id,
  learners.username AS learner_username,
  learners.firstnames,
  learners.surname,
  learners.gender,
  learners.imi_redeem_code,
  DATE_FORMAT(learners.dob, '%d/%m/%Y') AS date_of_birth,
  learners.home_postcode,
  learners.home_email AS learner_email,
  learners.home_mobile AS learner_mobile,
  learners.home_telephone AS learner_telephone,
  employers.legal_name AS employer,
  learners.duplex_status,
  (SELECT COUNT(*) FROM lookup_wmca_postcode WHERE lookup_wmca_postcode.postcode = learners.home_postcode) AS postcode_lookup_entries,
  (SELECT COUNT(*) FROM crm_learner_hs_form WHERE crm_learner_hs_form.learner_id = learners.id AND crm_learner_hs_form.learner_sign IS NOT NULL ) AS completed_hs_form_entries,
  (SELECT DATE_FORMAT(crm_training_schedule.`training_date`, '%d/%m/%Y') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = learners.`id` LIMIT 1) AS l3_date,
  (SELECT DATE_FORMAT(crm_training_schedule.`training_date`, '%d/%m/%Y') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = learners.`id` LIMIT 1) AS l4_date,
  (SELECT IF(training.`status` = 2, 'Completed', 'Booked') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L1' AND training.`learner_id` = learners.`id` LIMIT 1) AS l1_status,
  (SELECT IF(training.`status` = 2, 'Completed', 'Booked') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L2' AND training.`learner_id` = learners.`id` LIMIT 1) AS l2_status,
  (SELECT IF(training.`status` = 2, 'Completed', 'Booked') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L3' AND training.`learner_id` = learners.`id` LIMIT 1) AS l3_status,
  (SELECT IF(training.`status` = 2, 'Completed', 'Booked') FROM training INNER JOIN crm_training_schedule ON training.`schedule_id` = crm_training_schedule.`id` WHERE crm_training_schedule.`level` = 'L4' AND training.`learner_id` = learners.`id` LIMIT 1) AS l4_status,
  learners.l24,
  learners.l41a,
  learners.ni,
  learners.who_created

FROM
  users AS learners
  LEFT JOIN organisations AS employers
    ON learners.`employer_id` = employers.`id`
  LEFT JOIN locations
    ON (
      locations.`organisations_id` = employers.`id`
      AND locations.`is_legal_address` = 1
    )
			");

            $sql->setClause("WHERE learners.`type` = '" . User::TYPE_LEARNER . "'");

		$view = new VoltView('ViewEdimLearners', $sql->__toString());

		$f = new VoltTextboxViewFilter('filter_ids', "WHERE learners.id IN (%s)", null);
		$f->setDescriptionFormat("Records: %s");
		$view->addFilter($f);

		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
        $columns = DAO::getSingleColumn($link, "SELECT colum FROM view_columns WHERE VIEW = 'ViewLearnersV2' AND USER = 'master' ORDER BY sequence;");
        $columns[] = 'gender';
        $columns = array_diff($columns, ['l3_joining_emails', 'l4_joining_emails', 'home_address_line_1', 'home_address_line_2', 'home_address_line_3', 'home_address_line_4']);
		$st = $link->query($view->getSQLStatement()->__toString());
		if($st)
		{
            echo '<div align="center" ><table class="table table-bordered table-condensed" id="tblLearners"><caption class="text-bold lead text-center">'.$st->rowCount().' records</caption>';
            echo '<thead class="bg-gray-active"><tr><th>&nbsp;</th>';
            foreach($columns as $column)
            {
                echo '<th>' . ucwords(str_replace("_"," ",$column)) . '</th>';
            }
            echo '</tr></thead>';

            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo HTML::viewrow_opening_tag("/do.php?_action=read_learner&username={$row['learner_username']}&id={$row['learner_id']}");

                echo '<td>';
                if($row['home_postcode'] != '' && $row['postcode_lookup_entries'] == 0)
                    echo ' &nbsp; <label class="label label-danger">Invalid postcode</label>';
                if($row['home_postcode'] != '' && $row['postcode_lookup_entries'] > 0)
                    echo ' &nbsp; <label class="label label-success">Valid postcode</label>';
                echo '</td>';

                foreach($columns as $column)
                {
                    echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
                }

                echo '</tr>';
            }

            echo '</tbody></table></div><p><br></p>';
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}

	private function export_csv(PDO $link, VoltView $view)
	{
		$grades = InductionHelper::getListOpTaskStatus(8);
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{

			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=EPA Learners.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}
			echo "Programme,Firstnames,Surname,EPA Result Status,Resit,Actual Date,Comments";
			echo "\n";
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo HTML::csvSafe($row['programme']) . ",";
				echo HTML::csvSafe($row['firstnames']) . ",";
				echo HTML::csvSafe($row['surname']) . ",";
				echo isset($grades[$row['task_status']]) ? $grades[$row['task_status']].',':',';
				echo $row['task_type'] == '2' ? 'Yes,':'No,';
				echo $row['task_actual_date'] . ",";
				echo HTML::csvSafe($row['task_comments']);
				echo "\n";
			}
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}
}