<?php
class report1 implements IAction
{
	function sortArrayByArray(Array $array, Array $orderArray) {
		$ordered = array();
		foreach($orderArray as $key) {
			if(array_key_exists($key,$array)) {
				$ordered[$key] = $array[$key];
				unset($array[$key]);
			}
		}
		return $ordered + $array;
	}


	public function execute(PDO $link)
	{
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';

		$sql = <<<SQL
SELECT DISTINCT
  frameworks.id,
  frameworks.`title`,
  frameworks.`framework_code`
FROM
  frameworks
  INNER JOIN framework_qualifications
    ON frameworks.id = framework_qualifications.`framework_id`
ORDER BY frameworks.`framework_code`,
  frameworks.`title`
;
SQL;
		$frameworksDDL = DAO::getResultset($link, $sql);

		$text_html = 'Select framework from the dropdown list and press Enter';
		if($framework_id != '')
		{
			$text_html = $this->generate($link, $framework_id);
		}

		if(isset($_REQUEST['export']) && $_REQUEST['export'] == 'excel')
		{
			$this->exportToExcel($text_html);
			exit;
		}

		include('tpl_report1.php');

	}

	private function generate(PDO $link, $framework_id)
	{

		$frameworks = DAO::getResultset($link, "SELECT frameworks.`framework_code`, frameworks.`title`, framework_qualifications.`id`, framework_qualifications.evidences FROM frameworks INNER JOIN framework_qualifications ON frameworks.id = framework_qualifications.`framework_id` WHERE frameworks.id = '{$framework_id}'", DAO::FETCH_ASSOC);

		ini_set('memory_limit','2048M');
		$StatusList= array();
		$StatusList[0] = "";
		$StatusList[1]=" [Not Started]";
		$StatusList[2]=" [Behind]";
		$StatusList[3]=" [On Track]";
		$StatusList[4]=" [Completed]";

		$info_required = array();
		$info_required[] = "reference";
		$info_required[] = "title";
		$info_required[] = "owner_reference";
		$info_required[] = "mandatory";
		$info_required[] = "percentage";
		$info_required[] = "glh";
		$info_required[] = "credits";

		$text_html = "";
		$text_html .= "<table border='1' cellpadding='6' cellspacing='0' style='font-size: smaller;'>";
		$text_html .= "<thead>";
		$text_html .= "<th>Framework Code</th><th>Framework Title</th><th>Qualification Number</th><th>Unit Reference</th><th>Unit Title</th><th>Owner Ref</th><th>Mandatory</th><th>Percentage</th><th>GLH</th><th>Credits</th>";

		$text_html .= "</thead>";
		$text_html .= "<tbody>";

		foreach($frameworks AS $framework)
		{
			$evidence = XML::loadSimpleXML($framework['evidences']);

			if(count($evidence))
			{
				$units = $evidence->xpath("//unit");

				foreach($units AS $individual_unit)
				{
					$text_html .= "<tr>";
					$text_html .= "<td>" . $framework['framework_code'] ."</td>";
					$text_html .= "<td>" . $framework['title'] ."</td>";
					$text_html .= "<td>" . $framework['id'] ."</td>";

					$temp = array();
					$temp = (array) $individual_unit->attributes();
					$temp = $temp['@attributes'];

					$temp = $this->sortArrayByArray($temp, $info_required);
					//pre($temp);
					foreach($temp AS $key => $value)
					{
						if(in_array($key, $info_required))
							$text_html .= "<td>" . $value . "</td>";
					}
					$text_html .= "</tr>";
				}
				$text_html .= '<tr bgcolor="gray"><td colspan="10"></td></tr>';
			}
		}

		$text_html .= "</tbody>";
		$text_html .= "</table>";

		return $text_html;

	}

	private function exportToExcel($data)
	{

	}

}