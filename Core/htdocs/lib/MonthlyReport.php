<?php
class MonthlyReport extends View
{
	public $firstSetData;
	public $secondSetData;
	public $thirdSetData;
	public $fourthSetData;
	public $fifthSetData;
	public $sixthSetData;
    public $seventhSetData;
    public $eighthSetData;

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$where = '';
			if($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
			{
				$emp = $_SESSION['user']->employer_id;
				$where = " where (tr.provider_id= '$emp' or tr.employer_id='$emp')" ;
			}
			$sql = <<<HEREDOC
	SELECT
		tr.id
	FROM
		tr 
	$where
		limit 0,1
HEREDOC;

			$view = $_SESSION[$key] = new MonthlyReport();
			$view->setSQL($sql);

			// Add view filters
			/*			$options = array(
				   0=>array(20,20,null,null),
				   1=>array(50,50,null,null),
				   2=>array(100,100,null,null),
				   3=>array(200,200,null,null),
				   4=>array(0, 'No limit', null, null));
			   $f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			   $f->setDescriptionFormat("Records per page: %s");
			   $view->addFilter($f);

			   $options = array(
				   0=>array(1, 'Manufacturer (asc)', null, 'ORDER BY manufacturer'),
				   1=>array(2, 'Manufacturer (desc)', null, 'ORDER BY manufacturer DESC'),
				   2=>array(3, 'Group (asc)', null, 'ORDER BY dealer_group'),
				   3=>array(4, 'Group (desc)', null, 'ORDER BY dealer_group DESC'),
				   4=>array(4, 'Dealer (asc)', null, 'ORDER BY legal_name'),
				   5=>array(5, 'Dealer (desc)', null, 'ORDER BY legal_name DESC'));
			   $f = new DropDownViewFilter('order_by', $options, 1, false);
			   $f->setDescriptionFormat("Sort by: %s");
			   $view->addFilter($f);

			   // Manufacturer filter
			   $options = "SELECT DISTINCT manufacturer, manufacturer, null, CONCAT('WHERE organisations.manufacturer=',char(39),manufacturer,char(39)) FROM organisations where organisation_type=7";
			   $f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			   $f->setDescriptionFormat("Manufacturer: %s");
			   $view->addFilter($f);

			   // Group Filter
			   $options = "SELECT DISTINCT dealer_group, dealer_group, null, CONCAT('WHERE organisations.dealer_group=',char(39),dealer_group,char(39)) FROM organisations where organisation_type=7";
			   $f = new DropDownViewFilter('filter_group', $options, null, true);
			   $f->setDescriptionFormat("Group: %s");
			   $view->addFilter($f);

			   // Region Filter
			   $options = "SELECT DISTINCT region, region, null, CONCAT('WHERE organisations.region=',char(39),region,char(39)) FROM organisations where organisation_type=7";
			   $f = new DropDownViewFilter('filter_region', $options, null, true);
			   $f->setDescriptionFormat("Region: %s");
			   $view->addFilter($f);

			   // Dealer type filter
			   $options = "SELECT DISTINCT org_type, org_type, null, CONCAT('WHERE organisations.org_type=',char(39),org_type,char(39)) FROM organisations where organisation_type=7";
			   $f = new DropDownViewFilter('filter_type', $options, null, true);
			   $f->setDescriptionFormat("Dealer Type: %s");
			   $view->addFilter($f);

			   // Participating or not participating
			   $options = array(
				   0=>array(0, 'Show all', null, null),
				   1=>array(1, 'Participating', null, 'WHERE organisations.dealer_participating="1"'),
				   2=>array(2, 'Not Participating', null, 'WHERE organisations.dealer_participating<>"1"'));
			   $f = new DropDownViewFilter('filter_dealers_participating', $options, 0, false);
			   $f->setDescriptionFormat("Dealers Participating: %s");
			   $view->addFilter($f);

			   // Dealer Name Filter
			   $f = new TextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
			   $f->setDescriptionFormat("Dealer Name contains: %s");
			   $view->addFilter($f);

			   // PostCode Name Filter
			   $f = new TextboxViewFilter('filter_postcode', "WHERE locations.postcode LIKE '%s%%'", null);
			   $f->setDescriptionFormat("Postcode: %s");
			   $view->addFilter($f);

			   // Town Filter
			   $options = 'SELECT DISTINCT locations.town, locations.town, null, CONCAT("WHERE locations.town=",CHAR(39),town,CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%7%" order by locations.town';
			   $f = new DropDownViewFilter('filter_town', $options, null, true);
			   $f->setDescriptionFormat("Town: %s");
			   $view->addFilter($f);

			   // Locality Filter
			   $options = 'SELECT DISTINCT locations.locality, locations.locality, null, CONCAT("WHERE locations.locality=",CHAR(39),locality,CHAR(39)) FROM organisations LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id) WHERE organisations.organisation_type like "%7%" order by locations.locality';
			   $f = new DropDownViewFilter('filter_locality', $options, null, true);
			   $f->setDescriptionFormat("Locality: %s");
			   $view->addFilter($f);

			   $options = array(
				   0=>array(1, 'All', null, 'where organisations.id IS NOT NULL'),
				   1=>array(2, 'Due more than 1 month', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)>30'),
				   2=>array(3, 'Due within 1 month', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)<=30 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)>=0'),
				   3=>array(4, 'Overdue', null, 'Where (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)<0'));
			   $f = new DropDownViewFilter('by_health_safety_timeliness', $options, 1, false);
			   $f->setDescriptionFormat("Health & Safety Timeliness: %s");
			   $view->addFilter($f);

			   $options = array(
				   0=>array(1, 'All', null, 'where organisations.id IS NOT NULL'),
				   1=>array(2, 'Compliant', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=1'),
				   2=>array(3, 'Non-complient', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=2'),
				   3=>array(4, 'Outstaning action', null, 'where (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)=3'));
			   $f = new DropDownViewFilter('by_health_safety_compliance', $options, 1, false);
			   $f->setDescriptionFormat("Health & Safety compliance: %s");
			   $view->addFilter($f);

   *

   */

			// Employer Filter
			// Manufacturer Filter
			$options = "SELECT  id, title, null, CONCAT('WHERE tr.employer_id in (select id from organisations where manufacturer=',id,')') FROM brands";
			$f = new DropDownViewFilter('filter_manufacturer', $options, null, true);
			$f->setDescriptionFormat("Brand: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.employer_id=",id) FROM organisations WHERE organisation_type LIKE "%2%" AND organisations.parent_org = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.employer_id=',id) FROM organisations WHERE organisation_type like '%2%' or organisation_type like '%6%' order by legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);


			// Provider Filter
			$parent_org = $_SESSION['user']->employer_id;
			if($_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
				$options = "SELECT id, legal_name, null, CONCAT('WHERE  tr.provider_id=',id) FROM organisations WHERE id = $parent_org order by legal_name";
			else
				$options = "SELECT id, legal_name, null, CONCAT('WHERE tr.provider_id=',id) FROM organisations WHERE organisation_type like '%3%' order by legal_name";
			if($_SESSION['user']->type==8 || $_SESSION['user']->type==User::TYPE_ORGANISATION_VIEWER)
				$f = new DropDownViewFilter('filter_provider', $options, $parent_org, false);
			else
				$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$options = "SELECT code, description, null, CONCAT('WHERE courses.programme_type=',code) FROM lookup_programme_type order by description asc";
			$f = new DropDownViewFilter('filter_programme_type', $options, null, true);
			$f->setDescriptionFormat("Programme Type: %s");
			$view->addFilter($f);

			// Date filters
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);

			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);

			// Start Date Filter
			$format = "where 1=1";
			$sd = "01/" . date('m/Y');
			$f = new DateViewFilter('start_date', $format, $sd);
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));

			$format = "where 1=1";
			// Last day of month
			$ed = date('Y-m-d',strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
			$f = new DateViewFilter('end_date', $format, $ed);
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);

			// Assessor Filter
			$options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE tr.assessor=' , char(39),id, char(39)) FROM users WHERE type=3 ORDER BY firstnames,surname";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			// Tutor Filter
			$options = "SELECT id, CONCAT(firstnames,' ',surname), LEFT(firstnames, 1), CONCAT('WHERE tr.tutor=' , char(39),id, char(39)) FROM users WHERE type=2 ORDER BY firstnames,surname";
			$f = new DropDownViewFilter('filter_tutor', $options, null, true);
			$f->setDescriptionFormat("Tutor: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}


	public function render(PDO $link, $view)
	{
		$this->firstSetData = "";
		$this->secondSetData = "";
		$this->thirdSetData = "";
		$this->fourthSetData = "";
		$this->fifthSetData = "";
		$this->sixthSetData = "";
        $this->seventhSetData = "";
        $this->eighthSetData = "";

		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		//$st=$link->query("call view_training_providers();");
		if($st)
		{
			//echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" width="400" border="0" cellspacing="0" cellpadding="5">';
			//echo '<thead height="40px"><tr><th width="100px">Date</th><th width="70px">Required</th><th width="70px">Planned</th><th width="70px">To plan</th></tr></thead>';

			echo '<tbody>';
			echo '<tr><td colspan="3"><img onclick="exportMonthlyReport(0);" src="/images/excel_export.gif" /></td></tr>';
			while($row = $st->fetch())
			{
				// New Starts
				$start_date = Date::toMySQL($this->getFilterValue('start_date'));
				$end_date = Date::toMySQL($this->getFilterValue('end_date'));
				$employer = $this->getFilterValue('filter_employer');
				$provider = $this->getFilterValue('filter_provider');
				$brand = $this->getFilterValue('filter_manufacturer');
				$assessor = $this->getFilterValue('filter_assessor');
				$tutor = $this->getFilterValue('filter_tutor');

				$where = '';
				if($employer!='')
				{
					$where = " and employers.id = '$employer' ";
				}
				if($provider!='')
				{
					$where .= " and providers.id = '$provider' ";
				}
				if($_SESSION['user']->type==20)
				{
					$id = $_SESSION['user']->id;
					$where .= " and tr.programme = '$id' ";
				}
				if($brand!='')
				{
					$where = " and tr.employer_id in (select id from organisations where manufacturer = '$brand') ";
				}
				if($assessor!='')
				{
					$where = " and tr.assessor = '$assessor' ";
				}
				if($tutor!='')
				{
					$where = " and tr.tutor = '$tutor' ";
				}

                // Startters
				$starters = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id where start_date >= '$start_date' and start_date<='$end_date' $where");
				$this->firstSetData = "Number of Starters, {$starters}\nL03, First Name, Surname, Start Date, Planned End Date, Contract, Framework, Employer, Provider, Assessor";
				echo '<tr><td  class="dealer" align="left" onclick="details(1);" colspan="3">' . HTML::cell('No of Starters') . '</td>';
				echo '<td  class="dealer" align="right" onclick="details(1);" colspan="1">' . HTML::cell($starters) . '</td><td><button onclick="exportMonthlyReport(1);" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button></td></tr>';
				echo $this->getSub($link, "SELECT frameworks.`title` AS title, COUNT(tr.id) AS learners FROM tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.`framework_id` LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr ON ilr.contract_id = tr.contract_id 	AND ilr.tr_id = tr.id 	AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) WHERE tr.start_date >= '$start_date' AND tr.start_date<='$end_date' $where GROUP BY frameworks.`title`;",1);
				echo '<tr><td colspan=4>';
				echo '<div style = "display: none" id="1">';
				echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
				$st2 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title, student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as employers on employers.id = tr.employer_id  left join organisations as providers on providers.id = tr.provider_id  left join  users on users.id = tr.assessor LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) where tr.start_date >= '$start_date' and tr.start_date<='$end_date' $where");
				echo '<thead><tr><th>L03</th><th>Firstname</th><th>Surname</th><th>Start Date</th><th>Planned End Date</th><th>Contract</th><th>Framework</th><th>Employer</th><th>Provider</th><th>Assessor</th></tr></thead>';
				while($row2 = $st2->fetch())
				{
					echo '<tr class="">';

					if($row2['funding_body']==1)
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_lr_ilr'. $row2['contract_year'] . '&submission=' . $row2['submission'] . '&contract_id=' . $row2['contract_id'] . '&tr_id=' . $row2['tr_id'] . '&L03=' . $row2['l03'] . '">' .  $row2['l03'] . '</a></td>';
					else
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_ilr'. $row2['contract_year'] . '&submission=' . $row2['submission'] . '&contract_id=' . $row2['contract_id'] . '&tr_id=' . $row2['tr_id'] . '&L03=' . $row2['l03'] . '">' .  $row2['l03'] . '</a></td>';

					echo '<td width="100px" align="left">' . $row2['firstnames'] . '</td>';
					echo '<td width="70px" align="left"><a href="do.php?_action=read_training_record&id=' . $row2['tr_id'] . '">' .  HTML::cell($row2['surname']) . '</a></td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row2['start_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row2['target_date'])) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row2['title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row2['framework_title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row2['employer']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row2['provider']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row2['assessor']) . '</td>';

					$this->firstSetData .= "\n" . $row2['l03'] . "," . $row2['firstnames'] . "," . $row2['surname'] . "," . $row2['start_date'] . "," . $row2['target_date'] . "," . $row2['title'] . "," . $row2['framework_title'] . "," . $row2['employer'] . "," . $row2['provider'] . "," . $row2['assessor'];

					echo '</tr>';
				}
				echo '</table>';
				echo '</div>';
				//		echo '</td></tr>';


                // Restarters
                $starters = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id where start_date >= '$start_date' and start_date<='$end_date' and tr.id in (SELECT DISTINCT tr_id FROM ilr WHERE start_date >= '$start_date' AND start_date<='$end_date' AND extractvalue(ilr,'/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType=\'RES\' and LearnDelFAMCode=\'1\']/LearnDelFAMType') LIKE '%RES%')  $where");
                $this->eighthSetData = "Number of Re-Starters, {$starters}\nL03, First Name, Surname, Start Date, Planned End Date, Contract, Framework, Employer, Provider, Assessor";
                echo '<tr><td  class="dealer" align="left" onclick="details(8);" colspan="3">' . HTML::cell('No of Re-Starters') . '</td>';
                echo '<td  class="dealer" align="right" onclick="details(8);" colspan="1">' . HTML::cell($starters) . '</td><td><button onclick="exportMonthlyReport(8);" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button></td></tr>';
                echo $this->getSub($link, "SELECT frameworks.`title` AS title, COUNT(tr.id) AS learners FROM tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.`framework_id` LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr ON ilr.contract_id = tr.contract_id 	AND ilr.tr_id = tr.id 	AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) WHERE tr.start_date >= '$start_date' AND tr.start_date<='$end_date' and tr.id in (SELECT DISTINCT tr_id FROM ilr WHERE tr.start_date >= '$start_date' AND tr.start_date<='$end_date' AND extractvalue(ilr,'/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType=\'RES\' and LearnDelFAMCode=\'1\']/LearnDelFAMType') LIKE '%RES%') $where GROUP BY frameworks.`title`;",8);
                echo '<tr><td colspan=4>';
                echo '<div style = "display: none" id="8">';
                echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
                $st2 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title, student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as employers on employers.id = tr.employer_id  left join organisations as providers on providers.id = tr.provider_id  left join  users on users.id = tr.assessor LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) where tr.start_date >= '$start_date' and tr.start_date<='$end_date' and tr.id in (SELECT DISTINCT tr_id FROM ilr WHERE tr.start_date >= '$start_date' AND tr.start_date<='$end_date' AND extractvalue(ilr,'/Learner/LearningDelivery/LearningDeliveryFAM[LearnDelFAMType=\'RES\' and LearnDelFAMCode=\'1\']/LearnDelFAMType') LIKE '%RES%') $where");
                echo '<thead><tr><th>L03</th><th>Firstname</th><th>Surname</th><th>Start Date</th><th>Planned End Date</th><th>Contract</th><th>Framework</th><th>Employer</th><th>Provider</th><th>Assessor</th></tr></thead>';
                while($row2 = $st2->fetch())
                {
                    echo '<tr class="">';

                    if($row2['funding_body']==1)
                        echo '<td width="100px" align="center"><a href="do.php?_action=edit_lr_ilr'. $row2['contract_year'] . '&submission=' . $row2['submission'] . '&contract_id=' . $row2['contract_id'] . '&tr_id=' . $row2['tr_id'] . '&L03=' . $row2['l03'] . '">' .  $row2['l03'] . '</a></td>';
                    else
                        echo '<td width="100px" align="center"><a href="do.php?_action=edit_ilr'. $row2['contract_year'] . '&submission=' . $row2['submission'] . '&contract_id=' . $row2['contract_id'] . '&tr_id=' . $row2['tr_id'] . '&L03=' . $row2['l03'] . '">' .  $row2['l03'] . '</a></td>';

                    echo '<td width="100px" align="left">' . $row2['firstnames'] . '</td>';
                    echo '<td width="70px" align="left"><a href="do.php?_action=read_training_record&id=' . $row2['tr_id'] . '">' .  HTML::cell($row2['surname']) . '</a></td>';
                    echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row2['start_date'])) . '</td>';
                    echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row2['target_date'])) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row2['title']) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row2['framework_title']) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row2['employer']) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row2['provider']) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row2['assessor']) . '</td>';

                    $this->eighthSetData .= "\n" . $row2['l03'] . "," . $row2['firstnames'] . "," . $row2['surname'] . "," . $row2['start_date'] . "," . $row2['target_date'] . "," . $row2['title'] . "," . $row2['framework_title'] . "," . $row2['employer'] . "," . $row2['provider'] . "," . $row2['assessor'];

                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
                //		echo '</td></tr>';

				// Active
				$start_date = Date::toMySQL($this->getFilterValue('start_date'));
				$end_date = Date::toMySQL($this->getFilterValue('end_date'));
				$active = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id where start_date >= '$start_date' and start_date<='$end_date' and status_code = 1 $where");
				echo '<tr><td  class="dealer" align="left" onclick="details(5);" colspan="3">' . HTML::cell('No of starters currently active') . '</td>';
				echo '<td  class="dealer" align="right" onclick="details(5);" colspan="1">' . HTML::cell($active) . '</td><td><button onclick="exportMonthlyReport(2);" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button></td></tr>';

				$this->secondSetData = "Number of Starters Currently Active, {$active}\nL03, First Name, Surname, Start Date, Planned End Date, Contract, Framework, Employer, Provider, Assessor";

				echo $this->getSub($link, "SELECT frameworks.`title` AS title, COUNT(tr.id) AS learners FROM tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.`framework_id` LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr ON ilr.contract_id = tr.contract_id 	AND ilr.tr_id = tr.id 	AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) WHERE status_code = 1 and tr.start_date >= '$start_date' AND tr.start_date<='$end_date' $where GROUP BY frameworks.`title`;",2);
				echo '<tr><td colspan=4>';
				echo '<div style = "display: none" id="5">';
				echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
				$st5 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title,student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as providers on providers.id = tr.provider_id left join  users on users.id = tr.assessor left join organisations as employers on employers.id = tr.employer_id  LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) where tr.start_date >= '$start_date' and tr.start_date<='$end_date' and status_code = 1 $where");
				echo '<thead><tr><th>L03</th><th>Firstname</th><th>Surname</th><th>Start Date</th><th>Planned End Date</th><th>Contract</th><th>Framework</th><th>Employer</th><th>Provider</th><th>Assessor</th></tr></thead>';
				while($row5 = $st5->fetch())
				{
					echo '<tr class="">';

					if($row5['funding_body']==1)
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_lr_ilr'. $row5['contract_year'] . '&submission=' . $row5['submission'] . '&contract_id=' . $row5['contract_id'] . '&tr_id=' . $row5['tr_id'] . '&L03=' . $row5['l03'] . '">' .  $row5['l03'] . '</a></td>';
					else
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_ilr'. $row5['contract_year'] . '&submission=' . $row5['submission'] . '&contract_id=' . $row5['contract_id'] . '&tr_id=' . $row5['tr_id'] . '&L03=' . $row5['l03'] . '">' .  $row5['l03'] . '</a></td>';

					echo '<td width="100px" align="left">' . $row5['firstnames'] . '</td>';
					echo '<td width="70px" align="left"><a href="do.php?_action=read_training_record&id=' . $row5['tr_id'] . '">' .  HTML::cell($row5['surname']) . '</a></td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row5['start_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row5['target_date'])) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['framework_title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['employer']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['provider']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['assessor']) . '</td>';

					$this->secondSetData .= "\n" . $row5['l03'] . "," . $row5['firstnames'] . "," . $row5['surname'] . "," . $row5['start_date'] . "," . $row5['target_date'] . "," . $row5['title'] . "," . $row5['framework_title'] . "," . $row5['employer'] . "," . $row5['provider'] . "," . $row5['assessor'];

					echo '</tr>';
				}
				echo '</table>';
				echo '</div>';
				//		echo '</td></tr>';

				// Planned to finish but active
				$start_date = Date::toMySQL($this->getFilterValue('start_date'));
				$end_date = Date::toMySQL($this->getFilterValue('end_date'));
				$starters = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id where tr.status_code = 1 and target_date >= '$start_date' and target_date<='$end_date' $where");
				echo '<tr><td  class="dealer" align="left" onclick="details(2);" colspan="3">' . HTML::cell('No of learners planned to finish but are still active') . '</td>';
				echo '<td  class="dealer" align="right" onclick="details(2);" colspan="1">' . HTML::cell($starters) . '</td><td><button onclick="exportMonthlyReport(3);" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button></td></tr>';

				$this->thirdSetData = "No of learners planned to finish but are still active, {$starters}\nL03, First Name, Surname, Start Date, Planned End Date, Contract, Framework, Employer, Provider, Assessor";

				echo $this->getSub($link, "SELECT frameworks.`title` AS title, COUNT(tr.id) AS learners FROM tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.`framework_id` LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr ON ilr.contract_id = tr.contract_id 	AND ilr.tr_id = tr.id 	AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) WHERE tr.status_code = 1 and tr.target_date >= '$start_date' and tr.target_date<='$end_date' $where GROUP BY frameworks.`title`;",3);
				echo '<tr><td colspan=4>';
				echo '<div style = "display: none" id="2">';
				echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
				$st3 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title , contracts.title, student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as providers on providers.id = tr.provider_id left join  users on users.id = tr.assessor left join organisations as employers on employers.id = tr.employer_id LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) where tr.status_code = 1 and tr.target_date >= '$start_date' and tr.target_date<='$end_date' $where");
				echo '<thead><tr><th>L03</th><th>Firstname</th><th>Surname</th><th>Start Date</th><th>Planned End Date</th><th>Contract</th><th>Framework</th><th>Employer</th><th>Provider</th><th>Assessor</th></tr></thead>';
				while($row3 = $st3->fetch())
				{
					echo '<tr class="">';

					if($row3['funding_body']==1)
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_lr_ilr'. $row3['contract_year'] . '&submission=' . $row3['submission'] . '&contract_id=' . $row3['contract_id'] . '&tr_id=' . $row3['tr_id'] . '&L03=' . $row3['l03'] . '">' .  $row3['l03'] . '</a></td>';
					else
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_ilr'. $row3['contract_year'] . '&submission=' . $row3['submission'] . '&contract_id=' . $row3['contract_id'] . '&tr_id=' . $row3['tr_id'] . '&L03=' . $row3['l03'] . '">' .  $row3['l03'] . '</a></td>';

					echo '<td width="100px" align="left">' . $row3['firstnames'] . '</td>';
					echo '<td width="70px" align="left"><a href="do.php?_action=read_training_record&id=' . $row3['tr_id'] . '">' .  HTML::cell($row3['surname']) . '</a></td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row3['start_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row3['target_date'])) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['framework_title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['employer']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['provider']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['assessor']) . '</td>';
					echo '</tr>';

					$this->thirdSetData .= "\n" . $row3['l03'] . "," . $row3['firstnames'] . "," . $row3['surname'] . "," . $row3['start_date'] . "," . $row3['target_date'] . "," . $row3['title'] . "," . $row3['framework_title'] . "," . $row3['employer'] . "," . $row3['provider'] . "," . $row3['assessor'];
				}
				echo '</table>';
				echo '</div>';
				//		echo '</td></tr>';



				// Planned to finish and finished
				$start_date = Date::toMySQL($this->getFilterValue('start_date'));
				$end_date = Date::toMySQL($this->getFilterValue('end_date'));
				$starters = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id where tr.status_code!=1 and target_date >= '$start_date' and target_date<='$end_date' $where");
				echo '<tr><td  class="dealer" align="left" onclick="details(6);" colspan="3">' . HTML::cell('No of learners planned to finish and have finished learning') . '</td>';
				echo '<td  class="dealer" align="right" onclick="details(6);" colspan="1">' . HTML::cell($starters) . '</td><td><button onclick="exportMonthlyReport(4);" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button></td></tr>';

				$this->fourthSetData = "No of learners planned to finish and have finished learning, {$starters}\nL03, First Name, Surname, Start Date, Planned End Date, Actual End Date, Contract, Framework, Employer, Provider, Assessor";

				echo $this->getSub($link, "SELECT frameworks.`title` AS title, COUNT(tr.id) AS learners FROM tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.`framework_id` LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr ON ilr.contract_id = tr.contract_id 	AND ilr.tr_id = tr.id 	AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) WHERE tr.status_code != 1 and tr.target_date >= '$start_date' and tr.target_date<='$end_date' $where GROUP BY frameworks.`title`;",4);
				echo '<tr><td colspan=4>';
				echo '<div style = "display: none" id="6">';
				echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
				$st3 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title, contracts.title, student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer  from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as providers on providers.id = tr.provider_id left join  users on users.id = tr.assessor left join organisations as employers on employers.id = tr.employer_id LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) where tr.status_code != 1 and tr.target_date >= '$start_date' and tr.target_date<='$end_date' $where");
				echo '<thead><tr><th>L03</th><th>Firstname</th><th>Surname</th><th>Start Date</th><th>Planned End Date</th><th>Actual End Date</th><th>Contract</th><th>Framework</th><th>Employer</th><th>Provider</th><th>Assessor</th></tr></thead>';
				while($row3 = $st3->fetch())
				{
					echo '<tr class="">';

					if($row3['funding_body']==1)
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_lr_ilr'. $row3['contract_year'] . '&submission=' . $row3['submission'] . '&contract_id=' . $row3['contract_id'] . '&tr_id=' . $row3['tr_id'] . '&L03=' . $row3['l03'] . '">' .  $row3['l03'] . '</a></td>';
					else
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_ilr'. $row3['contract_year'] . '&submission=' . $row3['submission'] . '&contract_id=' . $row3['contract_id'] . '&tr_id=' . $row3['tr_id'] . '&L03=' . $row3['l03'] . '">' .  $row3['l03'] . '</a></td>';

					echo '<td width="100px" align="left">' . $row3['firstnames'] . '</td>';
					echo '<td width="70px" align="left"><a href="do.php?_action=read_training_record&id=' . $row3['tr_id'] . '">' .  HTML::cell($row3['surname']) . '</a></td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row3['start_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row3['target_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row3['closure_date'])) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['framework_title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['employer']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['provider']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row3['assessor']) . '</td>';
					echo '</tr>';

					$this->fourthSetData .= "\n" . $row3['l03'] . "," . $row3['firstnames'] . "," . $row3['surname'] . "," . $row3['start_date'] . "," . $row3['target_date'] . "," . $row3['closure_date'] . "," . $row3['title'] . "," . $row3['framework_title'] . "," . $row3['employer'] . "," . $row3['provider'] . "," . $row3['assessor'];
				}
				echo '</table>';
				echo '</div>';
				//		echo '</td></tr>';


				// Withdrawn
				$start_date = Date::toMySQL($this->getFilterValue('start_date'));
				$end_date = Date::toMySQL($this->getFilterValue('end_date'));
				$starters = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id where status_code = 3 and closure_date >= '$start_date' and closure_date<='$end_date' $where");
				echo '<tr><td  class="dealer" align="left" onclick="details(3);" colspan="3">' . HTML::cell('No of withdrawn learners') . '</td>';
				echo '<td  class="dealer" align="right" onclick="details(3);" colspan="1">' . HTML::cell($starters) . '</td><td><button onclick="exportMonthlyReport(5);" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button></td></tr>';

				$this->fifthSetData = "No of withdrawn learners, {$starters}\nL03, First Name, Surname, Start Date, Planned End Date, Actual End Date, Contract, Framework, Employer, Provider, Assessor";

				echo $this->getSub($link, "SELECT frameworks.`title` AS title, COUNT(tr.id) AS learners FROM tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.`framework_id` LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr ON ilr.contract_id = tr.contract_id 	AND ilr.tr_id = tr.id 	AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) WHERE tr.status_code = 3 and tr.closure_date >= '$start_date' and tr.closure_date<='$end_date' $where GROUP BY frameworks.`title`;",5);
				echo '<tr><td colspan=4>';
				echo '<div style = "display: none" id="3">';
				echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
				$st4 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title, contracts.title, student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as providers on providers.id = tr.provider_id left join  users on users.id = tr.assessor left join organisations as employers on employers.id = tr.employer_id LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) where tr.status_code = 3 and tr.closure_date >= '$start_date' and tr.closure_date<='$end_date' $where");
				echo '<thead><tr><th>L03</th><th>Firstname</th><th>Surname</th><th>Start Date</th><th>Planned End Date</th><th>Actual End Date</th><th>Contract</th><th>Framework</th><th>Employer</th><th>Provider</th><th>Assessor</th></tr></thead>';
				while($row4 = $st4->fetch())
				{
					echo '<tr class="">';

					if($row4['funding_body']==1)
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_lr_ilr'. $row4['contract_year'] . '&submission=' . $row4['submission'] . '&contract_id=' . $row4['contract_id'] . '&tr_id=' . $row4['tr_id'] . '&L03=' . $row4['l03'] . '">' .  $row4['l03'] . '</a></td>';
					else
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_ilr'. $row4['contract_year'] . '&submission=' . $row4['submission'] . '&contract_id=' . $row4['contract_id'] . '&tr_id=' . $row4['tr_id'] . '&L03=' . $row4['l03'] . '">' .  $row4['l03'] . '</a></td>';

					echo '<td width="100px" align="left">' . $row4['firstnames'] . '</td>';
					echo '<td width="70px" align="left"><a href="do.php?_action=read_training_record&id=' . $row4['tr_id'] . '">' .  HTML::cell($row4['surname']) . '</a></td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row4['start_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row4['target_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row4['closure_date'])) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row4['title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row4['framework_title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row4['employer']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row4['provider']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row4['assessor']) . '</td>';
					echo '</tr>';

					$this->fifthSetData .= "\n" . $row4['l03'] . "," . $row4['firstnames'] . "," . $row4['surname'] . "," . $row4['start_date'] . "," . $row4['target_date'] . "," . $row4['closure_date'] . "," . $row4['title'] . "," . $row4['framework_title'] . "," . $row4['employer'] . "," . $row4['provider'] . "," . $row4['assessor'];
				}
				echo '</table>';
				echo '</div>';
				echo '</td></tr>';



                // Temporarily Withdrawn
                $start_date = Date::toMySQL($this->getFilterValue('start_date'));
                $end_date = Date::toMySQL($this->getFilterValue('end_date'));
                $starters = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id where status_code = 6 and closure_date >= '$start_date' and closure_date<='$end_date' $where");
                echo '<tr><td  class="dealer" align="left" onclick="details(7);" colspan="3">' . HTML::cell('No of break in learning learners') . '</td>';
                echo '<td  class="dealer" align="right" onclick="details(7);" colspan="1">' . HTML::cell($starters) . '</td><td><button onclick="exportMonthlyReport(7);" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button></td></tr>';

                $this->seventhSetData = "No of break in learning learners, {$starters}\nL03, First Name, Surname, Start Date, Planned End Date, Actual End Date, Contract, Framework, Employer, Provider, Assessor";

                echo $this->getSub($link, "SELECT frameworks.`title` AS title, COUNT(tr.id) AS learners FROM tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.`framework_id` LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr ON ilr.contract_id = tr.contract_id 	AND ilr.tr_id = tr.id 	AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) WHERE tr.status_code = 6 and tr.closure_date >= '$start_date' and tr.closure_date<='$end_date' $where GROUP BY frameworks.`title`;",7);
                echo '<tr><td colspan=4>';
                echo '<div style = "display: none" id="7">';
                echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
                $st4 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title, contracts.title, student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as providers on providers.id = tr.provider_id left join  users on users.id = tr.assessor left join organisations as employers on employers.id = tr.employer_id LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) where tr.status_code = 6 and tr.closure_date >= '$start_date' and tr.closure_date<='$end_date' $where");
                echo '<thead><tr><th>L03</th><th>Firstname</th><th>Surname</th><th>Start Date</th><th>Planned End Date</th><th>Actual End Date</th><th>Contract</th><th>Framework</th><th>Employer</th><th>Provider</th><th>Assessor</th></tr></thead>';
                while($row4 = $st4->fetch())
                {
                    echo '<tr class="">';

                    if($row4['funding_body']==1)
                        echo '<td width="100px" align="center"><a href="do.php?_action=edit_lr_ilr'. $row4['contract_year'] . '&submission=' . $row4['submission'] . '&contract_id=' . $row4['contract_id'] . '&tr_id=' . $row4['tr_id'] . '&L03=' . $row4['l03'] . '">' .  $row4['l03'] . '</a></td>';
                    else
                        echo '<td width="100px" align="center"><a href="do.php?_action=edit_ilr'. $row4['contract_year'] . '&submission=' . $row4['submission'] . '&contract_id=' . $row4['contract_id'] . '&tr_id=' . $row4['tr_id'] . '&L03=' . $row4['l03'] . '">' .  $row4['l03'] . '</a></td>';

                    echo '<td width="100px" align="left">' . $row4['firstnames'] . '</td>';
                    echo '<td width="70px" align="left"><a href="do.php?_action=read_training_record&id=' . $row4['tr_id'] . '">' .  HTML::cell($row4['surname']) . '</a></td>';
                    echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row4['start_date'])) . '</td>';
                    echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row4['target_date'])) . '</td>';
                    echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row4['closure_date'])) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row4['title']) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row4['framework_title']) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row4['employer']) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row4['provider']) . '</td>';
                    echo '<td width="70px" align="left">' . HTML::cell($row4['assessor']) . '</td>';
                    echo '</tr>';

                    $this->seventhSetData .= "\n" . $row4['l03'] . "," . $row4['firstnames'] . "," . $row4['surname'] . "," . $row4['start_date'] . "," . $row4['target_date'] . "," . $row4['closure_date'] . "," . $row4['title'] . "," . $row4['framework_title'] . "," . $row4['employer'] . "," . $row4['provider'] . "," . $row4['assessor'];
                }
                echo '</table>';
                echo '</div>';
                echo '</td></tr>';


				// Achievers
				$start_date = Date::toMySQL($this->getFilterValue('start_date'));
				$end_date = Date::toMySQL($this->getFilterValue('end_date'));
				if(DB_NAME=="am_edudo")
					$starters = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id INNER JOIN student_qualifications ON student_qualifications.tr_id = tr.id where status_code = 2 and student_qualifications.achievement_date >= '$start_date' and student_qualifications.achievement_date<='$end_date' $where");
				else
					$starters = DAO::getSingleValue($link, "SELECT COUNT(*) from tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id where status_code = 2 and closure_date >= '$start_date' and closure_date<='$end_date' $where");
				echo '<tr><td  class="dealer" align="left" onclick="details(4);" colspan="3">' . HTML::cell('No of achievers') . '</td>';
				echo '<td  class="dealer" align="right" onclick="details(4);" colspan="1">' . HTML::cell($starters) . '</td><td><button onclick="exportMonthlyReport(6);" title="Export to .CSV file"><img src="/images/btn-excel.gif" width="16" height="16" style="vertical-align:text-bottom" /></button></td></tr>';

				$this->sixthSetData = "No of achievers, {$starters}\nL03, First Name, Surname, Start Date, Planned End Date, Actual End Date, Contract, Framework, Employer, Provider, Assessor";

				echo $this->getSub($link, "SELECT frameworks.`title` AS title, COUNT(tr.id) AS learners FROM tr left join organisations as employers on employers.id = tr.employer_id left join organisations as providers on providers.id = tr.provider_id LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.`id` LEFT JOIN courses ON courses.id = courses_tr.`course_id` LEFT JOIN frameworks ON frameworks.id = courses.`framework_id` LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr ON ilr.contract_id = tr.contract_id 	AND ilr.tr_id = tr.id 	AND ilr.submission = (SELECT MAX(submission) FROM ilr WHERE tr_id = tr.id AND contract_id =  tr.contract_id) WHERE tr.status_code = 2 and tr.closure_date >= '$start_date' and tr.closure_date<='$end_date' $where GROUP BY frameworks.`title`;",6);
				echo '<tr><td colspan=4>';
				echo '<div style = "display: none" id="4">';
				echo '<table class="resultset" border="0" cellspacing="0" cellpadding="5">';
				if(DB_NAME=="am_edudo")
					$st5 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title, contracts.title, student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as providers on providers.id = tr.provider_id left join  users on users.id = tr.assessor left join organisations as employers on employers.id = tr.employer_id LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) INNER JOIN student_qualifications ON student_qualifications.tr_id = tr.id where tr.status_code = 2 and student_qualifications.achievement_date >= '$start_date' and student_qualifications.achievement_date<='$end_date' $where ");
				else
					$st5 = $link->query("SELECT tr.*,contracts.funding_body, contracts.contract_year, ilr.submission, ilr.tr_id, contracts.title, contracts.title, student_frameworks.title as framework_title,providers.legal_name as provider, concat(users.firstnames,users.surname) as assessor, employers.legal_name as employer from tr left join student_frameworks on student_frameworks.tr_id = tr.id left join organisations as providers on providers.id = tr.provider_id left join  users on users.id = tr.assessor left join organisations as employers on employers.id = tr.employer_id LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN ilr on ilr.contract_id = tr.contract_id and ilr.tr_id = tr.id and ilr.submission = (select max(submission) from ilr where tr_id = tr.id AND contract_id =  tr.contract_id) where tr.status_code = 2 and tr.closure_date >= '$start_date' and tr.closure_date<='$end_date' $where ");
				echo '<thead><tr><th>L03</th><th>Firstname</th><th>Surname</th><th>Start Date</th><th>Planned End Date</th><th>Actual End Date</th><th>Contract</th><th>Framework</th><th>Employer</th><th>Provider</th><th>Assessor</th></tr></thead>';
				while($row5 = $st5->fetch())
				{
					echo '<tr class="">';

					if($row5['funding_body']==1)
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_lr_ilr'. $row5['contract_year'] . '&submission=' . $row5['submission'] . '&contract_id=' . $row5['contract_id'] . '&tr_id=' . $row5['tr_id'] . '&L03=' . $row5['l03'] . '">' .  $row5['l03'] . '</a></td>';
					else
						echo '<td width="100px" align="center"><a href="do.php?_action=edit_ilr'. $row5['contract_year'] . '&submission=' . $row5['submission'] . '&contract_id=' . $row5['contract_id'] . '&tr_id=' . $row5['tr_id'] . '&L03=' . $row5['l03'] . '">' .  $row5['l03'] . '</a></td>';

					echo '<td width="100px" align="left">' . $row5['firstnames'] . '</td>';
					echo '<td width="70px" align="left"><a href="do.php?_action=read_training_record&id=' . $row5['tr_id'] . '">' .  HTML::cell($row5['surname']) . '</a></td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row5['start_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row5['target_date'])) . '</td>';
					echo '<td width="70px" align="center">' . HTML::cell(Date::toShort($row5['closure_date'])) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['framework_title']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['employer']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['provider']) . '</td>';
					echo '<td width="70px" align="left">' . HTML::cell($row5['assessor']) . '</td>';
					echo '</tr>';

					$this->sixthSetData .= "\n" . $row5['l03'] . "," . $row5['firstnames'] . "," . $row5['surname'] . "," . $row5['start_date'] . "," . $row5['target_date'] . "," . $row5['closure_date'] . "," . $row5['title'] . "," . $row5['framework_title'] . "," . $row5['employer'] . "," . $row5['provider'] . "," . $row5['assessor'];
				}
				echo '</table>';
				echo '</div>';
				echo '</td></tr>';

			}

			echo '</tbody></table></div>';
			//echo $this->getViewNavigator();

		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}

	public function getSub($link, $sql, $id)
	{
		$html = '';
		$html .= '<tr class = "dealer2" style = "display:none" id = "subs' . $id . '"><td colspan=6>';
		$html .= '<table border="0" cellspacing="0" cellpadding="5">';
		$sub = $link->query($sql);
		while($rowsub = $sub->fetch())
		{
			$html .= '<tr class="dealer2"><td width=100% align=left>' . $rowsub['title'] . '</td><td>' . $rowsub['learners'] . '</td></tr>';
		}
		$html .= '</td></tr></table>';
		return $html;
	}
}
?>