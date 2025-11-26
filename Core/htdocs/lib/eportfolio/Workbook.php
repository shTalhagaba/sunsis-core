<?php
class Workbook extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}

		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	workbooks
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$wb = null;
		if($st)
		{
			$wb = null;
			$row = $st->fetch();
			if($row)
			{
				$class = $row['wb_title'];
				$wb = $class != '' ? new $class($row['tr_id']) : new Workbook();
				$wb->populate($row);
				$wb->wb_content = XML::loadSimpleXML($wb->wb_content);
				$employer_legal_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE tr.id = '{$row['tr_id']}'");
				if(strpos(strtolower($employer_legal_name), 'savers') !== false)
					$wb->savers_or_sp = 'savers';
				else
					$wb->savers_or_sp = 'superdrug';
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find workbook record. " . '----' . $query . '----' . $link->errorCode());
		}

		return $wb;
	}

	public static function loadFromDatabaseByTrainingId(PDO $link, $tr_id, $title)
	{
		if (!$tr_id || !is_numeric($tr_id)) {
			throw new Exception("Missing or non-numeric id");
		}

		$id = DAO::getSingleValue($link, "SELECT id FROM workbooks WHERE tr_id=" . $link->quote($tr_id) . " AND wb_title = " . $link->quote($title));
		if (!$id) {
			return null;
		}

		return self::loadFromDatabase($link, $id);
	}

	public function save(PDO $link)
	{
		libxml_use_internal_errors(true);

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($this->wb_content->saveXML());

		$errors = libxml_get_errors();
		if(!empty($errors))
		{
			$_temp = new stdClass();
			$_temp->issue = serialize($_POST);
			$_temp->tr_id = $this->tr_id;
			$_temp->wb_id = $this->id;
			$_temp->wb_title = $this->wb_title;
			DAO::saveObjectToTable($link, "workbooks_issues", $_temp);
		}

		$dom->formatOutput = TRUE;
		$this->wb_content = $dom->saveXml();
		$this->wb_content = str_replace('<?xml version="1.0"?>', '', $this->wb_content);

		return DAO::saveObjectToTable($link, 'workbooks', $this);
	}

	public function getStatusIcon()
	{
		$icons = array(
			self::STATUS_NOT_STARTED => 'fa fa-flag-o'
			,self::STATUS_IN_PROGRESS => 'fa fa-refresh'
			,self::STATUS_LEARNER_COMPLETED => 'fa fa-send-o'
			,self::STATUS_BEING_CHECKED => 'fa fa-hourglass-o'
			,self::STATUS_LEARNER_REFERRED => 'fa fa-warning'
			,self::STATUS_SIGNED_OFF => 'fa fa-thumbs-o-up'
			,self::STATUS_IV_ACCEPTED => 'fa fa-check'
			,self::STATUS_IV_REJECTED => 'fa fa-close'
		);
		return $icons[$this->wb_status];
	}

	public static function getWBStatusIcon($status)
	{
		$icons = array(
			self::STATUS_NOT_STARTED => 'fa fa-flag-o'
			,self::STATUS_IN_PROGRESS => 'fa fa-refresh'
			,self::STATUS_LEARNER_COMPLETED => 'fa fa-send-o'
			,self::STATUS_BEING_CHECKED => 'fa fa-hourglass-o'
			,self::STATUS_LEARNER_REFERRED => 'fa fa-warning'
			,self::STATUS_SIGNED_OFF => 'fa fa-thumbs-o-up'
			,self::STATUS_IV_ACCEPTED => 'fa fa-check'
			,self::STATUS_IV_REJECTED => 'fa fa-close'
		);
		return $icons[$status];
	}

	public static function getWBStatusTitle($status)
	{
		$icons = array(
			self::STATUS_NOT_STARTED => 'Not Started'
			,self::STATUS_IN_PROGRESS => 'Learner In Progress'
			,self::STATUS_LEARNER_COMPLETED => 'Learner Completed'
			,self::STATUS_BEING_CHECKED => 'Assessor Being Checked'
			,self::STATUS_LEARNER_REFERRED => 'Learner Referred'
			,self::STATUS_SIGNED_OFF => 'Assessor Signed Off'
			,self::STATUS_IV_ACCEPTED => 'IV Accepted'
			,self::STATUS_IV_REJECTED => 'IV Not Accepted'
		);
		return $icons[$status];
	}

	public function enableForUser()
	{
		if($_SESSION['user']->type == User::TYPE_LEARNER && in_array($this->wb_status, array(0,1,4)))
			return true;
		if($_SESSION['user']->type == User::TYPE_ASSESSOR && in_array($this->wb_status, array(2,3,6)))
			return true;

		return false;
	}

	protected function updateProgress(PDO $link, $workbook_unit_Reference, $workbook_qan)
	{
		//$qualification_xml = DAO::getSingleValue($link, "SELECT evidences FROM student_qualifications WHERE tr_id = '{$this->tr_id}' AND id = 'Z0001875'");
		$qualification_xml = DAO::getSingleValue($link, "SELECT evidences FROM student_qualifications WHERE tr_id = '{$this->tr_id}' AND id = '{$workbook_qan}'");
		$qualification = XML::loadSimpleXML($qualification_xml);

		$workbook_unit = $qualification->xpath('//units[@title="Workbooks"]/unit[@owner_reference="'.$workbook_unit_Reference.'"]');
		if(!isset($workbook_unit[0]))
		{
			$workbook_unit_Reference = str_replace(' ', '', $workbook_unit_Reference);
			$workbook_unit = $qualification->xpath('//units[@title="Workbooks"]/unit[@owner_reference="'.$workbook_unit_Reference.'"]');
		}
		$workbook_unit = $workbook_unit[0];
		$workbook_unit->attributes()->percentage = 100; // update unit progress

		$qualification->attributes()->percentage = (int)$qualification->attributes()->percentage + (int)$workbook_unit->attributes()->proportion; // updated qualification overall progress
		DAO::execute($link, "UPDATE student_qualifications SET unitsUnderAssessment = unitsUnderAssessment + " . (int)$workbook_unit->attributes()->proportion . " WHERE tr_id = '" . $this->tr_id . "' AND id = '{$workbook_qan}'"); // update this - not sure where its used

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($qualification->saveXML());
		$dom->formatOutput = TRUE;
		$qualification = $dom->saveXml();
		$qualification = str_replace('<?xml version="1.0"?>', '', $qualification);
		DAO::execute($link, "UPDATE student_qualifications SET evidences = '{$qualification}' WHERE tr_id = '{$this->tr_id}' AND id = '{$workbook_qan}'");
	}

	protected function showAssessorFeedback(PDO $link, $section)
	{
		$html = '';
		$duplicate = array(); // as the snapshot is taken every time so don't show the field which was accepted in the previous log otherwise same datestamp and details will be shown
		$results = DAO::getResultset($link, "SELECT wb_content, created FROM workbooks_log WHERE wb_id = '{$this->id}' AND user_type = '" . User::TYPE_ASSESSOR . "' ORDER BY created ASC", DAO::FETCH_ASSOC);
		if(count($results) == 0)
		{
			$html .= '<i>No records found</i>';
		}
		else
		{
			$html .= 'Following logs are sorted by date (ascending order)';
			foreach($results AS $row)
			{
				$log_xml = $row['wb_content'];
				$log_xml = XML::loadSimpleXML($log_xml);

				if(!in_array($section, $duplicate))
				{
					$table_bg = $log_xml->Feedback->$section->Status->__toString() == 'A' ? 'bg-green' : 'bg-red';
					$html .= '<table class="table table-bordered small ' . $table_bg . '"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr>';
					if($log_xml->Feedback->$section->Status->__toString() == 'A')
						$html .= '<td>Accepted</td>';
					else
						$html .= '<td>Not Accepted</td>';
					$html .= '<td>' . $log_xml->Feedback->$section->Comments->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
					if($log_xml->Feedback->$section->Status->__toString() == 'A')
						$duplicate[] = $section;
				}
				elseif($log_xml->Feedback->$section->Status->__toString() == 'NA')
				{
					$table_bg = $log_xml->Feedback->$section->Status->__toString() == 'A' ? 'bg-green' : 'bg-red';
					$html .= '<table class="table table-bordered small ' . $table_bg . '"><tr><th colspan="2">Modified DateTime: ' . Date::to($row['created'], Date::DATETIME) . '</th></tr>';
					$html .= '<tr>';
					if($log_xml->Feedback->$section->Status->__toString() == 'A')
						$html .= '<td>Accepted</td>';
					else
						$html .= '<td>Not Accepted</td>';
					$html .= '<td>' . $log_xml->Feedback->$section->Comments->__toString() . '</td>';
					$html .= '</tr>';
					$html .= '</table><hr>';
					if (($key = array_search($section, $duplicate)) !== false) {
						unset($duplicate[$key]);
					}
				}
			}
		}
		return $html;
	}

	protected function getCompletedPercentage()
	{
		$total = 0;
		$feedback = $this->wb_content->Feedback;
		$sections = count($feedback->children());
		$per_section_total = round(100/(int)$sections, 2);
		foreach($feedback->children() AS $child)
		{
			$total += $child->Status == 'A' ? $per_section_total : 0;
		}

		$total = $total > 100 ? 100 : $total;

		return round($total);
	}

	public function getHeaderLogo(PDO $link)
	{
		if(DB_NAME == "am_superdrug")
		{
			$employer_legal_name = DAO::getSingleValue($link, "SELECT legal_name FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE tr.id = '{$this->tr_id}'");
			return strpos(strtolower($employer_legal_name), 'savers') !== false ? 'Savers.png' : 'superdrug.png';
		}
		else
		{
			return 'SUNlogo.png';
		}
	}

	public function renderIVSection(PDO $link)
	{
		$html = '';
		$iv_signature = $_SESSION['user']->signature;
		if($_SESSION['user']->type == User::TYPE_VERIFIER && $this->wb_status == self::STATUS_SIGNED_OFF)
		{
			$iv_comments_rows = '<tr><td colspan="4"><i class="text-muted">No records found</i></td></tr>';
			$_result = DAO::getResultset($link, "SELECT * FROM workbooks_log WHERE wb_id = '{$this->id}' AND wb_status IN (6,7) ORDER BY created DESC", DAO::FETCH_ASSOC);
			if(count($_result) > 0)
			{
				$iv_comments_rows = '';
				foreach($_result AS $_row)
				{
					$iv_comments_rows .= '<tr>';
					$iv_comments_rows .= '<td>' . Date::to($_row['created'], Date::DATETIME) . '</td>';
					$iv_comments_rows .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$_row['by_whom']}'") . '</td>';
					$iv_comments_rows .= $_row['iv_status'] == 'NA' ? '<td>Not Accepted</td>' : '<td>Accepted</td>';
					$iv_comments_rows .= '<td class="small">' . HTML::nl2p($_row['iv_comments']) . '</td>';
					$iv_comments_rows .= '</tr>';
				}
			}

			$status_ddl = HTML::selectChosen('iv_status', array(array('A', 'Accepted'),array('NA', 'Not Accepted')), '', false, true);
			$html .= <<<HTML
<script type="text/javascript">
//<![CDATA[
var phpIVSignature = '$iv_signature';
function submitIVInfo()
{
	var wb_id = encodeURIComponent('$this->id');
	var iv_status = encodeURIComponent($('#iv_status').val());
	var iv_comments = encodeURIComponent($('#iv_comments').val());
	var iv_signature = encodeURIComponent($('#iv_signature').val());
	window.location.href = 'do.php?_action=save_workbook_iv&subaction=save_iv&wb_id='+wb_id+'&iv_status='+iv_status+'&iv_comments='+iv_comments+'&iv_signature='+iv_signature;
}
//]]>
</script>
<div class="row">
	<div class="col-sm-6">
		<div class="box box-solid box-success">
			<div class="box-header with-border">IV Section</div>
			<div class="box-body">
				<div class="form-group">
					<label for="iv_status" class="col-sm-12 control-label">Status:</label>
					<div class="col-sm-12"> $status_ddl </div>
				</div>
				<div class="form-group">
					<label for="iv_comments" class="col-sm-12 control-label">Comments:</label>
					<div class="col-sm-12"><textarea name="iv_comments" id="iv_comments" rows="7" style="width: 100%;"></textarea></div>
				</div>
				<div class="form-group">
					<span class="btn btn-info" onclick="getSignature('iv');">
						<img id="img_iv_signature" src="do.php?_action=generate_image&title=Click here to sign&font=Signature_Regular.ttf&size=25" style="border: 2px solid;border-radius: 15px;" />
						<input type="hidden" name="iv_signature" id="iv_signature" value="" />
					</span>
				</div>
			</div>
			<div class="box-footer with-border">
				<span class="btn btn-md btn-success" onclick="submitIVInfo();"><i class="fa fa-save"></i> Save IV</span>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="box box-solid box-success">
			<div class="box-header with-border">Previous Comments</div>
			<div class="box-body table-responsive">
				<table class="table table-bordered">
					<thead><tr><th>Date Time</th><th>IV</th><th>Status</th><th>Comments</th></tr></thead>
					<tbody>$iv_comments_rows</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
HTML;
		}
		if(in_array($this->wb_status, array(self::STATUS_IV_ACCEPTED)) && $_SESSION['user']->type != User::TYPE_LEARNER)
		{
			$iv_info = DAO::getObject($link, "SELECT * FROM workbooks_log WHERE wb_id = '{$this->id}' AND wb_status IN (6,7) ORDER BY created DESC LIMIT 1");
			$sd = Date::toShort($iv_info->iv_sign_date);

			$html .= <<<HTML
<div class="row">
	<div class="col-sm-12">
		<div class="box box-solid box-success">
			<div class="box-header with-border">IV Section</div>
			<div class="box-body">
				<p>IV Status: Accepted</p>
				<p>IV Comments: $iv_info->iv_comments</p>
				<p>IV Signature: <img src="do.php?_action=generate_image&$iv_info->iv_signature&size=25" style="border: 2px solid;border-radius: 15px;" /></p>
				<p>IV Signature Date: $sd</p>
			</div>
		</div>
	</div>
</div>
HTML;
		}
		if(in_array($this->wb_status, array(self::STATUS_IV_REJECTED)) && $_SESSION['user']->type == User::TYPE_ASSESSOR)
		{
			$iv_info = DAO::getObject($link, "SELECT * FROM workbooks_log WHERE wb_id = '{$this->id}' AND wb_status IN (6,7) ORDER BY created DESC LIMIT 1");
			$sd = Date::toShort($iv_info->iv_sign_date);

			$html .= <<<HTML
<script type="text/javascript">
//<![CDATA[
var phpIVSignature = '$iv_signature';
function reopenWorkbookAfterIVRejected()
{
	var wb_id = encodeURIComponent('$this->id');
	var reopen_comments = encodeURIComponent($('#reopen_comments').val());
	window.location.href = 'do.php?_action=save_workbook_iv&subaction=reopen_workbook&wb_id='+wb_id+'&reopen_comments='+reopen_comments;
}
var assessorFeedbackElements = document.getElementsByClassName("assessorFeedback");
for(var i = 0; i < assessorFeedbackElements.length; i++)
{
	assessorFeedbackElements[i].style.pointerEvents = "auto";
}
//]]>
</script>
<div class="row">
	<div class="col-sm-12">
		<div class="box box-solid box-danger">
			<div class="box-header with-border">IV Section</div>
			<div class="box-body">
				<p>IV Status: Not Accepted</p>
				<p>IV Comments: $iv_info->iv_comments</p>
				<p>IV Signature: <img src="do.php?_action=generate_image&$iv_info->iv_signature&size=25" style="border: 2px solid;border-radius: 15px;" /></p>
				<p>IV Signature Date: $sd</p>
			</div>

		</div>
	</div>
</div>
HTML;
		}
		return $html;
	}

	public $id = NULL;
	public $tr_id = NULL;
	public $wb_content = NULL;
	public $learner_signature = NULL;

	public $learner_sign_date = NULL;
	public $assessor_signature = NULL;

	public $assessor_sign_date = NULL;
	public $full_save = 'N';
	public $wb_title = NULL;
	public $wb_status = 0;
	public $full_save_feedback = 'N';

	public $savers_or_sp = null;

	const STATUS_NOT_STARTED = 0;
	const STATUS_IN_PROGRESS = 1;
	const STATUS_LEARNER_COMPLETED = 2;
	const STATUS_BEING_CHECKED = 3;
	const STATUS_LEARNER_REFERRED = 4;
	const STATUS_SIGNED_OFF = 5;
	const STATUS_IV_REJECTED = 6;
	const STATUS_IV_ACCEPTED = 7;

	const CS_QAN = 'Z0001875';
	const RETAIL_QAN = '60313432';

}
