<?php
class ajax_save_columns implements IAction
{
	public function execute(PDO $link)
	{
		$view = isset($_REQUEST['view'])?$_REQUEST['view']:'';
		$column = isset($_REQUEST['colum'])?$_REQUEST['colum']:'';
		$visible = isset($_REQUEST['visible'])?$_REQUEST['visible']:'';
		$visible = 1;

		// Take optimised route if JSON is supplied
		$json = isset($_REQUEST['json'])?$_REQUEST['json']:'';
		if($json){
			$this->saveColumns($link, $json, $view);
			return;
		}

		// Original route
		$view = addslashes((string)$view);
		$column = addslashes((string)$column);
		$user = addslashes((string)$_SESSION['user']->username);

		if($view == "ViewTrainingRecordsV2")
		{
			$section = DAO::getSingleValue($link, "SELECT section FROM view_columns WHERE view = '{$view}' AND user = 'master' AND colum = '{$column}'");
			$query = <<<HEREDOC
INSERT INTO view_columns (view, colum, sequence, visible, alignment, user, section) VALUES('$view', '$column', 1, 0, 'left', '$user', '$section');
HEREDOC;
		}
		else
		{
			$query = <<<HEREDOC
INSERT INTO view_columns (view, colum, sequence, visible, alignment, user) VALUES('$view', '$column', 1, 0, 'left', '$user');
HEREDOC;
		}
		DAO::execute($link, $query);
	}
	
	
	
	/**
	 * Optimised 
	 * @param PDO $link
	 * @param string $json
	 * @throws WrappedException
	 */
	private function saveColumns(PDO $link, $json, $view)
	{
		try
		{
			DAO::transaction_start($link);
			
			// Delete existing columns-to-hide data
			DAO::execute($link, "DELETE FROM view_columns WHERE view='".addslashes((string)$view)
				."' AND user='".addslashes((string)$_SESSION['user']->username)."'");
			
			// Insert new columns-to-hide data
			$rows = json_decode($json);
			foreach($rows as &$row)
			{
				$row->sequence = 1;
				$row->alignment = 'left';
				$row->user = $_SESSION['user']->username;
				if($view == "ViewTrainingRecordsV2")
				{
					$row->section = DAO::getSingleValue($link, "SELECT section FROM view_columns WHERE view = '{$view}' AND user = 'master' AND colum = '{$row->colum}'");
				}
			}
			DAO::multipleRowInsert($link, "view_columns", $rows);
			
			DAO::transaction_commit($link);			
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
	}
}
?>