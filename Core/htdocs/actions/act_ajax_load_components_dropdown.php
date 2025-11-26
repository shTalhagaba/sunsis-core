<?php
class ajax_load_components_dropdown implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		
		$framework_type = array_key_exists('framework_type', $_REQUEST)?$_REQUEST['framework_type']:'';
		$framework_code = array_key_exists('framework_code', $_REQUEST)?$_REQUEST['framework_code']:'';
		$aim_type = array_key_exists('aim_type', $_REQUEST)?$_REQUEST['aim_type']:'';
		
		
		if($framework_type == '')
		{
			throw new Exception("Missing querystring argument 'org_id'");
		}

		if($aim_type == 1)
		{
		
		$sql = <<<HEREDOC
SELECT DISTINCT Core_LARS_LearningDelivery.LearnAimRef, CONCAT(Core_LARS_LearningDelivery.LearnAimRef,' - ',Core_LARS_LearningDelivery.LearnAimRefTitle), NULL
FROM lars201415.Core_LARS_FrameworkAims
INNER JOIN lars201415.Core_LARS_LearningDelivery ON Core_LARS_LearningDelivery.`LearnAimRef` = Core_LARS_FrameworkAims.`LearnAimRef`
WHERE Core_LARS_FrameworkAims.`FrameworkComponentType` IN ('001','003') AND Core_LARS_FrameworkAims.`ProgType` ='$framework_type' AND Core_LARS_FrameworkAims.`FworkCode` ='$framework_code';

HEREDOC;
		
		}
		elseif($aim_type==2)
		{
		$sql = <<<HEREDOC
SELECT DISTINCT Core_LARS_LearningDelivery.LearnAimRef, CONCAT(Core_LARS_LearningDelivery.LearnAimRef,' - ',Core_LARS_LearningDelivery.LearnAimRefTitle), NULL
FROM lars201415.Core_LARS_FrameworkAims
INNER JOIN lars201415.Core_LARS_LearningDelivery ON Core_LARS_LearningDelivery.`LearnAimRef` = Core_LARS_FrameworkAims.`LearnAimRef`
WHERE Core_LARS_FrameworkAims.`FrameworkComponentType` IN ('002') AND Core_LARS_FrameworkAims.`ProgType` ='$framework_type' AND Core_LARS_FrameworkAims.`FworkCode` = '$framework_code';

HEREDOC;
		}
		else 
		{
		$sql = <<<HEREDOC
SELECT
	DISTINCT Core_LARS_LearningDelivery.LearnAimRef, CONCAT(Core_LARS_LearningDelivery.LearnAimRef,' - ',Core_LARS_LearningDelivery.LearnAimRefTitle), NULL
FROM
	lars201415.Core_LARS_FrameworkCmnComp
LEFT JOIN lars201415.Core_LARS_LearningDelivery ON Core_LARS_LearningDelivery.`FrameworkCommonComponent` = Core_LARS_FrameworkCmnComp.`CommonComponent`
WHERE
	Core_LARS_FrameworkCmnComp.`ProgType` ='$framework_type'
	AND Core_LARS_FrameworkCmnComp.`FworkCode` = '$framework_code'
	AND (Core_LARS_FrameworkCmnComp.`EffectiveTo` IS NULL OR Core_LARS_FrameworkCmnComp.`EffectiveTo` > NOW());
HEREDOC;
			
		}
//		throw new Exception($sql);

		$st = $link->query($sql);
		if($st)
		{
			echo "<?xml version=\"1.0\" ?>\r\n";
			echo "<select>\r\n";
			
			// First entry is empty
			echo "<option value=\"\"></option>\r\n";
			
			while($row = $st->fetch())
			{
				echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>\r\n";
			}
			
			echo '</select>';
			
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}
}
?>