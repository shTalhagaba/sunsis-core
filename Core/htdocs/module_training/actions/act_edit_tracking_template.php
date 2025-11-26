<?php
class edit_tracking_template implements IAction
{
	public function execute(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		if($course_id == '')
			throw new Exception('Missing querystring argument: course_id');

		$course = Course::loadFromDatabase($link, $course_id);
		$framework = Framework::loadFromDatabase($link, $course->framework_id);

		$provider = Organisation::loadFromDatabase($link, $course->organisations_id);
		$provider_main_location = null;
		foreach($provider->getLocations($link) AS $loc)
		{
			if($loc->is_legal_address == 1)
			{
				$provider_main_location = $loc;
				break;
			}
		}

		include_once('tpl_edit_tracking_template.php');
	}

	public function editTrackingTemplateTab(PDO $link, $course_id, $section_id)
	{
		$html = '';

		$html .= <<<HTML
<form class="form-horizontal" name="frmAddElement" role="form" method="post" action="do.php">
	<input type="hidden" name="_action" value="save_tracking_template" />
	<input type="hidden" name="subaction" value="add_element" />
	<input type="hidden" name="course_id" value="{$course_id}" />
	<input type="hidden" name="section_id" value="{$section_id}" />
	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<label for="title" class="col-sm-4 control-label fieldLabel_compulsory">Section Title:</label>
				<div class="col-sm-8">
					<input type="text" class="form-control compulsory" name="new_element_title" id="new_element_title" value="" maxlength="250" />
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<button type="button" class="btn btn-success btn-sm" id="btnAddElement">
				<i class="fa fa-save"></i> Add Element
			</button>
		</div>
	</div>
</form>
HTML;


		$section_row = DAO::getObject($link, "SELECT * FROM tracking_template WHERE id = '{$section_id}' ");

		$section_elements = DAO::getResultset($link, "SELECT * FROM tracking_template WHERE section_id = '{$section_id}'", DAO::FETCH_ASSOC);
		foreach($section_elements AS $element)
		{
			$html .= '<ul>';

			$html .= '<li><span class="text-blue">' . $element['title'] . '</span>';
			$element_evidences = DAO::getResultset($link, "SELECT * FROM tracking_template WHERE element_id = '{$element['id']}'", DAO::FETCH_ASSOC);
			foreach($element_evidences AS $evidence)
			{
				$html .= '<ul>';
				$html .= '<li>' . $evidence['title'] . '</li>';
				$html .= '</ul>';
			}
			$html .= '</li>';

			$html .= '</ul>';
		}

		return $html;
	}
}