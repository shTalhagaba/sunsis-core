<?php
class Safeguarding extends Entity
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
	safeguarding
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$safeguarding = null;
		if($st)
		{
			$safeguarding = null;
			$row = $st->fetch();
			if($row)
			{
				$safeguarding = new Safeguarding();
				$safeguarding->populate($row);
			}

		}
		else
		{
			throw new Exception("Could not execute database query to find safeguarding record for the user. " . '----' . $query . '----' . $link->errorCode());
		}

		return $safeguarding;
	}

	public function save(PDO $link)
	{
		$this->created_at = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created_at;
		$this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;
		$this->updated_at = date('Y-m-d H:i:s');

		return DAO::saveObjectToTable($link, 'safeguarding', $this);
	}

	public static function getDdlTriggers(PDO $link)
	{
		// return [
		// 	[1, 'Capability'],
		// 	[2, 'LDD'],
		// 	[3, 'SEN'],
		// 	[4, 'Attitude'],
		// 	[5, 'Language'],
		// 	[6, 'Lateness/Attendance'],
		// 	[7, 'Performance'],
		// ];
		return DAO::getResultset($link, "SELECT id, description FROM lookup_safeguarding_triggers");
	}

	public static function getListTriggers(PDO $link)
	{
		// return [
		// 	1 => 'Capability',
		// 	2 => 'LDD',
		// 	3 => 'SEN',
		// 	4 => 'Attitude',
		// 	5 => 'Language',
		// 	6 => 'Lateness/Attendance',
		// 	7 => 'Performance',
		// ];
		return DAO::getLookupTable($link, "SELECT id, description FROM lookup_safeguarding_triggers")	;
	}

	public static function getDdlContributingFactors(PDO $link)
	{
		// return [
		// 	[1, 'Job Role'],
		// 	[2, 'Apprenticeship Work'],
		// 	[3, 'Working Environment'],
		// 	[4, 'Home Life'],
		// 	[5, 'Finance'],
		// 	[6, 'Time'],
		// 	[7, 'Mental Health'],
		// 	[8, 'Family'],
		// 	[9, 'Relationship'],
		// ];
		return DAO::getResultset($link, "SELECT id, description FROM lookup_safeguarding_contr_factors");
	}

	public static function getListContributingFactors(PDO $link)
	{
		// return [
		// 	1 => 'Job Role',
		// 	2 => 'Apprenticeship Work',
		// 	3 => 'Working Environment',
		// 	4 => 'Home Life',
		// 	5 => 'Finance',
		// 	6 => 'Time',
		// 	7 => 'Mental Health',
		// 	8 => 'Family',
		// 	9 => 'Relationship',
		// ];
		return DAO::getLookupTable($link, "SELECT id, description FROM lookup_safeguarding_contr_factors")	;
	}

	public static function getDdlRouteways()
	{
		return [
			[1, 'IT'],
			[2, 'DM'],
			[3, 'Data'],
			[4, 'SW'],
		];
	}

	public static function getListRouteways()
	{
		return [
			1 => 'IT',
			2 => 'DM',
			3 => 'Data',
			4 => 'SW',
		];
	}

	public static function getDdlProRe()
	{
		return [
			['Proactive', 'Proactive'],
			['Reactive', 'Reactive'],
		];
	}

	public static function getDdlSupportProvider()
	{
		return [
			[1, 'Apprentice Success'],
			[2, 'Safeguarding'],
			[3, 'Programme Coach'],
			[4, 'External Support'],
			[5, 'Functional Skills'],
		];
	}

	public static function getListSupportProvider()
	{
		return [
			1 => 'Apprentice Success',
			2 => 'Safeguarding',
			3 => 'Programme Coach',
			4 => 'External Support',
			5 => 'Functional Skills',
		];
	}

	public static function getDdlCategories(PDO $link)
	{
		// return [
		// 	[1, 'SEN'],
		// 	[2, 'Mental Health & Wellbeing'],
		// 	[3, 'Safeguarding'],
		// ];
		return DAO::getResultset($link, "SELECT id, description FROM lookup_safeguarding_categories");
	}

	public static function getListCategories(PDO $link)
	{
		// return [
		// 	1 => 'SEN',
		// 	2 => 'Mental Health & Wellbeing',
		// 	3 => 'Safeguarding',
		// ];
		return DAO::getLookupTable($link, "SELECT id, description FROM lookup_safeguarding_categories")	;
	}

	public static function getDdlSafeguardingReportedBy()
    {
        return [
            [1, 'Apprentice'],
            [2, 'Employer'],
            [3, 'Baltic'],
            [4, 'Parent/Guardian'],
        ];
    }

    public static function getListSafeguardingReportedBy()
    {
        return [
            1 => 'Apprentice',
            2 => 'Employer',
            3 => 'Baltic',
            4 => 'Parent/Guardian',
        ];
    }

	public static function renderTrSafeguarding(PDO $link, TrainingRecord $tr)
	{
		$html = '<table class="resultset" cellpadding="6">';
		$html .= '<tr>';
		$html .= '<th>Triggers</th><th>Contributing Factors</th><th>Routeway</th><th>Summary</th><th>Action Plan</th><th>Categories</th>';
		$html .= '<th>Date</th><th>Reactive/Proactive</th><th>Recommended End Date</th><th>Support Provider</th>';
		$html .= '<th>Created By</th><th>Created At</th><th>Updated At</th>';
		$html .= '</tr>';
		$records = DAO::getResultset($link, "SELECT * FROM safeguarding WHERE safeguarding.tr_id = '{$tr->id}' ORDER BY created_at ", DAO::FETCH_ASSOC);
		if(count($records) == 0)
		{
			$html .= '<tr><td colspan="13"><i class="fa fa-info-circle"></i> No other records.</td></tr>';
		}
		else
		{
			$triggers = self::getListTriggers($link);
			$factors = self::getListContributingFactors($link);
			$routeways = self::getListRouteways();
			$categories = self::getListCategories($link);
			$support_providers = self::getListSupportProvider();

			foreach($records AS $row)
			{
				$html .= HTML::viewrow_opening_tag("do.php?_action=edit_safeguarding&id={$row['id']}&tr_id={$row['tr_id']}");

				$html .= isset( $triggers[$row['triggers']] ) ? '<td>' . $triggers[$row['triggers']] . '</td>' : '<td>' . $row['triggers'] . '</td>';
				$html .= '<td>';
				if($row['factors'] != '')
				{
					foreach( explode(',', $row['factors']) AS $factor )
					{
						$html .= isset( $factors[$factor] ) ? $factors[$factor] : $factor;
						$html .= '<br>';
					}
				}
				$html .= '</td>';
				$html .= isset( $routeways[$row['routeway']] ) ? '<td>' . $routeways[$row['routeway']] . '</td>' : '<td>' . $row['routeway'] . '</td>';
				$html .= '<td>' . nl2br($row['summary']) . '</td>';
				$html .= '<td>' . nl2br($row['action_plan']) . '</td>';
				$html .= '<td>';
				if($row['category'] != '')
				{
					foreach( explode(',', $row['category']) AS $category )
					{
						$html .= isset( $categories[$category] ) ? $categories[$category] : $category;
						$html .= '<br>';
					}
				}
				$html .= '</td>';
				$html .= '<td>' . Date::toShort($row['date']) . '</td>';
				$html .= '<td>' . $row['reactive_proactive'] . '</td>';
				$html .= '<td>' . Date::toShort($row['recommended_end_date']) . '</td>';
				$html .= '<td>';
				if($row['support_provider'] != '')
				{
					foreach( explode(',', $row['support_provider']) AS $support_provider )
					{
						$html .= isset( $support_providers[$support_provider] ) ? $support_providers[$support_provider] : $support_provider;
						$html .= '<br>';
					}
				}
				$html .= '</td>';
				$html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'") . '</td>';
				$html .= '<td>' . Date::to($row['created_at'], Date::DATETIME) . '</td>';
				$html .= '<td>' . Date::to($row['updated_at'], Date::DATETIME) . '</td>';

				$html .= '</tr> ';
			}

		}
		$html .= '</table>';

		return $html;
	}

	public $id = NULL;
	public $tr_id = NULL;
	public $triggers = NULL;
	public $factors = NULL;
	public $routeway = NULL;
	public $summary = NULL;
	public $action_plan = NULL;
	public $category = NULL;
	public $date = NULL;
	public $reactive_proactive = NULL;
	public $recommended_end_date = NULL;
	public $support_provider = NULL;
	public $created_by = NULL;
	public $created_at = NULL;
	public $updated_at = NULL;
	public $reported_by = NULL;
	public $date_closed = NULL;
	public $learner_voice = NULL;
	public $app_success_comments = NULL;

}
?>