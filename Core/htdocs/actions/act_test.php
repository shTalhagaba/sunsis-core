<?php
class test implements IAction
{
	public function execute(PDO $link)
	{
		

		$aNew = [];
        foreach([2, 3, 8, 11, 12, 17] AS $frameworkId)
        {
            $sql = "SELECT * FROM framework_qualifications WHERE framework_id = '{$frameworkId}' AND onefile_standard_id IS NOT NULL";
            $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
            foreach($result AS $row)
            {
                $aNew["{$frameworkId}_{$row['id']}"] = $row['onefile_standard_id'];
            }
        }

	pr($aNew);

	set_time_limit(0);

        $onefile = new Onefile();

        $sql = "SELECT
  DISTINCT tr.`onefile_id`
FROM
  student_qualifications
  INNER JOIN tr
    ON student_qualifications.`tr_id` = tr.`id`
WHERE student_qualifications.`onefile_learning_aim_id` IS NULL
  AND tr.`onefile_id` IS NOT NULL ORDER BY tr_id, framework_id
";

        $result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        foreach($result AS $row)
        {
            $response = $onefile->api_LearningAimSearch([
                'UserID' => $row['onefile_id']
            ]);

            if($response->getHttpCode() == 200)
            {
                $apiResult = $response->getBody();
    
                $apiResult = json_decode($apiResult);

                DAO::multipleRowInsert($link, 'temp', $apiResult);

                //pre('stop');
            }
        }

        pre('stop');


    	}
}