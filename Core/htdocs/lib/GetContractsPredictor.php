<?php
class GetContractsPredictor extends View
{

	public static function getInstance()
	{
		$key = 'view'.__CLASS__;

		if(true)
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT DISTINCT
	CASE contracts.funded WHEN '1' THEN 'Yes' WHEN '2' THEN 'No' ELSE 'Not Set' END AS 'funded_contract',
	contracts.*, organisations.legal_name,
	(select count(distinct tr_id) from ilr where ilr.contract_id = contracts.id) as ilrs
FROM
	contracts
	LEFT JOIN organisations on organisations.id = contracts.contract_holder 
	LEFT JOIN tr ON contracts.id = tr.`contract_id`
WHERE 
	contracts.active = 1
ORDER BY
	contract_year desc, title;
HEREDOC;


			$view = $_SESSION[$key] = new GetContractsPredictor();
			$view->setSQL($sql);

			$parent_org = $_SESSION['user']->employer_id;

			$options = 'SELECT id, CONCAT(firstnames," ", surname), null, CONCAT("WHERE assessors.username=",CHAR(39),username,CHAR(39)) FROM users where type=3 ORDER BY firstnames';
			$f = new DropDownViewFilter('assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = "SELECT id, legal_name, NULL, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisations.parent_org=$parent_org AND (organisation_type LIKE '%2%' OR organisation_type LIKE '%6%') order by legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
			$f = new DropDownViewFilter('employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			if(DB_NAME=="am_siemens" || DB_NAME=="am_siemens_demo")
			{
				$options = <<<OPTIONS
SELECT
  brands.id,
  brands.title,
  NULL,
  CONCAT(
    "WHERE tr.employer_id IN (",
    GROUP_CONCAT(employer_business_codes.`employer_id`),
    ")"
  )
FROM
  brands
  LEFT JOIN employer_business_codes
    ON employer_business_codes.brands_id = brands.id
    GROUP BY brands.id
ORDER BY brands.title ;
OPTIONS;
				//$options = "SELECT id, title, null FROM brands ORDER BY title";
				$f = new DropDownViewFilter('filter_emp_b_code', $options, null, true);
				$f->setDescriptionFormat("Employer Business Code: %s");
				$view->addFilter($f);
			}

			$parent_org = $_SESSION['user']->employer_id;
			if($_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  tr.provider_id=',id) FROM organisations WHERE id = $parent_org order by legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
			if($_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
				$f = new DropDownViewFilter('provider', $options, $parent_org, false);
			else
				$f = new DropDownViewFilter('provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT id, title, null, CONCAT('WHERE courses.id=',id) FROM courses ORDER BY title";
			$f = new DropDownViewFilter('course', $options, null, true);
			$f->setDescriptionFormat("Course: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, ' where 1=1'),
				1=>array(2, 'Valid', null, ' where is_valid=1'),
				2=>array(3, 'Invalid', null, ' where is_valid <> 1'));
			$f = new DropDownViewFilter('filter_valid', $options, 1, false);
			$f->setDescriptionFormat("Validity: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, ' where 1=1'),
				1=>array(2, 'Active', null, ' where is_active=1'),
				2=>array(3, 'Not Active', null, ' where is_active <> 1'));
			$f = new DropDownViewFilter('filter_active', $options, 1, false);
			$f->setDescriptionFormat("Active: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, ' where 1=1'),
				1=>array(2, 'With LSF', null, ' where lsf=1'));
			$f = new DropDownViewFilter('filter_lsf', $options, 1, false);
			$f->setDescriptionFormat("Learner Support Fund: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All', null, ' where 1=1'),
				1=>array(2, 'Yes', null, ' where zprog=1'),
				2=>array(3, 'No', null, ' where  zprog=0'));
			$f = new DropDownViewFilter('filter_zprog', $options, 1, false);
			$f->setDescriptionFormat("With ZPROG Aim: %s");
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
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>&nbsp;</th><th><input id="global" type="checkbox" onclick="checkAll(this);" /></th><th>Title</th><th>Contract Holder</th><th>Year</th><th>ILRs</th><th>Funded Contract</th></tr></thead>';
			$counter=1;
			echo '<tbody>';
			while($row = $st->fetch())
			{
				echo '<tr title="' . $row['contract_year'] .  '"><th>&nbsp;</td>';
				echo '<td><input id="button'.$counter++.'" type="checkbox" onclick="evidenceradio_onclick(this)" title="' . $row['title'] . '" name="evidenceradio" value="' . $row['id'] . '" />';
				echo '<td>' . $row['title'] . '</td>';
				echo '<td>' . $row['legal_name'] . '</td>';
				echo '<td align=center>' . $row['contract_year'] . '</td>';
				echo '<td align=center>' . $row['ilrs'] . '</td>';
				echo '<td align=center>' . $row['funded_contract'] . '</td>';
				echo '</tr>';

				$qid = $row['id'];

			}
			echo '</tbody></table></div>';

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}

	}

    public function render2(PDO $link)
    {
        /* @var $result pdo_result */
        $st = $link->query("select * from import_learners");
        if($st)
        {
            echo '<div align="left">
            <h3>Previous imports</h3>
            <table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th>&nbsp;</th><th>National Insurance</th><th>Status</th><th>Date Time</th></tr></thead>';
            $counter=1;
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo '<tr><th>&nbsp;</td>';
                echo '<td>' . $row['ni'] . '</td>';
                echo '<td>' . $row['message'] . '</td>';
                echo '<td align=center>' . $row['datetime'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }
	}

	public function renderBulkUpdate(PDO $link)
    {
        /* @var $result pdo_result */
		$index = 0;
        $st = $link->query("SELECT 
		value_1,
		value_2,
		value_3,
		value_4,
		value_5,
		value_6,
		value_7,
		value_8,
		value_9,
		value_10,
		value_11,
		'' as status
		#IF(value_1 = \"ULN\", \"Header Row\",IF(value_4 IS NULL, \"Learner not found\", IF(value_5 IS NULL, \"Assessor Not Found\" ,\"Acceptable\"))) AS `status`
		FROM bulk_update2;");
        if($st)
        {
            echo '<div align="left">
            <h3>CSV Data</h3>
			<span class="button" onclick="window.location.href=\'do.php?_action=apply_bulk_update\'"> Apply </span><br>
			<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<tbody>';
            while($row = $st->fetch())
            {
				if($index==0)
				{
					$index++;
					echo '<thead><tr><th>' . $row['value_1'] . '</th><th>' . $row['value_2'] . '</th><th>' . $row['value_3'] . '</th><th>' . $row['value_4'] . '</th><th>' . $row['value_5'] . '</th><th>' . $row['value_6'] . '</th><th>' . $row['value_7'] . '</th><th>' . $row['value_8'] . '</th><th>' . $row['value_9'] . '</th><th>' . $row['value_10'] . '</th><th>' . $row['value_11'] . '</th>';
					//echo '<th>Status</th><th>Comments</th>';
					echo '</thead>';
				}
				elseif($index==1)
				{
					$index++;
					echo '<tbody>';

					echo '<tr>';
					echo '<td>' . $row['value_1'] . '</td>';
					echo '<td>' . $row['value_2'] . '</td>';
					echo '<td>' . $row['value_3'] . '</td>';
					echo '<td>' . $row['value_4'] . '</td>';
					echo '<td>' . $row['value_5'] . '</td>';
					echo '<td>' . $row['value_6'] . '</td>';
					echo '<td>' . $row['value_7'] . '</td>';
					echo '<td>' . $row['value_8'] . '</td>';
					echo '<td>' . $row['value_9'] . '</td>';
					echo '<td>' . $row['value_10'] . '</td>';
					echo '<td>' . $row['value_11'] . '</td>';
					/*if($row['status']=="Acceptable")
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
					else
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
					echo '<td>' . $row['status'] . '</td>';*/
					echo '</tr>';
				}
				else
				{
					$index++;
					echo '<tr>';
					echo '<td>' . $row['value_1'] . '</td>';
					echo '<td>' . $row['value_2'] . '</td>';
					echo '<td>' . $row['value_3'] . '</td>';
					echo '<td>' . $row['value_4'] . '</td>';
					echo '<td>' . $row['value_5'] . '</td>';
					echo '<td>' . $row['value_6'] . '</td>';
					echo '<td>' . $row['value_7'] . '</td>';
					echo '<td>' . $row['value_8'] . '</td>';
					echo '<td>' . $row['value_9'] . '</td>';
					echo '<td>' . $row['value_10'] . '</td>';
					echo '<td>' . $row['value_11'] . '</td>';
					/*if($row['status']=="Acceptable")
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/green-tick.gif\" border=\"0\" alt=\"\" /></td>";
					else
						echo "<td align='center' style='border-right-style: solid;'> <img src=\"/images/red-cross.gif\" border=\"0\" alt=\"\" /></td>";
					echo '<td>' . $row['status'] . '</td>';*/
					echo '</tr>';
				}
            }
            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }

    public function renderBulkUpdateAudit(PDO $link)
    {
        /* @var $result pdo_result */
        /*$st = $link->query("select * from bulk_update_audit order by time desc");
        if($st)
        {
            echo '<div align="left">
            <h3>Audit - Previous updates</h3>
            <table class="resultset" border="0" cellspacing="0" cellpadding="6">';
            echo '<thead><tr><th>&nbsp;</th><th>Learner</th><th>Change</th><th>Date Time</th><th>User</th></tr></thead>';
            echo '<tbody>';
            while($row = $st->fetch())
            {
                echo '<tr><th>&nbsp;</td>';
                echo '<td>' . $row['learner'] . '</td>';
                echo '<td>' . $row['change'] . '</td>';
                echo '<td align=center>' . $row['time'] . '</td>';
                echo '<td>' . $row['who'] . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table></div>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }*/
	}


}
?>