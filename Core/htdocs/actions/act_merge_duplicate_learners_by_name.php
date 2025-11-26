<?php
class merge_duplicate_learners_by_name extends ActionController
{
	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		include('tpl_merge_duplicate_learners_by_name.php');
	}


	public function mergeAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		$primaryId = $this->_getParam('primary_id');
		$secondaryIds = $this->_getParam('secondary_id');

		// Validation
		if (!$primaryId) {
			throw new Exception("Missing argument: primaryId");
		}
		if (!$secondaryIds) {
			throw new Exception("Missing argument: secondaryId");
		}
		if (!is_numeric($primaryId)) {
			throw new Exception("primaryId value must be numeric");
		}
		if (is_array($secondaryIds)) {
			foreach ($secondaryIds as $id) {
				if(!is_numeric($id)) {
					throw new Exception("secondaryId values must be numeric");
				}
			}
		} else {
			if (!is_numeric($secondaryIds)) {
				throw new Exception("secondaryId value must be numeric");
			}
			$secondaryIds = (array) $secondaryIds;
		}

		DAO::transaction_start($link);
		try
		{
			User::merge($link, $primaryId, $secondaryIds, true);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link);
			throw $e;
		}
	}


	private function _renderDuplicates(PDO $link)
	{
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_users_1 (id BIGINT NOT NULL,
	username VARCHAR(45) NOT NULL,
	SOUNDEX VARCHAR(20) NOT NULL,
	firstnames VARCHAR(100) NOT NULL,
	surname VARCHAR(100),
	gender CHAR(1),
	dob DATE,
	l45 BIGINT,
	ni VARCHAR(9),
	employer_id BIGINT,
	PRIMARY KEY(`id`),
	KEY(`soundex`),
	KEY(`dob`))
SELECT
	id,
	username,
	CONCAT(SOUNDEX(SUBSTRING_INDEX(firstnames, ' ', 1)), SOUNDEX(SUBSTRING_INDEX(surname, ' ', -1))) AS `soundex`,
	firstnames,
	surname,
	gender,
	dob,
	l45,
	ni,
	employer_id
FROM
	users
WHERE
	users.type = 5;
SQL;
		DAO::execute($link, $sql);

		// Copy tmp_users_1
		DAO::execute($link, "CREATE TEMPORARY TABLE tmp_users_2 LIKE tmp_users_1;");
		DAO::execute($link, "INSERT INTO tmp_users_2 SELECT * FROM tmp_users_1");


		$sql = <<<SQL
SELECT DISTINCT
	u1.soundex,
	u1.id,
	u1.username,
	u1.firstnames,
	u1.surname,
	u1.gender,
	u1.dob,
	u1.l45,
	u1.ni,
	organisations.legal_name AS `employer`,
	(SELECT COUNT(id) FROM tr WHERE tr.username = u1.username) AS `tr_count`,
	(SELECT GROUP_CONCAT(l03) FROM tr WHERE tr.username = u1.username GROUP BY tr.username) AS `l03`
FROM
	tmp_users_1 AS u1 INNER JOIN tmp_users_2 AS u2 INNER JOIN organisations
		ON u1.soundex = u2.soundex
		AND (u1.dob IS NULL OR u2.dob IS NULL OR u1.dob = u2.dob)
		AND (u1.id != u2.id)
		AND (u1.employer_id = u2.employer_id)
		AND u1.`employer_id` = organisations.id
ORDER BY
	`soundex`,
	u1.dob
SQL;

		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		if (!$rs) {
			echo <<<HTML
<div style="margin-top:40px;font-size:14pt;color:#395596;border:1px solid silver; width:400px; margin-left:auto; margin-right: auto; padding: 20px;text-align: center">No potential duplicates found</div>
HTML;
			return;
		}

		$soundex = null;
		foreach ($rs as $row) {
			// New heading each time the ULN value changes
			if ($row['soundex'] != $soundex) {
				if (!empty($soundex)) {
					echo '<tr><td colspan="10" style="border-right-style:solid;border-right-width:2px"></td><td align="center" colspan="2"><input class="MergeButton" type="button" value="Merge"/></td></tr>';
					echo '</table>'; // Close previous table
				}
				//echo '<h3>', htmlspecialchars((string)$row['l45']), '</h3>';
				echo '<table class="resultset" cellpadding="4" cellspacing="0" width="900" >';
				echo '<col span="10" /><col span="2" width="70" />';
				echo '<tr class="topRow"><th colspan="10" style="border-right-style:solid;border-right-width:2px"></th><th colspan="2">Record Designation</th></tr>';
				echo '<tr><th colspan = "2">Name</th><th>Username</th><th>DOB</th><th><abbr title="Gender">Gen</abbr></th><th>ULN</th><th><abbr title="National Insurance">NI</abbr></th>';
				echo '<th>Employer</th><th><abbr title="Number of training records">TR\'s</abbr></th><th style="border-right-style:solid;border-right-width:2px">&nbsp;</th>';
				echo '<th>Primary</th><th>Duplicate</th></tr>';
				$soundex = $row['soundex'];
			}

			echo '<tr>';
			echo '<td align="left">', htmlspecialchars(Text::strtoproper($row['firstnames'])), '</td>';
			echo '<td align="left">', htmlspecialchars(Text::strtoproper($row['surname'])), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['username']), '</td>';
			echo '<td align="left">', htmlspecialchars(Date::toShort($row['dob'])), '</td>';
			echo '<td align="center">', htmlspecialchars((string)$row['gender']), '</td>';
			echo '<td align="left" style="font-family:monospace">', htmlspecialchars((string)$row['l45']), '</td>';
			echo '<td align="left" style="font-family:monospace">', htmlspecialchars((string)$row['ni']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['employer']), '</td>';
			echo '<td align="right">', htmlspecialchars((string)$row['tr_count']), '</td>';
			echo '<td align="center" style="border-right-style:solid;border-right-width:2px"><input type="button" value="View" onclick="viewRecord(\''
				. addslashes((string)$row['username']) . '\');" /></td>';
			echo '<td align="center"><input type="radio" class="MarkPrimary" name="pri_' . htmlspecialchars((string)$row['soundex'])
				. '" value="' . htmlspecialchars((string)$row['id']) . '" /></td>';
			echo '<td align="center"><input type="checkbox" class="MarkSecondary" name="sec_' . htmlspecialchars((string)$row['soundex'])
				. '" value="' . htmlspecialchars((string)$row['id']) . '" /></td>';
			echo '</tr>';
		}
		echo '<tr><td colspan="10" style="border-right-style:solid;border-right-width:2px"></td><td align="center" colspan="2"><input class="MergeButton" type="button" value="Merge"/></td></tr>';
		echo '</table>';
	}

}