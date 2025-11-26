<?php

class ob_dashboard implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=ob_dashboard", "Onbaording Dashboard");

		$sql = <<<SQL
SELECT COUNT(*) AS cnt,
CASE TRUE
  	WHEN tr.id IS NULL THEN 'Added'
  	WHEN tr.id IS NOT NULL AND (ob_learners.`is_finished` = "N" OR ob_learners.`is_finished` IS NULL) THEN 'Awaiting Learner'
  	WHEN tr.id IS NOT NULL AND ob_learners.`is_finished` = "Y" AND ob_learners.`learner_signature` IS NOT NULL AND ob_learners.`employer_signature` IS NULL THEN 'Learner Completed And Awaiting Employer'
  	WHEN tr.id IS NOT NULL AND ob_learners.`is_finished` = "Y" AND ob_learners.`learner_signature` IS NOT NULL AND ob_learners.`employer_signature` IS NOT NULL THEN 'Fully Completed'
  END AS stage
FROM ob_learners
LEFT JOIN users ON ob_learners.`user_id` = users.`id`
LEFT JOIN tr ON users.`username` = tr.`username`
GROUP BY stage
;
SQL;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$stats = array();
		foreach($result AS $row)
		{
			$stats[$row['stage']] = $row['cnt'];
		}

		$sql = <<<SQL
SELECT
(SELECT GROUP_CONCAT(brands.`title` SEPARATOR '; ') FROM brands INNER JOIN employer_business_codes ON brands.id = employer_business_codes.`brands_id` WHERE employer_business_codes.`employer_id` = employers.id) AS business_code,
SUM(IF(tr.id IS NULL, 1, 0)) AS 'Added',
SUM(IF(tr.id IS NOT NULL AND (ob_learners.`is_finished` = "N" OR ob_learners.`is_finished` IS NULL), 1, 0)) AS 'Awaiting Learner',
SUM(IF(tr.id IS NOT NULL AND ob_learners.`is_finished` = "Y" AND ob_learners.`learner_signature` IS NOT NULL AND ob_learners.`employer_signature` IS NULL, 1, 0)) AS 'Learner Completed And Awaiting Employer',
SUM(IF(tr.id IS NOT NULL AND ob_learners.`is_finished` = "Y" AND ob_learners.`learner_signature` IS NOT NULL AND ob_learners.`employer_signature` IS NOT NULL, 1, 0)) AS 'Fully Completed'

FROM
ob_learners
LEFT JOIN users ON ob_learners.`user_id` = users.`id`
LEFT JOIN tr ON users.`username` = tr.`username`
LEFT JOIN organisations AS employers ON employers.id = ob_learners.`employer_id`

GROUP BY business_code
ORDER BY business_code
;

SQL;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
		$employerCodeStats = "<table class='table row-border'>";
		$employerCodeStats .= "<thead><tr><th>Business Code</th><th><span class='label label-warning'>Added</span></th><th><span class='label label-danger'>Awaiting Learner</span></th><th><span class='label label-info'>Learner Completed and Awaiting Employer</span></th><th><span class='label label-success'>Fully Completed</span></th></tr></thead>";
		foreach($result AS $row)
		{
			$employerCodeStats .= "<tr><td align='center'>{$row['business_code']}</td><td align='center'>{$row['Added']}</td><td align='center'>{$row['Awaiting Learner']}</td><td align='center'>{$row['Learner Completed And Awaiting Employer']}</td><td align='center'>{$row['Fully Completed']}</td></tr>";
		}
		$employerCodeStats .= "</table>";

		require_once('tpl_ob_dashboard.php');
	}
}
?>
