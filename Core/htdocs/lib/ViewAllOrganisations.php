<?php
class ViewAllOrganisations extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;

		if($_SESSION['user']->type==3 || $_SESSION['user']->type==2 || $_SESSION['user']->type==4)
		{
			$where = ' where organisation_type = 2';
		}
		elseif($_SESSION['user']->type==8)
		{
			$org_id = $_SESSION['user']->employer_id;
			$where = " where parent_org = '$org_id'";
		}
		else
			$where = '';

		if(!isset($_SESSION[$key]))
		{
			$sql = <<<HEREDOC
	SELECT
		organisations.id,
		organisations.legal_name,
		organisation_type, locations.address_line_3,
		lookup_org_type.org_type,
		CONCAT(COALESCE(locations.full_name), ' (',COALESCE(locations.address_line_1,''),' ',COALESCE(locations.address_line_2,''),', ',COALESCE(locations.postcode,''), ')') AS main_site,
		organisations.active AS active_org
		
	FROM
		organisations 
		LEFT JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
		LEFT JOIN lookup_org_type on lookup_org_type.id = organisations.organisation_type
	ORDER BY organisations.legal_name
	$where
HEREDOC;

			$view = $_SESSION[$key] = new ViewAllOrganisations();
			$view->setSQL($sql);

			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			// Dealer Name Filter 	
			$f = new TextboxViewFilter('filter_legal_name', "WHERE organisations.legal_name LIKE '%%%s%%'", null);
			$f->setDescriptionFormat("Organisation Name contains: %s");
			$view->addFilter($f);

			$options = "SELECT id, org_type, null, CONCAT('WHERE lookup_org_type.id=',char(39),id,char(39)) FROM lookup_org_type ORDER BY org_type";
			$f = new DropDownViewFilter('filter_org_type', $options, null, true);
			$f->setDescriptionFormat("Organisation Type: %s");
			$view->addFilter($f);

		}

		return $_SESSION[$key];
	}


	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		//$st=$link->query("call view_training_providers();");
		if($st)
		{
			echo $this->getViewNavigator();
			echo '<div class="table-responsive"> <table id="tblAllOrganisations" class="table table-bordered">';
			echo '<thead><tr><th>Organisation Type</th><th>Organisation Name</th><th>Town/City</th><th>Main Site</th></tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch())
			{
				$class = $row['active_org'] != '1' ? 'text-muted' : '';
				switch($row['organisation_type'])
				{
					case 1:
						$soid = DAO::getSingleValue($link, "select id from organisations where organisation_type='1'");
						echo HTML::viewrow_opening_tag('/do.php?_action=read_system_owner&id=' . $soid, $class);
						break;
					case 2:
						echo SystemConfig::getEntityValue($link, 'module_recruitment_v2')?HTML::viewrow_opening_tag('/do.php?_action=rec_read_employer&id=' . $row['id'], $class):HTML::viewrow_opening_tag('/do.php?_action=read_employer&id=' . $row['id'], $class);
						break;
					case 3:
						echo HTML::viewrow_opening_tag('/do.php?_action=read_trainingprovider&id=' . $row['id'], $class);
						break;
					case 4:
						echo HTML::viewrow_opening_tag('/do.php?_action=read_contractholder&id=' . $row['id'], $class);
						break;
					case 5:
						echo HTML::viewrow_opening_tag('/do.php?_action=read_subcontractor&id=' . $row['id'], $class);
						break;
					case 6:
						echo HTML::viewrow_opening_tag('/do.php?_action=read_school&id=' . $row['id'], $class);
						break;
					case 7:
						echo HTML::viewrow_opening_tag('/do.php?_action=read_college&id=' . $row['id'], $class);
						break;
					case 22:
					case 33:
						echo HTML::viewrow_opening_tag('/do.php?_action=read_organisation&id=' . $row['id'], $class);
						break;
					default:
						echo HTML::viewrow_opening_tag('/do.php?_action=read_workplace&id=' . $row['id'], $class);
						break;
				}

				echo '<td>' 	. HTML::cell($row['org_type']) . '</td>';
				echo '<td>' 	. HTML::cell($row['legal_name']) . '</td>';
				echo '<td>' 	. HTML::cell($row['address_line_3']) . '</td>';
				echo '<td>' 	. HTML::cell($row['main_site']) . '</td>';
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