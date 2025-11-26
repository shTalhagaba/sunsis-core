<?php
class ViewScottishFundingDetailedReport extends View
{

	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			// Create new view object

			if($_SESSION['user']->isAdmin() || $_SESSION['user']->type== User::TYPE_SYSTEM_VIEWER)
			{
				$where = '';
			}
			else
			{
				throw new Exception('You are not authorised to view this report.');
			}


			$sql = <<<HEREDOC
SELECT
	tr.id AS tr_id,
	timestampdiff(YEAR,tr.dob,CURDATE()) AS age,
	student_frameworks.id AS framework_id,
	tr.l03,
	tr.surname,
	tr.firstnames,
	tr.ULN,
	employers.legal_name AS employer,
	frameworks.`framework_code`,
	frameworks.`title` AS framework_title,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
	DATE_FORMAT(tr.`closure_date`, '%d/%m/%Y') AS actual_end_date,
	scottish_payments.payment_type AS type_of_payment,
	DATE_FORMAT(scottish_payments.due_date, '%d/%m/%Y') AS due_date,
	DATE_FORMAT(scottish_payments.milestones_completion_date, '%d/%m/%Y') AS milestone_completion_date,
	'' AS amount_claimed,
	DATE_FORMAT(scottish_payments.doc_sent_date, '%d/%m/%Y') AS claimed_date,
	DATE_FORMAT(scottish_payments.date_paid, '%d/%m/%Y') AS date_paid,
	scottish_payments.amount_received AS amount,
	'' AS balance


FROM
	scottish_payments
	LEFT JOIN tr ON scottish_payments.tr_id = tr.id
	LEFT JOIN organisations AS employers ON employers.id = tr.employer_id
	LEFT JOIN student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT JOIN frameworks ON frameworks.id = student_frameworks.id AND frameworks.funding_stream = 2
where tr.id is not null
$where

HEREDOC;

			$view = $_SESSION[$key] = new ViewScottishFundingDetailedReport();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);


			$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type like "%2%" ORDER BY legal_name';
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Surname contains: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_firstname', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

			// ULN Filter
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("ULN: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type==8)
				$options = "SELECT DISTINCT frameworks.id, title, null, CONCAT('WHERE student_frameworks.id=',frameworks.id) FROM frameworks where frameworks.parent_org = $parent_org and frameworks.active = 1 order by frameworks.title";
			else
				$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE student_frameworks.id=',id) FROM frameworks where frameworks.active = 1 order by frameworks.title";
			$f = new DropDownViewFilter('filter_framework', $options, null, true);
			$f->setDescriptionFormat("Framework: %s");
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

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		//pre($this->getSQL());
		$payment_type_desc ['SP'] = 'Start Payment';
		$payment_type_desc ['OP'] = 'Outcome Payment';
		for($i = 1; $i <= 20; $i++)
			$payment_type_desc ['MP'.$i] = 'Milestone ' . $i;

		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr>';
			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," &amp; ",$column))) . '</th>';
			}
			echo '</tr></thead><tbody>';
			while($row = $st->fetch())
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $row['tr_id']);

				foreach($columns as $column)
				{
					if($column == 'amount_claimed')
					{
                        $claimed_amount = 0;
						switch($row['type_of_payment'])
						{
							case 'SP':
								if($row['age'] >= 16 && $row['age'] <= 19)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_SP' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 20 && $row['age'] <= 24)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_SP' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 25)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus_SP' AND fwrk_id = " . $row['framework_id']);
								echo '<td align="center">' . $claimed_amount . '</td>';
								break;
							case 'OP':
								if($row['age'] >= 16 && $row['age'] <= 19)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_OP' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 20 && $row['age'] <= 24)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_OP' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 25)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus_OP' AND fwrk_id = " . $row['framework_id']);
								echo '<td align="center">' . $claimed_amount . '</td>';
								break;
							default:
								$milestone = substr( $row['type_of_payment'], 2, 1 );
								if($row['age'] >= 16 && $row['age'] <= 19)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_MP_" . $milestone . "' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 20 && $row['age'] <= 24)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_MP_" . $milestone . "' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 25)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus__MP_" . $milestone . "' AND fwrk_id = " . $row['framework_id']);
								echo '<td align="center">' . $claimed_amount . '</td>';
								break;
						}
					}
					elseif($column == 'balance')
					{
                        $claimed_amount = 0;
						switch($row['type_of_payment'])
						{
							case 'SP':
								if($row['age'] >= 16 && $row['age'] <= 19)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_SP' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 20 && $row['age'] <= 24)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_SP' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 25)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus_SP' AND fwrk_id = " . $row['framework_id']);
								$balance = $claimed_amount - $row['amount'];
								echo '<td align="center">' . $balance . '</td>';
								break;
							case 'OP':
								if($row['age'] >= 16 && $row['age'] <= 19)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_OP' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 20 && $row['age'] <= 24)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_OP' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 25)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus_OP' AND fwrk_id = " . $row['framework_id']);
								$balance = $claimed_amount - $row['amount'];
								echo '<td align="center">' . $balance . '</td>';
								break;
							default:
								$milestone = substr( $row['type_of_payment'], 2, 1 );
								if($row['age'] >= 16 && $row['age'] <= 19)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '16_19_MP_" . $milestone . "' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 20 && $row['age'] <= 24)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '20_24_MP_" . $milestone . "' AND fwrk_id = " . $row['framework_id']);
								elseif($row['age'] >= 25)
									$claimed_amount = DAO::getSingleValue($link, "SELECT amount FROM fwrk_scottish_funding WHERE description = '25_Plus__MP_" . $milestone . "' AND fwrk_id = " . $row['framework_id']);
								$balance = $claimed_amount - $row['amount'];
								echo '<td align="center">' . $balance . '</td>';
								break;
						}
					}
					elseif($column == 'type_of_payment')
					{
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$payment_type_desc[$row[$column]]):'&nbsp') . '</td>';
					}
					else
						echo '<td align="center">' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
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