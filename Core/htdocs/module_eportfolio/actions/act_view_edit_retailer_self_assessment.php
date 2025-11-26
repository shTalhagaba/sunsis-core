<?php
class view_edit_retailer_self_assessment implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
			throw new Exception('Training record not found');

		if($_SESSION['user']->type == User::TYPE_LEARNER && $_SESSION['user']->username != $tr->username)
		{
			throw new UnauthorizedException();
		}

		$_SESSION['bc']->add($link, "do.php?_action=view_edit_retailer_self_assessment&tr_id={$tr->id}", "View/Edit Retailer Assessment");

		$assessment = DAO::getObject($link, "SELECT * FROM retailer_self_assessment WHERE tr_id = '{$tr->id}'");
		if(!isset($assessment->tr_id))
		{
			$assessment = new stdClass();
			$assessment->tr_id = $tr->id;
			$assessment->criteria_codes = null;
		}

		include_once('tpl_view_edit_retailer_self_assessment.php');
	}

	private function renderForm(PDO $link, $assessment)
	{
		$rows = '';

		$result_sections = DAO::getResultset($link, "SELECT * FROM lookup_retail_assessment_sections ORDER BY section_sort", DAO::FETCH_ASSOC);
		foreach($result_sections AS $section_row)
		{
			$rows .= '<tr class="bg-gray">';
			$rows .= '<th>'.$section_row['section_title'].'-'.$section_row['section_code'].'</th>';
			$rows .= '<th>' . $section_row['section_guide'] . '</th>';
			$rows .= '<th>Learner to tick once competent</th>';
			$rows .= '</tr>';
			$result_criteria = DAO::getResultset($link, "SELECT * FROM lookup_retail_assessment_criteria WHERE section_code = '{$section_row['section_code']}' ORDER BY criteria_sort", DAO::FETCH_ASSOC);
			foreach($result_criteria AS $criteria_row)
			{
				$rows .= in_array($criteria_row['criteria_code'], explode(',', $assessment->criteria_codes)) ? '<tr class="bg-green">' : '<tr>';
				$rows .= '<td>'.$criteria_row['criteria_code'].'</td>';
				$rows .= '<td style="font-size: medium">' . $criteria_row['criteria'] . '</td>';
				if(in_array($criteria_row['criteria_code'], explode(',', $assessment->criteria_codes)))
					$rows .= '<td class="text-center"><input type="checkbox" name="criteria_codes[]" checked value="'.$criteria_row['criteria_code'].'" /></td>';
				else
					$rows .= '<td class="text-center"><input type="checkbox" name="criteria_codes[]" value="'.$criteria_row['criteria_code'].'" /></td>';
				$rows .= '</tr>';
			}
		}

		echo <<<HTML
<table class="table table-bordered">
	$rows
</table>
HTML;
	}
}