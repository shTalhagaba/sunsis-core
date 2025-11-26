<?php
class ajax_save_lookup_ref_sources implements IAction
{
	public function execute(PDO $link)
	{
		$training_provider_id = isset($_REQUEST['training_provider_id'])?$_REQUEST['training_provider_id']:'';
		$new_record = isset($_REQUEST['new_record'])?$_REQUEST['new_record']:'';
		$referral_sources = isset($_REQUEST['referral_sources'])?$_REQUEST['referral_sources']:'';

		$sql = "";
		if($referral_sources != '')
		{
			$referral_sources_xml = XML::loadSimpleXML($referral_sources);

			foreach($referral_sources_xml->source as $source)
			{
				if($source->description != '')
				{
					if($new_record == 0)
					{
/*						$sql .= "INSERT INTO lookup_referral_source (id, provider_id, description, active) VALUES (";
						$sql .= "'" . $source->id . "', ";
						$sql .= "'" . $training_provider_id . "', ";
						$sql .= "'" . $source->description . "', 1); ";
*/
						$sql .= "UPDATE lookup_referral_source SET description = '{$source->description}', active = 1 WHERE id = {$source->id}; ";
					}
					else
					{
						$sql .= "INSERT INTO lookup_referral_source (provider_id, description, active) VALUES (";
						$sql .= "'" . $training_provider_id . "', ";
						$sql .= "'" . $source->description . "', 1); ";
					}
				}
				else
				{
					$sql .= "UPDATE lookup_referral_source SET active = 0 WHERE id = {$source->id}; ";
				}
			}
		}

		if($sql != "")
		{
			DAO::transaction_start($link);
			try
			{
				if($new_record == 0)
					//DAO::execute($link, "DELETE FROM lookup_referral_source WHERE provider_id = " . $training_provider_id);
					DAO::execute($link, "UPDATE lookup_referral_source SET active = 0 WHERE provider_id = " . $training_provider_id);
				DAO::execute($link, $sql);

				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link);
				throw new WrappedException($e);

			}
		}
		else
		{
			if($referral_sources == '<sources></sources>')
				//DAO::execute($link, "DELETE FROM lookup_referral_source WHERE provider_id = " . $training_provider_id);
				DAO::execute($link, "UPDATE lookup_referral_source SET active = 0 WHERE provider_id = " . $training_provider_id);
		}
	}
}
?>
