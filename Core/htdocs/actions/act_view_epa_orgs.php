<?php
class view_epa_orgs implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_epa_orgs", "View EPA Organisations");

		$view = VoltView::getViewFromSession('ViewEPAOrgs', 'ViewEPAOrgs'); /* @var $view VoltView */
		if(is_null($view))
		{
			$view = $_SESSION['ViewEPAOrgs'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'export_csv')
		{
			$this->exportView($link, $view);
			exit;
		}

		include_once('tpl_view_epa_orgs.php');
	}

	private function buildView(PDO $link)
	{
		$sql = new SQLStatement("
SELECT
	central.epa_organisations.*,
	(SELECT COUNT(*) FROM central.epa_orgs_standards WHERE epa_orgs_standards.EPA_ORG_ID = epa_organisations.EPA_ORG_ID) AS Number_of_Standards
FROM
	central.epa_organisations
;
		");

		$view = new VoltView('ViewEPAOrgs', $sql->__toString());

		$f = new VoltTextboxViewFilter('filter_EP_Assessment_Organisations', "WHERE epa_organisations.EP_Assessment_Organisations LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("Org. Name: %s");
		$view->addFilter($f);

		$f = new VoltTextboxViewFilter('filter_EPA_ORG_ID', "WHERE epa_organisations.EPA_ORG_ID LIKE '%%%s%%'", null);
		$f->setDescriptionFormat("ORG ID: %s");
		$view->addFilter($f);

		$options = "SELECT DISTINCT Contact_address4, Contact_address4, null, CONCAT('WHERE epa_organisations.Contact_address4=',char(39),epa_organisations.Contact_address4,char(39)) FROM central.epa_organisations WHERE Contact_address4 != '' ORDER BY Contact_address4";
		$f = new VoltDropDownViewFilter('filter_Contact_address4', $options, null, true);
		$f->setDescriptionFormat("City: %s");
		$view->addFilter($f);

		$options = "SELECT DISTINCT Delivery_Area_1, Delivery_Area_1, null, CONCAT('WHERE epa_organisations.Delivery_Area_1=',char(39),epa_organisations.Delivery_Area_1,char(39)) FROM central.epa_organisations WHERE Delivery_Area_1 != '' ORDER BY Delivery_Area_1";
		$f = new VoltDropDownViewFilter('filter_Delivery_Area_1', $options, null, true);
		$f->setDescriptionFormat("Delivery Area 1: %s");
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
		$f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
//		pr($view->getSQLStatement()->__toString());
		$st = $link->query($view->getSQLStatement()->__toString());
		if($st)
		{
			$columns = array();
			for($i = 0; $i < $st->columnCount(); $i++)
			{
				$column = $st->getColumnMeta($i);
				$columns[] = $column['name'];
			}
			echo $view->getViewNavigatorExtra('', $view->getViewName());
			echo '<div align="center" ><table id="tblEPAOrgs" class="table table-bordered">';
			echo '<thead class="bg-gray"><tr>';
			foreach($columns AS $column)
			{
				echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . '</th>';
			}
			echo '</tr></thead>';
			echo '<tbody>';
			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_epa_org&id='.$row['EPA_ORG_ID']);
				foreach($columns AS $column)
				{
					if($column == 'Link_to_website')
						echo '<td class="small"><a href="' . $row[$column] . '" target="_blank">' . substr($row[$column], 0, 30) . '</a></td>';
					elseif($column == 'Postcode')
						echo '<td><code>' . str_replace(' ', '&nbsp;', $row[$column]) . '</code></td>';
					elseif($column == 'Contact_number')
						echo '<td>' . str_replace(' ', '&nbsp;', $row[$column]) . '</td>';
					elseif($column == 'Contact_Name')
						echo '<td>' . str_replace(' ', '&nbsp;', $row[$column]) . '</td>';
					else
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}
				echo '</tr>';
			}
			echo '</tbody></table></div><p><br></p>';
			echo $view->getViewNavigatorExtra('', $view->getViewName());
		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}

	private function exportView(PDO $link, VoltView $view)
	{
		$statement = $view->getSQLStatement();
		$statement->removeClause('limit');
		$st = $link->query($statement->__toString());
		if($st)
		{
			header("Content-Type: application/vnd.ms-excel");
			header('Content-Disposition: attachment; filename=EPA Organisations.csv');
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
			{
				header('Pragma: public');
				header('Cache-Control: max-age=0');
			}

			$columns = array();
			for($i = 0; $i < $st->columnCount(); $i++)
			{
				$column = $st->getColumnMeta($i);
				$columns[] = $column['name'];
			}
			foreach($columns AS $column)
			{
				echo ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . ',';
			}
			echo "\r\n";

			while($row = $st->fetch(DAO::FETCH_ASSOC))
			{
				foreach($columns AS $column)
				{
					echo ((isset($row[$column]))?(($row[$column]=='')?'':$this->csvSafe($row[$column])):'') . ',';
				}
				echo "\r\n";
			}

		}
		else
		{
			throw new DatabaseException($link, $view->getSQLStatement()->__toString());
		}
	}

	private function csvSafe($value)
	{
		$value = str_replace(',', '; ', $value);
		$value = str_replace(array("\n", "\r"), '', $value);
		$value = str_replace("\t", '', $value);
		$value = str_replace("&nbsp;", '', $value);
		$value = '"' . str_replace('"', '""', $value) . '"';
		return $value;
	}
}